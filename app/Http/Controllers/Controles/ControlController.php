<?php

namespace App\Http\Controllers\Controles;

use App\Http\Controllers\Controller;
use App\Models\Extras;
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

        $infoRutaWeb = Extras::where('id', 1)->first();

        return view('backend.index', compact( 'ruta', 'user', 'infoRutaWeb'));
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }
}
