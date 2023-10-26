<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Linea;
use App\Models\SubLinea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LineasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    // *********** LINEAS ********

    public function indexVistaLinea(){

        return view('backend.admin.configuracion.lineas.linea.vistalinea');
    }

    public function tablaVistaLinea(){

        $arrayLineas = Linea::orderBy('nombre')->get();

        return view('backend.admin.configuracion.lineas.linea.tablalinea', compact('arrayLineas'));
    }


    public function registroNuevaLinea(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $registro = new Linea();
        $registro->nombre = $request->nombre;

        if($registro->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionLinea(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoLinea = Linea::where('id', $request->id)->first()){


            return ['success' => 1, 'info' => $infoLinea];
        }else{
            return ['success' => 2];
        }
    }


    public function editarLinea(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Linea::where('id', $request->id)->update([
            'nombre' => $request->nombre,
        ]);

        return ['success' => 1];
    }











    // *********** SUB LINEAS ********

    public function indexVistaSubLinea(){

        return view('backend.admin.configuracion.lineas.sublineas.vistasublinea');
    }

    public function tablaVistaSubLinea(){

        $arraySubLineas = SubLinea::orderBy('nombre')->get();

        return view('backend.admin.configuracion.lineas.sublineas.tablasublinea', compact('arraySubLineas'));
    }


    public function registroSubNuevaLinea(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $registro = new SubLinea();
        $registro->nombre = $request->nombre;

        if($registro->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionSubLinea(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoSubLinea = SubLinea::where('id', $request->id)->first()){


            return ['success' => 1, 'info' => $infoSubLinea];
        }else{
            return ['success' => 2];
        }
    }


    public function editarSubLinea(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        SubLinea::where('id', $request->id)->update([
            'nombre' => $request->nombre,
        ]);

        return ['success' => 1];
    }



}
