<?php

namespace App\Http\Controllers\backend\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Consulta_Paciente;
use App\Models\Diagnosticos;
use App\Models\EntradaMedicamento;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\Estado_Civil;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Linea;
use App\Models\MotivoFarmacia;
use App\Models\NotasPaciente;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaDetalle;
use App\Models\Paciente;
use App\Models\Profesion;
use App\Models\Proveedores;
use App\Models\Recetas;
use App\Models\RecetasDetalle;
use App\Models\SalidaReceta;
use App\Models\SalidaRecetaDetalle;
use App\Models\SubLinea;
use App\Models\Tipo_Documento;
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


        $arrayFuente = FuenteFinanciamiento::where('id', 3)->get();

        return view('backend.admin.reportes.entradas.vistareporteentradas', compact('arrayFuente'));
    }


    public function reporteEntradaArticulos($idfuente, $desde, $hasta)
    {

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $nombreFuente = "Todos";

        if ($infoFuente = FuenteFinanciamiento::where('id', $idfuente)->first()) {
            $nombreFuente = $infoFuente->nombre;
        }

        $resultsBloque = array();
        $index = 0;

        if ($idfuente == '0') {
            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();
        } else {
            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
                ->where('fuentefina_id', $idfuente)
                ->orderBy('fecha', 'ASC')
                ->get();
        }

        $totalGeneral = 0; // sumatoria de todas las fuentes de financiamiento
        $totalFundel = 0;
        $totalCovid = 0;
        $totalPropios = 0;
        $totalGeneralDonacion = 0; // sumatoria de todas las Multi de costo donacion

        foreach ($arrayEntradas as $infoFila) {

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
                    'deta.lote', 'deta.fecha_vencimiento', 'fa.id', 'deta.precio_donacion')
                ->where('deta.entrada_medicamento_id', $infoFila->id)
                ->orderBy('fa.nombre', 'ASC')
                ->get();

            $totalXColumna = 0;
            $totalXColumnaCostoDonacion = 0;
            $totalXColumnaCostoMultiDonacion = 0;

            foreach ($arrayDetalle as $dato) {
                $multi = $dato->cantidad_fija * $dato->precio;
                $totalXColumna += $multi;

                $multiFormateo = round($multi, 4);
                $dato->multiFormat = '$' . number_format($multiFormateo, 4, '.', ',');
                $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));

                $dato->precioFormat = '$' . number_format((float)$dato->precio, 4, '.', ',');

                // DONACION
                $precioDonaFormateo = round($dato->precio_donacion, 4);
                $dato->precioDonacionFormat = sprintf("$%.4f", $precioDonaFormateo);
                $totalXColumnaCostoDonacion += $dato->precio_donacion;

                $multiDonaFormateo = round(($dato->cantidad_fija * $dato->precio_donacion), 4);
                $dato->multiDonaFormateo = sprintf("$%.4f", $multiDonaFormateo);
                $totalXColumnaCostoMultiDonacion += $multiDonaFormateo;
                $totalGeneralDonacion += $multiDonaFormateo;
            }


            // SUMATORIAS PARA FUENTE DE FINANCIAMIENTO

            if ($infoFila->fuentefina_id == 1) {
                $totalFundel = $totalFundel + $totalXColumna;
            }

            if ($infoFila->fuentefina_id == 2) {
                $totalCovid = $totalCovid + $totalXColumna;
            }

            if ($infoFila->fuentefina_id == 3) {
                $totalPropios = $totalPropios + $totalXColumna;
            }

            $totalGeneral = $totalGeneral + $totalXColumna;

            // SUMATORIA DE COLUMNA MONTO
            $totalXColumnaFormateo = round($totalXColumna, 2);
            $infoFila->totalxfilas = sprintf("$%.2f", $totalXColumnaFormateo);

            // SUMATORIA DE COLUMNA: COSTO DONACION
            $totalXColumnaCostoDonaFormateo = round($totalXColumnaCostoDonacion, 2);
            $infoFila->totalXColumnaCostoDonacion = sprintf("$%.2f", $totalXColumnaCostoDonaFormateo);

            // SUMATORIA DE COLUMNA: TOTAL DONACION

            $totalXColumnaMultiCostoDona = round($totalXColumnaCostoMultiDonacion, 2);
            $infoFila->totalXColumnaMultiTotalDonacion = sprintf("$%.2f", $totalXColumnaMultiCostoDona);

            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }


        $totalFundel = sprintf("%.2f", floor($totalFundel * 100) / 100);
        $totalFundel = '$' . number_format((float)$totalFundel, 2, '.', ',');

        $totalCovid = sprintf("%.2f", floor($totalCovid * 100) / 100);
        $totalCovid = '$' . number_format((float)$totalCovid, 2, '.', ',');

        $totalPropios = round($totalPropios, 2);
        $totalPropios = '$' . number_format((float)$totalPropios, 2, '.', ',');

        $totalGeneral = sprintf("%.2f", floor($totalGeneral * 100) / 100);
        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        $mpdf->SetTitle('Entrada Medicamento');

        // mostrar errores
        $mpdf->showImageErrors = false;
        $logoalcaldia = 'images/gobiernologo.jpg';
        $logosantaana = 'images/logo.png';

        $tabla = "
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <!-- Logo izquierdo -->
                    <td style='width: 15%; text-align: left;'>
                        <img src='$logosantaana' alt='Santa Ana Norte' style='max-width: 100px; height: auto;'>
                    </td>
                    <!-- Texto centrado -->
                    <td style='width: 60%; text-align: center;'>
                        <h1 style='font-size: 16px; margin: 0; color: #003366;'>ALCALDÍA MUNICIPAL DE SANTA ANA NORTE</h1>
                        <h3 style='font-size: 16px; margin: 0; color: #003366;'>Clinica Municipal Cristobal Peraza</h3>
                        <h3 style='font-size: 16px; margin: 0; color: #003366;'>Reporte de Entrada de Medicamento</h3>
                        <h3 style='font-size: 16px; margin: 0; color: #003366;'>Fecha: $desdeFormat - $hastaFormat</h3>
                    </td>
                    <!-- Logo derecho -->
                    <td style='width: 10%; text-align: right;'>
                        <img src='$logoalcaldia' alt='Gobierno de El Salvador' style='max-width: 60px; height: auto;'>
                    </td>
                </tr>
            </table>
            <hr style='border: none; border-top: 2px solid #003366; margin: 0;'>
            ";

        $tabla .= "<div>
                    <p>Fuente de Financiamiento: $nombreFuente</p>
                    </div>";

        foreach ($arrayEntradas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'><tbody>";

            $tabla .= "<tr>
                        <td style='font-weight: bold; width: 11%; font-size: 11px'>Fecha Entrada</td>
                        <td style='font-weight: bold; width: 12%; font-size: 11px'>Factura</td>
                        <td style='font-weight: bold; width: 12%; font-size: 11px'># Factura</td>
                        <td style='font-weight: bold; width: 15%; font-size: 11px'>Proveedor</td>
                        <td style='font-weight: bold; width: 15%; font-size: 11px'>Fuente F.</td>
                    <tr>";


            $tabla .= "<tr style='font-size: 10px'>
                        <td style='font-size: 10px'>$detaFila->fechaFormat</td>
                        <td style='font-size: 10px'>$detaFila->tipofactura</td>
                        <td style='font-size: 10px'>$detaFila->numero_factura</td>
                        <td style='font-size: 10px'>$detaFila->nombreprove</td>
                        <td style='font-size: 10px'>$detaFila->nombrefuente</td>
                    <tr>";

            $tabla .= "</tbody></table>";

            $tabla .= "<table width='100%' id='tablaFor'><tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 10px'>Fecha Venc.</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Artículo</td>
                <td style='font-weight: bold; width: 8%; font-size: 10px'>Lote</td>
                <td style='font-weight: bold; width: 13%; font-size: 10px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Monto</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Costo Dona.</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Total Dona.</td>
            <tr>";

            // TOTAL DONA: CANTIDAD * PRECIO DONACION

            foreach ($detaFila->detallefila as $dato) {

                $tabla .= "<tr style='font-size: 10px'>
                <td style='font-size: 10px;' >$dato->fechaVencFormat</td>
                <td style='font-size: 10px;' >$dato->nombre</td>
                <td style='font-size: 10px;' >$dato->lote</td>
                <td style='font-size: 10px;' >$dato->cantidad_fija</td>
                <td style='font-size: 10px;' >$dato->precioFormat</td>
                <td style='font-size: 10px;' >$dato->multiFormat</td>
                <td style='font-size: 10px;'>$dato->precioDonacionFormat</td>
                <td style='font-size: 10px;'>$dato->multiDonaFormateo</td>
            <tr>";
            }

            $tabla .= "<tr>
                <td colspan='5' style='font-size: 10px; font-weight: bold'>Total</td>
                <td style='font-size: 10px; font-weight: normal'>$detaFila->totalxfilas</td>
                <td style='font-size: 10px; font-weight: normal'>$detaFila->totalXColumnaCostoDonacion</td>
                <td style='font-size: 10px; font-weight: normal'>$detaFila->totalXColumnaMultiTotalDonacion</td>
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



        $generalFormateoMultiDon = round($totalGeneralDonacion, 2);
        $generalFormateoDonacion = "$" . number_format($generalFormateoMultiDon, 2, '.', ',');

        $tabla .= "<div style='margin-top: 0px'>
                    <p id='textoFinal'>TOTAL DONACION:  $generalFormateoDonacion<br>
                </div>";


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


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Salida Manual');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            Reporte de Salida Manual<br>
            Fecha:  $desdeFormat - $hastaFormat</p>
            </div>";


        foreach ($arraySalidas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 12px'>Fecha Salida</td>
                <td style='font-weight: bold; width: 12%; font-size: 12px'>Hora</td>
                <td style='font-weight: bold; width: 12%; font-size: 12px'>Motivo</td>
                <td style='font-weight: bold; width: 15%; font-size: 12px'>Usuario Descargo</td>
            <tr>";


            $tabla .= "<tr>
                <td style='font-size: 11px'>$detaFila->fechaFormat</td>
                <td style='font-size: 11px'>$detaFila->horaFormat</td>
                <td style='font-size: 11px'>$detaFila->nombremotivo</td>
                <td style='font-size: 11px'>$detaFila->nombreUser</td>
            <tr>";


            $tabla .= "</tbody></table>";



            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 12px'>Artículo.</td>
                <td style='font-weight: bold; width: 9%; font-size: 12px'>Lote.</td>
                <td style='font-weight: bold; width: 12%; font-size: 12px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 12px'>Precio</td>
                <td style='font-weight: bold; width: 12%; font-size: 12px'>Monto</td>
            <tr>";

            foreach ($detaFila->detallefila as $dato) {
                $tabla .= "<tr>
                <td style='font-size: 11px; width: 11%'>$dato->nombre</td>
                <td style='font-size: 11px; width: 9%'>$dato->lote</td>
                <td style='font-size: 11px; width: 12%'>$dato->cantidad</td>
                <td style='font-size: 11px; width: 12%'>$dato->precioFormat</td>
                <td style='font-size: 11px; width: 12%'>$dato->multiFormat</td>
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
                ->select('re.estado', 're.consulta_id',
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


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        if($idestado == '2'){
            $mpdf->SetTitle('Recetas Procesadas');
        }else{
            $mpdf->SetTitle('Recetas Denegadas');
        }


        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
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
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Diagnóstico</td>
                <td style='font-weight: bold; width: 15%; font-size: 14px'>Doctor</td>
            <tr>";


                $tabla .= "<tr>
                <td>$detaFila->fechaSalidaRecetaFormat</td>
                <td>$detaFila->nombrePaciente</td>
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
                ->select('deta.cantidad', 'deta.descripcion', 're.paciente_id',
                            're.fecha_estado', 're.estado', 're.usuario_estado_id',
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


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        if($idestado == '2'){
            $mpdf->SetTitle('Recetas Procesadas');
        }else{
            $mpdf->SetTitle('Recetas Denegadas');
        }

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
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

        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);


        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Catálogo');

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
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


        $arrayIdDeta = EntradaMedicamentoDetalle::where('cantidad', '>', 0)->get();

        $pilaIdEntrada = array();

        foreach ($arrayIdDeta as $info){
            array_push($pilaIdEntrada, $info->entrada_medicamento_id);
        }

        $resultsBloque = array();
        $index = 0;

        $arrayEntradas = EntradaMedicamento::whereIn('id', $pilaIdEntrada)->get();

        $totalGeneral = 0;
        $totalColumna = 0;
        $totalFinalDonacion = 0;

        foreach ($arrayEntradas as $infoFila){

            array_push($resultsBloque, $infoFila);

            $infoTipoFact = TipoFactura::where('id', $infoFila->tipofactura_id)->first();
            $infoFuente = FuenteFinanciamiento::where('id', $infoFila->fuentefina_id)->first();
            $infoProveedor = Proveedores::where('id', $infoFila->proveedor_id)->first();

            $infoFila->fechaFormat = date("d-m-Y", strtotime($infoFila->fecha));
            $infoFila->nombreTipoFactura = $infoTipoFact->nombre;
            $infoFila->nombreFuente = $infoFuente->nombre;
            $infoFila->nombreProveedor = $infoProveedor->nombre;

            $contador = 0;

            $arrayDetalle = EntradaMedicamentoDetalle::where('entrada_medicamento_id', $infoFila->id)
                ->orderBy('fecha_vencimiento', 'ASC')
                ->get();

            foreach ($arrayDetalle as $dato){

               if($dato->cantidad > 0){
                   $contador++;
                   $infoArticulo = FarmaciaArticulo::where('id', $dato->medicamento_id)->first();
                   $dato->nombreArticulo = $infoArticulo->nombre;

                   $dato->fechaVecFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));

                   $multiFila = $dato->precio * $dato->cantidad_fija;

                   //$totalColumna += $multiFila;

                   $dato->contador = $contador;
                   $dato->precioXFila = '$' . number_format((float)$multiFila, 2, '.', ',');
                   $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
               }
            }

            //$totalGeneral += $totalColumna;

            $totalColumna = '$' . number_format((float)$totalColumna, 2, '.', ',');
            $infoFila->totalColumna = $totalColumna;


            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Existencias');

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            Existencias<br>
            </div>";

        foreach ($arrayEntradas as $detaFila) {

            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Fecha Entrada</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Tipo Factura</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'># Factura</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Fuente</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Proveedor</td>
            <tr>";

            $tabla .= "<tr style='font-size: 10px'>
                <td style='font-size: 10px'>$detaFila->fechaFormat</td>
                <td style='font-size: 10px'>$detaFila->nombreTipoFactura</td>
                <td style='font-size: 10px'>$detaFila->numero_factura</td>
                <td style='font-size: 10px'>$detaFila->nombreFuente</td>
                <td style='font-size: 10px'>$detaFila->nombreProveedor</td>
            <tr>";


            $tabla .= "</tbody></table>";


            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 6%;  font-size: 14px'>#.</td>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Fecha Venc.</td>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Artículo</td>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Cantidad</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Precio</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Monto</td>
            <tr>";

            foreach ($detaFila->detallefila as $dato) {

                if($dato->cantidad > 0) {

                        $tabla .= "<tr style='font-size: 10px'>
                        <td style='font-size: 10px'>$dato->contador</td>
                        <td style='font-size: 10px'>$dato->fechaVecFormat</td>
                        <td style='font-size: 10px'>$dato->nombreArticulo</td>
                        <td style='font-size: 10px'>$dato->lote</td>
                        <td style='font-size: 10px'>$dato->cantidad</td>
                        <td style='font-size: 10px'>$dato->precioFormat</td>
                        <td style='font-size: 10px'>$dato->precioXFila</td>
                    <tr>";
                }
            }

            $tabla .= "<tr>
                <td colspan='6'>Total</td>
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
                'deta.lote', 'deta.fecha_vencimiento', 'deta.precio_donacion')
            ->whereIn('deta.entrada_medicamento_id', $pilaIdEntrada)
            ->where('deta.cantidad', '>', 0)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        $totalGeneral = 0;
        $totalFinalDonacion = 0;

        foreach ($arrayEntradasDetalle as $infoFila){

            $infoFila->fechaVencFormat = date("d-m-Y", strtotime($infoFila->fecha_vencimiento));
            $multiFila = $infoFila->cantidad * $infoFila->precio;
            $totalGeneral = $totalGeneral + $multiFila;
            $infoFila->multiFila = '$' . number_format((float)$multiFila, 2, '.', ',');
            $infoFila->precioFormat = '$' . number_format((float)$infoFila->precio, 2, '.', ',');


            $multiDona = $infoFila->cantidad * $infoFila->precio_donacion;
            $totalFinalDonacion += $multiDona;

            $infoFila->totalMontoDonacion = '$' . number_format((float)$multiDona, 2, '.', ',');
            $infoFila->precio_donacion = '$' . number_format((float)$infoFila->precio_donacion, 2, '.', ',');
        }

        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');
        $totalFinalDonacion = '$' . number_format((float)$totalFinalDonacion, 2, '.', ',');

        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Existencias Juntos');
        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            Existencias<br>
            </div>";



            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Artículo</td>
                <td style='font-weight: bold; width: 9%; font-size: 11px'>Fecha Vencimiento</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Monto</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Dona.</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Monto Dona.</td>
            <tr>";




        foreach ($arrayEntradasDetalle as $detaFila) {
            $tabla .= "<tr>
                <td>$detaFila->nombre</td>
                <td>$detaFila->fechaVencFormat</td>
                <td>$detaFila->lote</td>
                <td>$detaFila->cantidad</td>
                <td>$detaFila->precioFormat</td>
                <td>$detaFila->multiFila</td>

                 <td>$detaFila->precio_donacion</td>
                 <td>$detaFila->totalMontoDonacion</td>
            <tr>";
        }

        $tabla .= "</tbody></table>";


        $tabla .= "<div style='margin-top: 30px'>
            <hr>
            <p id='textoFinal'>Total General:  $totalGeneral<br>
            <p id='textoFinal'>Total Donación:  $totalFinalDonacion<br>
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
            ->join('entrada_medicamento_detalle AS enta', 'deta.entrada_detalle_id', '=', 'enta.id')
            ->join('farmacia_articulo AS fa', 'fa.id', '=', 'enta.medicamento_id')
            ->select('fa.nombre', 'deta.recetas_id', 'deta.cantidad', 'deta.descripcion',
                            'deta.via_id')
            ->where('deta.recetas_id', $idreceta)
            ->orderBy('fa.nombre', 'ASC')
            ->get();

        foreach ($arrayRecetaDeta as $info){

            $infoVia = ViaReceta::where('id', $info->via_id)->first();
            $info->nombreVia = $infoVia->nombre;

        }





        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);








        // mostrar errores
        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Receta');
        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            </div>";


        $tabla .= "

             <table width='100%'>
                <tr>
                    <td style='text-align: left; width: 33%'>
                        <!-- Contenido izquierdo -->
                        <p style='font-size: 12px; font-family: normal'><strong>Paciente: </strong>$nombrePaciente</p>
                    </td>
                    <td style='text-align: center; width: 34%'>
                        <!-- Contenido central -->
                         <p style='font-size: 12px; font-family: normal'><strong>Edad: </strong>$edad</p>
                    </td>
                    <td style='text-align: right; width: 33%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 12px; font-family: normal'><strong>Fecha: </strong>$fechaReceta</p>
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
                         <p style='font-size: 12px; font-family: normal'><strong>Proxima Consulta: </strong>$fechaProxCita</p>
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
                    <table width='100%' style='margin-top: 0px; line-height: 1'>
                    ";
                }else{
                    $tabla .= "
                    <table width='100%' style='margin-top: 20px; line-height: 1'>
                    ";
                }

                $tabla .= " <tr style='line-height: 1'>
                    <td style='text-align: left; width: 33%'>
                        <!-- Contenido izquierdo -->
                         <p style='font-size: 11px; font-family: normal'><strong><ul><li>$dato->nombre</li></ul></strong></p>
                    </td>
                    <td style='text-align: center; width: 34%'>
                        <!-- Contenido central -->
                         <p style='font-size: 11px; font-family: normal'><strong>Cantidad: </strong>$dato->cantidad</p>
                    </td>
                    <td style='text-align: right; width: 33%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 11px; font-family: normal'><strong>Vía: </strong>$dato->nombreVia</p>
                    </td>
                </tr>
            </table>

            <p style='font-size: 12px; line-height: 1'><strong>Indicaciones del Medicamento:</strong> <br>
                    $dato->descripcion
            </p>
            ";
        }



        $stylesheet = file_get_contents('css/cssreceta.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }


    // NUEVO REPORTE UNIDOS POR FECHA
    public function reporteRecetaPacientePorFechasTodos($estado, $desde, $hasta)
    {
        // ---------- Validación de fechas en servidor ----------
        try {
            $start = Carbon::parse($desde)->startOfDay();
            $end   = Carbon::parse($hasta)->endOfDay();
        } catch (\Throwable $e) {
            abort(400, 'Fechas inválidas.');
        }

        if ($start->gt($end)) {
            abort(400, 'La fecha de inicio no puede ser mayor que la fecha fin.');
        }

        // ---------- Construcción del dataset según estado ----------
        // Nota: replico tu lógica: estado=1 no filtra por fecha; 2 y 3 sí filtran por rango
        if ($estado == '1') {
            $recetas = Recetas::where('estado', 1)
                ->orderBy('fecha', 'ASC')
                ->get();
        } elseif ($estado == '2') {
            $recetas = Recetas::where('estado', 2)
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();
        } else { // estado == 3 (u otros valores caen aquí)
            $recetas = Recetas::where('estado', 3)
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'ASC')
                ->get();
        }

        // ---------- Instanciar mPDF ----------
        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);



        $mpdf->showImageErrors = false;
        $mpdf->SetTitle('Recetas por bloque');

        // Cargar CSS (ajusta la ruta si difiere)
        if (file_exists(public_path('css/cssreceta.css'))) {
            $stylesheet = file_get_contents(public_path('css/cssreceta.css'));
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        }

        switch ((string)$estado) {
            case '1':
                $tituloEstado = 'PENDIENTES';
                break;
            case '2':
                $tituloEstado = 'PROCESADAS';
                break;
            case '3':
                $tituloEstado = 'ANULADAS';
                break;
            default:
                $tituloEstado = 'ESTADO DESCONOCIDO';
                break;
        }


        $rango = $start->format('d-m-Y').' al '.$end->format('d-m-Y');

        $logoalcaldia = public_path('images/logodis.png'); // ajusta si tu logo está en otra carpeta

        // ---------- Generar una página por receta ----------
        $total = $recetas->count();
        if ($total === 0) {
            // Página simple informando que no hay datos
            $htmlVacio = "
                <div class='contenedorp'>
                    <p id='titulo'>Clinica Municipal Cristobal Peraza <br>
                    Tahuilapa, Distrito de Metapán, Santa Ana Norte</p>
                    <h3 style='text-align:center;margin-top:10px'>No se encontraron recetas</h3>
                    <p style='text-align:center'>Estado: <b>{$tituloEstado}</b> &middot; Rango: <b>{$rango}</b></p>
                </div>";
            $mpdf->WriteHTML($htmlVacio, \Mpdf\HTMLParserMode::HTML_BODY);
            return $mpdf->Output('recetas-'.$desde.'_'.$hasta.'.pdf', 'I');
        }

        foreach ($recetas as $idx => $receta) {
            // Datos del paciente
            $paciente = Paciente::find($receta->paciente_id);
            $nombrePaciente = $paciente ? trim(($paciente->nombres ?? '').' '.($paciente->apellidos ?? '')) : 'N/D';
            $edad = $paciente && $paciente->fecha_nacimiento ? Carbon::parse($paciente->fecha_nacimiento)->age : 'N/D';

            // Fechas formato
            $fechaReceta = $receta->fecha ? Carbon::parse($receta->fecha)->format('d-m-Y') : '';
            $fechaProxCita = $receta->proxima_cita ? Carbon::parse($receta->proxima_cita)->format('d-m-Y') : '';

            // Detalle de medicamentos (como tu ejemplo)
            $detalles = DB::table('recetas_detalle AS deta')
                ->join('entrada_medicamento_detalle AS enta', 'deta.entrada_detalle_id', '=', 'enta.id')
                ->join('farmacia_articulo AS fa', 'fa.id', '=', 'enta.medicamento_id')
                ->select('fa.nombre', 'deta.recetas_id', 'deta.cantidad', 'deta.descripcion', 'deta.via_id')
                ->where('deta.recetas_id', $receta->id)
                ->orderBy('fa.nombre', 'ASC')
                ->get();

            // Resolver nombre de vía
            foreach ($detalles as $d) {
                $via = ViaReceta::find($d->via_id);
                $d->nombreVia = $via ? $via->nombre : '';
            }

            // ---------- Armar la página ----------
            $top = "
                <div class='contenedorp'>
                    ".(file_exists($logoalcaldia) ? "<img id='logo' src='{$logoalcaldia}'>" : "")."
                    <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte</p>
                </div>

                <div style='text-align:center;margin:6px 0 10px'>
                    <span style='font-size:13px'><b>Estado:</b> {$tituloEstado} &nbsp;&middot;&nbsp; <b>Rango:</b> {$rango}</span>
                </div>

                <table width='100%'>
                    <tr>
                        <td style='text-align:left;width:33%'>
                            <p style='font-size:12px'><strong>Paciente:</strong> {$nombrePaciente}</p>
                        </td>
                        <td style='text-align:center;width:34%'>
                            <p style='font-size:12px'><strong>Edad:</strong> {$edad}</p>
                        </td>
                        <td style='text-align:right;width:33%'>
                            <p style='font-size:12px'><strong>Fecha:</strong> {$fechaReceta}</p>
                        </td>
                    </tr>";

            if (!empty($fechaProxCita)) {
                $top .= "
                    <tr>
                        <td style='text-align:left;width:33%'><p style='font-size:12px'>&nbsp;</p></td>
                        <td style='text-align:center;width:34%'><p style='font-size:12px'>&nbsp;</p></td>
                        <td style='text-align:right;width:33%'>
                            <p style='font-size:12px'><strong>Próxima Consulta:</strong> {$fechaProxCita}</p>
                        </td>
                    </tr>";
            }

            $top .= "</table><hr>";

            $body = "";
            $vueltas = 0;
            foreach ($detalles as $dato) {
                $vueltas++;
                $body .= "
                    <table width='100%' style='margin-top:".($vueltas === 1 ? "0" : "20")."px; line-height:1'>
                        <tr>
                            <td style='text-align:left;width:33%'>
                                <p style='font-size:11px'><strong><ul><li>{$dato->nombre}</li></ul></strong></p>
                            </td>
                            <td style='text-align:center;width:34%'>
                                <p style='font-size:11px'><strong>Cantidad:</strong> {$dato->cantidad}</p>
                            </td>
                            <td style='text-align:right;width:33%'>
                                <p style='font-size:11px'><strong>Vía:</strong> {$dato->nombreVia}</p>
                            </td>
                        </tr>
                    </table>

                    <p style='font-size:12px; line-height:1.2; margin-top:4px'>
                        <strong>Indicaciones del Medicamento:</strong><br>
                        {$dato->descripcion}
                    </p>";
            }

            $htmlPagina = $top . $body;

            // Escribir página
            $mpdf->WriteHTML($htmlPagina, \Mpdf\HTMLParserMode::HTML_BODY);

            // Footer con numeración
            $mpdf->setFooter("Página {PAGENO} de {nb}");

            // Salto de página si no es la última
            if ($idx < $total - 1) {
                $mpdf->WriteHTML("<pagebreak />", \Mpdf\HTMLParserMode::HTML_BODY);
            }
        }

        // ---------- Salida ----------
        return $mpdf->Output('recetas-'.$desde.'_'.$hasta.'.pdf', 'I');
    }






























    public function vistaReporteFinal(){

        $materiales = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.reportes.final.vistareportefinal', compact('materiales'));
    }


    public function generarReporteFinal($desde, $hasta){


        // 2- procesados
        // 3- denegados

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $dataArray = array();

        $totalFondoPropioDescargado = 0;
        $totalMaterialCovidDescargado = 0;
        $totalMaterialFundelDescargado = 0;

        $totalFondoPropioExistencia = 0;
        $totalMaterialCovidExistencia = 0;
        $totalMaterialFundelExistencia = 0;


        $columnaTotalDescargadoDonac = 0;


        // Total que va hasta el final de la columna
        $totalDonacionColumna = 0;

        // obtener ID de entradas de esa fecha
        $arrayEntradas = EntradaMedicamento::all();

        $pilaIdEntradas = array();

        foreach ($arrayEntradas as $info) {
            array_push($pilaIdEntradas, $info->id);
        }


        $arrayMedicamentos = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();
        $contador = 0;

        $hayDatos = false;

        foreach ($arrayMedicamentos as $dato){

            $arrayDetalle = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)->get();
            $infoLinea = Linea::where('id', $dato->linea_id)->first();

            foreach ($arrayDetalle as $fila){
                $contador++;
                $hayDatos = true;

                $infoEntradaFi = EntradaMedicamento::where('id', $fila->entrada_medicamento_id)->first();
                $infoProve = Proveedores::where('id', $infoEntradaFi->proveedor_id)->first();
                $infoFuenteFi = FuenteFinanciamiento::where('id', $infoEntradaFi->fuentefina_id)->first();


                $fechaVen = date("d-m-Y", strtotime($fila->fecha_vencimiento));
                $precioFormat = '$' . number_format((float)$fila->precio, 2, '.', ',');

                if($fila->precio_donacion != null){

                    $precioFormatDonacion = '$' . number_format((float)$fila->precio_donacion, 2, '.', ',');
                }else{
                    $precioFormatDonacion = '$0.00';
                }

                $cantiEntregada = $fila->cantidad_fija - $fila->cantidad;
                $multiDescargado = $fila->precio * $cantiEntregada;

                if($fila->precio_donacion != null){
                    $multiDescargadoDonacion = $fila->precio_donacion * $cantiEntregada;

                    $totalDonacionColumna = $totalDonacionColumna + $multiDescargadoDonacion;
                }else{
                    $multiDescargadoDonacion = 0;
                }




                $columnaTotalDescargadoDonac += $multiDescargadoDonacion;






                $multiDescargadoFormat = '$' . number_format((float)$multiDescargado, 2, '.', ',');
                $multiDescargadoFormatDonacion = '$' . number_format((float)$multiDescargadoDonacion, 2, '.', ',');


                $multiExist = $fila->precio * $fila->cantidad;
                $multiExistFormat = '$' . number_format((float)$multiExist, 2, '.', ',');


                if($infoFuenteFi->id == 1){
                    // MATERIALES FUNDEL
                    $totalMaterialFundelDescargado += $multiDescargado;
                    $totalMaterialFundelExistencia += $multiExist;

                }else if($infoFuenteFi->id == 2){
                    // MATERIALES COVID
                    $totalMaterialCovidDescargado += $multiDescargado;
                    $totalMaterialCovidExistencia += $multiExist;

                }else{
                    // FONDOS PROPIOS
                    $totalFondoPropioDescargado += $multiDescargado; // precio x cantidad entregada
                    $totalFondoPropioExistencia += $multiExist;
                }



                $dataArray[] = [
                    'contador' => $contador,
                    'codigo' => $dato->codigo_articulo,
                    'nombre' => $dato->nombre,
                    'financiamiento' => $infoFuenteFi->nombre,
                    'linea' => $infoLinea->nombre,
                    'proveedor' => $infoProve->nombre,
                    'lote' => $fila->lote,
                    'fecha_vencimiento' => $fechaVen,
                    'costo' => $precioFormat,
                    'costo_donacion' => $precioFormatDonacion,
                    'cantidad_inicial' => $fila->cantidad_fija,
                    'entregado' => $cantiEntregada,
                    'existencia' => $fila->cantidad,
                    'total_descargado' => $multiDescargadoFormat,
                    'total_existencia' => $multiExistFormat,
                    'total_descargado_donacion' => $multiDescargadoFormatDonacion,
                ];

            }
        }



        $totalColumnaDescargado = $totalFondoPropioDescargado + $totalMaterialCovidDescargado + $totalMaterialFundelDescargado;
        $totalColumnaExistencia = $totalFondoPropioExistencia + $totalMaterialCovidExistencia + $totalMaterialFundelExistencia;


        $totalColumnaExistenciaEntero = intval($totalColumnaExistencia);

        $numeroCadena = (string) $totalColumnaExistencia;
        $posicionPunto = strpos($numeroCadena, '.');

        if ($posicionPunto !== false) {
            // Extraer los dos primeros caracteres después del punto decimal
            $totalColumnaExistenciaDosDecimales = substr($numeroCadena, $posicionPunto + 1, 2);
        } else {
            // Si no hay punto decimal, establecer los dos decimales como "00"
            $totalColumnaExistenciaDosDecimales = '00';
        }

        $totalCoEx = $totalColumnaExistenciaEntero . "." . $totalColumnaExistenciaDosDecimales;

        $totalColumnaExistenciaFinal = '$' . number_format($totalCoEx, 2, '.', ',');


        $totalColumnaDescargado = '$' . number_format((float)$totalColumnaDescargado, 2, '.', ',');



        $totalColumnaExistencia = round($totalColumnaExistencia, 2);
        $totalColumnaExistencia = '$' . number_format((float)$totalColumnaExistencia, 2, '.', ',');


        // precio x cantidad entregada
        $totalFondoPropioDescargado = '$' . number_format((float)$totalFondoPropioDescargado, 2, '.', ',');
        //$totalFondoPropioExistencia = '$' . number_format((float)$totalFondoPropioExistencia, 2, '.', ',');



        $totalFondoPropioExistenciaEntero = intval($totalFondoPropioExistencia);


        $numeroCadena2 = (string) $totalFondoPropioExistencia;
        $posicionPunto2 = strpos($numeroCadena2, '.');

        if ($posicionPunto2 !== false) {
            // Extraer los dos primeros caracteres después del punto decimal
            $totalColumnaPropiosDecimales = substr($numeroCadena2, $posicionPunto2 + 1, 2);
        } else {
            // Si no hay punto decimal, establecer los dos decimales como "00"
            $totalColumnaPropiosDecimales = '00';
        }

        $totalCoExFondoPro = $totalFondoPropioExistenciaEntero . "." . $totalColumnaPropiosDecimales;

        $totalFondoPropioExistenciaFinal = number_format($totalCoExFondoPro, 2, '.', ',');




        $totalDonacionColumna = round($totalDonacionColumna, 2);
        $totalDonacionColumna = '$' . number_format((float)$totalDonacionColumna, 2, '.', ',');





        //$totalMaterialCovidDescargado = '$' . number_format((float)$totalMaterialCovidDescargado, 2, '.', ',');
        //$totalMaterialCovidExistencia = '$' . number_format((float)$totalMaterialCovidExistencia, 2, '.', ',');

        //$totalMaterialFundelDescargado = '$' . number_format((float)$totalMaterialFundelDescargado, 2, '.', ',');
        //$totalMaterialFundelExistencia = '$' . number_format((float)$totalMaterialFundelExistencia, 2, '.', ',');


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER', 'orientation' => 'L']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER', 'orientation' => 'L']);

        $mpdf->SetTitle('Reporte Existencias');


        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
                REPORTE DE EXISTENCIAS POR FECHAS <br><br>
             <strong>INTERVALO DESDE</strong> $desdeFormat <strong>HASTA</strong> $hastaFormat</p>
            </div>";


                $tabla .= "<table id='tablaFor'>
                    <tbody>";

                $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 12px'>#</td>
                <td style='font-weight: bold; font-size: 12px'>CODIGO</td>
                <td style='font-weight: bold; font-size: 12px'>DESCRIPCION</td>
                <td style='font-weight: bold; font-size: 12px'>FINANCIAMIENTO</td>
                <td style='font-weight: bold; font-size: 12px'>LINEA</td>
                <td style='font-weight: bold; font-size: 12px'>PROVEEDOR</td>
                <td style='font-weight: bold; font-size: 12px'>LOTE</td>
                <td style='font-weight: bold; font-size: 12px'>FECHA VENCIMIENTO</td>
                <td style='font-weight: bold; font-size: 12px'>COSTO</td>
                <td style='font-weight: bold; font-size: 12px'>COSTO DONA.</td>
                <td style='font-weight: bold; font-size: 12px'>CANTIDAD INICIAL</td>
                <td style='font-weight: bold; font-size: 12px'>ENTREGADO</td>
                <td style='font-weight: bold; font-size: 12px'>EXISTENCIA</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO</td>
                 <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO DONA.</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA</td>
            <tr>";

                foreach ($dataArray as $fila){
                    if($hayDatos){

                        $detaContador = $fila['contador'];
                        $detaCodigo = $fila['codigo'];
                        $detaNombre = $fila['nombre'];
                        $detaFinanci = $fila['financiamiento'];
                        $detaLinea = $fila['linea'];
                        $detaProveedor = $fila['proveedor'];
                        $detaLote = $fila['lote'];
                        $detaFechaVen = $fila['fecha_vencimiento'];
                        $detaCosto = $fila['costo'];
                        $detaCostoDonacion = $fila['costo_donacion'];
                        $detaCantiIni = $fila['cantidad_inicial'];
                        $detaEntregado = $fila['entregado'];
                        $detaExistencia = $fila['existencia'];
                        $detaTotalDesc = $fila['total_descargado'];
                        $detaTotalDescDonacion = $fila['total_descargado_donacion'];
                        $detaTotalExis = $fila['total_existencia'];

                        $tabla .= "<tr>
                            <td>$detaContador</td>
                            <td>$detaCodigo</td>
                            <td>$detaNombre</td>
                            <td>$detaFinanci</td>
                            <td>$detaLinea</td>
                            <td>$detaProveedor</td>
                            <td>$detaLote</td>
                            <td>$detaFechaVen</td>
                            <td>$detaCosto</td>
                            <td>$detaCostoDonacion</td>
                            <td>$detaCantiIni</td>
                            <td>$detaEntregado</td>
                            <td>$detaExistencia</td>
                            <td>$detaTotalDesc</td>
                            <td>$detaTotalDescDonacion</td>
                            <td>$detaTotalExis</td>
                        <tr>";
                    }
                }


        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 12px'>#</td>
                <td style='font-weight: bold; font-size: 12px'>CODIGO</td>
                <td style='font-weight: bold; font-size: 12px'>DESCRIPCION</td>
                <td style='font-weight: bold; font-size: 12px'>FINANCIAMIENTO</td>
                <td style='font-weight: bold; font-size: 12px'>LINEA</td>
                <td style='font-weight: bold; font-size: 12px'>PROVEEDOR</td>
                <td style='font-weight: bold; font-size: 12px'>LOTE</td>
                <td style='font-weight: bold; font-size: 12px'>FECHA VENCIMIENTO</td>
                <td style='font-weight: bold; font-size: 12px'>COSTO</td>
                <td style='font-weight: bold; font-size: 12px'>COSTO DONA.</td>
                <td style='font-weight: bold; font-size: 12px'>CANTIDAD INICIAL</td>
                <td style='font-weight: bold; font-size: 12px'>ENTREGADO</td>
                <td style='font-weight: bold; font-size: 12px'>EXISTENCIA</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO DONA.</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA</td>
            <tr>";


        $columnaTotalDescargadoDonac = round($columnaTotalDescargadoDonac, 2);
        $columnaTotalDescargadoDonac = '$' . number_format((float)$columnaTotalDescargadoDonac, 2, '.', ',');




        $tabla .= "<tr>
                    <td colspan='13' style='text-align: right; font-weight: bold'></td>
                    <td style='font-weight: bold'>$totalColumnaDescargado</td>
                    <td style='font-weight: bold'>$columnaTotalDescargadoDonac</td>
                     <td style='font-weight: bold'>$totalColumnaExistenciaFinal</td>
                <tr>";


        $tabla .= "</tbody></table><br>";




        $tabla .= "<table id='tablaFor'>
                    <tbody>";



        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 11px'>Total Fondos Propios</td>
                <td style='font-weight: bold; font-size: 11px'>Total Existencia</td>
            <tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 11px'>$totalFondoPropioDescargado</td>
                <td style='font-weight: bold; font-size: 11px'>$$totalColumnaExistenciaFinal</td>
            <tr>";



        // $totalMaterialCovidDescargado
        // $totalMaterialCovidExistencia
        // $totalMaterialFundelDescargado
        // $totalMaterialFundelExistencia


        $tabla .= "</tbody></table>";


        $mpdf->setMargins(5, 5, 5);

        $stylesheet = file_get_contents('css/cssreportefinal.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }



    public function generarReporteFinalv2($desde, $hasta, $soloExistencia = '0')
    {
        ini_set('memory_limit', '6024M');
        ini_set('pcre.backtrack_limit', '10000000');
        ini_set('pcre.recursion_limit', '10000000');

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();

        $desdeDate = Carbon::parse($desde)->toDateString();
        $hastaDate = Carbon::parse($hasta)->toDateString();

        $desdeFormat = date("d-m-Y", strtotime($desde));
        $hastaFormat = date("d-m-Y", strtotime($hasta));

        $dataArray = [];

        // *** IDs salida_receta RANGO desde-hasta (para ENTREGADO TOTAL) ***
        $pilaIdSalidaRecetaRango = DB::table('recetas AS r')
            ->join('salida_receta AS sr', 'sr.recetas_id', '=', 'r.id')
            ->where('r.estado', 2)
            ->whereBetween('sr.fecha', [$start, $end])
            ->pluck('sr.id')
            ->toArray();

        // *** IDs salida_receta ACUMULADO hasta "hasta" (para ENTREGADO) ***
        $pilaIdSalidaRecetaHasta = DB::table('recetas AS r')
            ->join('salida_receta AS sr', 'sr.recetas_id', '=', 'r.id')
            ->where('r.estado', 2)
            ->where('sr.fecha', '<=', $end)
            ->pluck('sr.id')
            ->toArray();

        // *** Pre-cargar SalidaRecetaDetalle ***
        $allDetallesHasta = DB::table('salida_receta_detalle')
            ->whereIn('salidareceta_id', $pilaIdSalidaRecetaHasta)
            ->select('entrada_detalle_id', 'cantidad')
            ->get()
            ->groupBy('entrada_detalle_id');

        $allDetallesRango = DB::table('salida_receta_detalle')
            ->whereIn('salidareceta_id', $pilaIdSalidaRecetaRango)
            ->select('entrada_detalle_id', 'cantidad')
            ->get()
            ->groupBy('entrada_detalle_id');

        // *** Salidas por ORDEN acumuladas hasta "hasta" (para ENTREGADO) ***
        $ordenesHasta = DB::table('orden_salida AS os')
            ->join('orden_salida_detalle AS osd', 'osd.orden_salida_id', '=', 'os.id')
            ->where('os.fecha', '<=', $hastaDate)
            ->select('osd.entrada_medi_detalle_id', DB::raw('SUM(osd.cantidad) AS total'))
            ->groupBy('osd.entrada_medi_detalle_id')
            ->pluck('total', 'entrada_medi_detalle_id');

        // *** Salidas por ORDEN en el RANGO desde-hasta (para ENTREGADO TOTAL) ***
        $ordenesRango = DB::table('orden_salida AS os')
            ->join('orden_salida_detalle AS osd', 'osd.orden_salida_id', '=', 'os.id')
            ->whereBetween('os.fecha', [$desdeDate, $hastaDate])
            ->select('osd.entrada_medi_detalle_id', DB::raw('SUM(osd.cantidad) AS total'))
            ->groupBy('osd.entrada_medi_detalle_id')
            ->pluck('total', 'entrada_medi_detalle_id');

        // *** Pre-cargar relaciones ***
        $allEntradas = EntradaMedicamento::all()->keyBy('id');
        $allProveedores = Proveedores::all()->keyBy('id');
        $allFuentes = FuenteFinanciamiento::all()->keyBy('id');
        $allLineas = Linea::all()->keyBy('id');

        $arrayMedicamentos = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();
        $contador = 0;

        $sumatoriaTotalDescargado = 0;
        $sumatoriaTotalDescargadoDonac = 0;
        $sumatoriaTotalDescaFecha = 0;
        $sumatoriaTotalDescaDonacionFecha = 0;
        $sumatoriaTotalExistencia = 0;
        $sumatoriaTotalDona = 0;

        foreach ($arrayMedicamentos as $dato) {

            $arrayDetalle = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)->get();
            $infoLinea = $allLineas->get($dato->linea_id);


            foreach ($arrayDetalle as $fila) {

                // ============================================================
                // ENTREGADO ACUMULADO HASTA LA FECHA FINAL
                // ============================================================
                $entregado_hasta_COL = 0;

                if (isset($allDetallesHasta[$fila->id])) {
                    foreach ($allDetallesHasta[$fila->id] as $d) {
                        $entregado_hasta_COL += $d->cantidad;
                    }
                }

                $entregado_hasta_COL += (int)($ordenesHasta[$fila->id] ?? 0);

                // ============================================================
                // EXISTENCIA
                // ============================================================
                $existencia_rango_COL = $fila->cantidad_fija - $entregado_hasta_COL;

                // ============================================================
                // ENTREGADO TOTAL EN EL RANGO
                // ============================================================
                $entregadoTotalF_COL = 0;

                if (isset($allDetallesRango[$fila->id])) {
                    foreach ($allDetallesRango[$fila->id] as $d) {
                        $entregadoTotalF_COL += $d->cantidad;
                    }
                }

                $entregadoTotalF_COL += (int)($ordenesRango[$fila->id] ?? 0);

                $infoEntradaFi = $allEntradas->get($fila->entrada_medicamento_id);
                $infoProve = $infoEntradaFi ? $allProveedores->get($infoEntradaFi->proveedor_id) : null;
                $infoFuenteFi = $infoEntradaFi ? $allFuentes->get($infoEntradaFi->fuentefina_id) : null;

                $fechaVen = date("d-m-Y", strtotime($fila->fecha_vencimiento));

                // ============================================================
                // COSTOS
                // ============================================================
                $precioFormat_COL = '$' . number_format((float)$fila->precio, 4, '.', ',');
                $precioFormatDonacion_COL = '$' . number_format((float)$fila->precio_donacion, 4, '.', ',');
                $cantidadInicial_COL = $fila->cantidad_fija;

                // ============================================================
                // TOTALES
                // ============================================================
                $totalDescargado_COL = '$' . number_format((float)($fila->precio * $entregado_hasta_COL), 4, '.', ',');
                $sumatoriaTotalDescargado += ($fila->precio * $entregado_hasta_COL);

                $totalDescargadoDonac_COL = '$' . number_format((float)($fila->precio_donacion * $entregado_hasta_COL), 2, '.', ',');
                $sumatoriaTotalDescargadoDonac += ($fila->precio_donacion * $entregado_hasta_COL);

                $totalDescaFecha_COL = '$' . number_format((float)($fila->precio * $entregadoTotalF_COL), 4, '.', ',');
                $sumatoriaTotalDescaFecha += ($fila->precio * $entregadoTotalF_COL);

                $totalDescaDonacionFecha_COL = '$' . number_format((float)($fila->precio_donacion * $entregadoTotalF_COL), 4, '.', ',');
                $sumatoriaTotalDescaDonacionFecha += ($fila->precio_donacion * $entregadoTotalF_COL);

                $totalExistencia_COL = '$' . number_format((float)($fila->precio * $existencia_rango_COL), 4, '.', ',');
                $sumatoriaTotalExistencia += ($fila->precio * $existencia_rango_COL);

                $totalExistenciaDona_COL = '$' . number_format((float)($fila->precio_donacion * $existencia_rango_COL), 4, '.', ',');
                $sumatoriaTotalDona += ($fila->precio_donacion * $existencia_rango_COL);

                // ============================================================
                // FILTRO (SOLO AFECTA LO QUE SE MUESTRA)
                // ============================================================
                if ($soloExistencia === '1') {
                    if ($existencia_rango_COL <= 0 && $entregadoTotalF_COL <= 0) {
                        continue;
                    }
                }

                $contador++;

                $dataArray[] = [
                    'contador' => $contador,
                    'codigo' => $dato->codigo_articulo,
                    'nombre' => $dato->nombre,
                    'financiamiento' => $infoFuenteFi ? $infoFuenteFi->nombre : '',
                    'linea' => $infoLinea ? $infoLinea->nombre : '',
                    'proveedor' => $infoProve ? $infoProve->nombre : '',
                    'lote' => $fila->lote,
                    'fecha_vencimiento' => $fechaVen,
                    'costo' => $precioFormat_COL,
                    'costo_donacion' => $precioFormatDonacion_COL,
                    'cantidad_inicial' => $cantidadInicial_COL,
                    'entregado' => $entregado_hasta_COL,
                    'entregadototal' => $entregadoTotalF_COL,
                    'existencia' => $existencia_rango_COL,
                    'total_descargado' => $totalDescargado_COL,
                    'total_descargado_donacion' => $totalDescargadoDonac_COL,
                    'totaldescafecha' => $totalDescaFecha_COL,
                    'totaldescadonacionfecha' => $totalDescaDonacionFecha_COL,
                    'total_existencia' => $totalExistencia_COL,
                    'totalexistencia_dona' => $totalExistenciaDona_COL,
                ];
            }



            if($dato->id == 123){
              //  return $dataArray;
            }
        }


        // --- Sumatorias formato ---
        $sumatoriaTotalDescaDonacionFecha = '$' . number_format((float)$sumatoriaTotalDescaDonacionFecha, 2, '.', ',');
        $sumatoriaTotalDescargadoDonac = '$' . number_format(round($sumatoriaTotalDescargadoDonac, 2), 2, '.', ',');
        $sumatoriaTotalDescaFecha = '$' . number_format((float)$sumatoriaTotalDescaFecha, 2, '.', ',');
        $sumatoriaTotalDescargado = '$' . number_format((float)$sumatoriaTotalDescargado, 2, '.', ',');
        $sumatoriaTotalExistencia = '$' . number_format((float)$sumatoriaTotalExistencia, 2, '.', ',');
        $sumatoriaTotalDona = '$' . number_format((float)$sumatoriaTotalDona, 4, '.', ',');

        //return $sumatoriaTotalDona;


        // --- Agrupar por linea ---
        $dataGrouped = collect($dataArray)->groupBy('linea');

        $contadorCorrelativo = 0;

        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER', 'orientation' => 'L']);
        $mpdf->SetTitle('Reporte Final');
        $mpdf->showImageErrors = false;

        $logoGobiernoData = base64_encode(file_get_contents(public_path('images/gobiernologo.jpg')));
        $logoGobierno = 'data:image/jpg;base64,' . $logoGobiernoData;

        $logoAlcaldiaData = base64_encode(file_get_contents(public_path('images/logojpg.jpg')));
        $logoAlcaldia = 'data:image/jpg;base64,' . $logoAlcaldiaData;

        $tabla = "
    <table style='width: 100%; border-collapse: collapse; margin-bottom: 0px'>
        <tr>
            <td style='width: 15%; text-align: left;'>
                <img src='$logoAlcaldia' alt='Santa Ana Norte' style='max-width: 100px; height: auto;'>
            </td>
            <td style='width: 60%; text-align: center;'>
                <h1 style='font-size: 16px; margin: 0; color: #003366;'>ALCALDÍA MUNICIPAL DE SANTA ANA NORTE</h1>
                <h3 style='font-size: 16px; margin: 0; color: #003366;'>Clinica Municipal Cristobal Peraza</h3>
                <h3 style='font-size: 16px; margin: 0; color: #003366;'>REPORTE DE EXISTENCIAS POR FECHAS</h3>
                <h3 style='font-size: 16px; margin: 0; color: #003366;'><strong>INTERVALO DESDE:</strong> $desdeFormat <strong>HASTA</strong> $hastaFormat</h3>
            </td>
            <td style='width: 10%; text-align: right;'>
                <img src='$logoGobierno' alt='Gobierno de El Salvador' style='max-width: 60px; height: auto;'>
            </td>
        </tr>
    </table>
    <hr style='border: none; border-top: 2px solid #003366; margin: 0;'>
    ";

        $stylesheet = file_get_contents('css/cssreportefinal.css');
        $mpdf->WriteHTML($stylesheet, 1);

        $tablaHeader = "
    <table id='tablaFor' style='margin-top: 40px'><tbody>
    <tr>
        <td style='font-weight: bold; font-size: 12px'>#</td>
        <td style='font-weight: bold; font-size: 12px'>CODIGO</td>
        <td style='font-weight: bold; font-size: 12px'>DESCRIPCION</td>
        <td style='font-weight: bold; font-size: 12px'>FINANCIAMIENTO</td>
        <td style='font-weight: bold; font-size: 12px'>LINEA</td>
        <td style='font-weight: bold; font-size: 12px'>PROVEEDOR</td>
        <td style='font-weight: bold; font-size: 12px'>LOTE</td>
        <td style='font-weight: bold; font-size: 12px'>FECHA VENCIMIENTO</td>
        <td style='font-weight: bold; font-size: 12px'>COSTO</td>
        <td style='font-weight: bold; font-size: 12px'>COSTO DONA.</td>
        <td style='font-weight: bold; font-size: 12px'>CANTIDAD INICIAL</td>
        <td style='font-weight: bold; font-size: 12px'>ENTREGADO</td>
        <td style='font-weight: bold; font-size: 12px'>ENTREGADO TOTAL</td>
        <td style='font-weight: bold; font-size: 12px'>EXISTENCIA</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO DONAC.</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCA. FECHAS</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCA. DONA FECHAS</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA DONA.</td>
    </tr>";

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla, 2);
        $mpdf->WriteHTML($tablaHeader, 2);

        // *** FILAS POR GRUPO en bloques de 40 para evitar pcre.backtrack_limit ***
        $chunkSize = 40;

        foreach ($dataGrouped as $linea => $items) {

            $mpdf->WriteHTML("<tr style='background-color: #ddd; font-weight: bold;'>
            <td colspan='20'>$linea</td>
        </tr>", 2);

            $chunk = '';
            $filasEnChunk = 0;

            foreach ($items as $fila) {
                $contadorCorrelativo++;

                $detaCodigo = $fila['codigo'];
                $detaNombre = $fila['nombre'];
                $detaFinanci = $fila['financiamiento'];
                $detaProveedor = $fila['proveedor'];
                $detaLote = $fila['lote'];
                $detaFechaVen = $fila['fecha_vencimiento'];
                $detaCosto = $fila['costo'];
                $detaCostoDonacion = $fila['costo_donacion'];
                $detaCantiIni = $fila['cantidad_inicial'];
                $detaEntregado = $fila['entregado'];
                $detaEntregadoTotal = $fila['entregadototal'];
                $detaExistencia = $fila['existencia'];
                $detaTotalDesc = $fila['total_descargado'];
                $detaTotalDescDonacion = $fila['total_descargado_donacion'];
                $totalDescaFecha = $fila['totaldescafecha'];
                $totalDescaDonacionFecha = $fila['totaldescadonacionfecha'];
                $detaTotalExis = $fila['total_existencia'];
                $detaTotalExistenciaDona = $fila['totalexistencia_dona'];

                $chunk .= "<tr>
                <td>$contadorCorrelativo</td>
                <td>$detaCodigo</td>
                <td>$detaNombre</td>
                <td>$detaFinanci</td>
                <td>$linea</td>
                <td>$detaProveedor</td>
                <td>$detaLote</td>
                <td>$detaFechaVen</td>
                <td>$detaCosto</td>
                <td>$detaCostoDonacion</td>
                <td>$detaCantiIni</td>
                <td>$detaEntregado</td>
                <td>$detaEntregadoTotal</td>
                <td>$detaExistencia</td>
                <td>$detaTotalDesc</td>
                <td>$detaTotalDescDonacion</td>
                <td>$totalDescaFecha</td>
                <td>$totalDescaDonacionFecha</td>
                <td>$detaTotalExis</td>
                <td>$detaTotalExistenciaDona</td>
            </tr>";

                $filasEnChunk++;

                if ($filasEnChunk >= $chunkSize) {
                    $mpdf->WriteHTML($chunk, 2);
                    $chunk = '';
                    $filasEnChunk = 0;
                }
            }

            if ($chunk !== '') {
                $mpdf->WriteHTML($chunk, 2);
            }
        }

        // *** SUMATORIAS + CIERRE ***
        $tablaFooter = "
    <tr>
        <td style='font-weight: bold; font-size: 12px'>#</td>
        <td style='font-weight: bold; font-size: 12px'>CODIGO</td>
        <td style='font-weight: bold; font-size: 12px'>DESCRIPCION</td>
        <td style='font-weight: bold; font-size: 12px'>FINANCIAMIENTO</td>
        <td style='font-weight: bold; font-size: 12px'>LINEA</td>
        <td style='font-weight: bold; font-size: 12px'>PROVEEDOR</td>
        <td style='font-weight: bold; font-size: 12px'>LOTE</td>
        <td style='font-weight: bold; font-size: 12px'>FECHA VENCIMIENTO</td>
        <td style='font-weight: bold; font-size: 12px'>COSTO</td>
        <td style='font-weight: bold; font-size: 12px'>COSTO DONA.</td>
        <td style='font-weight: bold; font-size: 12px'>CANTIDAD INICIAL</td>
        <td style='font-weight: bold; font-size: 12px'>ENTREGADO</td>
        <td style='font-weight: bold; font-size: 12px'>ENTREGADO TOTAL</td>
        <td style='font-weight: bold; font-size: 12px'>EXISTENCIA</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO DONAC.</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCA. FECHAS</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL DESCA. DONA FECHAS</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA</td>
        <td style='font-weight: bold; font-size: 12px'>TOTAL EXISTENCIA DONA.</td>
    </tr>
    <tr>
        <td colspan='14' style='text-align: right; font-weight: bold'></td>
        <td style='font-weight: bold'>$sumatoriaTotalDescargado</td>
        <td style='font-weight: bold'>$sumatoriaTotalDescargadoDonac</td>
        <td style='font-weight: bold'>$sumatoriaTotalDescaFecha</td>
        <td style='font-weight: bold'>$sumatoriaTotalDescaDonacionFecha</td>
        <td style='font-weight: bold'>$sumatoriaTotalExistencia</td>
        <td style='font-weight: bold'>$sumatoriaTotalDona</td>
    </tr>
    </tbody></table>

    <table style='border-collapse: collapse;' border='1' width='500'><tbody>
    <tr>
        <td style='font-weight: bold; font-size: 11px'>Total Descargado</td>
        <td style='font-weight: bold; font-size: 11px'>Total Existencias</td>
    </tr>
    <tr>
        <td style='font-weight: bold; font-size: 11px'>$sumatoriaTotalDescargado</td>
        <td style='font-weight: bold; font-size: 11px'>$sumatoriaTotalExistencia</td>
    </tr>
    </tbody></table>
    <br><br>";

        $mpdf->WriteHTML($tablaFooter, 2);
        $mpdf->Output();
    }




























    public function generarReporteFichaGeneralPaciente($idpaciente){

        $infoPaciente = Paciente::where('id', $idpaciente)->first();

        $nombreCompleto = $infoPaciente->nombres . ' ' . $infoPaciente->apellidos;
        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $fechaFormat = date("d-m-Y", strtotime($infoPaciente->fecha_nacimiento));

        $infoProfesion = Profesion::where('id', $infoPaciente->profesion_id)->first();

        $tipoDoc = Tipo_Documento::where('id', $infoPaciente->tipo_documento_id)->first();
        $tipoCivil = Estado_Civil::where('id', $infoPaciente->estado_civil_id)->first();


        $imagePath = public_path('storage/archivos/' . $infoPaciente->foto);

        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);


        $mpdf->SetTitle('Ficha Paciente');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            Hoja de Datos Generales de Paciente <br><br>
            Expediente:  $infoPaciente->numero_expediente</p>
            </div>";

        if($infoPaciente->foto != null){
            $tabla .= "<table width='100%' style='margin-top: 25px'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='text-align: center'>
                <img src='$imagePath' width='150px' height='150px'>
                </td>
            <tr>";


            $tabla .= "</tbody></table>";
        }


        $tabla .= "<table id='tablaForSubrayada'>
                    <thead>";

        $tabla .= "<tr>
                <th style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Nombre:</th>
                <th style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$nombreCompleto</th>
            <tr>
            </thead>
            </tbody>
            ";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Fecha de Nacimiento</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$fechaFormat</td>
            </tr>";


        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Edad:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$edad</td>
            </tr>";


        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Sexo:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->sexo</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Estado Civil:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$tipoCivil->nombre</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Tipo Documento:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$tipoDoc->nombre</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Número de documento:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->num_documento</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Correo electrónico:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->correo</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Teléfono celular:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->celular</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Teléfono alternativo:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->telefono</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Domicilio:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoPaciente->direccion</td>
            </tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 14px; width: 20% !important; text-align: left; font-weight: bold'>Profesión:</td>
                <td style='font-weight: bold; font-size: 14px; width: 30% !important; text-align: left; font-weight: normal'>$infoProfesion->nombre</td>
            </tr>";


        $tabla .= "</tbody></table>";


        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }




    public function reporteNotaPaciente($idfila){


        $infoNota = NotasPaciente::where('id', $idfila)->first();
        $infoPaciente = Paciente::where('id', $infoNota->id_paciente)->first();
        $nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;
        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $fechaFormat = date("d-m-Y", strtotime($infoNota->fecha));

        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);

        $mpdf->SetTitle('Nota Paciente');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
            Nota de Paciente <br>
            </div>";



        $tabla .= "

             <table width='100%'>
                <tr>
                    <td style='text-align: left; width: 45%'>
                        <!-- Contenido izquierdo -->
                        <p style='font-size: 12px; font-family: normal'><strong>Paciente: </strong>$nombrePaciente</p>
                    </td>
                    <td style='text-align: center; width: 20%'>
                        <!-- Contenido central -->
                         <p style='font-size: 12px; font-family: normal'><strong>Edad: </strong>$edad</p>
                    </td>
                    <td style='text-align: right; width: 20%'>
                        <!-- Contenido derecho -->
                         <p style='font-size: 12px; font-family: normal'><strong>Fecha: </strong>$fechaFormat</p>
                    </td>
                </tr> </table> <br><br> ";


        $tabla .= "$infoNota->nota";

        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }






    public function reportePDFInicialPorPeriodosFarmacia($desde, $hasta)
    {
        $start = Carbon::parse($desde)->startOfDay();
        $end   = Carbon::parse($hasta)->endOfDay();

        $desdeFormat = Carbon::parse($desde)->format('d/m/Y');
        $hastaFormat = Carbon::parse($hasta)->format('d/m/Y');

        $rows = DB::select("
    WITH movimientos AS (
        SELECT
            ed.medicamento_id AS id_material,
            COALESCE(NULLIF(fa.codigo_articulo, ''), 'SIN-CODIGO') AS codigo,
            fa.nombre AS descripcion,
            ed.precio,
            em.fecha AS fecha_movimiento,
            ed.cantidad_fija AS entrada,
            0 AS salida
        FROM entrada_medicamento_detalle ed
        INNER JOIN entrada_medicamento em ON em.id = ed.entrada_medicamento_id
        INNER JOIN farmacia_articulo fa   ON fa.id = ed.medicamento_id

        UNION ALL

        -- SALIDAS POR RECETA MEDICA
        SELECT
            ed.medicamento_id,
            COALESCE(NULLIF(fa.codigo_articulo, ''), 'SIN-CODIGO'),
            fa.nombre,
            ed.precio,
            sr.fecha,
            0 AS entrada,
            srd.cantidad AS salida
        FROM salida_receta_detalle srd
        INNER JOIN salida_receta sr               ON sr.id = srd.salidareceta_id
        INNER JOIN entrada_medicamento_detalle ed  ON ed.id = srd.entrada_detalle_id
        INNER JOIN farmacia_articulo fa            ON fa.id = ed.medicamento_id

        UNION ALL

        -- SALIDAS MANUALES (ORDEN DE SALIDA)
        SELECT
            ed.medicamento_id,
            COALESCE(NULLIF(fa.codigo_articulo, ''), 'SIN-CODIGO'),
            fa.nombre,
            ed.precio,
            os.fecha,
            0 AS entrada,
            osd.cantidad AS salida
        FROM orden_salida_detalle osd
        INNER JOIN orden_salida os                 ON os.id = osd.orden_salida_id
        INNER JOIN entrada_medicamento_detalle ed  ON ed.id = osd.entrada_medi_detalle_id
        INNER JOIN farmacia_articulo fa            ON fa.id = ed.medicamento_id
    )
    SELECT
        id_material, codigo, descripcion,
        MAX(precio) AS precio,

        SUM(CASE WHEN fecha_movimiento <  ? THEN entrada - salida ELSE 0 END) AS saldo_inicial_cant,
        SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN entrada ELSE 0 END) AS entradas_mes_cant,
        SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN salida  ELSE 0 END) AS salidas_mes_cant,

        (
            SUM(CASE WHEN fecha_movimiento <  ? THEN entrada - salida ELSE 0 END)
          + SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN entrada ELSE 0 END)
          - SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN salida  ELSE 0 END)
        ) AS saldo_final_cant,

        SUM(CASE WHEN fecha_movimiento <  ? THEN entrada - salida ELSE 0 END) * MAX(precio) AS saldo_inicial_money,
        SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN entrada ELSE 0 END) * MAX(precio) AS entradas_mes_money,
        SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN salida  ELSE 0 END) * MAX(precio) AS salidas_mes_money,

        (
            SUM(CASE WHEN fecha_movimiento <  ? THEN entrada - salida ELSE 0 END)
          + SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN entrada ELSE 0 END)
          - SUM(CASE WHEN fecha_movimiento >= ? AND fecha_movimiento <= ? THEN salida  ELSE 0 END)
        ) * MAX(precio) AS saldo_final_money

        FROM movimientos
        GROUP BY id_material, codigo, descripcion
        ORDER BY codigo, descripcion
    ", [
            $start->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(),
            $start->toDateString(), $end->toDateString(),
            $start->toDateString(), $end->toDateString(),
        ]);

        // ── Filtrar filas completamente en cero ───────────────────────────────────
        $rows = array_values(array_filter($rows, function ($r) {
            return !((float)($r->saldo_inicial_cant ?? 0) == 0
                && (float)($r->entradas_mes_cant  ?? 0) == 0
                && (float)($r->salidas_mes_cant   ?? 0) == 0
                && (float)($r->saldo_final_cant   ?? 0) == 0);
        }));

        // ── Verificar si hay negativos ────────────────────────────────────────────
        $hayNegativos = false;
        foreach ($rows as $r) {
            if ((int)($r->saldo_final_cant ?? 0) < 0 || (int)($r->saldo_inicial_cant ?? 0) < 0) {
                $hayNegativos = true;
                break;
            }
        }

        // ── Totales y agrupado por código ─────────────────────────────────────────
        $totales = [
            'inicial_cant'   => 0, 'inicial_money'  => 0.0,
            'entradas_cant'  => 0, 'entradas_money' => 0.0,
            'salidas_cant'   => 0, 'salidas_money'  => 0.0,
            'final_cant'     => 0, 'final_money'    => 0.0,
        ];
        $sumPorCodigo = [];

        foreach ($rows as $r) {
            $totales['inicial_cant']   += (int)   ($r->saldo_inicial_cant  ?? 0);
            $totales['entradas_cant']  += (int)   ($r->entradas_mes_cant   ?? 0);
            $totales['salidas_cant']   += (int)   ($r->salidas_mes_cant    ?? 0);
            $totales['final_cant']     += (int)   ($r->saldo_final_cant    ?? 0);
            $totales['inicial_money']  += (float) ($r->saldo_inicial_money ?? 0);
            $totales['entradas_money'] += (float) ($r->entradas_mes_money  ?? 0);
            $totales['salidas_money']  += (float) ($r->salidas_mes_money   ?? 0);
            $totales['final_money']    += (float) ($r->saldo_final_money   ?? 0);

            $cod = $r->codigo ?? 'SIN-CODIGO';
            if (!isset($sumPorCodigo[$cod])) {
                $sumPorCodigo[$cod] = [
                    'codigo'        => $cod,
                    'inicial_cant'  => 0, 'inicial_money'  => 0.0,
                    'entradas_cant' => 0, 'entradas_money' => 0.0,
                    'salidas_cant'  => 0, 'salidas_money'  => 0.0,
                    'final_cant'    => 0, 'final_money'    => 0.0,
                ];
            }
            $sumPorCodigo[$cod]['inicial_cant']   += (int)   ($r->saldo_inicial_cant  ?? 0);
            $sumPorCodigo[$cod]['entradas_cant']  += (int)   ($r->entradas_mes_cant   ?? 0);
            $sumPorCodigo[$cod]['salidas_cant']   += (int)   ($r->salidas_mes_cant    ?? 0);
            $sumPorCodigo[$cod]['final_cant']     += (int)   ($r->saldo_final_cant    ?? 0);
            $sumPorCodigo[$cod]['inicial_money']  += (float) ($r->saldo_inicial_money ?? 0);
            $sumPorCodigo[$cod]['entradas_money'] += (float) ($r->entradas_mes_money  ?? 0);
            $sumPorCodigo[$cod]['salidas_money']  += (float) ($r->salidas_mes_money   ?? 0);
            $sumPorCodigo[$cod]['final_money']    += (float) ($r->saldo_final_money   ?? 0);
        }

        // ── PDF ───────────────────────────────────────────────────────────────────
        $mpdf = new \Mpdf\Mpdf([
            'tempDir'      => sys_get_temp_dir(),
            'format'       => 'LETTER',
            'orientation'  => 'L',
            'default_font' => 'arial',
        ]);
        $mpdf->SetTitle('Control de Entradas/Salidas por Período');
        $mpdf->showImageErrors = false;

        $logoalcaldia = public_path('images/logodis.png');

        if (file_exists(public_path('css/cssbodega.css'))) {
            $mpdf->WriteHTML(
                file_get_contents(public_path('css/cssbodega.css')),
                \Mpdf\HTMLParserMode::HEADER_CSS
            );
        }

        // ══ ENCABEZADO ═══════════════════════════════════════════════════════════
        $html = "
