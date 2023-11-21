<?php

namespace App\Http\Controllers\backend\Reportes;

use App\Http\Controllers\Controller;
use App\Models\EntradaMedicamento;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\FuenteFinanciamiento;
use App\Models\Proveedores;
use App\Models\TipoFactura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


        $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
            ->orderBy('fecha', 'ASC')
            ->get();

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

                $totalXFilas = 0;
                $cantidadXFilas = 0;

                foreach ($arrayDetalle as $dato){

                    $totalXFilas = $totalXFilas + $dato->precio;
                    $cantidadXFilas = $cantidadXFilas + $dato->cantidad_fija;

                    $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));
                    $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
                }

            $infoFila->totalxfilas = '$' . number_format((float)$totalXFilas, 2, '.', ',');
            $infoFila->cantidadxfila = $cantidadXFilas;


            $resultsBloque[$index]->detallefila = $arrayDetalle;
            $index++;
        }

        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Nueva Herramienta');

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

            <tr>";


            foreach ($detaFila->detallefila as $dato) {
                $tabla .= "<tr>
                <td>$dato->fechaVencFormat</td>
                <td>$dato->nombre</td>
                <td>$dato->lote</td>
                <td>$dato->cantidad_fija</td>
                <td>$dato->precioFormat</td>
            <tr>";
            }

            $tabla .= "<tr>
                <td colspan='3'>Total</td>
                <td>$detaFila->cantidadxfila</td>
                <td>$detaFila->totalxfilas</td>
            <tr>";

            $tabla .= "</tbody></table>";
        }



        $stylesheet = file_get_contents('css/cssregistro.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        $mpdf->WriteHTML($tabla,2);

        $mpdf->Output();

    }


}
