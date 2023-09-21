<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estado_Civil;
use Illuminate\Support\Facades\Validator;

class EstadoCivilController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    // retorna vista de Estado Civil
    public function indexEstadoCivil(){
        return view('backend.admin.configuracion.estadocivil.vistaestadocivil');
    }

    // retorna tabla de Estado Civil
    public function tablaEstadoCivil(){
        $lista = Estado_Civil::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.estadocivil.tablaestadocivil', compact('lista'));
    }

    // registrar un nuevo Estado Civil
    public function nuevoEstadoCivil(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new Estado_Civil();
        $dato->nombre = $request->nombre;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    // obtener información de un Estado Civil
    public function infoEstadoCivil(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Estado_Civil::where('id', $request->id)->first()){

            return ['success' => 1, 'lista' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    // editar un Estado Civil
    public function editarEstadoCivil(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Estado_Civil::where('id', $request->id)->first()){

            Estado_Civil::where('id', $request->id)->update([
                'nombre' => $request->nombre
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }
}
