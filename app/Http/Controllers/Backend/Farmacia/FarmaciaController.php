<?php

namespace App\Http\Controllers\Backend\Farmacia;

use App\Http\Controllers\Controller;
use App\Models\ContenidoFarmaceutica;
use App\Models\Linea;
use App\Models\SubLinea;
use Illuminate\Http\Request;

class FarmaciaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }



    public function indexRegistroArticulo(){

        $arrayLinea = Linea::orderBy('nombre')->get();
        $arraySubLinea = SubLinea::orderBy('nombre')->get();

        // array envase
        $arrayEnvase = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 1)->orderBy('nombre')->get();

        // array forma farmaceutica
        $arrayFormaFarmaceutica = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 2)->orderBy('nombre')->get();


        // array concentracion
        $arrayConcentracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 3)->orderBy('nombre')->get();


        // array contenido
        $arrayContenido = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 4)->orderBy('nombre')->get();


        // array forma administracion
        $arrayAdministracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 5)->orderBy('nombre')->get();


        return view('backend.admin.farmacia.registro.vistaregistro', compact('arrayLinea',
            'arraySubLinea', 'arrayEnvase', 'arrayFormaFarmaceutica', 'arrayConcentracion',
            'arrayContenido', 'arrayAdministracion'));
    }


    public function registrarArticulo(Request $request){


        return ['success' => 1];
    }




}
