<?php

namespace App\Http\Controllers\Backend\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Consulta_Paciente;
use App\Models\Estado_Civil;
use App\Models\Medico;
use App\Models\Motivo;
use App\Models\Paciente;
use App\Models\Profesion;
use App\Models\SalasEspera;
use App\Models\Tipo_Documento;
use App\Models\Tipo_Paciente;
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
        /*if($infoConsultorio = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 1)
            ->first()){

            $infoPaciente = Paciente::where('id', $infoConsultorio->paciente_id)->first();

            $salaConsulPaciente = "Paciente: " . $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $botonConsultoria = 1;
        }else{
            $salaConsulPaciente = "Paciente: (No asignado)";
            $botonConsultoria = 0;
        }*/

        // PUEDE HABER 1 O MAS PACIENTES ASIGNADOS A SALA
        $countConsultoria = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 1)
            ->count();

        // PUEDE HABER 1 O MAS PACIENTES ASIGNADOS A SALA
        $countEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 1)
            ->count();

        if($countConsultoria>0){
            $salaConsulPaciente = "Pacientes asignados: " . $countConsultoria;
            $botonConsultoria = 1;
        }
        else{
            $salaConsulPaciente = "Paciente en sala: 0";
            $botonConsultoria = 0;
        }



        // OBTENER AL PACIENTE QUE ESTA DENTRO DE LA SALA ENFERMERIA

        if($countEnfermeria>0){
            $salaEnfermePaciente = "Pacientes asignados: " . $countEnfermeria;
            $botonEnfermeria = 1;
        }
        else{
            $salaEnfermePaciente = "Paciente en sala: 0";
            $botonEnfermeria = 0;
        }

        $arrayPaciente = [
            "salaConsultorioPaciente" => $salaConsulPaciente,
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


        $output = '<ul class="dropdown-menu" style="display:block; position:relative; overflow: auto; max-height: 300px; width: 550px">';
        $tiene = true;
        foreach($data as $row){

            // si solo hay 1 fila, No mostrara el hr, salto de linea
            if(count($data) == 1){
                if(!empty($row)){
                    $tiene = false;
                    $output .= '
             <li class="cursor-pointer" onclick="modificarValor(this)" id="'.$row->id.'" ><a href="#" style="margin-left: 3px; font-size: 15px; font-weight: bold; color: black !important;">Exp#'.$row->id . '&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;' .$row->nombres . ' ' .$row->apellidos . ' - ' .$row->num_documento .'</a></li>
            ';
                }
            }

            else{
                if(!empty($row)){
                    $tiene = false;
                    $output .= '
             <li class="cursor-pointer" onclick="modificarValor(this)" id="'.$row->id.'" style="font-weight: normal; font-size: 16px; color: black !important;"><a href="#" style="margin-left: 3px; font-weight: bold; font-size: 15px; color: black !important;">Exp#'.$row->id . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .$row->nombres . ' ' .$row->apellidos . ' - ' .$row->num_documento .'</a></li>
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


        // ESTA EN SALA DE ESPERA
        if($infoPaciente = Consulta_Paciente::where('paciente_id', $request->idpaciente)
            ->where('estado_paciente', 0)->first()){

            $infoSala = SalasEspera::where('id', $infoPaciente->salaespera_id)->first();

            $msj = "El Paciente ya se encuentra en sala de espera: " . $infoSala->nombre;

            return ['success' => 1, 'mensaje' => $msj];
        }

        // ESTA DENTRO DE LA SALA
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
            ->orderBy('fecha_hora', 'ASC')
            ->get();


        foreach ($arrayTablaEnfermeria as $dato){

            $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
            $infoRazonUso = Motivo::where('id', $dato->motivo_id)->first();

            $dato->nombrepaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $dato->razonUso = $infoRazonUso->nombre;

            $dato->horaFormat = date("d-m-Y h:i A", strtotime($dato->fecha_hora));
        }


        return view('backend.admin.asignaciones.tablamodalenfermeria.vistamodaltablaenfermeria', compact('arrayTablaEnfermeria'));
    }



    // muestra la tabla de pacientes en espera para Consultoria
    public function tablaModalConsultoria(){

        // lista de pacientes en modo espera para tabla enfermeria
        $arrayTablaConsultoria = Consulta_Paciente::where('estado_paciente', 0)
            ->where('salaespera_id', 1) // CONSULTORIA
            ->orderBy('fecha_hora', 'ASC')
            ->get();


        foreach ($arrayTablaConsultoria as $dato){

            $infoPaciente = Paciente::where('id', $dato->paciente_id)->first();
            $infoRazonUso = Motivo::where('id', $dato->motivo_id)->first();

            $dato->nombrepaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;
            $dato->razonUso = $infoRazonUso->nombre;

            $dato->horaFormat = date("d-m-Y h:i A", strtotime($dato->fecha_hora));
        }

        return view('backend.admin.asignaciones.tablamodalconsultoria.vistamodaltablaconsultoria', compact('arrayTablaConsultoria'));
    }

    // informacion de un paciente
    public function informacionPaciente(Request $request){

        $regla = array(
            'id' => 'required', // id consulta_paciente
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($info = Consulta_Paciente::where('id', $request->id)->first()){

            $arraySala = SalasEspera::orderBy('nombre', 'ASC')->get();
            $arrayRazonUso = Motivo::orderBy('nombre', 'ASC')->get();

            return ['success' => 1, 'info' => $info,
                'arraysala' => $arraySala,
                'arrayrazonuso' => $arrayRazonUso];
        }else{
            return ['success' => 2];
        }
    }


    public function guardarInformacionEditadaPaciente(Request $request){

        $regla = array(
            'idconsulta' => 'required', // id consulta_paciente
            'idsala' => 'required',
            'idrazonuso' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Consulta_Paciente::where('id', $request->idconsulta)->update([
            'salaespera_id' => $request->idsala,
            'motivo_id' => $request->idrazonuso,
        ]);


        return ['success' => 1];
    }


    // finalizar la consulta medica
    public function finalizarConsultaPaciente(Request $request){

        $regla = array(
            'idconsulta' => 'required', // id consulta_paciente
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Consulta_Paciente::where('id', $request->idconsulta)->update([
            'estado_paciente' => 2, // consulta finalizada
        ]);


        return ['success' => 1];
    }

    // ingresar paciente a la sala
    public function ingresarPacienteALaSala(Request $request){

        $regla = array(
            'idconsulta' => 'required', // id consulta_paciente
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        //$infoConsulta = Consulta_Paciente::where('id', $request->idconsulta)->first();

       /* if(Consulta_Paciente::where('estado_paciente', 1)
            ->where('salaespera_id', $infoConsulta->salaespera_id)
            ->first()){

            // NO PUEDE INGRESAR A LA SALA PORQUE ESTA OCUPADA
            return ['success' => 1];
        }*/

        $fechaCarbon = Carbon::parse(Carbon::now());

        Consulta_Paciente::where('id', $request->idconsulta)->update([
            'estado_paciente' => 1, // paciente dentro a la Sala
            'hora_dentrosala' => $fechaCarbon
        ]);

        $infoPaciente = Consulta_Paciente::where('id', $request->idconsulta)->first();
        $infoSala = SalasEspera::where('id', $infoPaciente->salaespera_id)->first();

        return ['success' => 1, 'nombresala' => $infoSala->nombre];
    }






    public function personasDentroSala(Request $request){

        // 1- CUNSULTORIA
        // 2- ENFERMERIA

        $regla = array(
            'tipo' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $listado = Consulta_Paciente::where('estado_paciente', 1)
            ->where('salaespera_id', $request->tipo)
            ->get();

        foreach ($listado as $data){
            $infoPaciente = Paciente::where('id', $data->paciente_id)->first();

            $data->nombre = $infoPaciente->nombres;
        }

        return ['success' => 1, "listado" => $listado];
    }




    public function vistaEditarPaciente($idpaciente){

        $infoPa = Paciente::where('id', $idpaciente)->first();

        $arrayEstadoCivil = Estado_Civil::orderBy('nombre')->get();
        $arrayTipoPaciente = Tipo_Paciente::orderBy('nombre')->get();
        $arrayTipoDocumento = Tipo_Documento::orderBy('nombre')->get();
        $arrayProfesion = Profesion::orderBy('nombre')->get();

        if($infoPa->sexo == 'M'){
            $tiposexo = 1;
        }else{
            $tiposexo = 2;
        }

        return view('backend.admin.expedientes.buscar.editarpaciente.vistaeditarpaciente', compact('infoPa', 'arrayEstadoCivil',
            'arrayTipoPaciente', 'tiposexo', 'arrayTipoDocumento', 'arrayProfesion', 'idpaciente'));
    }



    public function informacionPacienteDentroDeSala(Request $request){


        $regla = array(
            'idconsulta' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        // buscar directamente con id consulta
        if($infoConsulta = Consulta_Paciente::where('id', $request->idconsulta)->first()){

            $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();
            $nombrePaciente = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

            if($infoPaciente->foto == null){
                $hayfoto = 0;
            }else{
                $hayfoto = 1;
            }

            $horaEntroEsperar = date("h:i A", strtotime($infoConsulta->fecha_hora));
            $horaEntroSala = date("h:i A", strtotime($infoConsulta->hora_dentrosala));

            $arrayrazonuso = Motivo::orderBy('nombre')->get();

            // CONTEO DIRECTO
            $numeroConsulta = $infoPaciente->numero_expediente;

            return ['success' => 1, 'infoconsulta' => $infoConsulta,
                'infopaciente' => $infoPaciente, 'hayfoto' => $hayfoto,
                'horaentro' => $horaEntroSala, 'entroespera' => $horaEntroEsperar,
                'arrayrazonuso' => $arrayrazonuso, 'numeroConsulta' => $numeroConsulta,
                'nombrepaciente' => $nombrePaciente];
        }
        else{
            return ['success' => 2];
        }
    }


    public function actualizarRazonUsoPaciente(Request $request){

        $regla = array(
            'idconsulta' => 'required',
            'idrazonuso' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Consulta_Paciente::where('id', $request->idconsulta)->update([
            'motivo_id' => $request->idrazonuso,
        ]);


        return ['success' => 1];
    }


    // liberar sala del paciente
    public function liberarSalaPaciente(Request $request){

        $regla = array(
            'idconsulta' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Consulta_Paciente::where('id', $request->idconsulta)->update([
            'estado_paciente' => 2, // liberado
        ]);


        return ['success' => 1];
    }


    public function informacionPacienteDentroSala(Request $request){

        $regla = array(
            'idconsulta' => 'required', // id consulta_paciente
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($info = Consulta_Paciente::where('id', $request->idconsulta)->first()){

            $arraySala = SalasEspera::orderBy('nombre', 'ASC')
            ->get();

            $infoSala = SalasEspera::where('id', $info->salaespera_id)->first();


            $arrayRazonUso = Motivo::orderBy('nombre', 'ASC')->get();

            return ['success' => 1, 'info' => $info,
                'arraysala' => $arraySala,
                'arrayrazonuso' => $arrayRazonUso, 'salactual' => $infoSala->nombre];
        }else{
            return ['success' => 2];
        }
    }



    public function reseteoTrasladoPacienteNuevaSala(Request $request){


        $regla = array(
            'idconsulta' => 'required', // id consulta_paciente
            'nuevasala' => 'required',
            'nuevomotivo' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if(Consulta_Paciente::where('id', $request->idconsulta)->first()){

            $fechaCarbon = Carbon::parse(Carbon::now());

            Consulta_Paciente::where('id', $request->idconsulta)->update([
                'salaespera_id' => $request->nuevasala,
                'motivo_id' => $request->nuevomotivo,
                'fecha_hora' => $fechaCarbon, // cambia fecha a cuando esta en sala de espera
                'estado_paciente' => 0, // vuelve a sala de espera
                'hora_dentrosala' => null,
            ]);


            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    // recargando vista por cronometro
    public function recargandoVistaCronometro(Request $request){

        // cuantos pacientes hay en Espera para cada sala

        $conteoConsultorio = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 0)
            ->count();

        $conteoEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 0)
            ->count();


        // PUEDE HABER 1 O MAS PACIENTES ASIGNADOS A SALA
        $countConsultoria = Consulta_Paciente::where('salaespera_id', 1)
            ->where('estado_paciente', 1)
            ->count();

        // PUEDE HABER 1 O MAS PACIENTES ASIGNADOS A SALA
        $countEnfermeria = Consulta_Paciente::where('salaespera_id', 2)
            ->where('estado_paciente', 1)
            ->count();

        if($countConsultoria>0){
            $salaConsulPaciente = "Pacientes asignados: " . $countConsultoria;
            $botonConsultoria = 1;
        }
        else{
            $salaConsulPaciente = "Paciente en sala: 0";
            $botonConsultoria = 0;
        }

        // OBTENER AL PACIENTE QUE ESTA DENTRO DE LA SALA ENFERMERIA

        if($countEnfermeria>0){
            $salaEnfermePaciente = "Pacientes asignados: " . $countEnfermeria;
            $botonEnfermeria = 1;
        }
        else{
            $salaEnfermePaciente = "Paciente en sala: 0";
            $botonEnfermeria = 0;
        }

        $arrayPaciente = [
            "salaConsultorioPaciente" => $salaConsulPaciente,
            "salaEnfermeriaPaciente" => $salaEnfermePaciente,
            "botonOpcionConsultoria" => $botonConsultoria,
            "botonOpcionEnfermeria" => $botonEnfermeria
        ];

        return ['success' => 1, 'arraypaciente' => $arrayPaciente,
            'conteoConsultorio' => $conteoConsultorio, 'conteoEnfermeria' => $conteoEnfermeria,];
    }




}
