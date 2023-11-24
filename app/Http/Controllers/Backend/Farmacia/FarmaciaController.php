<?php

namespace App\Http\Controllers\Backend\Farmacia;

use App\Http\Controllers\Controller;
use App\Models\ArticuloMedicamento;
use App\Models\Consulta_Paciente;
use App\Models\ContenidoFarmaceutica;
use App\Models\EntradaMedicamento;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Linea;
use App\Models\MotivoFarmacia;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaDetalle;
use App\Models\Paciente;
use App\Models\Proveedores;
use App\Models\Recetas;
use App\Models\RecetasDetalle;
use App\Models\SalidaReceta;
use App\Models\SalidaRecetaDetalle;
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


    public function actualizarNuevoMedicamento(Request $request){

        $regla = array(
            'numFactura' => 'required',
            'tipoFactura' => 'required',
            'fuenteFina' => 'required',
            'proveedor' => 'required',
            'identrada' => 'required',
            'fecha' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            // Obtiene los datos enviados desde el formulario como una cadena JSON y luego decódificala
            $datosContenedor = json_decode($request->contenedorArray, true); // El segundo argumento convierte el resultado en un arreglo



            EntradaMedicamento::where('id', $request->identrada)->update([
                'tipofactura_id' => $request->tipoFactura,
                'fuentefina_id' => $request->fuenteFina,
                'proveedor_id' => $request->proveedor,
                'fecha' => $request->fecha,
                'numero_factura' => $request->numFactura
            ]);


            foreach ($datosContenedor as $filaArray) {

                $infoMedicamento = FarmaciaArticulo::where('id', $filaArray['infoIdMedicamento'])->first();

                $detalle = new EntradaMedicamentoDetalle();
                $detalle->entrada_medicamento_id = $request->identrada;
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




    public function indexSalidaFarmaciaPorReceta(){

        return view('backend.admin.farmacia.salidareceta.vistasalidarecetafarmacia');
    }



    public function tablaSalidaFarmaciaPorReceta($estado, $desde, $hasta){

        if($estado == '1'){

            $arrayRecetas = Recetas::where('estado', 1)
                ->orderBy('fecha', 'ASC')
                ->get();

            foreach ($arrayRecetas as $info){

                $info->fechaFormat = date("d-m-Y", strtotime($info->fecha));

                $infoPaciente = Paciente::where('id', $info->paciente_id)->first();

                $info->nombrepaciente = $info->nombres . " " . $infoPaciente->apellidos;

                $infoUsuario = Usuario::where('id', $info->usuario_id)->first();
                $info->doctor = $infoUsuario->nombre;
            }

            return view('backend.admin.farmacia.salidareceta.tablarecetapendiente', compact('arrayRecetas'));


        }else if($estado == '2'){

            $start = Carbon::parse($desde)->startOfDay();
            $end = Carbon::parse($hasta)->endOfDay();

            // PROCESADOS

            $arrayRecetas = Recetas::where('estado', 2)
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();

            foreach ($arrayRecetas as $info){

                $info->fechaFormat = date("d-m-Y", strtotime($info->fecha));
                $info->fechaEstadoFormat = date("d-m-Y", strtotime($info->fecha_estado));
                $infoPaciente = Paciente::where('id', $info->paciente_id)->first();

                $info->nombrepaciente = $info->nombres . " " . $infoPaciente->apellidos;

                $infoUsuario = Usuario::where('id', $info->usuario_id)->first();
                $info->doctor = $infoUsuario->nombre;
            }

            return view('backend.admin.farmacia.salidareceta.tablarecetasalidaprocesada', compact('arrayRecetas'));

        }else{

            $start = Carbon::parse($desde)->startOfDay();
            $end = Carbon::parse($hasta)->endOfDay();

            $arrayRecetas = Recetas::where('estado', 3)
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();

            foreach ($arrayRecetas as $info){

                $info->fechaFormat = date("d-m-Y", strtotime($info->fecha));

                $info->fechaEstadoFormat = date("d-m-Y", strtotime($info->fecha_estado));

                $infoPaciente = Paciente::where('id', $info->paciente_id)->first();

                $info->nombrepaciente = $info->nombres . " " . $infoPaciente->apellidos;

                $infoUsuario = Usuario::where('id', $info->usuario_id)->first();
                $info->nombreuser = $infoUsuario->nombre;
            }


            // TABLA PARA DENEGADOS
            return view('backend.admin.farmacia.salidareceta.tablarecetadenegada', compact('arrayRecetas'));
        }
    }


    // FORMA ANTERIOR DE USUARIO FARMACIA ELIGE DE QUE MEDICAMENTO SACAR

    /*public function vistaRecetaDetalleProcesar($idreceta){

        $infoReceta = Recetas::where('id', $idreceta)->first();

        $infoConsulta = Consulta_Paciente::where('id', $infoReceta->consulta_id)->first();

        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $infoUsuario = Usuario::where('id', $infoReceta->usuario_id)->first();

        $nombreDoctor = $infoUsuario->nombre;

        $fechaReceta = date("d-m-Y", strtotime($infoReceta->fecha));

        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $arrayNombreMedicamento = DB::table('recetas_detalle AS rd')
            ->join('farmacia_articulo AS fa', 'rd.medicamento_id', '=', 'fa.id')
            ->select('fa.nombre', 'rd.id', 'rd.recetas_id', 'rd.cantidad')
            ->where('rd.recetas_id', $idreceta)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        foreach ($arrayNombreMedicamento as $info){

            $info->nombreFormat = $info->nombre . " ( Cantidad: " . $info->cantidad . " )";
        }

        // ENTRADA DETALLE


        $resultsBloque = array();
        $index = 0;

        $arrayDetalle = DB::table('recetas_detalle AS rd')
            ->join('farmacia_articulo AS fa', 'rd.medicamento_id', '=', 'fa.id')
            ->select('fa.nombre', 'rd.id', 'rd.recetas_id', 'rd.cantidad', 'rd.medicamento_id')
            ->where('rd.recetas_id', $idreceta)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        foreach($arrayDetalle as $secciones) {
            array_push($resultsBloque, $secciones);

            $nombreGenerico = "";
            if($infoArti = ArticuloMedicamento::where('farmacia_articulo_id', $secciones->medicamento_id)->first()){
                $nombreGenerico = $infoArti->nombre_generico;
            }
            $secciones->nombreGenerico = $nombreGenerico;

            $listaDetalle = DB::table('entrada_medicamento AS en')
                ->join('entrada_medicamento_detalle AS deta', 'en.id', '=', 'deta.entrada_medicamento_id')
                ->select('en.fecha', 'deta.entrada_medicamento_id', 'deta.medicamento_id', 'deta.cantidad',
                    'deta.precio', 'deta.lote', 'deta.fecha_vencimiento', 'en.numero_factura', 'deta.id AS identradadetalle',
                    'deta.cantidad AS cantidadMaxima')
                ->where('deta.medicamento_id', $secciones->medicamento_id)
                ->where('deta.cantidad', '>', 0)
                ->orderBy('deta.fecha_vencimiento', 'ASC')
                ->get();

            $conteo = count($listaDetalle);
            $secciones->conteo = $conteo;

            foreach ($listaDetalle as $info){

                $info->fechaVencimiento = date("d-m-Y", strtotime($info->fecha_vencimiento));
                $info->fechaEntrada = date("d-m-Y", strtotime($info->fecha));



            }


            $resultsBloque[$index]->listadetalle = $listaDetalle;
            $index++;
        }



        return view('backend.admin.farmacia.salidareceta.procesar.vistaprocesarreceta', compact('idreceta',
            'infoPaciente', 'nombreCompleto', 'nombreDoctor', 'fechaReceta', 'edad', 'arrayNombreMedicamento',
            'arrayDetalle'));
    }*/


    public function vistaRecetaDetalleProcesar($idreceta){

        $infoReceta = Recetas::where('id', $idreceta)->first();

        $infoConsulta = Consulta_Paciente::where('id', $infoReceta->consulta_id)->first();

        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $infoUsuario = Usuario::where('id', $infoReceta->usuario_id)->first();

        $nombreDoctor = $infoUsuario->nombre;

        $fechaReceta = date("d-m-Y", strtotime($infoReceta->fecha));

        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $arrayNombreMedicamento = DB::table('recetas_detalle AS rd')
            ->join('entrada_medicamento_detalle AS enta', 'rd.entrada_detalle_id', '=', 'enta.id')
            ->join('farmacia_articulo AS fama', 'enta.medicamento_id', '=', 'fama.id')
            ->select('fama.nombre', 'rd.id', 'rd.recetas_id', 'enta.fecha_vencimiento', 'rd.cantidad', 'enta.lote', 'enta.cantidad AS cantidadActual',
                    'rd.cantidad AS cantidadRetirar')
            ->where('rd.recetas_id', $idreceta)
            ->orderBy('fama.nombre', 'ASC')
            ->get();

        $contador = 0;
        foreach ($arrayNombreMedicamento as $info){
            $contador++;
            $info->contador = $contador;

            $info->nombreFormat = $info->nombre;

            $info->fechaVencimiento = date("d-m-Y", strtotime($info->fecha_vencimiento));
        }


        return view('backend.admin.farmacia.salidareceta.procesar.vistaprocesarreceta', compact('idreceta',
        'infoPaciente', 'nombreCompleto', 'nombreDoctor', 'fechaReceta', 'edad', 'arrayNombreMedicamento',
        ));
    }


    public function infoRecetaParaDenegar(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoReceta = Recetas::where('id', $request->id)->first()){

            $infoDoctor = Usuario::where('id', $infoReceta->usuario_id)->first();

            $infoPaciente = Paciente::where('id', $infoReceta->paciente_id)->first();

            $paciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;


            return ['success' => 1, 'doctor' => $infoDoctor->nombre, 'paciente' => $paciente];
        }else{
            return ['success' => 2];
        }
    }



    public function guardarDenegacionReceta(Request $request){

        $regla = array(
            'id' => 'required',
            'descripcion' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoReceta = Recetas::where('id', $request->id)->first()){

            // ESTADOS
            // 1: pendiente
            // 2: procesada
            // 3: denegada

            if($infoReceta->estado == 2){
                // procesada yap
                return ['success' => 1];
            }

            if($infoReceta->estado == 3){
                // ya fue denegada
                return ['success' => 2];
            }

            if($infoReceta->estado == 1){

                // esta pendiente asi que puede denegar

                $fechaCarbon = Carbon::parse(Carbon::now());
                $usuario = auth()->user();


                Recetas::where('id', $request->id)->update([
                    'estado' => 3,
                    'nota_denegada' => $request->descripcion,
                    'fecha_denegada' => $fechaCarbon,
                    'usuario_estado_id' => $usuario->id
                ]);

                return ['success' => 3];
            }

            // defecto
            return ['success' => 3];
        }else{
            return ['success' => 2];
        }
    }



    // VERSION ANTIGUA CUANDO FARMACIA SELECCIONABA DE CUAL MEDICAMENTO RETIRAR
    /*public function guardarSalidaProcesadaDeReceta(Request $request){

        $regla = array(
            'idreceta' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        $datosContenedor = json_decode($request->contenedorArray, true);

        DB::beginTransaction();

        try {

            $usuario = auth()->user();
            $fechaCarbon = Carbon::parse(Carbon::now());

            $salida = new SalidaReceta();
            $salida->recetas_id = $request->idreceta;
            $salida->usuario_id = $usuario->id;
            $salida->fecha = $fechaCarbon;
            $salida->notas = $request->txtNotas;
            $salida->save();


            // RESTAR ENTRADA DETALLE
            foreach ($datosContenedor as $filaArray) {

                $infoEntradaDeta = EntradaMedicamentoDetalle::where('id', $filaArray['idEntradaDetalle'])->first();

                // RESTAR

                $resta = $infoEntradaDeta->cantidad - $filaArray['salida'];

                if($resta < 0){
                    // se esta restando de mas a esta entrada

                    $infoMedicamento = FarmaciaArticulo::where('id', $infoEntradaDeta->medicamento_id)->first();

                    $fechaVencimiento = date("d-m-Y", strtotime($infoEntradaDeta->fecha_vencimiento));


                    return ['success' => 1, 'nombre' => $infoMedicamento->nombre,
                        'cantidadhay' => $infoEntradaDeta->cantidad,
                        'lote' => $infoEntradaDeta->lote,
                        'fechavencimiento' => $fechaVencimiento,
                        'cantidadsalida' => $filaArray['salida']];
                }

                EntradaMedicamentoDetalle::where('id', $filaArray['idEntradaDetalle'])->update([
                    'cantidad' => $resta
                ]);


                $newDetalle = new SalidaRecetaDetalle();
                $newDetalle->salidareceta_id = $salida->id;
                $newDetalle->entrada_detalle_id = $filaArray['idEntradaDetalle'];
                $newDetalle->cantidad = $filaArray['salida'];
                $newDetalle->save();
            }

            // actualizar estado

            Recetas::where('id', $request->idreceta)->update([
                'estado' => 2 // PROCESADO
            ]);

            DB::commit();
            return ['success' => 2];

        }catch(\Throwable $e){
            DB::rollback();
            Log::info('err ' . $e);
            return ['success' => 99];
        }
    }*/



    public function guardarSalidaProcesadaDeReceta(Request $request){

        $regla = array(
            'idreceta' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            $infoReceta = Recetas::where('id', $request->idreceta)->first();

            if($infoReceta->estado != '1'){
                return ['success' => 1];
            }

            $usuario = auth()->user();
            $fechaCarbon = Carbon::parse(Carbon::now());

            $salida = new SalidaReceta();
            $salida->recetas_id = $request->idreceta;
            $salida->usuario_id = $usuario->id;
            $salida->fecha = $fechaCarbon;
            $salida->notas = $request->txtNotas;
            $salida->save();

            // LISTADO DE RECETAS DETALLE

            $arrayDetalle = RecetasDetalle::where('recetas_id', $request->idreceta)->get();

            // RESTAR ENTRADA DETALLE
            foreach ($arrayDetalle as $filaArray) {

                $infoEntradaDeta = EntradaMedicamentoDetalle::where('id', $filaArray->entrada_detalle_id)->first();

                // RESTAR

                $resta = $infoEntradaDeta->cantidad - $filaArray->cantidad;

                if($resta < 0){
                    // se esta restando de mas a esta entrada

                    $infoMedicamento = FarmaciaArticulo::where('id', $infoEntradaDeta->medicamento_id)->first();

                    $fechaVencimiento = date("d-m-Y", strtotime($infoEntradaDeta->fecha_vencimiento));

                    return ['success' => 2, 'nombre' => $infoMedicamento->nombre,
                        'cantidadhay' => $infoEntradaDeta->cantidad,
                        'lote' => $infoEntradaDeta->lote,
                        'fechavencimiento' => $fechaVencimiento,
                        'cantidadsalida' => $filaArray->cantidad];
                }

                EntradaMedicamentoDetalle::where('id', $filaArray['idEntradaDetalle'])->update([
                    'cantidad' => $resta
                ]);


                $newDetalle = new SalidaRecetaDetalle();
                $newDetalle->salidareceta_id = $salida->id;
                $newDetalle->entrada_detalle_id = $filaArray->entrada_detalle_id;
                $newDetalle->cantidad = $filaArray->cantidad;
                $newDetalle->save();
            }

            // actualizar estado

            Recetas::where('id', $request->idreceta)->update([
                'estado' => 2 // PROCESADO
            ]);

            DB::commit();
            return ['success' => 3];

        }catch(\Throwable $e){
            DB::rollback();
            Log::info('err ' . $e);
            return ['success' => 99];
        }
    }



    public function guardarExtraContenidoFarmaceutica(Request $request){

        $regla = array(
            'idtipo' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        $dato = new ContenidoFarmaceutica();
        $dato->tipo_farmaceutica_id = $request->idtipo;
        $dato->nombre = $request->nombre;
        $dato->save();

        if($request->idtipo == 1){
            $lista = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 1)
                ->orderBy('nombre', 'ASC')->get();

            return ['success' => 1, 'lista' => $lista];
        }
        else if($request->idtipo == 2){
            $lista = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 2)
                ->orderBy('nombre', 'ASC')->get();

            return ['success' => 2, 'lista' => $lista];
        }
        else if($request->idtipo == 3){
            $lista = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 3)
                ->orderBy('nombre', 'ASC')->get();

            return ['success' => 3, 'lista' => $lista];
        }
        else if($request->idtipo == 4){
            $lista = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 4)
                ->orderBy('nombre', 'ASC')->get();

            return ['success' => 4, 'lista' => $lista];
        }else{
            $lista = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 5)
                ->orderBy('nombre', 'ASC')->get();

            return ['success' => 5, 'lista' => $lista];
        }
    }




    // ------ EXISTENCIAS DE FARMACIA ------


    public function indexExistenciaFarmacia(){

        $arrayFuenteFina = FuenteFinanciamiento::orderBy('nombre', 'ASC')->get();
        $arrayLinea = Linea::orderBy('nombre', 'ASC')->get();


        return view('backend.admin.farmacia.existencia.vistaexistenciasfarmacia', compact('arrayFuenteFina',
        'arrayLinea'));
    }


    public function tablaExistenciaFarmacia($idfuente, $idlinea){

        // TODAS LAS FUENTES Y TODAS LAS LINEAS
        if($idfuente == '0' && $idlinea == '0'){

            // TODOS LOS MEDICAMENTOS
            $arrayListado = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();

            foreach ($arrayListado as $dato){

                $infoLinea = Linea::where('id', $dato->linea_id)->first();
                $dato->nombreLinea = $infoLinea->nombre;

                $nomSubLinea = "";

                if($infoSubLinea = SubLinea::where('id', $dato->sublinea_id)->first()){
                    $nomSubLinea = $infoSubLinea->nombre;
                }
                $dato->nombreSubLinea = $nomSubLinea;

                // SUMANDO LOS ARTICULOS DE ENTRADAS

                $sumatoria = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)
                            ->sum('cantidad');

                $dato->totalArticulo = $sumatoria;
            }

            return view('backend.admin.farmacia.existencia.tablaexistenciasfarmacia', compact('arrayListado'));
        }

        // BUSCAR POR UNA FUENTE PERO TODAS LAS LINEAS
        if($idfuente != '0' && $idlinea == '0') {

            // BUSCAR POR FUENTE DE FINANCIAMIENTO, TODOS LOS ARTICULOS QUE TENGAN REGISTRADO ESTE TIPO DE FUENTE

            // obtener listado de id medicamento que pertenecen a una fuente de financiamiento
            $listado = DB::table('entrada_medicamento AS en')
                ->join('entrada_medicamento_detalle AS deta', 'deta.entrada_medicamento_id', '=', 'en.id')
                ->select('en.fuentefina_id', 'deta.medicamento_id')
                ->where('en.fuentefina_id', $idfuente)
                ->get();

            $pilaIdArticulo = array();

            foreach ($listado as $info) {
                array_push($pilaIdArticulo, $info->medicamento_id);
            }

            // obtener listado filtrado de solo id articulos
            $arrayListado = FarmaciaArticulo::whereIn('id', $pilaIdArticulo)
                ->orderBy('nombre', 'ASC')->get();

            foreach ($arrayListado as $dato){

                $infoLinea = Linea::where('id', $dato->linea_id)->first();
                $dato->nombreLinea = $infoLinea->nombre;

                $nomSubLinea = "";

                if($infoSubLinea = SubLinea::where('id', $dato->sublinea_id)->first()){
                    $nomSubLinea = $infoSubLinea->nombre;
                }
                $dato->nombreSubLinea = $nomSubLinea;

                // SUMANDO LOS ARTICULOS DE ENTRADAS

                $sumatoria = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)
                    ->sum('cantidad');


                $dato->totalArticulo = $sumatoria;
            }

            return view('backend.admin.farmacia.existencia.tablaexistenciasfarmacia', compact('arrayListado'));
        }

        if($idfuente == '0' && $idlinea != '0') {

            // BUSCAR POR UNA LINEA PERO TODAS LAS FUENTE
            $arrayListado = FarmaciaArticulo::where('linea_id', $idlinea)
                ->orderBy('nombre', 'ASC')
                ->get();

            foreach ($arrayListado as $dato){

                $infoLinea = Linea::where('id', $dato->linea_id)->first();
                $dato->nombreLinea = $infoLinea->nombre;

                $nomSubLinea = "";

                if($infoSubLinea = SubLinea::where('id', $dato->sublinea_id)->first()){
                    $nomSubLinea = $infoSubLinea->nombre;
                }
                $dato->nombreSubLinea = $nomSubLinea;

                // SUMANDO LOS ARTICULOS DE ENTRADAS

                $sumatoria = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)
                    ->sum('cantidad');


                $dato->totalArticulo = $sumatoria;
            }

            return view('backend.admin.farmacia.existencia.tablaexistenciasfarmacia', compact('arrayListado'));
        }


        // BUSCAR POR UNA FUENTE Y POR UNA LINEA

        $listado = DB::table('entrada_medicamento AS en')
            ->join('entrada_medicamento_detalle AS deta', 'deta.entrada_medicamento_id', '=', 'en.id')
            ->select('en.fuentefina_id', 'deta.medicamento_id')
            ->where('en.fuentefina_id', $idfuente)
            ->get();

        $pilaIdArticulo = array();

        foreach ($listado as $info) {
            array_push($pilaIdArticulo, $info->medicamento_id);
        }

        // obtener listado filtrado de solo id articulos
        $arrayListado = FarmaciaArticulo::whereIn('id', $pilaIdArticulo)
            ->where('linea_id', $idlinea)
            ->orderBy('nombre', 'ASC')
            ->get();

        foreach ($arrayListado as $dato){

            $infoLinea = Linea::where('id', $dato->linea_id)->first();
            $dato->nombreLinea = $infoLinea->nombre;

            $nomSubLinea = "";

            if($infoSubLinea = SubLinea::where('id', $dato->sublinea_id)->first()){
                $nomSubLinea = $infoSubLinea->nombre;
            }
            $dato->nombreSubLinea = $nomSubLinea;

            // SUMANDO LOS ARTICULOS DE ENTRADAS

            $sumatoria = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)
                ->sum('cantidad');


            $dato->totalArticulo = $sumatoria;
        }

        return view('backend.admin.farmacia.existencia.tablaexistenciasfarmacia', compact('arrayListado'));
    }




    // VER LISTADO DE ENTRADAS DE UN MEDICAMENTO PARA VER SU CANTIDAD ACTUAL
    public function vistaEntradaDetalleArticuloCantidad($idarticulo){

        $infoArticulo = FarmaciaArticulo::where('id', $idarticulo)->first();
        $articulo = $infoArticulo->nombre;

        return view('backend.admin.farmacia.existencia.detalle.vistadetalleexistencias', compact('idarticulo',
            'articulo'));
    }

    public function tablaEntradaDetalleArticuloCantidad($idarticulo){

        $arrayListado = DB::table('entrada_medicamento_detalle AS en')
            ->join('farmacia_articulo AS fa', 'en.medicamento_id', '=', 'fa.id')
            ->select('fa.nombre AS nombreMedicamento', 'en.entrada_medicamento_id', 'en.cantidad', 'en.precio',
                    'en.lote', 'en.fecha_vencimiento')
            ->where('en.medicamento_id', $idarticulo)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        foreach ($arrayListado as $dato){

            $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
            $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));

            $infoEntrada = EntradaMedicamento::where('id', $dato->entrada_medicamento_id)->first();
            $dato->fechaEntradaFormat = date("d-m-Y", strtotime($infoEntrada->fecha));
            $dato->numfactura = $infoEntrada->numero_factura;

            $infoProveedor = Proveedores::where('id', $infoEntrada->proveedor_id)->first();
            $dato->nombreProveedor = $infoProveedor->nombre;

            $infoFuente = FuenteFinanciamiento::where('id', $infoEntrada->fuentefina_id)->first();
            $dato->nombreFuente = $infoFuente->nombre;
        }


        return view('backend.admin.farmacia.existencia.detalle.tabladetalleexistencias', compact('idarticulo', 'arrayListado'));
    }



    // ---------- CATALOGO DE FARMACIA -----------


    public function indexCatalogoFarmacia(){



        return view('backend.admin.farmacia.catalogo.vistacatalogofarmacia');
    }


    public function tablaCatalogoFarmacia(){


        $arrayListado = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();

        foreach ($arrayListado as $dato){

            $infoLinea = Linea::where('id', $dato->linea_id)->first();
            $dato->nombreLinea = $infoLinea->nombre;

            $nomSubLinea = "";

            if($infoSubLinea = SubLinea::where('id', $dato->sublinea_id)->first()){
                $nomSubLinea = $infoSubLinea->nombre;
            }
            $dato->nombreSubLinea = $nomSubLinea;
        }

        return view('backend.admin.farmacia.catalogo.tablacatalogofarmacia', compact('arrayListado'));
    }



    public function vistaEditarArticuloCatalogo($idarticulo){

        $infoArticulo = FarmaciaArticulo::where('id', $idarticulo)->first();

        $arrayLinea = Linea::orderBy('nombre','ASC')->get();
        $arraySubLinea = SubLinea::orderBy('nombre','ASC')->get();


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


        $tieneExtras = 0;
        $infoArticuloMedi = null;
        $nombreGenerico = "";
        if($infoMedi = ArticuloMedicamento::where('farmacia_articulo_id', $idarticulo)->first()){
            $tieneExtras = 1;
            $infoArticuloMedi = $infoMedi;
            $nombreGenerico = $infoMedi->nombre_generico;
        }


        return view('backend.admin.farmacia.catalogo.vistaeditarcatalogo', compact('infoArticulo',
        'arrayLinea', 'arraySubLinea', 'arrayEnvase', 'arrayFormaFarmaceutica', 'arrayConcentracion',
            'arrayContenido', 'arrayAdministracion', 'tieneExtras',
            'infoArticuloMedi', 'idarticulo', 'nombreGenerico'));
    }



    public function actualizarArticuloCatalogo(Request $request){

        $regla = array(
            'idArticulo' => 'required',
            'idLinea' => 'required',
            'nombre' => 'required',
            'infoextra' => 'required' // saber si agregara datos extras
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            FarmaciaArticulo::where('id', $request->idArticulo)->update([
                'linea_id' => $request->idLinea,
                'sublinea_id' => $request->idSubLinea,
                'nombre' => $request->nombre,
                'codigo_articulo' => $request->codigoArticulo,
                'existencia_minima' => $request->existencia,
            ]);


            if(ArticuloMedicamento::where('farmacia_articulo_id', $request->idArticulo)->first()){

                ArticuloMedicamento::where('farmacia_articulo_id', $request->idArticulo)->update([
                    'con_far_envase_id' => $request->idEnvase,
                    'con_far_forma_id' => $request->idFormaFarma,
                    'con_far_concentracion_id' => $request->idConcentracion,
                    'con_far_contenido_id' => $request->idContenido,
                    'con_far_administra_id' => $request->idAdministracion,
                    'nombre_generico' => $request->nombreGenerico,
                ]);
            }
            else if($request->infoextra == 1){

                // CREAR AL NUEVO DATOS EXTRA

                $datoNuevo = new ArticuloMedicamento();
                $datoNuevo->farmacia_articulo_id = $request->idArticulo;
                $datoNuevo->con_far_envase_id = $request->idEnvase;
                $datoNuevo->con_far_forma_id = $request->idFormaFarma;
                $datoNuevo->con_far_concentracion_id = $request->idConcentracion;
                $datoNuevo->con_far_contenido_id = $request->idContenido;
                $datoNuevo->con_far_administra_id = $request->idAdministracion;
                $datoNuevo->nombre_generico = $request->nombreGenerico;
                $datoNuevo->save();
            }

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    // ESTO ES PARA AGREGARLE MAS MEDICAMENTOS A LA LISTA, OSEA LA MISMA FACTURA
    public function vistaListadoEntradasRegistradas(){

        return view('backend.admin.farmacia.editarentrada.vistalistadoregistrados');
    }


    public function tablaListadoEntradasRegistradas(){

        $arrayRegistros = EntradaMedicamento::orderBy('fecha', 'DESC')->get();

        foreach ($arrayRegistros as $dato){

             $infoTipoFac = TipoFactura::where('id', $dato->tipofactura_id)->first();
             $dato->nombreTipoFac = $infoTipoFac->nombre;

             $infoFuenteFina = FuenteFinanciamiento::where('id', $dato->fuentefina_id)->first();
             $dato->nombreFina = $infoFuenteFina->nombre;

             $infoProveedor = Proveedores::where('id', $dato->proveedor_id)->first();
             $dato->nombreProve = $infoProveedor->nombre;

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
        }


        return view('backend.admin.farmacia.editarentrada.tablalistadoregistrados', compact('arrayRegistros'));
    }


    public function vistaEditarEntrada($identrada){

        $infoEntrada = EntradaMedicamento::where('id', $identrada)->first();

        $arrayFuente = FuenteFinanciamiento::orderBy('nombre', 'ASC')->get();
        $arrayTipoFac = TipoFactura::orderBy('nombre', 'ASC')->get();
        $arrayProvee = Proveedores::orderBy('nombre', 'ASC')->get();

        $fechaFormat = Carbon::parse($infoEntrada->fecha)->format('Y-m-d');


        $arrayDetalle = DB::table('entrada_medicamento_detalle AS deta')
            ->join('farmacia_articulo AS fama', 'fama.id', '=', 'deta.medicamento_id')
            ->select('fama.nombre', 'deta.entrada_medicamento_id', 'deta.cantidad_fija', 'deta.precio', 'deta.lote', 'deta.fecha_vencimiento')
            ->where('deta.entrada_medicamento_id', $identrada)
            ->orderBy('fama.nombre', 'ASC')
            ->get();

        $contador = 0;
        foreach ($arrayDetalle as $dato){
            $contador++;
            $dato->contador = $contador;

            $dato->fechaVencimiento = date("d-m-Y", strtotime($dato->fecha_vencimiento));
            $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');



        }

        return view('backend.admin.farmacia.editarentrada.vistaeditarentrada', compact('identrada',
                'infoEntrada', 'arrayFuente', 'arrayTipoFac', 'arrayProvee', 'fechaFormat',
                            'arrayDetalle'));
    }



}
