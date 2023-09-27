<?php

namespace App\Http\Controllers\Backend\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Motivo;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsignacionesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexAsignaciones(){

        $arrayRazonUso = Motivo::orderBy('nombre')->get();

        return view('backend.admin.asignaciones.nuevo.vistanuevaasignacion', compact('arrayRazonUso'));
    }


    public function buscadorPaciente(Request $request){

        $queryData = $request->get('query');

        $data = Paciente::
            where(function ($query) use ($queryData) {
                $query->where('nombres', 'like', "%{$queryData}%")
                    ->orWhere('apellidos', 'like', "%{$queryData}%")
                    ->orWhere('num_documento', 'like', "%{$queryData}%");
            })
            ->orderBy('id', 'ASC')
            ->get();


        $output = '<ul class="dropdown-menu" style="display:block; position:relative;">';
        $tiene = true;
        foreach($data as $row){

            // si solo hay 1 fila, No mostrara el hr, salto de linea
            if(count($data) == 1){
                if(!empty($row)){
                    $tiene = false;
                    $output .= '
             <li onclick="modificarValor(this)" id="'.$row->id.'" ><a href="#" style="margin-left: 3px; font-size: 15px; font-weight: bold; color: black !important;">Exp#'.$row->id . '&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;' .$row->nombres . ' - ' .$row->num_documento .'</a></li>
            ';
                }
            }

            else{
                if(!empty($row)){
                    $tiene = false;
                    $output .= '
             <li onclick="modificarValor(this)" id="'.$row->id.'" style="font-weight: normal; font-size: 16px; color: black !important;"><a href="#" style="margin-left: 3px; font-weight: bold; font-size: 15px; color: black !important;">Exp#'.$row->id . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .$row->nombres . ' - ' .$row->num_documento .'</a></li>
               <hr>
            ';
                }
            }
        }
        $output .= '</ul>';
        if($tiene){
            $output = '';
        }
        echo $output;
    }



    function nuevoRegistro(Request $request){

        $regla = array(
            'idpaciente' => 'required',
            'idrazon' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}








    }






}
