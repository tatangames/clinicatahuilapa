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
            $ruta = 'admin.asignaciones.vista';
        }

        // Enfermeria
        else  if($user->hasRole('enfermeria')){
            $ruta = 'admin.asignaciones.vista';
        }

        // Doctora
        else  if($user->hasRole('doctora')){
            $ruta = 'admin.asignaciones.vista';
        }

        // Farmacia
        else  if($user->hasRole('farmacia')){
            $ruta = 'admin.farmacia.registrar.articulo';
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
