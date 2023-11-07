<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Diagnosticos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosticoController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoTipoDiagnostico(){

        return view('backend.admin.configuracion.diagnostico.vistadiagnostico');
    }

    public function tablaNuevoTipoDiagnostico(){

        $arrayDiagnosticos = Diagnosticos::orderBy('nombre')->get();

        return view('backend.admin.configuracion.diagnostico.tabladiagnostico', compact('arrayDiagnosticos'));
    }


    public function registroNuevoTipoDiagnostico(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $registro = new Diagnosticos();
        $registro->nombre = $request->nombre;
        $registro->descripcion = $request->descripcion;

        if($registro->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionNuevoTipoDiagnostico(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoTipoDiagnostico = Diagnosticos::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $infoTipoDiagnostico];
        }else{
            return ['success' => 2];
        }
    }


    public function editarNuevoTipoDiagnostico(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Diagnosticos::where('id', $request->id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return ['success' => 1];
    }


}