<table width='100%' style='border-collapse:collapse; font-family:Arial,sans-serif; margin-bottom:6px;'>
    <tr>
        <td style='width:22%; border:0.8px solid #000; padding:6px 8px;'>
            <table width='100%'>
                <tr>
                    <td style='width:35%; text-align:left;'>
                        <img src='{$logoalcaldia}' style='height:40px'>
                    </td>
                    <td style='width:65%; text-align:left; color:#104e8c; font-size:11px; font-weight:bold; line-height:1.4;'>
                        SANTA ANA NORTE<br>EL SALVADOR
                    </td>
                </tr>
            </table>
        </td>
        <td style='width:56%; border-top:0.8px solid #000; border-bottom:0.8px solid #000;
                    padding:8px; text-align:center; vertical-align:middle;'>
            <div style='font-size:15px; font-weight:bold; color:#000; letter-spacing:.5px;'>
                CONTROL DE ENTRADAS / SALIDAS - FARMACIA
            </div>
        </td>
        <td style='width:22%; border:0.8px solid #000; padding:0; vertical-align:top;'>
            <table width='100%' style='font-size:10px; border-collapse:collapse;'>
                <tr>
                    <td style='border-right:0.8px solid #000; border-bottom:0.8px solid #000; padding:4px 6px; font-weight:bold;'>Código:</td>
                    <td style='border-bottom:0.8px solid #000; padding:4px 6px; text-align:center;'></td>
                </tr>
                <tr>
                    <td style='border-right:0.8px solid #000; border-bottom:0.8px solid #000; padding:4px 6px; font-weight:bold;'>Versión:</td>
                    <td style='border-bottom:0.8px solid #000; padding:4px 6px; text-align:center;'>000</td>
                </tr>
                <tr>
                    <td style='border-right:0.8px solid #000; padding:4px 6px; font-weight:bold;'>Fecha de vigencia:</td>
                    <td style='padding:4px 6px; text-align:center;'></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<strong>Del {$desdeFormat} al {$hastaFormat}</strong><br>
