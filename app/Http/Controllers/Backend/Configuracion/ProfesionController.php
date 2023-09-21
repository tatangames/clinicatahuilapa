<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profesion;
use Illuminate\Support\Facades\Validator;

class ProfesionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // retorna vista de profesiones
    public function indexProfesion(){
        return view('backend.admin.configuracion.profesion.vistaprofesion');
    }

    // retorna tabla de profesiones
    public function tablaProfesion(){
        $lista = Profesion::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.profesion.tablaprofesion', compact('lista'));
    }

    // registrar una nueva profesion
    public function nuevaProfesion(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new Profesion();
        $dato->nombre = $request->nombre;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    // obtener información de una profesion
    public function infoProfesion(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Profesion::where('id', $request->id)->first()){

            return ['success' => 1, 'lista' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    // editar una Profesion
    public function editarProfesion(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Profesion::where('id', $request->id)->first()){

            Profesion::where('id', $request->id)->update([
                'nombre' => $request->nombre
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }
}
