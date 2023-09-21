<?php

namespace App\Http\Controllers\Backend\Expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpedientesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoExpediente(){


        return view('backend.admin.expedientes.vistanuevoexpediente');
    }
}