";

        // ══ TABLA DETALLE ════════════════════════════════════════════════════════
        $html .= "
<table width='100%' border='1' cellspacing='0' cellpadding='4'
       style='border-collapse:collapse; font-family:Arial,sans-serif; font-size:11px; margin-top:8px;'>
    <thead style='background:#f2f4f8;'>
        <tr>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:3%;'>#</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:25%;'>Descripción / Nombre</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:8%;'>PRECIO</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:7%;'>INICIAL</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:8%;'>\$ INICIAL</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:7%;'>ENTRADAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:8%;'>\$ ENTRADAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:7%;'>SALIDAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:8%;'>\$ SALIDAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:7%;'>SALDO</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:8%;'>\$ SALDO</th>
        </tr>
    </thead>
    <tbody>
";

        $i = 1;
        foreach ($rows as $r) {
            $saldoNegativo   = (int)($r->saldo_final_cant   ?? 0) < 0;
            $inicialNegativo = (int)($r->saldo_inicial_cant ?? 0) < 0;
            $hayNegativoFila = $saldoNegativo || $inicialNegativo;

            $rowStyle   = $hayNegativoFila ? "background:#fff0f0;" : "";
            $alertStyle = $hayNegativoFila ? "color:#cc0000; font-weight:bold;" : "";
            $alerta     = $hayNegativoFila ? " &#9888;" : "";

            $html .= "
<tr style='{$rowStyle}'>
    <td style='border:1px solid #000; padding:4px; text-align:center; {$alertStyle}'>{$i}</td>
    <td style='border:1px solid #000; padding:4px; {$alertStyle}'>" . e($r->descripcion ?? '—') . $alerta . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>\$" . number_format($r->precio ?? 0, 4) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyle}'>" . number_format($r->saldo_inicial_cant ?? 0) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyle}'>\$" . number_format($r->saldo_inicial_money ?? 0, 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>" . number_format($r->entradas_mes_cant ?? 0) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>\$" . number_format($r->entradas_mes_money ?? 0, 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>" . number_format($r->salidas_mes_cant ?? 0) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>\$" . number_format($r->salidas_mes_money ?? 0, 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyle}'>" . number_format($r->saldo_final_cant ?? 0) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyle}'>\$" . number_format($r->saldo_final_money ?? 0, 2) . "</td>
