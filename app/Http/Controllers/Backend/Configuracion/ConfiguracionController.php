<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoTipoPaciente(){

        return view('backend.admin.configuracion.tipopaciente.vistanuevotipopaciente');
    }
}
