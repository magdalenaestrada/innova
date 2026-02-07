<?php

namespace App\Providers;

use App\Models\Contrato;
use App\Models\LqCliente;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $clientesActivos = LqCliente::where('estado', '!=', 'I')->pluck('id');

            $porVencer = Contrato::whereNotNull('fecha_fin_contrato')
                ->whereIn('cliente_id', $clientesActivos)
                ->whereBetween('fecha_fin_contrato', [now(), now()->addMonth()])
                ->count();

            $vencidos = Contrato::whereNotNull('fecha_fin_contrato')
                ->whereIn('cliente_id', $clientesActivos)
                ->where('fecha_fin_contrato', '<', now())
                ->count();

            $view->with('notificacionesContratos', [
                'por_vencer' => $porVencer,
                'vencidos' => $vencidos,
                'total' => $porVencer + $vencidos,
            ]);
        });
    }
}
