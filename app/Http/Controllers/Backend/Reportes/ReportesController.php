<?php

namespace App\Http\Controllers\backend\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Consulta_Paciente;
use App\Models\Diagnosticos;
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
use App\Models\SubLinea;
use App\Models\TipoFactura;
use App\Models\Usuario;
use App\Models\ViaReceta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function vistaReporteEntradas(){

        $arrayFuente = FuenteFinanciamiento::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.reportes.entradas.vistareporteentradas', compact('arrayFuente'));
    }


    public function reporteEntradaArticulos($idfuente, $desde, $hasta){


        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $nombreFuente = "Todos";

        if($infoFuente = FuenteFinanciamiento::where('id', $idfuente)->first()){
            $nombreFuente = $infoFuente->nombre;
        }

        $resultsBloque = array();
        $index = 0;

        if($idfuente == '0'){
            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();
        }else{
            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
                ->where('fuentefina_id', $idfuente)
                ->orderBy('fecha', 'ASC')
                ->get();
        }

        $totalGeneral = 0; // sumatoria de todas las fuentes de financiamiento
        $totalFundel = 0;
        $totalCovid = 0;
        $totalPropios = 0;


        foreach ($arrayEntradas as $infoFila){

            array_push($resultsBloque, $infoFila);

            $infoFila->fechaFormat = date("d-m-Y", strtotime($infoFila->fecha));

            $infoFactura = TipoFactura::where('id', $infoFila->tipofactura_id)->first();
            $infoFila->tipofactura = $infoFactura->nombre;

            $infoProveedor = Proveedores::where('id', $infoFila->proveedor_id)->first();
            $infoFila->nombreprove = $infoProveedor->nombre;

            $infoFuente = FuenteFinanciamiento::where('id', $infoFila->fuentefina_id)->first();
            $infoFila->nombrefuente = $infoFuente->nombre;

            $arrayDetalle = DB::table('entrada_medicamento_detalle AS deta')
                ->join('farmacia_articulo AS fa', 'fa.id', '=', 'deta.medicamento_id')
                ->select('fa.nombre', 'deta.entrada_medicamento_id', 'deta.cantidad_fija', 'deta.precio',
                        'deta.lote', 'deta.fecha_vencimiento')
                ->where('deta.entrada_medicamento_id', $infoFila->id)
                ->orderBy('fa.nombre', 'ASC')
                ->get();

                $totalXColumna = 0;

                foreach ($arrayDetalle as $dato){

                    $multi = $dato->cantidad_fija * $dato->precio;
                    $totalXColumna = $totalXColumna + $multi;

                    $dato->multiFormat = '$' . number_format((float)$multi, 2, '.', ',');
                    $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));
                    $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
                }


             // SUMATORIAS PARA FUENTE DE FINANCIAMIENTO

            if($infoFila->fuentefina_id == 1){
                $totalFundel = $totalFundel + $totalXColumna;
            }

            if($infoFila->fuentefina_id == 2){
                $totalCovid = $totalCovid + $totalXColumna;
            }

            if($infoFila->fuentefina_id == 3){
                $totalPropios = $totalPropios + $totalXColumna;
            }

            $totalGeneral = $totalGeneral + $totalXColumna;

            $infoFila->totalxfilas = '$' . number_format((float)$totalXColumna, 2, '.', ',');

            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }


        $totalFundel = '$' . number_format((float)$totalFundel, 2, '.', ',');
        $totalCovid = '$' . number_format((float)$totalCovid, 2, '.', ',');
        $totalPropios = '$' . number_format((float)$totalPropios, 2, '.', ',');

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');



        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Entrada Artículos');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            Reporte de Entradas<br>
            Fecha:  $desdeFormat - $hastaFormat</p>
            </div>";

        $tabla .= "<div>
                    <p>Fuente de Financiamiento: $nombreFuente</p>
                    </div>";

        foreach ($arrayEntradas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Entrada</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Factura</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'># Factura</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Proveedor</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Fuente F.</td>
            <tr>";


            $tabla .= "<tr>
                <td>$detaFila->fechaFormat</td>
                <td>$detaFila->tipofactura</td>
                <td>$detaFila->numero_factura</td>
                <td>$detaFila->nombreprove</td>
                <td>$detaFila->nombrefuente</td>
            <tr>";


            $tabla .= "</tbody></table>";


            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Venc.</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Artículo</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Monto</td>
            <tr>";


            foreach ($detaFila->detallefila as $dato) {
                $tabla .= "<tr>
                <td>$dato->fechaVencFormat</td>
                <td>$dato->nombre</td>
                <td>$dato->lote</td>
                <td>$dato->cantidad_fija</td>
                <td>$dato->precioFormat</td>
                <td>$dato->multiFormat</td>
            <tr>";
            }

            $tabla .= "<tr>
                <td colspan='5'>Total</td>
                <td>$detaFila->totalxfilas</td>
            <tr>";

            $tabla .= "</tbody></table>";
        }



        // PRECIO FINAL DE TODAS LAS SALIDAS

        if($idfuente == '0') {

            $tabla .= "<div style='margin-top: 30px'>
            <p id='textoFinal'>Materiales FUNDEL:  $totalFundel<br>
            <p id='textoFinal'>Materiales COVID:  $totalCovid<br>
            <p id='textoFinal'>Fondos PROPIOS:  $totalPropios<br>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";
        }else{

            if($idfuente == '1'){
                $tabla .= "<div style='margin-top: 30px'>
                    <p id='textoFinal'>Materiales FUNDEL:  $totalFundel<br>
                </div>";
            }
            else if($idfuente == '2'){
                $tabla .= "<div style='margin-top: 30px'>
                    <p id='textoFinal'>Materiales COVID:  $totalCovid<br>
                </div>";
            }else{
                $tabla .= "<div style='margin-top: 30px'>
                    <p id='textoFinal'>Materiales PROPIOS:  $totalPropios<br>
                </div>";
            }

        }


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }





    public function vistaReporteSalidaManual(){

        return view('backend.admin.reportes.manual.vistareportesalidamanual');
    }



    public function reporteSalidaManual($desde, $hasta){

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $arraySalidas = OrdenSalida::whereBetween('fecha', [$start, $end])
            ->orderBy('fecha', 'ASC')
            ->get();

        $resultsBloque = array();
        $index = 0;
        $totalGeneral = 0;

        foreach ($arraySalidas as $infoFila){

            array_push($resultsBloque, $infoFila);

            $infoUsuario = Usuario::where('id', $infoFila->usuario_id)->first();
            $infoFila->nombreUser = $infoUsuario->nombre;

            $infoMotivo = MotivoFarmacia::where('id', $infoFila->motivo_id)->first();
            $infoFila->nombremotivo = $infoMotivo->nombre;

            $infoFila->fechaFormat = date("d-m-Y", strtotime($infoFila->fecha));

            $infoFila->horaFormat = date("h:i A", strtotime($infoFila->hora));

            $arrayDetalle = DB::table('orden_salida_detalle AS deta')
                ->join('entrada_medicamento_detalle AS enta', 'enta.id', '=', 'deta.entrada_medi_detalle_id')
                ->join('farmacia_articulo AS fama', 'fama.id', '=', 'enta.medicamento_id')
                ->select('fama.nombre', 'deta.cantidad', 'deta.orden_salida_id', 'enta.precio', 'enta.lote')
                ->where('deta.orden_salida_id', $infoFila->id)
                ->orderBy('fama.nombre', 'ASC')
                ->get();

            $totalXColumna = 0;

            foreach ($arrayDetalle as $dato){

                $multi = $dato->cantidad * $dato->precio;
                $totalXColumna = $totalXColumna + $multi;

                $dato->multiFormat = '$' . number_format((float)$multi, 2, '.', ',');
                $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
            }

            $totalGeneral = $totalGeneral + $totalXColumna;

            $infoFila->totalXcolumna = '$' . number_format((float)$totalXColumna, 2, '.', ',');

            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Salida Manual');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            Reporte de Salida Manual<br>
            Fecha:  $desdeFormat - $hastaFormat</p>
            </div>";


        foreach ($arraySalidas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Salida</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Hora</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Motivo</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Usuario Descargo</td>
            <tr>";


            $tabla .= "<tr>
                <td>$detaFila->fechaFormat</td>
                <td>$detaFila->horaFormat</td>
                <td>$detaFila->nombremotivo</td>
                <td>$detaFila->nombreUser</td>
            <tr>";


            $tabla .= "</tbody></table>";



            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Artículo.</td>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Lote.</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Monto</td>
            <tr>";

            foreach ($detaFila->detallefila as $dato) {
                $tabla .= "<tr>
                <td>$dato->nombre</td>
                <td>$dato->lote</td>
                <td>$dato->cantidad</td>
                <td>$dato->precioFormat</td>
                <td>$dato->multiFormat</td>
            <tr>";
            }

            $tabla .= "<tr>
                <td colspan='4'>Total</td>
                <td>$detaFila->totalXcolumna</td>
            <tr>";

            $tabla .= "</tbody></table>";


            // OBSERVACIONES

            if($infoFila->observaciones != null){

                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                    <td style='font-weight: bold; width: 11%; font-size: 14px'>Observaciones</td>
                <tr>";

                $tabla .= "<tr>
                    <td>$infoFila->observaciones</td>
                <tr>";

                $tabla .= "</tbody></table>";
            }
        }


        // PRECIO FINAL DE TODAS LAS SALIDAS


        $tabla .= "<div style='margin-top: 30px'>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }



    public function vistaReporteSalidaRecetas(){

        return view('backend.admin.reportes.recetas.vistareporterecetas');
    }


    public function reporteSalidaRecetasEstadosSeparados($idestado, $desde, $hasta){

        // 2- procesados
        // 3- denegados

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $nombreEstado = "Procesados";
        $tituloReporte = "Reporte Recetas Procesadas";

        if($idestado == '3'){
            $nombreEstado = "Denegados";
            $tituloReporte = "Reporte Recetas Denegadas";
        }

        $resultsBloque = array();
        $index = 0;

        if($idestado == '2'){ // RECETA PROCESADA
            $arraySalidaRecetas = DB::table('salida_receta AS sa')
                ->join('recetas AS re', 're.id', '=', 'sa.recetas_id')
                ->select('re.estado', 're.consulta_id', 're.via_id',
                    're.diagnostico_id', 're.usuario_id', 're.descripcion_general',
                    'sa.usuario_id AS usuarioDescargo', 'sa.fecha', 'sa.notas',
                    're.fecha_estado AS fechaEstadoDenegada', 'sa.id AS idSalidaReceta')
                ->where('re.estado', 2) // PROCESADOS
                ->whereBetween('sa.fecha', [$start, $end])
                ->orderBy('sa.fecha', 'ASC')
                ->get();

            // re.fecha_estado: es cuando


        }else{
            $arraySalidaRecetas = Recetas::where('estado', 3) // DENEGADOS
                ->whereBetween('fecha_estado', [$start, $end]) // se busca por fecha denegada
                ->orderBy('fecha_estado', 'ASC')
                ->get();
        }


        $totalGeneral = 0; // sumatoria de todas las fuentes de financiamiento


        if($idestado == '2'){ // SOLO PARA PROCESADOS

            foreach ($arraySalidaRecetas as $infoFila){

                array_push($resultsBloque, $infoFila);

                $infoFila->fechaSalidaRecetaFormat = date("d-m-Y", strtotime($infoFila->fecha));


                $infoConsulta = Consulta_Paciente::where('id', $infoFila->consulta_id)->first();
                $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

                $infoFila->nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;


                $infoViaReceta = ViaReceta::where('id', $infoFila->via_id)->first();
                $infoFila->nombreViaReceta = $infoViaReceta->nombre;


                $infoDiagnostico = Diagnosticos::where('id', $infoFila->diagnostico_id)->first();
                $infoFila->nombreDiagnostico = $infoDiagnostico->nombre;

                // doctor creo la receta
                $infoUsuario = Usuario::where('id', $infoFila->usuario_id)->first();
                $infoFila->doctorReceto = $infoUsuario->nombre;

                // usuario que despacho la receta
                $infoUsuario = Usuario::where('id', $infoFila->usuarioDescargo)->first();
                $infoFila->usuarioDespachoReceta = $infoUsuario->nombre;


                // DETALLE DE LOS ARTICULOS ENTREGADOS

                $arrayDetalle = DB::table('salida_receta_detalle AS sa')
                    ->join('entrada_medicamento_detalle AS deta', 'sa.entrada_detalle_id', '=', 'deta.id')
                    ->join('farmacia_articulo AS fama', 'deta.medicamento_id', '=', 'fama.id')
                    ->select('fama.nombre', 'sa.salidareceta_id', 'sa.cantidad', 'deta.precio',
                            'deta.lote')
                    ->where('sa.salidareceta_id', $infoFila->idSalidaReceta)
                    ->orderBy('fama.nombre', 'ASC')
                    ->get();

                $totalXColumna = 0;

                foreach ($arrayDetalle as $dato) {

                    $multi = $dato->precio * $dato->cantidad;

                    $totalXColumna = $totalXColumna + $multi;


                    $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
                    $dato->multiFilaFormat = '$' . number_format((float)$multi, 2, '.', ',');
                }

                $totalGeneral = $totalGeneral + $totalXColumna;

                $infoFila->totalXColumna = '$' . number_format((float)$totalXColumna, 2, '.', ',');

                $resultsBloque[$index]->detallefila = $arrayDetalle;
                $index++;
            } // end foreach

            $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');

        }else{

            // SOLO PARA DENEGADOS

            foreach ($arraySalidaRecetas as $infoFila){

                array_push($resultsBloque, $infoFila);

                // fecha de receta
                $infoFila->fechaRecetaFormat = date("d-m-Y", strtotime($infoFila->fecha));
                $infoFila->fechaEstadoDenegadaFormat = date("d-m-Y", strtotime($infoFila->fecha_estado));

                $infoConsulta = Consulta_Paciente::where('id', $infoFila->consulta_id)->first();
                $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

                $infoFila->nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

                $infoViaReceta = ViaReceta::where('id', $infoFila->via_id)->first();
                $infoFila->nombreViaReceta = $infoViaReceta->nombre;

                $infoDiagnostico = Diagnosticos::where('id', $infoFila->diagnostico_id)->first();
                $infoFila->nombreDiagnostico = $infoDiagnostico->nombre;

                $infoUsuario = Usuario::where('id', $infoFila->usuario_id)->first();
                $infoFila->doctorReceto = $infoUsuario->nombre;

                $infoUsuarioDenegado = Usuario::where('id', $infoFila->usuario_estado_id)->first();
                $infoFila->usuarioDenegoReceta = $infoUsuarioDenegado->nombre;

                // DETALLE DE LOS ARTICULOS DE LA RECETA DENEGADOS

                $arrayDetalle = DB::table('recetas_detalle AS re')
                    ->join('farmacia_articulo AS fama', 're.medicamento_id', '=', 'fama.id')
                    ->select('fama.nombre', 're.recetas_id', 're.cantidad', 're.descripcion')
                    ->where('re.recetas_id', $infoFila->id)
                    ->orderBy('fama.nombre', 'ASC')
                    ->get();

                /*foreach ($arrayDetalle as $dato){

                }*/

                $resultsBloque[$index]->detallefila = $arrayDetalle;
                $index++;
            }
        }


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        if($idestado == '2'){
            $mpdf->SetTitle('Recetas Procesadas');
        }else{
            $mpdf->SetTitle('Recetas Denegadas');
        }


        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            $tituloReporte<br>
            Fecha:  $desdeFormat - $hastaFormat</p>
            </div>";

        $tabla .= "<div>
                    <p>Estado: $nombreEstado</p>
                    </div>";


        if($idestado == '2'){ // SOLO PROCESADOS

            foreach ($arraySalidaRecetas as $detaFila) {

                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Salida</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Paciente</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Vía</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Diagnóstico</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Doctor</td>
            <tr>";


                $tabla .= "<tr>
                <td>$detaFila->fechaSalidaRecetaFormat</td>
                <td>$detaFila->nombrePaciente</td>
                <td>$detaFila->nombreViaReceta</td>
                <td>$detaFila->nombreDiagnostico</td>
                <td>$detaFila->doctorReceto</td>
            <tr>";


                $tabla .= "</tbody></table>";


                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Artículo.</td>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Lote.</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Monto</td>
            <tr>";

                foreach ($detaFila->detallefila as $dato) {
                    $tabla .= "<tr>
                    <td>$dato->nombre</td>
                    <td>$dato->lote</td>
                    <td>$dato->cantidad</td>
                    <td>$dato->precioFormat</td>
                    <td>$dato->multiFilaFormat</td>
                <tr>";
                }

                $tabla .= "<tr>
                <td colspan='4'>Total</td>
                <td>$infoFila->totalXColumna</td>
            <tr>";

                $tabla .= "</tbody></table>";

            } // endforeach


            $tabla .= "<div style='margin-top: 30px'>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";


        }else{

            // SOLO DENEGADOS

            foreach ($arraySalidaRecetas as $detaFila) {

                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Denegado</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Paciente</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Vía</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Diagnóstico</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Usuario Denego</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Doctor</td>
            <tr>";


                $tabla .= "<tr>
                <td>$detaFila->fechaEstadoDenegadaFormat</td>
                <td>$detaFila->nombrePaciente</td>
                <td>$detaFila->nombreViaReceta</td>
                <td>$detaFila->nombreDiagnostico</td>
                <td>$detaFila->usuarioDenegoReceta</td>
                <td>$detaFila->doctorReceto</td>

            <tr>";


                $tabla .= "</tbody></table>";


                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Artículo.</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Descripción</td>
            <tr>";

                foreach ($detaFila->detallefila as $dato) {
                    $tabla .= "<tr>
                    <td>$dato->nombre</td>
                    <td>$dato->cantidad</td>
                    <td>$dato->descripcion</td>
                <tr>";
                }


                $tabla .= "</tbody></table>";

                $tabla .= "<div style='margin-top: 30px'>

            <p style='font-weight: bold'>Denegado Por: <span>$detaFila->nota_denegada</span>  <br>
            </div>  <hr>";


            } // endforeach
        }


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }




    public function reporteSalidaRecetasEstadosJuntos($idestado, $desde, $hasta){

        // 2- procesados
        // 3- denegados

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $nombreEstado = "Procesados";
        $tituloReporte = "Reporte Recetas Procesadas";

        if($idestado == '3'){
            $nombreEstado = "Denegados";
            $tituloReporte = "Reporte Recetas Denegadas";
        }


        if($idestado == '2'){ // RECETA PROCESADA
            $arraySalidaRecetas = DB::table('salida_receta AS sa')
                ->join('salida_receta_detalle AS deta', 'sa.id', '=', 'deta.salidareceta_id')
                ->join('recetas AS re', 're.id', '=', 'sa.recetas_id')
                ->select('sa.fecha', 'deta.entrada_detalle_id', 'deta.cantidad', 'deta.salidareceta_id',
                                're.paciente_id', 're.estado')
                ->where('re.estado', 2) // PROCESADOS
                ->whereBetween('sa.fecha', [$start, $end])
                ->orderBy('sa.fecha', 'ASC')
                ->get();
        }else{
            $arraySalidaRecetas = DB::table('recetas AS re')
                ->join('recetas_detalle AS deta', 're.id', '=', 'deta.recetas_id')
                ->select('deta.medicamento_id', 'deta.cantidad', 'deta.descripcion', 're.paciente_id',
                            're.fecha_estado', 're.estado', 're.usuario_estado_id', 're.via_id',
                            're.diagnostico_id')
                ->where('re.estado', 3) // DENEGADOS
                ->whereBetween('re.fecha_estado', [$start, $end])
                ->orderBy('re.fecha_estado', 'ASC')
                ->get();
        }


        $totalGeneral = 0;

        if($idestado == '2'){ // SOLO PARA PROCESADOS

            foreach ($arraySalidaRecetas as $infoFila){

                $infoFila->fechaSalidaFormat = date("d-m-Y", strtotime($infoFila->fecha));

                $infoEntradaDetalle = EntradaMedicamentoDetalle::where('id', $infoFila->entrada_detalle_id)->first();

                $infoMedicamento = FarmaciaArticulo::where('id', $infoEntradaDetalle->medicamento_id)->first();
                $infoFila->nombreArticulo = $infoMedicamento->nombre;

                $infoFila->precioFormat = '$' . number_format((float)$infoEntradaDetalle->precio, 2, '.', ',');

                $infoPaciente = Paciente::where('id', $infoFila->paciente_id)->first();
                $infoFila->nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

                // TOTALES POR FILA Y COLUMNA

                $multi = $infoEntradaDetalle->precio * $infoFila->cantidad;
                $totalGeneral = $totalGeneral + $multi;

                $infoFila->totalXFila = '$' . number_format((float)$multi, 2, '.', ',');


            } // end foreach

            $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');

        }else{

            // SOLO PARA DENEGADOS

            foreach ($arraySalidaRecetas as $infoFila){

                // fecha cuando fue denegada
                $infoFila->fechaEstadoDenegadaFormat = date("d-m-Y", strtotime($infoFila->fecha_estado));

                $infoPaciente = Paciente::where('id', $infoFila->paciente_id)->first();

                $infoFila->nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

                $infoUsuarioDenegado = Usuario::where('id', $infoFila->usuario_estado_id)->first();
                $infoFila->usuarioDenegoReceta = $infoUsuarioDenegado->nombre;


                $infoVia = ViaReceta::where('id', $infoFila->via_id)->first();
                $infoFila->nombreVia = $infoVia->nombre;

                $infoDiagn = Diagnosticos::where('id', $infoFila->diagnostico_id)->first();
                $infoFila->nombreDiagnostico = $infoDiagn->nombre;


            }
        }


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        if($idestado == '2'){
            $mpdf->SetTitle('Recetas Procesadas');
        }else{
            $mpdf->SetTitle('Recetas Denegadas');
        }


        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            $tituloReporte<br>
            Fecha:  $desdeFormat - $hastaFormat</p>
            </div>";

        $tabla .= "<div>
                    <p>Estado: $nombreEstado</p>
                    </div>";


        if($idestado == '2'){ // SOLO PROCESADOS

                $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Salida</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Paciente</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Artículo</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Monto</td>
            <tr>";

            foreach ($arraySalidaRecetas as $detaFila) {

                    $tabla .= "<tr>
                    <td>$detaFila->fechaSalidaFormat</td>
                    <td>$detaFila->nombrePaciente</td>
                    <td>$detaFila->nombreArticulo</td>
                    <td>$detaFila->cantidad</td>
                    <td>$detaFila->precioFormat</td>
                    <td>$detaFila->totalXFila</td>
                <tr>";

            } // endforeach

                $tabla .= "</tbody></table>";

            $tabla .= "<div style='margin-top: 30px'>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";


        }else{

            // SOLO DENEGADOS

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";


            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Denegado</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Paciente</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Vía</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Diagnóstico</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Usuario Denego</td>
            <tr>";

            foreach ($arraySalidaRecetas as $detaFila) {

                $tabla .= "<tr>
                <td>$detaFila->fechaEstadoDenegadaFormat</td>
                <td>$detaFila->nombrePaciente</td>
                <td>$detaFila->nombreVia</td>
                <td>$detaFila->nombreDiagnostico</td>
                <td>$detaFila->usuarioDenegoReceta</td>

            <tr>";
            } // endforeach

            $tabla .= "</tbody></table>";
        }


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();

    }




    public function vistaReporteCatalogo(){


        $arrayLinea = Linea::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.reportes.catalogo.vistareportecatalogo', compact('arrayLinea'));
    }


    public function reporteCatalogoPorLinea($idlinea){


        if($idlinea == '0'){
            $arrayCatalogo = FarmaciaArticulo::orderBy('nombre', 'ASC')
                ->get();
        }else{
            $arrayCatalogo = FarmaciaArticulo::where('linea_id', $idlinea)
                ->orderBy('nombre', 'ASC')
                ->get();
        }


        foreach ($arrayCatalogo as $infoFila){

            $infoLinea = Linea::where('id', $infoFila->linea_id)->first();
            $infoFila->nombreLinea = $infoLinea->nombre;

            $nombreSub = "";
            if($infoSub = SubLinea::where('id', $infoFila->sublinea_id)->first()){
                $nombreSub = $infoSub->nombre;
            }
            $infoFila->nombreSubLinea = $nombreSub;
        }

        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);


        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Catálogo');

        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            Catálogo<br>
            </div>";


            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Línea</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Sub Línea</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Código</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Artículo</td>
            <tr>";

            foreach ($arrayCatalogo as $detaFila) {

                $tabla .= "<tr>
                    <td>$detaFila->nombreLinea</td>
                    <td>$detaFila->nombreSubLinea</td>
                    <td>$detaFila->codigo_articulo</td>
                    <td>$detaFila->nombre</td>
                <tr>";

            } // endforeach

            $tabla .= "</tbody></table>";


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }





    public function vistaReporteExistencias(){

        return view('backend.admin.reportes.existencias.vistareporteexistencias');
    }


    public function reporteExistenciasFormatoSeparados(){

        // VERIFICAR QUE ENTRADAS AUN TIENEN MEDICAMENTO DENTRO DE SU DETALLE AUN


        $arrayIdDeta = EntradaMedicamentoDetalle::where('cantidad', '>', 0)
            ->get();


        $pilaIdEntrada = array();

        foreach ($arrayIdDeta as $info){
            array_push($pilaIdEntrada, $info->entrada_medicamento_id);
        }

        $resultsBloque = array();
        $index = 0;

        $arrayEntradas = EntradaMedicamento::whereIn('id', $pilaIdEntrada)->get();

        $totalGeneral = 0;

        foreach ($arrayEntradas as $infoFila){

            array_push($resultsBloque, $infoFila);

            $infoTipoFact = TipoFactura::where('id', $infoFila->tipofactura_id)->first();
            $infoFuente = FuenteFinanciamiento::where('id', $infoFila->fuentefina_id)->first();
            $infoProveedor = Proveedores::where('id', $infoFila->proveedor_id)->first();

            $infoFila->fechaFormat = date("d-m-Y", strtotime($infoFila->fecha));
            $infoFila->nombreTipoFactura = $infoTipoFact->nombre;
            $infoFila->nombreFuente = $infoFuente->nombre;
            $infoFila->nombreProveedor = $infoProveedor->nombre;

            $totalColumna = 0;

            $arrayDetalle = EntradaMedicamentoDetalle::where('entrada_medicamento_id', $infoFila->id)
                ->orderBy('fecha_vencimiento', 'ASC')
                ->get();

            foreach ($arrayDetalle as $dato){

                $infoArticulo = FarmaciaArticulo::where('id', $dato->medicamento_id)->first();
                $dato->nombreArticulo = $infoArticulo->nombre;

                $dato->fechaVecFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));

                $multiFila = $dato->precio * $dato->cantidad;
                $totalColumna = $totalColumna + $multiFila;

                $dato->precioXFila = '$' . number_format((float)$multiFila, 2, '.', ',');
                $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
            }

            $totalGeneral = $totalGeneral + $totalColumna;

            $totalColumna = '$' . number_format((float)$totalColumna, 2, '.', ',');
            $infoFila->totalColumna = $totalColumna;

            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Existencias');
        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            Existencias<br>
            </div>";

        foreach ($arrayEntradas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Entrada</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Tipo Factura</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'># Factura</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Fuente</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Proveedor</td>
            <tr>";

            $tabla .= "<tr>
                <td>$detaFila->fechaFormat</td>
                <td>$detaFila->nombreTipoFactura</td>
                <td>$detaFila->numero_factura</td>
                <td>$detaFila->nombreFuente</td>
                <td>$detaFila->nombreProveedor</td>
            <tr>";


            $tabla .= "</tbody></table>";


            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Fecha Venc.</td>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Artículo</td>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Monto</td>
            <tr>";

            foreach ($detaFila->detallefila as $dato) {

                if($dato->cantidad > 0){
                    $tabla .= "<tr>
                    <td>$dato->fechaVecFormat</td>
                    <td>$dato->nombreArticulo</td>
                    <td>$dato->lote</td>
                    <td>$dato->cantidad</td>
                    <td>$dato->precioFormat</td>
                    <td>$dato->precioXFila</td>
                <tr>";
                }
            }

            $tabla .= "<tr>
                <td colspan='5'>Total</td>
                <td>$detaFila->totalColumna</td>
            <tr>";

            $tabla .= "</tbody></table>";

        } // endforeach


        $tabla .= "<div style='margin-top: 30px'>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";



        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }







    public function reporteExistenciasFormatoJuntos(){

        $arrayIdDeta = EntradaMedicamentoDetalle::where('cantidad', '>', 0)->get();

        $pilaIdEntrada = array();

        foreach ($arrayIdDeta as $info){
            array_push($pilaIdEntrada, $info->entrada_medicamento_id);
        }

        $arrayEntradasDetalle =  DB::table('entrada_medicamento_detalle AS deta')
            ->join('farmacia_articulo AS fa', 'fa.id', '=', 'deta.medicamento_id')
            ->select('fa.nombre', 'deta.entrada_medicamento_id', 'deta.cantidad', 'deta.precio',
                'deta.lote', 'deta.fecha_vencimiento')
            ->whereIn('deta.entrada_medicamento_id', $pilaIdEntrada)
            ->where('deta.cantidad', '>', 0)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        $totalGeneral = 0;

        foreach ($arrayEntradasDetalle as $infoFila){

            $infoFila->fechaVencFormat = date("d-m-Y", strtotime($infoFila->fecha_vencimiento));


            $multiFila = $infoFila->cantidad * $infoFila->precio;
            $totalGeneral = $totalGeneral + $multiFila;

            $infoFila->multiFila = '$' . number_format((float)$multiFila, 2, '.', ',');
            $infoFila->precioFormat = '$' . number_format((float)$infoFila->precio, 2, '.', ',');
        }

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Existencias');
        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza<br>
            Tahuilapa Metapán<br>
            Existencias<br>
            </div>";



            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 14px'>Artículo</td>
                <td style='font-weight: bold; width: 9%; font-size: 14px'>Fecha Vencimiento</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 14px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Monto</td>
            <tr>";

        foreach ($arrayEntradasDetalle as $detaFila) {
            $tabla .= "<tr>
                <td>$detaFila->nombre</td>
                <td>$detaFila->fechaVencFormat</td>
                <td>$detaFila->lote</td>
                <td>$detaFila->cantidad</td>
                <td>$detaFila->precioFormat</td>
                <td>$detaFila->multiFila</td>
            <tr>";
        }

        $tabla .= "</tbody></table>";


        $tabla .= "<div style='margin-top: 30px'>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            </div>";

        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }



    public function reporteRecetaPaciente($idreceta){


        $infoReceta = Recetas::where('id', $idreceta)->first();
        $infoPaciente = Paciente::where('id', $infoReceta->paciente_id)->first();
        $nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $fechaReceta = date("d-m-Y", strtotime($infoReceta->fecha));

        $fechaProxCita = "";
        if($infoReceta->proxima_cita != null){
            $fechaProxCita = date("d-m-Y", strtotime($infoReceta->proxima_cita));

        }


        $arrayRecetaDeta = DB::table('recetas_detalle AS deta')
            ->join('farmacia_articulo AS fa', 'fa.id', '=', 'deta.medicamento_id')
            ->select('fa.nombre', 'deta.recetas_id', 'deta.cantidad', 'deta.descripcion',
                            'deta.via_id')
            ->where('deta.recetas_id', $idreceta)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        foreach ($arrayRecetaDeta as $info){

            $infoVia = ViaReceta::where('id', $info->via_id)->first();
            $info->nombreVia = $infoVia->nombre;

        }


        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Receta');
        $logoalcaldia = 'images/logo2.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Unidad de Salud Cristobal Peraza <br>
            Tahuilapa
            </div>";


        $tabla .= "

             <table width='100%'>
                <tr>
                    <td style='text-align: left; width: 33%'>
                        <!-- Contenido izquierdo -->
                        <p style='font-size: 13px'><strong>Paciente: </strong>$nombrePaciente</p>
                    </td>
                    <td style='text-align: center; width: 34%'>
                        <!-- Contenido central -->
                         <p style='font-size: 13px'><strong>Edad: </strong>$edad</p>
                    </td>
                    <td style='text-align: right; width: 33%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 13px'><strong>Fecha: </strong>$fechaReceta</p>
                    </td>
                </tr> ";

        if($infoReceta->proxima_cita != null){
            $tabla .="<tr>
                    <td style='text-align: left; width: 33%'>
                        <!-- Contenido izquierdo -->
                        <p style='font-size: 13px'><strong></strong></p>
                    </td>
                    <td style='text-align: center; width: 34%'>
                        <!-- Contenido central -->
                         <p style='font-size: 13px'><strong></strong></p>
                    </td>
                    <td style='text-align: right; width: 40%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 13px'><strong>Proxima Consulta: </strong>$fechaProxCita</p>
                    </td>
                </tr> ";
        }

        $tabla .= "</table>

    <hr style='color: #0c84ff'>
                ";

        $vueltas = 0;

        foreach ($arrayRecetaDeta as $dato){
                $vueltas++;
                if($vueltas == 0){
                    $tabla .= "
                    <table width='100%' style='margin-top: 0px'>
                    ";
                }else{
                    $tabla .= "
                    <table width='100%' style='margin-top: 20px'>
                    ";
                }

                $tabla .= " <tr>
                    <td style='text-align: left; width: 33%'>
                        <!-- Contenido izquierdo -->

                         <p style='font-size: 13px'><strong><ul><li>$dato->nombre</li></ul></strong></p>

                    </td>
                    <td style='text-align: center; width: 34%'>
                        <!-- Contenido central -->
                         <p style='font-size: 13px'><strong>Cantidad: </strong>$dato->cantidad</p>
                    </td>
                    <td style='text-align: right; width: 33%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 13px'><strong>Vía: </strong>$dato->nombreVia</p>
                    </td>
                </tr>
            </table>

            <p style='font-size: 14px'><strong>Indicaciones del Medicamento:</strong> <br>
                    $dato->descripcion
            </p>

            ";

            $vueltas++;
        }



        $htmlFooter = '<div style="text-align: center;">Pie de página solo en la página 1</div>';
        $mpdf->SetHTMLFooter($htmlFooter, 'EVEN|ODD');

        $stylesheet = file_get_contents('css/cssreceta.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();





    }








}
