<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\ContratosEmpresas;
use App\Models\ListaContacto;
use App\Models\LqCliente;
use App\Models\Persona;
use App\Models\RepresentanteLegal;
use App\Models\TsEmpresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContratoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gestionar clientes');
    }

    public function index($id)
    {
        $empresa = LqCliente::findOrFail($id);
        $contrato = Contrato::where("cliente_id", $id)
            ->with('cliente')
            ->orderBy('fecha_inicio_contrato', 'desc')
            ->first();
        if (!$contrato) {
            $contrato = Contrato::create([
                'cliente_id' => $id,
                'usuario_id' => auth()->id(),
                'permitir_acceso' => false,
            ]);
        }
        $empresas = TsEmpresa::orderBy('nombre')->get();
        $contratoEmpresas = ContratosEmpresas::where('contrato_id', $contrato->id)
            ->with('empresa')
            ->get();

        $hoy = Carbon::now("America/Lima");

        $dias_restantes = null;
        if ($contrato && $contrato->fecha_fin_contrato) {
            $fechaFin = Carbon::parse($contrato->fecha_fin_contrato);
            $dias_restantes = $hoy->diffInDays($fechaFin, false);
        }
        $representantes = RepresentanteLegal::where("cliente_id", $id)
            ->with("persona")
            ->orderBy("id")
            ->get();

        $contactos = ListaContacto::where("cliente_id", $id)
            ->orderBy("id")
            ->get();

        return view('liquidaciones.clientes.contratos.index', compact(
            'empresas',
            'contratoEmpresas',
            'dias_restantes',
            'contrato',
            'empresa',
            'representantes',
            'contactos'
        ));
    }


    public function guardar_empresa(Request $request)
    {
        $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'empresa_id' => [
                'required',
                'exists:ts_empresas,id',
                Rule::unique('contratos_empresas')
                    ->where(function ($query) use ($request) {
                        return $query->where('contrato_id', $request->contrato_id);
                    }),
            ],
        ], [
            'empresa_id.unique' => 'Esta empresa ya está asociada a este contrato'
        ]);

        $contratoEmpresa = ContratosEmpresas::firstOrCreate([
            'contrato_id' => $request->contrato_id,
            'empresa_id' => $request->empresa_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Empresa agregada al contrato',
            'data' => [
                'id' => $contratoEmpresa->id,
                'empresa_nombre' => $contratoEmpresa->empresa->nombre
            ]
        ]);
    }

    public function eliminar_empresa($id)
    {
        $registro = ContratosEmpresas::findOrFail($id);
        $registro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Empresa eliminada correctamente'
        ]);
    }

    public function create()
    {
        $clientes = LqCliente::orderBy('nombre')->get();
        return view('contratos.create', compact('clientes'));
    }

    public function guardar_representante(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:lq_clientes,id',
            'documento'  => 'required|string|min:8|max:11',
            'nombre'     => 'required|string',
        ]);

        $persona = Persona::firstOrCreate(
            ['documento_persona' => $request->documento],
            ['datos_persona' => $request->nombre]
        );

        $existe = RepresentanteLegal::where('cliente_id', $request->cliente_id)
            ->where('persona_id', $persona->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Este documento ya está registrado como representante de esta empresa'
            ], 422);
        }

        $representante = RepresentanteLegal::create([
            'cliente_id' => $request->cliente_id,
            'persona_id' => $persona->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Representante guardado exitosamente',
            'data' => [
                'id' => $representante->id,
                'documento' => $persona->documento_persona,
                'nombre' => $persona->datos_persona
            ]
        ]);
    }
    public function eliminar_representante($id)
    {
        RepresentanteLegal::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Representante eliminado'
        ]);
    }
    public function guardar_contacto(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:lq_clientes,id',

            'celular' => [
                'required',
                'digits:9', // solo números, exactamente 9
                Rule::unique('lista_contactos', 'celular')
                    ->where(function ($query) use ($request) {
                        return $query->where('cliente_id', $request->cliente_id)->whereNull('deleted_at');
                    }),
            ],

            'solo_whatsapp' => 'boolean',
        ], [
            'celular.unique' => 'Este número ya está registrado para este cliente',
            'celular.digits' => 'El número debe tener exactamente 9 dígitos',
        ]);

        $contacto = ListaContacto::create([
            'cliente_id' => $request->cliente_id,
            'celular' => $request->celular,
            'solo_whatsapp' => $request->boolean('solo_whatsapp'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contacto guardado exitosamente',
            'data' => [
                'id' => $contacto->id,
                'celular' => $contacto->celular,
                'solo_whatsapp' => $contacto->solo_whatsapp
            ]
        ]);
    }

    public function eliminar_contacto(ListaContacto $contacto)
    {
        $contacto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contacto eliminado exitosamente'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:lq_clientes,id',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_fin_contrato' => 'nullable|date|after_or_equal:fecha_inicio_contrato',
            'numero_contrato' => 'required|string|max:100',
        ]);

        Contrato::create([
            'cliente_id' => $request->cliente_id,
            'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
            'fecha_fin_contrato' => $request->fecha_fin_contrato,
            'numero_contrato' => $request->numero_contrato,
            'recepcionado_cliente' => $request->recepcionado_cliente ?? 0,
            'legalizado_jurgen' => $request->legalizado_jurgen ?? 0,
            'permitir_acceso' => $request->permitir_acceso ?? 0,
            'observaciones' => $request->observaciones,
            'numero_juegos' => $request->numero_juegos,
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('contratos.index')
            ->with('status', 'Contrato registrado correctamente');
    }

    public function show(Contrato $contrato)
    {
        $contrato->load('cliente', 'usuario', 'usuario_edit');
        return view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $clientes = LqCliente::orderBy('nombre')->get();
        return view('contratos.edit', compact('contrato', 'clientes'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $numeroNormalizado = strtoupper(
            preg_replace('/[^A-Z0-9]/', '', $request->numero_contrato)
        );

        $contratoExistente = Contrato::whereRaw(
            "UPPER(REGEXP_REPLACE(numero_contrato, '[^A-Z0-9]', '')) = ?",
            [$numeroNormalizado]
        )
            ->where('id', '!=', $contrato->id)
            ->with('cliente')
            ->first();

        if ($contratoExistente) {
            throw ValidationException::withMessages([
                'numero_contrato' => 'Este número de contrato le pertenece al cliente ' .
                    $contratoExistente->cliente->nombre,
            ]);
        }

        $request->validate([
            'cliente_id' => 'required|exists:lq_clientes,id',
            'fecha_inicio_contrato' => 'required|date',
            'fecha_fin_contrato' => 'nullable|date|after_or_equal:fecha_inicio_contrato',
            'numero_contrato' => [
                'required',
                'max:100',
                'regex:/^\S+$/', // Sin espacios
                Rule::unique('contratos', 'numero_contrato')->ignore($contrato->id)
            ],
        ], [
            'numero_contrato.regex' => 'El número de contrato no puede contener espacios',
            'numero_contrato.unique' => 'Este número de contrato ya existe',
        ]);

        $contrato->update([
            'cliente_id' => $request->cliente_id,
            'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
            'fecha_fin_contrato' => $request->fecha_fin_contrato,
            'numero_contrato' => $request->numero_contrato,
            'numero_juegos' => $request->numero_juegos,
            'recepcionado_cliente' => $request->recepcionado_cliente,
            'legalizado_jurgen' => $request->legalizado_jurgen,
            'permitir_acceso' => $request->boolean('permitir_acceso'),
            'observaciones' => $request->observaciones,
            'usuario_edit_id' => Auth::id(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contrato actualizado correctamente'
            ]);
        }

        return back()->with('status', 'Contrato actualizado correctamente');
    }


    public function destroy(Contrato $contrato)
    {
        $contrato->delete();

        return redirect()->route('contratos.index')
            ->with('status', 'Contrato eliminado correctamente');
    }
}
