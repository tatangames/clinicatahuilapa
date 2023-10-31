<?php

namespace App\Http\Controllers\Backend\Farmacia;

use App\Http\Controllers\Controller;
use App\Models\ArticuloMedicamento;
use App\Models\ContenidoFarmaceutica;
use App\Models\EntradaMedicamento;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Linea;
use App\Models\Proveedores;
use App\Models\SubLinea;
use App\Models\TipoFactura;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FarmaciaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }



    public function indexRegistroArticulo(){

        $arrayLinea = Linea::orderBy('nombre')->get();
        $arraySubLinea = SubLinea::orderBy('nombre')->get();

        // array envase
        $arrayEnvase = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 1)->orderBy('nombre')->get();

        // array forma farmaceutica
        $arrayFormaFarmaceutica = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 2)->orderBy('nombre')->get();


        // array concentracion
        $arrayConcentracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 3)->orderBy('nombre')->get();


        // array contenido
        $arrayContenido = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 4)->orderBy('nombre')->get();


        // array forma administracion
        $arrayAdministracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 5)->orderBy('nombre')->get();


        return view('backend.admin.farmacia.registro.vistaregistro', compact('arrayLinea',
            'arraySubLinea', 'arrayEnvase', 'arrayFormaFarmaceutica', 'arrayConcentracion',
            'arrayContenido', 'arrayAdministracion'));
    }


    public function registrarArticulo(Request $request){


        $regla = array(
            'idLinea' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            if(FarmaciaArticulo::where('codigo_articulo', $request->codigoArticulo)->first()){
                return ['success' => 1];
            }


            $articulo = new FarmaciaArticulo();
            $articulo->linea_id = $request->idLinea;
            $articulo->sublinea_id = $request->idSubLinea;
            $articulo->nombre = $request->nombre;
            $articulo->codigo_articulo = $request->codigoArticulo;
            $articulo->existencia_minima = $request->existencia;
            $articulo->save();


            if($request->idLinea == 1){
                // SOLO GUARDAR SI ES TIPO MEDICAMENTOS

                $dato = new ArticuloMedicamento();
                $dato->farmacia_articulo_id = $articulo->id;
                $dato->con_far_envase_id = $request->idEnvase;
                $dato->con_far_forma_id = $request->idFormaFarma;
                $dato->con_far_concentracion_id = $request->idConcentracion;
                $dato->con_far_contenido_id = $request->idContenido;
                $dato->con_far_administra_id = $request->idAdministracion;
                $dato->nombre_generico = $request->nombreGenerico;
                $dato->save();
            }

            DB::commit();
            return ['success' => 2];

        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




    //*************************************************************

    public function indexIngresoArticulo(){

        $arrayTipoFactura = TipoFactura::orderBy('nombre')->get();
        $arrayFuente = FuenteFinanciamiento::orderBy('nombre')->get();
        $arrayProveedor = Proveedores::orderBy('nombre')->get();

        return view('backend.admin.farmacia.ingreso.vistaingresoinventario', compact('arrayTipoFactura',
            'arrayFuente', 'arrayProveedor'));
    }


    public function buscarMedicamento(Request $request){

        if($request->get('query')){
            $query = $request->get('query');
            $data = FarmaciaArticulo::where('nombre', 'LIKE', "%{$query}%")
                ->orWhere('codigo_articulo', 'LIKE', "%{$query}%")
                ->get();

            foreach ($data as $info){

                if($info->codigo_articulo != null){
                    $info->nombreunido = $info->codigo_articulo . ' - ' . $info->nombre;
                }else{
                    $info->nombreunido = $info->nombre;
                }


                // BUSCAR CANTIDAD Y ULTIMO PRECIO DEL MISMO

                $existencia = EntradaMedicamentoDetalle::where('medicamento_id', $info->id)->sum('cantidad');
                $info->existencia = $existencia;

                if($filaUltima = EntradaMedicamentoDetalle::where('medicamento_id', $info->id)
                    ->orderBy('id', 'DESC')
                    ->first()){

                    $precioUltimo = '$' . number_format((float)$filaUltima->precio, 2, '.', ',');

                    $info->ultimoprecio = $precioUltimo;
                }else{
                    $info->ultimoprecio = "No Tiene un Registro aun";
                }
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:relative; overflow: auto; max-height: 300px; width: 550px">';
            $tiene = true;
            foreach($data as $row){

                // si solo hay 1 fila, No mostrara el hr, salto de linea
                if(count($data) == 1){
                    if(!empty($row)){
                        $tiene = false;
                        $output .= '
                 <li class="cursor-pointer" onclick="modificarValor(this)" id="'.$row->id.'" data-ultimoprecio="'.$row->ultimoprecio.'" data-existencia="'.$row->existencia.'">'.$row->nombreunido.'</li>
                ';
                    }
                }

                else{
                    if(!empty($row)){
                        $tiene = false;
                        $output .= '
                 <li class="cursor-pointer" onclick="modificarValor(this)" id="'.$row->id.'"  data-ultimoprecio="'.$row->ultimoprecio.'" data-existencia="'.$row->existencia.'">'.$row->nombreunido.'</li>
                   <hr>
                ';
                    }
                }
            }
            $output .= '</ul>';
            if($tiene){
                $output = '';
            }
            echo $output;
        }
    }



    public function registrarNuevoMedicamento(Request $request){


        $regla = array(
            'numFactura' => 'required',
            'tipoFactura' => 'required',
            'fuenteFina' => 'required',
            'proveedor' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            // Obtiene los datos enviados desde el formulario como una cadena JSON y luego decódificala
            $datosContenedor = json_decode($request->contenedorArray, true); // El segundo argumento convierte el resultado en un arreglo

            $usuario = auth()->user();

            Usuario::where('id', $usuario->id);
            $fechaCarbon = Carbon::parse(Carbon::now());

            $nuevoMedi = new EntradaMedicamento();
            $nuevoMedi->numero_factura = $request->numFactura;
            $nuevoMedi->tipofactura_id = $request->tipoFactura;
            $nuevoMedi->fuentefina_id = $request->fuenteFina;
            $nuevoMedi->proveedor_id = $request->proveedor;
            $nuevoMedi->usuario_id = $usuario->id;
            $nuevoMedi->fecha = $fechaCarbon;
            $nuevoMedi->save();

            foreach ($datosContenedor as $filaArray) {

                Log::info($filaArray['infoIdMedicamento']);
                Log::info($filaArray['infoCantidad']);
                Log::info($filaArray['infoPrecio']);

                $infoMedicamento = FarmaciaArticulo::where('id', $filaArray['infoIdMedicamento'])->first();

                $detalle = new EntradaMedicamentoDetalle();
                $detalle->entrada_medicamento_id = $nuevoMedi->id;
                $detalle->medicamento_id = $filaArray['infoIdMedicamento'];
                $detalle->nombre_copia = $infoMedicamento->nombre;
                $detalle->cantidad = $filaArray['infoCantidad'];
                $detalle->cantidad_fija = $filaArray['infoCantidad'];
                $detalle->precio = $filaArray['infoPrecio'];
                $detalle->lote = $filaArray['infoLote'];
                $detalle->fecha_vencimiento = $filaArray['infoFecha'];
                $detalle->save();
            }


            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }


    }



}
