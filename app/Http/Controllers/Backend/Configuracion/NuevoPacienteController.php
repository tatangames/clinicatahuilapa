<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Tipo_Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NuevoPacienteController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoTipoPaciente(){

        return view('backend.admin.configuracion.tipopaciente.vistanuevotipopaciente');
    }

    public function tablaNuevoTipoPaciente(){

        $arrayTipoPaciente = Tipo_Paciente::orderBy('nombre')->get();

        return view('backend.admin.configuracion.tipopaciente.tablanuevotipopaciente', compact('arrayTipoPaciente'));
    }


    public function registroNuevoTipoPaciente(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        $registro = new Tipo_Paciente();
        $registro->nombre = $request->nombre;

        if($registro->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionNuevoTipoPaciente(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoTipoPaciente = Tipo_Paciente::where('id', $request->id)->first()){


            return ['success' => 1, 'info' => $infoTipoPaciente];
        }else{
            return ['success' => 2];
        }
    }


    public function editarNuevoTipoPaciente(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Tipo_Paciente::where('id', $request->id)->update([
            'nombre' => $request->nombre,
        ]);

        return ['success' => 1];
    }


}