</tr>
";
            $i++;
        }

        if (empty($rows)) {
            $html .= "<tr><td colspan='11' style='text-align:center; color:#888; padding:12px;'>Sin registros en el rango seleccionado.</td></tr>";
        }

        // ── Fila totales ──────────────────────────────────────────────────────────
        $html .= "
    </tbody>
    <tfoot>
        <tr style='font-weight:bold; background:#f9fafb;'>
            <td colspan='3' style='border:1px solid #000; padding:5px 8px; text-align:right;'>Totales:</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>" . number_format($totales['inicial_cant']) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>\$" . number_format($totales['inicial_money'], 2) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>" . number_format($totales['entradas_cant']) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>\$" . number_format($totales['entradas_money'], 2) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>" . number_format($totales['salidas_cant']) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>\$" . number_format($totales['salidas_money'], 2) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>" . number_format($totales['final_cant']) . "</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>\$" . number_format($totales['final_money'], 2) . "</td>
        </tr>
    </tfoot>
</table>
";

        // ── Nota de negativos ─────────────────────────────────────────────────────
        if ($hayNegativos) {
            $html .= "
<p style='color:#cc0000; font-size:11px; font-family:Arial,sans-serif; margin-top:6px;'>
    &#9888; Las filas marcadas en rojo presentan saldo negativo. Esto puede indicar salidas manuales duplicadas o datos incorrectos. Verificar en el sistema.
</p>
";
        }

        // ══ RESUMEN DEL PERÍODO ════════════════════════════════════════════════
        $html .= "
