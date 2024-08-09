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
use App\Models\Recetas;
use App\Models\RecetasDetalle;
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

        // SOLO SERA FONDOS PROPIOS
        $arrayFuente = FuenteFinanciamiento::where('id', 3)->get();

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
        $arrayMedicamentos = DB::table('entrada_medicamento AS em')
            ->join('entrada_medicamento_detalle AS deta', 'em.id', '=', 'deta.entrada_medicamento_id')
            ->select('deta.cantidad', 'deta.id', 'em.fuentefina_id', 'deta.medicamento_id', 'deta.lote',
                        'deta.fecha_vencimiento')
            ->where('deta.cantidad', '>', 0)
            ->where('em.fuentefina_id', $request->idfuente)
            ->get();


        $hayFilas = false;
        foreach ($arrayMedicamentos as $detalle){
            $hayFilas = true;

            $infoFarmacia = FarmaciaArticulo::where('id', $detalle->medicamento_id)->first();

            $detalle->nombre = $infoFarmacia->nombre;

            $fechaVencimiento = date("d-m-Y", strtotime($detalle->fecha_vencimiento));

            $textoLote = "(Lote: " . $detalle->lote . " )";
            $textoFechaVen = "(Vencimiento: " . $fechaVencimiento . " )";
            $textoExistencia = "(Existencia: " . $detalle->cantidad . " )";

            $detalle->nombretotal = $infoFarmacia->nombre . " " . $textoExistencia . " " . $textoLote . " " . $textoFechaVen;

            $nombreGenerico = "";

            if($infoArticulo = ArticuloMedicamento::where('farmacia_articulo_id', $detalle->medicamento_id)->first()){

                if($infoArticulo->nombre_generico != null){
                    $nombreGenerico = $infoArticulo->nombre_generico;
                }
            }

            $detalle->nombreGenerico = $nombreGenerico;
            $detalle->cantidadTotal = $detalle->cantidad;
        }

        return ['success' => 1, 'dataArray' => $arrayMedicamentos, 'hayfilas' => $hayFilas];
    }



    public function registroNuevaRecetaParaPaciente(Request $request){

        $regla = array(
            'idconsulta' => 'required',
            'fecha' => 'required',
            'diagnostico' => 'required',
        );

        // indicacionGeneral
        // proximaCita

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Recetas::where('consulta_id', $request->idconsulta)->first()){
            return ['success' => 1];
        }
        else{

            DB::beginTransaction();

            try {
                $usuario = auth()->user();


                $infoConsulta = Consulta_Paciente::where('id', $request->idconsulta)->first();
                $datosContenedor = json_decode($request->contenedorArray, true);

                $receta = new Recetas();
                $receta->consulta_id = $request->idconsulta;
                $receta->paciente_id = $infoConsulta->paciente_id;
                $receta->diagnostico_id = $request->diagnostico;
                $receta->descripcion_general = $request->indicacionGeneral;
                $receta->fecha = $request->fecha;
                $receta->proxima_cita = $request->proximaCita;
                $receta->estado = 1;
                $receta->usuario_id = $usuario->id;
                $receta->usuario_estado_id = null; // saber que usuario denego receta
                $receta->save();

                // REGISTRAR CADA FILA MEDICAMENTO
                // SE DEBE REGISTRAR EL ID ENTRADA DETALLE Y LA CANTIDAD A RETIRARLE

                foreach ($datosContenedor as $filaArray) {

                    $detalle = new RecetasDetalle();
                    $detalle->recetas_id = $receta->id;
                    $detalle->entrada_detalle_id = $filaArray['infoIdMedicamento']; // VIENE ID ENTRADA MEDICAMENTO DETALLE
                    $detalle->cantidad = $filaArray['infoCantidad'];
                    $detalle->descripcion = $filaArray['infoIndicacion'];
                    $detalle->via_id = $filaArray['infoIdVia'];
                    $detalle->save();
                }

                DB::commit();
                return ['success' => 2];

            }catch(\Throwable $e){
                Log::info('error: ' . $e);
                DB::rollback();
                return ['success' => 99];
            }
        }
    }



    public function indexVistaEditarVerReceta($idreceta){

        $infoReceta = Recetas::where('id', $idreceta)->first();

        $infoConsulta = Consulta_Paciente::where('id', $infoReceta->consulta_id)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $arrayFuente = FuenteFinanciamiento::where('id',3)->get();

        $arrayDiagnostico = Diagnosticos::orderBy('nombre', 'ASC')->get();

        $arrayVia = ViaReceta::orderBy('nombre', 'ASC')->get();

        $fechaActual = Carbon::now()->toDateString();

        $arrayDetalle = DB::table('recetas_detalle AS red')
            ->join('entrada_medicamento_detalle AS entrade', 'entrade.id', '=', 'red.entrada_detalle_id')
            ->join('farmacia_articulo AS fama', 'fama.id', '=', 'entrade.medicamento_id')
            ->select('fama.nombre', 'red.recetas_id', 'entrade.cantidad AS cantidadActual',
                        'red.via_id', 'red.cantidad', 'red.descripcion', 'fama.id AS idfarmacia', 'entrade.id AS idEntradaDeta',
                        'entrade.lote')
            ->where('red.recetas_id', $idreceta)
            ->orderBy('fama.nombre', 'ASC')
            ->get();

        $contador = 0;

        foreach ($arrayDetalle as $info){
            $contador++;

            $info->contador = $contador;

            $nombreGenerico = "";
            if($infoGenerico = ArticuloMedicamento::where('farmacia_articulo_id', $info->idfarmacia)->first()){
                $nombreGenerico = $infoGenerico->nombre_generico;
            }
            $info->nombreGenerico = $nombreGenerico;

            $infoVia = ViaReceta::where('id', $info->via_id)->first();
            $info->nombreVia = $infoVia->nombre;
        }

        if($infoReceta->estado != 1){
            $titulo = "VER FICHA DE RECETA";
        }else{
            $titulo = "MODIFICACIÓN FICHA DE RECETA";
        }

        return view('backend.admin.historialclinico.recetas.vistaeditarreceta', compact('idreceta',
            'nombreCompleto', 'infoReceta', 'arrayDiagnostico', 'arrayFuente', 'arrayVia',
                'fechaActual', 'arrayDetalle', 'titulo', 'infoConsulta'));
    }



    public function actualizarRecetaMedica(Request $request){

        $regla = array(
            'idreceta' => 'required',
            'fecha' => 'required',
            'diagnostico' => 'required',
        );

        // indicacionGeneral
        // proximaCita

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($infoReceta = Recetas::where('id', $request->idreceta)->first()){

            if($infoReceta->estado == 2){
                return ['success' => 1, 'estado' => 'Procesada', 'idconsulta' => $infoReceta->consulta_id];
            }

            if($infoReceta->estado == 3){
                return ['success' => 1, 'estado' => 'Denegada', 'idconsulta' => $infoReceta->consulta_id];
            }


            DB::beginTransaction();

            try {

                // BORRAR ANTERIORES

                RecetasDetalle::where('recetas_id', $request->idreceta)->delete();

                $datosContenedor = json_decode($request->contenedorArray, true);


                Recetas::where('id', $request->idreceta)->update([
                    'diagnostico_id' => $request->diagnostico,
                    'descripcion_general' => $request->indicacionGeneral,
                    'fecha' => $request->fecha,
                    'proxima_cita' => $request->proximaCita,
                ]);

                // REGISTRAR CADA FILA MEDICAMENTO
                foreach ($datosContenedor as $filaArray) {

                    $detalle = new RecetasDetalle();
                    $detalle->recetas_id = $request->idreceta;
                    $detalle->entrada_detalle_id = $filaArray['infoIdMedicamento']; // ID ENTRADA DETALLE
                    $detalle->cantidad = $filaArray['infoCantidad'];
                    $detalle->descripcion = $filaArray['infoIndicacion'];
                    $detalle->via_id = $filaArray['infoIdVia'];
                    $detalle->save();
                }

                DB::commit();
                return ['success' => 2, 'idconsulta' => $infoReceta->consulta_id];

            }catch(\Throwable $e){
                Log::info('error: ' . $e);
                DB::rollback();
                return ['success' => 99];
            }
        }
        else{
            return ['success' => 2];
        }
    }








}
