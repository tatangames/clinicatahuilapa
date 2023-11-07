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
use App\Models\MotivoFarmacia;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaDetalle;
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




    //************ ORDEN DE SALIDAS PARA FARMACIA   *****

    public function indexSalidaFarmacia(){

        $arrayMotivo = MotivoFarmacia::orderBy('nombre')->get();

        $arrayProductoFarmacia = FarmaciaArticulo::all();

        $pilaIdProducto = array();

        foreach ($arrayProductoFarmacia as $info){

            // NECESITO UNICAMENTE CANTIDAD MAYOR A 0

            $cantidad = EntradaMedicamentoDetalle::where('medicamento_id', $info->id)->sum('cantidad');

            if($cantidad > 0){
                array_push($pilaIdProducto, $info->id);
            }
        }


        $arrayProducto = FarmaciaArticulo::whereIn('id', $pilaIdProducto)->orderBy('nombre', 'ASC')->get();

        foreach ($arrayProducto as $detalle){

            $cantidadTotal = EntradaMedicamentoDetalle::where('medicamento_id', $detalle->id)->sum('cantidad');

            if($detalle->codigo_articulo != null){
                $detalle->nombretotal = $detalle->codigo_articulo . ' - ' . $detalle->nombre . ' (Existencia: ' . $cantidadTotal . ')';
            }else{
                $detalle->nombretotal = $detalle->nombre . ' (Existencia: ' . $cantidadTotal . ')';
            }
        }


        return view('backend.admin.farmacia.ordensalida.vistaordensalida', compact('arrayMotivo', 'arrayProducto'));
    }




    public function elegirProductoParaSalida($idproducto){

        $arraySalidas = DB::table('entrada_medicamento AS en')
                ->join('entrada_medicamento_detalle AS deta', 'en.id', '=', 'deta.entrada_medicamento_id')
                ->select('en.fecha', 'deta.entrada_medicamento_id', 'deta.medicamento_id', 'deta.cantidad',
                            'deta.precio', 'deta.lote', 'deta.fecha_vencimiento', 'en.numero_factura', 'deta.id AS identradadetalle')
                ->where('deta.medicamento_id', $idproducto)
                ->where('deta.cantidad', '>', 0)
                ->orderBy('deta.fecha_vencimiento', 'ASC')
                ->get();

        $conteo = 1;
        if (count($arraySalidas) == 0) {
            $conteo = 0;
        }


        foreach ($arraySalidas as $dato){

            $infoDe = FarmaciaArticulo::where('id', $dato->medicamento_id)->first();
            $dato->nombre = $infoDe->nombre;
            $dato->fechaVencimiento = date("d-m-Y", strtotime($dato->fecha_vencimiento));

            $dato->fechaEntrada = date("d-m-Y", strtotime($dato->fecha));

            $dato->precio = '$' . number_format((float)$dato->precio, 2, '.', ',');
        }

        return view('backend.admin.farmacia.ordensalida.tabla.modalproductosalida', compact('conteo', 'arraySalidas'));
    }


    public function registrarOrdenSalidaFarmacia(Request $request){


        $regla = array(
            'motivo' => 'required',
            'fecha' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            // Obtiene los datos enviados desde el formulario como una cadena JSON y luego decódificala
            $datosContenedor = json_decode($request->contenedorArray, true); // El segundo argumento convierte el resultado en un arreglo

            $usuario = auth()->user();
            $horaCarbon = Carbon::parse(Carbon::now());

            $orden = new OrdenSalida();
            $orden->usuario_id = $usuario->id;
            $orden->motivo_id = $request->motivo;
            $orden->fecha = $request->fecha;
            $orden->hora = $horaCarbon;
            $orden->observaciones = $request->observaciones;
            $orden->save();

            $fila = 0;

            // REGISTRAR CADA SALIDA
            foreach ($datosContenedor as $filaArray) {
                $fila++;

                $infoEntrada = EntradaMedicamentoDetalle::where('id', $filaArray['infoIdEntrada'])->first();

                $resta = $infoEntrada->cantidad - $filaArray['infoCantidad'];

                if($resta < 0){
                    return ['success' => 1, 'fila' => $fila, 'cantidad' => $infoEntrada->cantidad];
                }

                // ACTUALIZAR CANTIDAD

                EntradaMedicamentoDetalle::where('id', $filaArray['infoIdEntrada'])->update([
                    'cantidad' => $resta
                ]);

                $detalle = new OrdenSalidaDetalle();
                $detalle->orden_salida_id = $orden->id;
                $detalle->entrada_medi_detalle_id = $filaArray['infoIdEntrada'];
                $detalle->cantidad = $filaArray['infoCantidad'];
                $detalle->save();
            }


            DB::commit();
            return ['success' => 2];

        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




}
