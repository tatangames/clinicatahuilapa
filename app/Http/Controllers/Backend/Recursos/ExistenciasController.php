<?php

namespace App\Http\Controllers\Backend\Recursos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExistenciasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function vistaExistencias(){

        return view('backend.admin.existencias.vistaexistencia');
    }

    public function tablaExistencias($cantidad){


        return view('backend.admin.existencia.tablaexistencia');
    }
}