<br>
<table width='60%' border='1' cellspacing='0' cellpadding='6'
       style='border-collapse:collapse; font-family:Arial,sans-serif; font-size:12px;'>
    <tr style='background:#eef3ff; font-weight:bold; text-align:center;'>
        <td colspan='3'>Resumen del período {$desdeFormat} - {$hastaFormat}</td>
    </tr>
    <tr style='font-weight:bold; background:#f9fafb;'>
        <td></td>
        <td style='text-align:right;'>Cantidad</td>
        <td style='text-align:right;'>Dinero ($)</td>
    </tr>
    <tr>
        <td>Ingresó (Entradas del período)</td>
        <td style='text-align:right;'>" . number_format($totales['entradas_cant']) . "</td>
        <td style='text-align:right;'>\$" . number_format($totales['entradas_money'], 2) . "</td>
    </tr>
    <tr>
        <td>Salió (Salidas del período)</td>
        <td style='text-align:right;'>" . number_format($totales['salidas_cant']) . "</td>
        <td style='text-align:right;'>\$" . number_format($totales['salidas_money'], 2) . "</td>
    </tr>
    <tr>
        <td>Disponible al cierre (Saldo final)</td>
        <td style='text-align:right;'>" . number_format($totales['final_cant']) . "</td>
        <td style='text-align:right;'>\$" . number_format($totales['final_money'], 2) . "</td>
    </tr>
