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
                        'deta.lote', 'deta.fecha_vencimiento', 'fa.id')
                ->where('deta.entrada_medicamento_id', $infoFila->id)
                ->orderBy('fa.nombre', 'ASC')
                ->get();

                $totalXColumna = 0;

                foreach ($arrayDetalle as $dato){


                    $multi = $dato->cantidad_fija * $dato->precio;
                    $totalXColumna = $totalXColumna + $multi;

                    $dato->multiFormat = '$' . number_format((float)$multi, 4, '.', ',');
                    $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));
                    $dato->precioFormat = '$' . number_format((float)$dato->precio, 4, '.', ',');
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


        $totalFundel = sprintf("%.2f", floor($totalFundel * 100) / 100);
        $totalFundel = '$' . number_format((float)$totalFundel, 2, '.', ',');

        $totalCovid = sprintf("%.2f", floor($totalCovid * 100) / 100);
        $totalCovid = '$' . number_format((float)$totalCovid, 2, '.', ',');

       // $totalPropios = sprintf("%.2f", floor($totalPropios * 100) / 100);

        $totalPropios = '$' . round($totalPropios, 2);
        $totalPropios = '$' . number_format((float)$totalPropios, 2, '.', ',');

        $totalGeneral = sprintf("%.2f", floor($totalGeneral * 100) / 100);
        $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');


        //$mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Entrada Artículos');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logodis.png';

        $tabla = "<div class='contenedorp'>
            <img id='logo' src='$logoalcaldia'>
            <p id='titulo'>Clinica Municipal Cristobal Peraza <br> Tahuilapa, Distrito de Metapán, Santa Ana Norte<br>
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


            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 10px'>Fecha Venc.</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Artículo</td>
                <td style='font-weight: bold; width: 8%; font-size: 10px'>Lote</td>
                <td style='font-weight: bold; width: 13%; font-size: 10px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 10px'>Monto</td>
            <tr>";


            foreach ($detaFila->detallefila as $dato) {

                $tabla .= "<tr style='font-size: 10px'>
                <td style='font-size: 10px;' >$dato->fechaVencFormat</td>
                <td style='font-size: 10px;' >$dato->nombre</td>
                <td style='font-size: 10px;' >$dato->lote</td>
                <td style='font-size: 10px;' >$dato->cantidad_fija</td>
                <td style='font-size: 10px;' >$dato->precioFormat</td>
                <td style='font-size: 10px;' >$dato->multiFormat</td>
            <tr>";
            }

            $tabla .= "<tr>
                <td colspan='5' style='font-size: 10px; font-weight: bold'>Total</td>
                <td style='font-size: 10px; font-weight: normal'>$detaFila->totalxfilas</td>
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

        $totalColumna = 0;

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

                   $totalColumna += $multiFila;

                   $dato->contador = $contador;
                   $dato->precioXFila = '$' . number_format((float)$multiFila, 2, '.', ',');
                   $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
               }
            }

            $totalGeneral += $totalColumna;

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



            $tabla .= "<table width='100%' id='tablaFor'>
                    <tbody>";

            $tabla .= "<tr>
                <td style='font-weight: bold; width: 11%; font-size: 11px'>Artículo</td>
                <td style='font-weight: bold; width: 9%; font-size: 11px'>Fecha Vencimiento</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Lote</td>
                <td style='font-weight: bold; width: 12%; font-size: 11px'>Cantidad</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Precio</td>
                <td style='font-weight: bold; width: 15%; font-size: 11px'>Monto</td>
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


    public function vistaReporteFinal(){

        return view('backend.admin.reportes.final.vistareportefinal');
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
                }else{
                    $multiDescargadoDonacion = 0;
                }

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
                    $totalFondoPropioDescargado += $multiDescargado;
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

        $totalColumnaExistenciaFinal = number_format($totalCoEx, 2, '.', ',');


        $totalColumnaDescargado = '$' . number_format((float)$totalColumnaDescargado, 2, '.', ',');
        $totalColumnaExistencia = '$' . number_format((float)$totalColumnaExistencia, 2, '.', ',');



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




        $totalMaterialCovidDescargado = '$' . number_format((float)$totalMaterialCovidDescargado, 2, '.', ',');
        $totalMaterialCovidExistencia = '$' . number_format((float)$totalMaterialCovidExistencia, 2, '.', ',');

        $totalMaterialFundelDescargado = '$' . number_format((float)$totalMaterialFundelDescargado, 2, '.', ',');
        $totalMaterialFundelExistencia = '$' . number_format((float)$totalMaterialFundelExistencia, 2, '.', ',');


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
                            <td colspan='12' style='text-align: right; font-weight: bold'>TOTAL FONDOS PROPIOS: </td>
                            <td style='font-weight: bold'>$totalFondoPropioDescargado</td>
                            <td style='font-weight: bold'>$totalFondoPropioExistenciaFinal</td>
                        <tr>";


        $tabla .= "<tr>
                            <td colspan='12' style='text-align: right; font-weight: bold'>TOTAL MATERIALES COVID: </td>
                            <td style='font-weight: bold'>$totalMaterialCovidDescargado</td>
                            <td style='font-weight: bold'>$totalMaterialCovidExistencia</td>
                        <tr>";

        $tabla .= "<tr>
                            <td colspan='12' style='text-align: right; font-weight: bold'>TOTAL MATERIALES FUNDEL: </td>
                            <td style='font-weight: bold'>$totalMaterialFundelDescargado</td>
                            <td style='font-weight: bold'>$totalMaterialFundelExistencia</td>
                    <tr>";

        $tabla .= "<tr>
                            <td colspan='12' style='text-align: right; font-weight: bold'>TOTAL: </td>
                            <td style='font-weight: bold'>$totalColumnaDescargado</td>
                            <td style='font-weight: bold'>$totalColumnaExistenciaFinal</td>
                        <tr>";

                $tabla .= "</tbody></table>";


        $mpdf->setMargins(5, 5, 5);

        $stylesheet = file_get_contents('css/cssreportefinal.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();
    }


    public function generarReporteFinalv2($desde, $hasta){

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


        // obtener ID de entradas de esa fecha
        $arrayEntradas = EntradaMedicamento::all();

        $pilaIdEntradas = array();

        foreach ($arrayEntradas as $info) {
            array_push($pilaIdEntradas, $info->id);
        }


        $arrayMedicamentos = FarmaciaArticulo::orderBy('nombre', 'ASC')->get();
        $contador = 0;

        $hayDatos = false;

        // TABLAS
        // recetas
        // recetas_detalle
        // salida_receta
        // salida_receta_detalle



        // Columna: Total Desca. Fechas
        $columnaTotalDescaFecha = 0;
        // Columna: Total Descargado
        $columnaTotalDescargado = 0;

        foreach ($arrayMedicamentos as $dato){

            $arrayDetalle = EntradaMedicamentoDetalle::where('medicamento_id', $dato->id)->get();


            $infoLinea = Linea::where('id', $dato->linea_id)->first();

            foreach ($arrayDetalle as $fila){
                $contador++;
                $hayDatos = true;

                $entregadoTotal = 0;

                $infoEntradaFi = EntradaMedicamento::where('id', $fila->entrada_medicamento_id)->first();
                $infoProve = Proveedores::where('id', $infoEntradaFi->proveedor_id)->first();
                $infoFuenteFi = FuenteFinanciamiento::where('id', $infoEntradaFi->fuentefina_id)->first();

                $fechaVen = date("d-m-Y", strtotime($fila->fecha_vencimiento));
                $precioFormat = '$' . number_format((float)$fila->precio, 2, '.', ',');
                $precioFormatDonacion = '$' . number_format((float)$fila->precio_donacion, 2, '.', ',');




                $cantiEntregada = $fila->cantidad_fija - $fila->cantidad;

                $multiDescargado = $fila->precio * $cantiEntregada;
                $multiDescargadoDonacion = $fila->precio_donacion * $cantiEntregada;

                // Columna: Total Descargado
                $columnaTotalDescargado += $multiDescargado;

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
                    $totalFondoPropioDescargado += $multiDescargado;
                    $totalFondoPropioExistencia += $multiExist;
                }


                // necesito obtener de ese medicamento cuantas salidas hubo,


                $listaIDR = DB::table('recetas AS r')
                    ->join('salida_receta AS sr', 'sr.recetas_id', '=', 'r.id')
                    ->select('r.estado', 'sr.fecha', 'sr.id')
                    ->where('r.estado', 2) // solo procesadas
                    ->whereBetween('sr.fecha', [$start, $end])
                    ->get();


                $pilaIdSalidaReceta = array();

                foreach ($listaIDR as $infoR){
                    array_push($pilaIdSalidaReceta, $infoR->id);
                }

                // hoy sumar la cantidad de esos materiales
                $listaSumadaR = SalidaRecetaDetalle::whereIn('salidareceta_id', $pilaIdSalidaReceta)
                    ->where('entrada_detalle_id', $fila->id)
                    ->get();


                foreach ($listaSumadaR as $datoR){
                    $entregadoTotal = $entregadoTotal + $datoR->cantidad;
                }

                $total2Dec = sprintf("%.2f", floor($fila->precio * 100) / 100);
                $totalDescaFecha = $total2Dec * $entregadoTotal;

                // Columna: Total Desca. Fechas
                $columnaTotalDescaFecha += $totalDescaFecha;
                $totalDescaFecha = '$' . number_format((float)$totalDescaFecha, 2, '.', ',');



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
                    'entregadototal' => $entregadoTotal,
                    'existencia' => $fila->cantidad,
                    'total_descargado' => $multiDescargadoFormat,
                    'total_descargado_donacion' => $multiDescargadoFormatDonacion,

                    'totaldescafecha' => $totalDescaFecha,
                    'total_existencia' => $multiExistFormat,
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
        $totalFondoPropioDescargado = '$' . number_format((float)$totalFondoPropioDescargado, 2, '.', ',');
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
        $totalFondoPropioExistenciaFinal = '$' . number_format($totalCoExFondoPro, 2, '.', ',');

        $totalMaterialCovidDescargado = '$' . number_format((float)$totalMaterialCovidDescargado, 2, '.', ',');
        $totalMaterialCovidExistencia = '$' . number_format((float)$totalMaterialCovidExistencia, 2, '.', ',');

        $totalMaterialFundelDescargado = '$' . number_format((float)$totalMaterialFundelDescargado, 2, '.', ',');
        $totalMaterialFundelExistencia = '$' . number_format((float)$totalMaterialFundelExistencia, 2, '.', ',');


        // Columna: Total existencias
        // Columna: Total Desca. Fechas
        $columnaTotalDescaFecha = '$' . number_format((float)$columnaTotalDescaFecha, 2, '.', ',');
        // Columna: Total Descargado
        $columnaTotalDescargado = '$' . number_format((float)$columnaTotalDescargado, 2, '.', ',');



        //************ DATOS PARA SABER LOS INGRESOS POR MES (FECHA DESDE - FECHA HASTA), OBTENER EL TOTAL DINERO INGRESADO



        // Inicializar un array para almacenar los resultados
        $rangosFechas = [];
        $rangosFechasReparado = [];

        // Iterar a través de cada mes en el rango
        $current = $start->copy();
        while ($current->startOfMonth()->lessThanOrEqualTo($end)) {
            // Calcular el inicio del mes actual
            $inicioMes = $current->copy()->startOfMonth()->toDateString();
            // Si es el primer mes del rango, ajustar la fecha de inicio
            if ($current->isSameMonth($start)) {
                $inicioMes = $start->toDateString();
            }

            // Calcular el fin del mes actual
            $finMes = $current->copy()->endOfMonth()->toDateString();
            // Si es el último mes del rango, ajustar la fecha de fin
            if ($current->isSameMonth($end)) {
                $finMes = $end->toDateString();
            }

            // Almacenar las fechas en el array
            $rangosFechas[] = [
                'mes' => $current->locale('es')->translatedFormat('F Y'),
                'inicio' => $inicioMes,
                'fin' => $finMes
            ];

            // Avanzar al siguiente mes
            $current->addMonth();
        }


        usort($rangosFechas, function ($a, $b) {
            return strtotime($a['inicio']) <=> strtotime($b['inicio']);
        });



        // RECORRER FECHAS
        foreach ($rangosFechas as $datoArray){

            $desdeFecha = date("Y-m-d", strtotime($datoArray['inicio']));
            $hastaFecha = date("Y-m-d", strtotime($datoArray['fin']));

            $startF = Carbon::parse($desdeFecha)->startOfDay(); // año - mes - dia
            $endF = Carbon::parse($hastaFecha)->endOfDay();

            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$startF, $endF])
                ->orderBy('fecha', 'ASC')
                ->get();

            $totalGeneral = 0; // sumatoria de todas las fuentes de financiamiento

            foreach ($arrayEntradas as $infoFila){

                $arrayDetalle = DB::table('entrada_medicamento_detalle AS deta')
                    ->join('farmacia_articulo AS fa', 'fa.id', '=', 'deta.medicamento_id')
                    ->select('fa.nombre', 'deta.entrada_medicamento_id', 'deta.cantidad_fija', 'deta.precio',
                        'deta.lote', 'deta.fecha_vencimiento', 'fa.id')
                    ->where('deta.entrada_medicamento_id', $infoFila->id)
                    ->orderBy('fa.nombre', 'ASC')
                    ->get();

                $totalXColumna = 0;

                foreach ($arrayDetalle as $dato){

                    $multi = $dato->cantidad_fija * $dato->precio;
                    $totalXColumna = $totalXColumna + $multi;

                    $dato->multiFormat = '$' . number_format((float)$multi, 4, '.', ',');
                    $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));
                    $dato->precioFormat = '$' . number_format((float)$dato->precio, 4, '.', ',');
                }


                $totalGeneral = $totalGeneral + $totalXColumna;
            }


            $totalGeneral = sprintf("%.2f", floor($totalGeneral * 100) / 100);
            $totalGeneral = '$' . number_format((float)$totalGeneral, 2, '.', ',');

            $miMes =  $datoArray['mes'];
            $miInicio =  $datoArray['inicio'];
            $miFinal =  $datoArray['fin'];

            $rangosFechasReparado[] = [
                'mes' => $miMes,
                'inicio' => $miInicio,
                'fin' => $miFinal,
                'total' => $totalGeneral
            ];
        }













        //*******************************************************************************************



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
                <td style='font-weight: bold; font-size: 12px'>ENTREGADO TOTAL</td>
                <td style='font-weight: bold; font-size: 12px'>EXISTENCIA</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO</td>
                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCARGADO DONAC.</td>


                <td style='font-weight: bold; font-size: 12px'>TOTAL DESCA. FECHAS</td>
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

                $detaEntregadoTotal = $fila['entregadototal'];

                $detaExistencia = $fila['existencia'];
                $detaTotalDesc = $fila['total_descargado'];
                $detaTotalDescDonacion = $fila['total_descargado_donacion'];

                $totalDescaFecha = $fila['totaldescafecha'];

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
                            <td>$detaEntregadoTotal</td>
                            <td>$detaExistencia</td>
                            <td>$detaTotalDesc</td>
                            <td>$detaTotalDescDonacion</td>

                            <td>$totalDescaFecha</td>
                            <td>$detaTotalExis</td>
                        <tr>";
            }
        }

        $tabla .= "<tr>
                            <td colspan='13' style='text-align: right; font-weight: bold'></td>
                            <td style='font-weight: bold'>$columnaTotalDescargado</td>
                            <td style='font-weight: bold'>$columnaTotalDescaFecha</td>
                            <td style='font-weight: bold'>$totalColumnaExistenciaFinal</td>
                        <tr>";


        $tabla .= "</tbody></table>";


        $tabla .= "<table style='border-collapse: collapse;' border='1'; width='500'>
                    <tbody>";


        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 11px'>Total Descargado</td>
                <td style='font-weight: bold; font-size: 11px'>Total Existencias</td>
            <tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 11px'>$totalColumnaDescargado</td>
                <td style='font-weight: bold; font-size: 11px'>$totalColumnaExistenciaFinal</td>
            <tr>";


        $tabla .= "</tbody></table>";


        $tabla .= "<br><br>";

        // PARTE DE INGRESOS POR MES, PERO DE FECHA A FECHA

        $tabla .= "<table style='border-collapse: collapse;' border='1'; width='330'>
                    <tbody>";

        $tabla .= "<tr>
                <td colspan='4' style='font-weight: bold; font-size: 11px; text-align: center'>Ingresos</td>
            <tr>";

        $tabla .= "<tr>
                <td style='font-weight: bold; font-size: 11px'>Mes</td>
                <td style='font-weight: bold; font-size: 11px'>Fecha Desde</td>
                <td style='font-weight: bold; font-size: 11px'>Fecha Hasta</td>
                <td style='font-weight: bold; font-size: 11px'>Total</td>
            <tr>";

        foreach ($rangosFechasReparado as $dato){

            $dInicio = $dato['inicio'];
            $dHasta = $dato['fin'];
            $dMes = $dato['mes'];
            $dTotal = $dato['total'];

            $tabla .= "<tr>
                <td style='font-size: 10px'>$dMes</td>
                <td style='font-size: 10px'>$dInicio</td>
                <td style='font-size: 10px'>$dHasta</td>
                <td style='font-size: 10px'>$dTotal</td>
            <tr>";
        }



        $tabla .= "</tbody></table>";













        $mpdf->setMargins(5, 5, 5);

        $stylesheet = file_get_contents('css/cssreportefinal.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

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



}
