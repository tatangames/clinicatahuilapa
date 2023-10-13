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



        // OBTENER AL PACIENTE QUE ESTA DENTRO DE LA SALA CONSULTORIA

        if($infoConsultorio = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 1)
            ->first()){

            $infoPaciente = Paciente::where('id', $infoConsultorio->paciente_id)->first();

            $salaConsulPaciente = "Paciente: " . $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $botonConsultoria = 1;
        }else{
            $salaConsulPaciente = "Paciente: (No asignado)";
            $botonConsultoria = 0;
        }

        // OBTENER AL PACIENTE QUE ESTA DENTRO DE LA SALA ENFERMERIA

        if($infoEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 1)
            ->first()){

            $infoPaciente = Paciente::where('id', $infoEnfermeria->paciente_id)->first();

            $salaEnfermePaciente = "Paciente: " . $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $botonEnfermeria = 1;
        }else{
            $salaEnfermePaciente = "Paciente: (No asignado)";
            $botonEnfermeria = 0;
        }

        $arrayPaciente = [
            "salaConsultaPaciente" => $salaConsulPaciente,
            "salaEnfermeriaPaciente" => $salaEnfermePaciente,
            "botonOpcionConsultoria" => $botonConsultoria,
            "botonOpcionEnfermeria" => $botonEnfermeria
        ];

        return view('backend.admin.asignaciones.nuevo.vistanuevaasignacion', compact('arrayRazonUso',
            'conteoConsultorio', 'conteoEnfermeria', 'arraySalaEspera', 'arrayPaciente'));
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


        if($infoPaciente = Consulta_Paciente::where('paciente_id', $request->idpaciente)
            ->where('estado_paciente', 0)->first()){

            $infoSala = SalasEspera::where('id', $infoPaciente->salaespera_id)->first();

            $msj = "El Paciente ya se encuentra en sala de espera: " . $infoSala->nombre;

            return ['success' => 1, 'mensaje' => $msj];
        }

        if($infoPaciente = Consulta_Paciente::where('paciente_id', $request->idpaciente)
            ->where('estado_paciente', 1)->first()){

            $infoSala = SalasEspera::where('id', $infoPaciente->salaespera_id)->first();

            $msj = "El Paciente ya se encuentra asignado a la sala: " . $infoSala->nombre;

            return ['success' => 1, 'mensaje' => $msj];
        }



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
            return ['success' => 2];


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


    public function recargarVistaPorCronometro(Request $request){

        /*$hayPacientes = 0;
        if(Consulta_Paciente::where('estado_paciente', 1)->count()){
            $hayPacientes = 1;
        }

        return ['success' => 1, 'haypacientes' => $hayPacientes];*/
    }


    // muestra la tabla de pacientes en espera para Enfermeria
    public function tablaModalEnfermeria(){

        // lista de pacientes en modo espera para tabla enfermeria
        $arrayTablaEnfermeria = Consulta_Paciente::where('estado_paciente', 0)
            ->where('salaespera_id', 2) // ENFERMERIA
            ->orderBy('id', 'ASC')
            ->get();


        foreach ($arrayTablaEnfermeria as $dato){

            $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
            $infoRazonUso = Motivo::where('id', $dato->motivo_id)->first();

            $dato->nombrepaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $dato->razonUso = $infoRazonUso->nombre;

            $dato->horaFormat = date("h:i A", strtotime($dato->fecha_hora));
        }


        return view('backend.admin.asignaciones.tablamodalenfermeria.vistamodaltablaenfermeria', compact('arrayTablaEnfermeria'));
    }



    // muestra la tabla de pacientes en espera para Consultoria
    public function tablaModalConsultoria(){

        // lista de pacientes en modo espera para tabla enfermeria
        $arrayTablaConsultoria = Consulta_Paciente::where('estado_paciente', 0)
            ->where('salaespera_id', 1) // CONSULTORIA
            ->orderBy('id', 'ASC')
            ->get();


        foreach ($arrayTablaConsultoria as $dato){

            $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
            $infoRazonUso = Motivo::where('id', $dato->motivo_id)->first();

            $dato->nombrepaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $dato->razonUso = $infoRazonUso->nombre;

            $dato->horaFormat = date("h:i A", strtotime($dato->fecha_hora));
        }

        return view('backend.admin.asignaciones.tablamodalconsultoria.vistamodaltablaconsultoria', compact('arrayTablaConsultoria'));
    }



}
