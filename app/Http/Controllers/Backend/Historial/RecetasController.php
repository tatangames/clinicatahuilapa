<?php

namespace App\Http\Controllers\backend\historial;

use App\Http\Controllers\Controller;
use App\Models\ArticuloMedicamento;
use App\Models\Consulta_Paciente;
use App\Models\Diagnosticos;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Paciente;
use App\Models\ViaReceta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RecetasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexVistaNuevaReceta($idconsulta){

        $infoConsulta = Consulta_Paciente::where('id', $idconsulta)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $arrayFuente = FuenteFinanciamiento::orderBy('nombre', 'ASC')->get();

        $arrayDiagnostico = Diagnosticos::orderBy('nombre', 'ASC')->get();

        $arrayVia = ViaReceta::orderBy('nombre', 'ASC')->get();

        $fechaActual = Carbon::now()->toDateString();






        return view('backend.admin.historialclinico.recetas.vistanuevareceta', compact('idconsulta',
                'nombreCompleto', 'arrayFuente', 'arrayDiagnostico', 'arrayVia', 'fechaActual'));
    }



    public function listadoMedicamentosPorFuenteFinan(Request $request){

        $regla = array(
            'idfuente' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        // OBTENER LISTADO DE MEDICAMENTOS
        $listado = DB::table('entrada_medicamento AS em')
            ->join('entrada_medicamento_detalle AS deta', 'em.id', '=', 'deta.entrada_medicamento_id')
            ->select('deta.cantidad', 'deta.id', 'em.fuentefina_id', 'deta.medicamento_id')
            ->where('deta.cantidad', '>', 0)
            ->where('em.fuentefina_id', $request->idfuente)
            ->get();

        $pilaIdMedicamento = array();

        foreach ($listado as $info){
            array_push($pilaIdMedicamento, $info->medicamento_id);
        }


        // NECESITO FILTRAR PARA UNIR LAS CANTIDADES PARA 1 SOLO MEDICAMENTO

        $arrayMedicamentos = FarmaciaArticulo::whereIn('id', $pilaIdMedicamento)
            ->orderBy('nombre', 'ASC')
            ->get();

        $hayFilas = false;
        foreach ($arrayMedicamentos as $detalle){
            $hayFilas = true;

            $cantidadTotal = EntradaMedicamentoDetalle::where('medicamento_id', $detalle->id)->sum('cantidad');

            $detalle->nombretotal = $detalle->nombre . ' (Existencia: ' . $cantidadTotal . ')';

            $nombreGenerico = "";

            if($infoArticulo = ArticuloMedicamento::where('farmacia_articulo_id', $detalle->id)->first()){

                if($infoArticulo->nombre_generico != null){
                    $nombreGenerico = $infoArticulo->nombre_generico;
                }
            }

            $detalle->nombreGenerico = $nombreGenerico;
            $detalle->cantidadTotal = $cantidadTotal;

        }

        return ['success' => 1, 'dataArray' => $arrayMedicamentos, 'hayfilas' => $hayFilas];
    }


}