</table>
";

        // ══ RESUMEN POR CÓDIGO ═══════════════════════════════════════════════════
        if (!empty($sumPorCodigo)) {
            $totalSaldoFinalCodigos = 0;

            $html .= "
<br><br>
<table width='100%' border='1' cellspacing='0' cellpadding='4'
       style='border-collapse:collapse; font-family:Arial,sans-serif; font-size:11px;'>
    <thead style='background:#f2f4f8;'>
        <tr>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:4%;'>#</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:10%;'>Código</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:6%;'>INICIAL</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:10%;'>\$ INICIAL</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:6%;'>ENTRADAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:10%;'>\$ ENTRADAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:6%;'>SALIDAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:10%;'>\$ SALIDAS</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:center; width:6%;'>SALDO</th>
            <th style='border:1px solid #000; padding:5px 3px; text-align:right;  width:10%;'>\$ SALDO</th>
        </tr>
    </thead>
    <tbody>
";

            $j = 1;
            foreach ($sumPorCodigo as $s) {
                $totalSaldoFinalCodigos += (float) $s['final_money'];

                $codNegativo    = (int)$s['final_cant'] < 0 || (int)$s['inicial_cant'] < 0;
                $rowStyleCod    = $codNegativo ? "background:#fff0f0;" : "";
                $alertStyleCod  = $codNegativo ? "color:#cc0000; font-weight:bold;" : "";

                $html .= "
<tr style='{$rowStyleCod}'>
    <td style='border:1px solid #000; padding:4px; text-align:center; {$alertStyleCod}'>{$j}</td>
    <td style='border:1px solid #000; padding:4px; text-align:center; {$alertStyleCod}'>" . e($s['codigo']) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyleCod}'>" . number_format($s['inicial_cant']) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyleCod}'>\$" . number_format($s['inicial_money'], 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>" . number_format($s['entradas_cant']) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>\$" . number_format($s['entradas_money'], 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>" . number_format($s['salidas_cant']) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right;'>\$" . number_format($s['salidas_money'], 2) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyleCod}'>" . number_format($s['final_cant']) . "</td>
    <td style='border:1px solid #000; padding:4px; text-align:right; {$alertStyleCod}'>\$" . number_format($s['final_money'], 2) . "</td>
</tr>
";
                $j++;
            }

            $html .= "
    </tbody>
    <tfoot>
        <tr style='font-weight:bold; background:#f9fafb;'>
            <td colspan='9' style='border:1px solid #000; padding:5px 8px; text-align:right;'>TOTAL</td>
            <td style='border:1px solid #000; padding:5px; text-align:right;'>\$" . number_format($totalSaldoFinalCodigos, 2) . "</td>
        </tr>
    </tfoot>
