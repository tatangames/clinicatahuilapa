<?php

namespace App\Http\Controllers\Backend\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Consulta_Paciente;
use App\Models\Medico;
use App\Models\Motivo;
use App\Models\Paciente;
use App\Models\SalasEspera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AsignacionesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexAsignaciones(){

        $arrayRazonUso = Motivo::orderBy('nombre')->get();
        $arraySalaEspera = SalasEspera::orderBy('nombre')->get();


        // cuantos pacientes hay en Espera para cada sala

        $conteoConsultorio = Consulta_Paciente::where('salaespera_id', 1)
                                ->where('estado_paciente', 0)
                                ->count();

        $conteoEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 0)
            ->count();


        // obtener el paciente que esta dentro de la sala Consultorio

        if($infoConsultorio = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 1)
            ->first()){

            $infoPaciente = Paciente::where('id', $infoConsultorio->paciente_id)->first();
            $infoMedico = Medico::where('id', $infoConsultorio->medico_id)->first();

            $salaConsulPaciente = "";
            $salaConsulMedico = "";
            $salaConsulAsignado = "";
        }else{
            $salaConsulPaciente = "Paciente: (No asignado)";
            $salaConsulMedico = "Médico: (No asignado)";
            $salaConsulAsignado = "Asignado Por: (No asignado)";
        }

        if($infoEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 1)
            ->first()){
            $salaEnfermePaciente = "";
            $salaEnfermeMedico = "";
            $salaEnfermeAsignado = "";
        }else{
            $salaEnfermePaciente = "Paciente: (No asignado)";
            $salaEnfermeMedico = "Médico: (No asignado)";
            $salaEnfermeAsignado = "Asignado Por: (No asignado)";
        }

        $array = [
            "foo" => "bar",
            "bar" => "foo",
        ];


        return view('backend.admin.asignaciones.nuevo.vistanuevaasignacion', compact('arrayRazonUso',
            'conteoConsultorio', 'conteoEnfermeria', 'arraySalaEspera'));
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
            'idsalaespera' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();

        try {
            $fechaCarbon = Carbon::parse(Carbon::now());

            $dato = new Consulta_Paciente();
            $dato->paciente_id = $request->idpaciente;
            $dato->motivo_id = $request->idrazon;
            $dato->fecha_hora = $fechaCarbon;
            $dato->estado_paciente = 0;
            $dato->estado_receta = 0;
            $dato->salaespera_id = $request->idsalaespera;
            $dato->save();

            DB::commit();
            return ['success' => 1];


        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    public function tablaPacientesEnEspera(){

        $arrayPacientes = Consulta_Paciente::where('estado_paciente', 1)
            ->orderBy('fecha_hora', 'ASC')
            ->get();

        foreach ($arrayPacientes as $dato){

            $fechaFormat = date("d-m-Y h:i A", strtotime($dato->fecha_hora));
            $dato->fechaFormat = $fechaFormat;

            $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
            $dato->nombrePaciente = $infoPaciente->nombres;
            $dato->apellidoPaciente = $infoPaciente->apellidos;
            $dato->idExpediente = $infoPaciente->id;
            $dato->numdocumento = $infoPaciente->num_documento;
        }

        return view('backend.admin.asignaciones.pacientes.tablapacientesenespera', compact('arrayPacientes'));
    }


    public function verificarPacientesEnEspera(Request $request){

        $hayPacientes = 0;
        if(Consulta_Paciente::where('estado_paciente', 1)->count()){
            $hayPacientes = 1;
        }

        return ['success' => 1, 'haypacientes' => $hayPacientes];
    }




}
