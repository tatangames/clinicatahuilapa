<?php

namespace App\Http\Controllers\Backend\Asignaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AsignacionesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexAsignaciones(){


        return view('backend.admin.asignaciones.nuevo.vistanuevaasignacion');
    }
}
