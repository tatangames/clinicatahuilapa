<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Tipo_Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoDocumentoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoTipoDocumento(){

        return view('backend.admin.configuracion.tipodocumento.vistatipodocumento');
    }

    public function tablaNuevoTipoDocumento(){

        $arrayTipoDocumento = Tipo_Documento::orderBy('nombre')->get();

        return view('backend.admin.configuracion.tipodocumento.tablatipodocumento', compact('arrayTipoDocumento'));
    }


    public function registroNuevoTipoDocumento(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        $registro = new Tipo_Documento();
        $registro->nombre = $request->nombre;

        if($registro->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionNuevoTipoDocumento(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoTipoDocumento = Tipo_Documento::where('id', $request->id)->first()){


            return ['success' => 1, 'info' => $infoTipoDocumento];
        }else{
            return ['success' => 2];
        }
    }


    public function editarNuevoTipoDocumento(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Tipo_Documento::where('id', $request->id)->update([
            'nombre' => $request->nombre,
        ]);

        return ['success' => 1];
    }

}
