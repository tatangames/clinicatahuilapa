<?php

namespace App\Http\Controllers\Controles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ControlController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexRedireccionamiento(){

        $user = Auth::user();

        // ADMINISTRADOR
        if($user->hasRole('admin')){
            $ruta = 'admin.roles.index';
        }

        // Inventario
        else  if($user->hasRole('archivo')){
            $ruta = 'admin.expediente.nuevo';
        }

        else  if($user->hasRole('auditora')){
            $ruta = 'admin.entrada.reporte.index';
        }

        else{
            $ruta = 'no.permisos.index';
        }


        return view('backend.index', compact( 'ruta', 'user'));
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }
}
