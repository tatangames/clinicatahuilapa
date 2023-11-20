<?php

namespace App\Http\Controllers\backend\historial;

use App\Http\Controllers\Controller;
use App\Models\Diagnosticos;
use App\Models\EntradaMedicamento;
use App\Models\EntradaMedicamentoDetalle;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Paciente;
use App\Models\Proveedores;
use App\Models\RecetasDetalle;
use App\Models\TipoFactura;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistorialReportesController extends Controller
{


    public function __construct(){
        $this->middleware('auth');
    }


    public function indexHistorialEntradas(){

        $arrayFuente = FuenteFinanciamiento::orderBy('nombre')->get();

        return view('backend.admin.historial.entradas.vistahistorialentradas', compact('arrayFuente'));
    }


    public function tablaHistorialEntradas($idfuente, $desde, $hasta){

        $start = Carbon::parse($desde)->startOfDay();
        $end = Carbon::parse($hasta)->endOfDay();


            // SERAN TODAS LAS FUENTES DE FINANCIAMIENTO
        if($idfuente == '0'){

            $arrayEntradas = EntradaMedicamento::whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'DESC')
                ->get();

        }else{
            // X FUENTE

            $arrayEntradas = EntradaMedicamento::where('fuentefina_id', $idfuente)
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'DESC')
                ->get();
        }


        foreach ($arrayEntradas as $info){

            $info->fechaFormat = date("d-m-Y", strtotime($info->fecha));

            $infoProveedor = Proveedores::where('id', $info->proveedor_id)->first();
            $info->nomproveedor = $infoProveedor->nombre;

            $infoFuente = FuenteFinanciamiento::where('id', $info->fuentefina_id)->first();
            $info->nombrefuente = $infoFuente->nombre;

            $infoFactura = TipoFactura::where('id', $info->tipofactura_id)->first();
            $info->nombrefactura = $infoFactura->nombre;

        }


        return view('backend.admin.historial.entradas.tablahistorialentradas', compact('arrayEntradas'));
    }



    public function indexHistorialEntradasListado($identrada){

        $infoEntrada = EntradaMedicamento::where('id', $identrada)->first();

        $fechaentrada = date("d-m-Y", strtotime($infoEntrada->fecha));

        return view('backend.admin.historial.entradas.detalle.vistadetallehistorialentradas', compact('identrada', 'fechaentrada'));
    }


    public function tablaHistorialEntradasListado($identrada){


        $arrayDetalle = EntradaMedicamentoDetalle::where('entrada_medicamento_id', $identrada)
            ->orderBy('fecha_vencimiento', 'DESC')->get();

        foreach ($arrayDetalle as $dato){

            $dato->fechaVencFormat = date("d-m-Y", strtotime($dato->fecha_vencimiento));

            $infoMedi = FarmaciaArticulo::where('id', $dato->medicamento_id)->first();
            $dato->nombremedi = $infoMedi->nombre;

            $dato->precioFormat = '$' . number_format((float)$dato->precio, 2, '.', ',');
        }

        return view('backend.admin.historial.entradas.detalle.tabladetallehistorialentradas', compact('arrayDetalle'));
    }


    // **************** HISTORIAL SALIDAS RECETA *******


    public function indexHistorialSalidasReceta(){




        return view('backend.admin.historial.salidasreceta.vistahistorialsalidasreceta');
    }


    public function tablaHistorialSalidasReceta($idproceso, $desde, $hasta){

        if($idproceso == '1'){
            // ESTADO: PROCESADA


            $arrayRecetas = RecetasDetalle::where('estado', 2)
                ->orderBy('fecha', 'DESC')
                ->get();

            foreach ($arrayRecetas as $dato){

                $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));

                $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
                $dato->nombrepaci = $infoPaciente->nombres . ' ' . $infoPaciente->apellidos;

                $infoDiagn = Diagnosticos::where('id', $dato->diagnostico_id)->first();
                $dato->nombrediagn = $infoDiagn->nombre;
            }


            return view('backend.admin.historial.salidasreceta.tablahistorialsalidasreceta');
        }
        else{
            // DEFECTO ESTADO: DENEGADA


            return "denegado";

            $arrayRecetas = RecetasDetalle::where('estado', 3)
                ->orderBy('fecha', 'DESC')
                ->get();

            foreach ($arrayRecetas as $dato){

                $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));

                $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
                $dato->nombrepaci = $infoPaciente->nombres . ' ' . $infoPaciente->apellidos;

                $infoDiagn = Diagnosticos::where('id', $dato->diagnostico_id)->first();
                $dato->nombrediagn = $infoDiagn->nombre;
            }

            return view('backend.admin.historial.salidasreceta.tablahistorialsalidasrecetadenegada');
        }





    }














}