</table>
";
        }

        $mpdf->setFooter('Página {PAGENO} de {nb}');
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output();
    }





    public function movimientosMedicamento($id, $desde = null, $hasta = null){

        ini_set('memory_limit', '1024M');

        $medicamento = FarmaciaArticulo::findOrFail($id);

        $start = $desde ? Carbon::parse($desde)->startOfDay() : null;
        $end   = $hasta ? Carbon::parse($hasta)->endOfDay()   : null;

        $desdeFormat = $desde ? date("d-m-Y", strtotime($desde)) : 'Inicio';
        $hastaFormat = $hasta ? date("d-m-Y", strtotime($hasta)) : 'Hoy';

        // *** ENTRADAS: lotes del medicamento ***
        $entradas = DB::table('entrada_medicamento_detalle AS emd')
            ->join('entrada_medicamento AS em', 'em.id', '=', 'emd.entrada_medicamento_id')
            ->join('proveedores AS p', 'p.id', '=', 'em.proveedor_id')
            ->join('fuente_financiamiento AS ff', 'ff.id', '=', 'em.fuentefina_id')
            ->where('emd.medicamento_id', $id)
            ->when($start && $end, function($q) use ($start, $end){
                $q->whereBetween('em.fecha', [$start, $end]);
            })
            ->select(
                'em.fecha',
                'em.numero_factura',
                'p.nombre AS proveedor',
                'ff.nombre AS fuente',
                'emd.lote',
                'emd.fecha_vencimiento',
                'emd.cantidad_fija AS cantidad_entrada',
                'emd.precio',
                'emd.precio_donacion'
            )
            ->orderBy('em.fecha', 'ASC')
            ->get();

        // *** SALIDAS POR RECETA ***
        $salidasReceta = DB::table('salida_receta_detalle AS srd')
            ->join('salida_receta AS sr', 'sr.id', '=', 'srd.salidareceta_id')
            ->join('recetas AS r', 'r.id', '=', 'sr.recetas_id')
            ->join('entrada_medicamento_detalle AS emd', 'emd.id', '=', 'srd.entrada_detalle_id')
            ->join('usuario AS u', 'u.id', '=', 'sr.usuario_id')
            ->where('emd.medicamento_id', $id)
            ->where('r.estado', 2)
            ->when($start && $end, function($q) use ($start, $end){
                $q->whereBetween('sr.fecha', [$start, $end]);
            })
            ->select(
                'sr.fecha',
                'r.id AS receta_id',
                'u.nombre AS usuario',
                'emd.lote',
                'srd.cantidad AS cantidad_salida',
                'emd.precio',
                'emd.precio_donacion',
                'sr.notas'
            )
            ->orderBy('sr.fecha', 'ASC')
            ->get();

        // *** SALIDAS POR ORDEN ***
        $salidasOrden = DB::table('orden_salida_detalle AS osd')
            ->join('orden_salida AS os', 'os.id', '=', 'osd.orden_salida_id')
            ->join('entrada_medicamento_detalle AS emd', 'emd.id', '=', 'osd.entrada_medi_detalle_id')
            ->join('usuario AS u', 'u.id', '=', 'os.usuario_id')
            ->join('motivo_farmacia AS mf', 'mf.id', '=', 'os.motivo_id')
            ->where('emd.medicamento_id', $id)
            ->when($start && $end, function($q) use ($start, $end){
                $q->whereBetween('os.fecha', [$start, $end]);
            })
            ->select(
                'os.fecha',
                'os.hora',
                'mf.nombre AS motivo',
                'u.nombre AS usuario',
                'emd.lote',
                'osd.cantidad AS cantidad_salida',
                'emd.precio',
                'emd.precio_donacion',
                'os.observaciones'
            )
            ->orderBy('os.fecha', 'ASC')
            ->get();

        // *** TOTALES ***
        $totalEntradas      = $entradas->sum('cantidad_entrada');
        $totalSalidasReceta = $salidasReceta->sum('cantidad_salida');
        $totalSalidasOrden  = $salidasOrden->sum('cantidad_salida');
        $totalSalidas       = $totalSalidasReceta + $totalSalidasOrden;

        // *** mPDF ***
        $mpdf = new \Mpdf\Mpdf([
            'tempDir'      => sys_get_temp_dir(),
            'format'       => 'LETTER',
            'orientation'  => 'L',
            'margin_top'   => 35,
            'margin_left'  => 8,
            'margin_right' => 8,
            'margin_bottom'=> 12,
        ]);

        $mpdf->SetTitle('Movimientos ' . $medicamento->nombre);
        $mpdf->showImageErrors = false;

        $logoalcaldiaData = base64_encode(file_get_contents(public_path('images/gobiernologo.jpg')));
        $logosantaanaData = base64_encode(file_get_contents(public_path('images/logo.png')));
        $logoalcaldia     = 'data:image/jpeg;base64,' . $logoalcaldiaData;
        $logosantaana     = 'data:image/png;base64,'  . $logosantaanaData;

        $mpdf->SetHTMLFooter("
        <table style='width:100%; font-size:9px;'>
            <tr>
                <td style='text-align:right;'>Página {PAGENO} de {nb}</td>
            </tr>
        </table>
    ");

        $stylesheet = file_get_contents('css/cssreportefinal.css');
        $mpdf->WriteHTML($stylesheet, 1);

        // *** HEADER ***
        $header = "
    <table style='width:100%; border-collapse:collapse; margin-bottom:0px'>
        <tr>
            <td style='width:15%; text-align:left;'>
                <img src='$logosantaana' style='max-width:100px; height:auto;'>
            </td>
            <td style='width:70%; text-align:center;'>
                <h1 style='font-size:15px; margin:0; color:#003366;'>ALCALDÍA MUNICIPAL DE SANTA ANA NORTE</h1>
                <h3 style='font-size:14px; margin:0; color:#003366;'>Clínica Municipal Cristóbal Peraza</h3>
                <h3 style='font-size:13px; margin:0; color:#003366;'>REPORTE DE MOVIMIENTOS DE MEDICAMENTO</h3>
                <h3 style='font-size:12px; margin:2px 0 0 0; color:#003366;'>
                    <strong>" . strtoupper($medicamento->nombre) . "</strong>
                </h3>
                <h4 style='font-size:11px; margin:2px 0 0 0; color:#003366;'>
                    PERÍODO: $desdeFormat &nbsp;–&nbsp; $hastaFormat
                </h4>
            </td>
            <td style='width:15%; text-align:right;'>
                <img src='$logoalcaldia' style='max-width:60px; height:auto;'>
            </td>
        </tr>
    </table>
    <hr style='border:none; border-top:2px solid #003366; margin:0;'>
    ";

        $mpdf->WriteHTML($header, 2);

        // *** SECCIÓN ENTRADAS ***
        $seccionEntradas = "
    <br>
    <table style='width:100%; border-collapse:collapse;'>
        <tr style='background-color:#003366; color:#ffffff;'>
            <td colspan='9' style='font-size:11px; font-weight:bold; padding:4px 6px;'>
                ENTRADAS DE MEDICAMENTO &nbsp;|&nbsp; Total unidades: $totalEntradas
            </td>
        </tr>
        <tr style='background-color:#cce0ff;'>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>FECHA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>N° FACTURA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>PROVEEDOR</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>FUENTE</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>LOTE</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>FECHA VEN.</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>CANTIDAD</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>COSTO</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>COSTO DONA.</td>
        </tr>";

        if($entradas->isEmpty()){
            $seccionEntradas .= "<tr><td colspan='9' style='font-size:9px; padding:3px; text-align:center;'>Sin entradas en el período</td></tr>";
        } else {
            foreach($entradas as $e){
                $fechaE    = date('d-m-Y', strtotime($e->fecha));
                $fechaVenE = date('d-m-Y', strtotime($e->fecha_vencimiento));
                $costoE    = '$' . number_format((float)$e->precio, 2, '.', ',');
                $costoDonaE= '$' . number_format((float)$e->precio_donacion, 2, '.', ',');

                $seccionEntradas .= "<tr>
                <td style='font-size:8px; padding:1px 2px;'>$fechaE</td>
                <td style='font-size:8px; padding:1px 2px;'>$e->numero_factura</td>
                <td style='font-size:8px; padding:1px 2px;'>$e->proveedor</td>
                <td style='font-size:8px; padding:1px 2px;'>$e->fuente</td>
                <td style='font-size:8px; padding:1px 2px;'>$e->lote</td>
                <td style='font-size:8px; padding:1px 2px;'>$fechaVenE</td>
                <td style='font-size:8px; padding:1px 2px;'>$e->cantidad_entrada</td>
                <td style='font-size:8px; padding:1px 2px;'>$costoE</td>
                <td style='font-size:8px; padding:1px 2px;'>$costoDonaE</td>
            </tr>";
            }
        }

        $seccionEntradas .= "</table>";
        $mpdf->WriteHTML($seccionEntradas, 2);

        // *** SECCIÓN SALIDAS POR RECETA ***
        $seccionRecetas = "
    <br>
    <table style='width:100%; border-collapse:collapse;'>
        <tr style='background-color:#003366; color:#ffffff;'>
            <td colspan='7' style='font-size:11px; font-weight:bold; padding:4px 6px;'>
                SALIDAS POR RECETA &nbsp;|&nbsp; Total unidades: $totalSalidasReceta
            </td>
        </tr>
        <tr style='background-color:#cce0ff;'>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>FECHA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>N° RECETA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>USUARIO</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>LOTE</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>CANTIDAD</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>COSTO UNIT.</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>NOTAS</td>
        </tr>";

        if($salidasReceta->isEmpty()){
            $seccionRecetas .= "<tr><td colspan='7' style='font-size:9px; padding:3px; text-align:center;'>Sin salidas por receta en el período</td></tr>";
        } else {
            foreach($salidasReceta as $sr){
                $fechaSR  = date('d-m-Y H:i', strtotime($sr->fecha));
                $costoSR  = '$' . number_format((float)$sr->precio, 2, '.', ',');

                $seccionRecetas .= "<tr>
                <td style='font-size:8px; padding:1px 2px;'>$fechaSR</td>
                <td style='font-size:8px; padding:1px 2px;'># $sr->receta_id</td>
                <td style='font-size:8px; padding:1px 2px;'>$sr->usuario</td>
                <td style='font-size:8px; padding:1px 2px;'>$sr->lote</td>
                <td style='font-size:8px; padding:1px 2px;'>$sr->cantidad_salida</td>
                <td style='font-size:8px; padding:1px 2px;'>$costoSR</td>
                <td style='font-size:8px; padding:1px 2px;'>$sr->notas</td>
            </tr>";
            }
        }

        $seccionRecetas .= "</table>";
        $mpdf->WriteHTML($seccionRecetas, 2);

        // *** SECCIÓN SALIDAS POR ORDEN ***
        $seccionOrdenes = "
    <br>
    <table style='width:100%; border-collapse:collapse;'>
        <tr style='background-color:#003366; color:#ffffff;'>
            <td colspan='7' style='font-size:11px; font-weight:bold; padding:4px 6px;'>
                SALIDAS POR ORDEN &nbsp;|&nbsp; Total unidades: $totalSalidasOrden
            </td>
        </tr>
        <tr style='background-color:#cce0ff;'>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>FECHA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>HORA</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>MOTIVO</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>USUARIO</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>LOTE</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>CANTIDAD</td>
            <td style='font-weight:bold; font-size:9px; padding:2px;'>OBSERVACIONES</td>
        </tr>";

        if($salidasOrden->isEmpty()){
            $seccionOrdenes .= "<tr><td colspan='7' style='font-size:9px; padding:3px; text-align:center;'>Sin salidas por orden en el período</td></tr>";
        } else {
            foreach($salidasOrden as $so){
                $fechaSO = date('d-m-Y', strtotime($so->fecha));

                $seccionOrdenes .= "<tr>
                <td style='font-size:8px; padding:1px 2px;'>$fechaSO</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->hora</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->motivo</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->usuario</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->lote</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->cantidad_salida</td>
                <td style='font-size:8px; padding:1px 2px;'>$so->observaciones</td>
            </tr>";
            }
        }

        $seccionOrdenes .= "</table>";
        $mpdf->WriteHTML($seccionOrdenes, 2);

        // *** RESUMEN FINAL ***
        $resumen = "
    <br>
    <table style='border-collapse:collapse;' border='1' width='350'>
        <tr style='background-color:#003366; color:#ffffff;'>
            <td style='font-weight:bold; font-size:10px; padding:3px;'>Total Entradas</td>
            <td style='font-weight:bold; font-size:10px; padding:3px;'>Total Salidas Receta</td>
            <td style='font-weight:bold; font-size:10px; padding:3px;'>Total Salidas Orden</td>
            <td style='font-weight:bold; font-size:10px; padding:3px;'>Total Salidas</td>
        </tr>
        <tr>
            <td style='font-size:10px; padding:3px; font-weight:bold;'>$totalEntradas</td>
            <td style='font-size:10px; padding:3px; font-weight:bold;'>$totalSalidasReceta</td>
            <td style='font-size:10px; padding:3px; font-weight:bold;'>$totalSalidasOrden</td>
            <td style='font-size:10px; padding:3px; font-weight:bold;'>$totalSalidas</td>
        </tr>
    </table>
    <br><br>";

        $mpdf->WriteHTML($resumen, 2);
        $mpdf->Output();
    }



}
