<?php

namespace App\Policies;

use App\Models\DetalleControlGarita;
use App\Models\ControlGarita;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DetalleControlGaritaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DetalleControlGarita $detalleControlGarita): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ControlGarita $controlGarita): bool
    {
        // debe haber turno activo del usuario (propio) para poder crear registros
        $tActivo = ControlGarita::where('usuario_id', $user->id)
                    ->where('estado','activo')->latest()->first();

        // si quieres que solo el owner o agentes asignados con rol 'registrar turno' puedan crear:
        if ($tActivo && $tActivo->id === $controlGarita->id) {
            // el dueño del turno puede crear
            return true;
        }

        // si el usuario tiene rol 'registrar turno' y está asignado al turno:
        if ($user->hasRole('registrar turno') && $controlGarita->isAssigned($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DetalleControlGarita $detalleControlGarita): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DetalleControlGarita $detalleControlGarita): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DetalleControlGarita $detalleControlGarita): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DetalleControlGarita $detalleControlGarita): bool
    {
        return false;
    }
}
