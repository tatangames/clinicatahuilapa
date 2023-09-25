<?php

namespace App\Http\Controllers\backend\configuracion;

use App\Http\Controllers\Controller;
use App\Models\Motivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MotivoConsultaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // retorna vista de Estado Civil
    public function indexMotivoConsulta(){
        return view('backend.admin.configuracion.motivoconsulta.vistamotivoconsulta');
    }

    // retorna tabla de Estado Civil
    public function tablaMotivoConsulta(){
        $lista = Motivo::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.motivoconsulta.tablamotivoconsulta', compact('lista'));
    }

    // registrar un nuevo Estado Civil
    public function nuevoMotivoConsulta(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new Motivo();
        $dato->nombre = $request->nombre;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    // obtener información de un Estado Civil
    public function infoMotivoConsulta(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Motivo::where('id', $request->id)->first()){

            return ['success' => 1, 'lista' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    // editar un Estado Civil
    public function editarMotivoConsulta(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Motivo::where('id', $request->id)->first()){

            Motivo::where('id', $request->id)->update([
                'nombre' => $request->nombre
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }
}
