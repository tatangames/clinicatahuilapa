<?php

namespace App\Http\Controllers\backend\expedientes;

use App\Http\Controllers\Controller;
use App\Models\Antecedentes;
use App\Models\AntecedentesMedicos;
use App\Models\Antropometria;
use App\Models\Consulta_Paciente;
use App\Models\Diagnosticos;
use App\Models\Paciente;
use App\Models\PacienteAntecedentes;
use App\Models\Recetas;
use App\Models\TipeoSanguineo;
use App\Models\Usuario;
use App\Models\ViaReceta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentoRecetaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexDocumentosRecetas($idpaciente){

        $infoPaciente = Paciente::where('id', $idpaciente)->first();

        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $miFecha = date("d-m-Y", strtotime($infoPaciente->fecha_nacimiento));

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos . " (" . $edad . " Años)";

        $totalConsulta = Consulta_Paciente::where('paciente_id', $infoPaciente)->count();



        return view('backend.admin.expedientes.buscar.docurecetas.vistadocumentorecetas', compact('idpaciente',
        'edad', 'miFecha', 'nombreCompleto', 'totalConsulta', 'infoPaciente'));
    }



    public function tablaAntecedentesPorPaciente($idpaciente){


        $b1_antecedentes = null;
        // buscar si paciente tiene antecedentes
        if($infoAntecedente = Antecedentes::where('paciente_id', $idpaciente)->first()){
            $b1_antecedentes = $infoAntecedente;
        }


        // ARRAY TIPEO SANGUINEO
        $b1_arrayTipeoSanguineo = TipeoSanguineo::orderBy('nombre', 'ASC')->get();



        // ARRAY DE ANTECEDENTES MEDICOS
        $b1_arrayAntecedentesMedico = AntecedentesMedicos::where('tipo_id', 1)
            ->orderBy('nombre', 'ASC')
            ->get();

        // ARRAY COMPLICACIONES AGUDAS
        $b1_arrayComplicacionAguda = AntecedentesMedicos::where('tipo_id', 2)
            ->orderBy('nombre', 'ASC')
            ->get();


        // ARRAY ENFERMEDADES CRONICAS
        $b1_arrayEnfermedadCronicas = AntecedentesMedicos::where('tipo_id', 3)
            ->orderBy('nombre', 'ASC')
            ->get();


        // ARRAY ANTECEDENTES CRONICOS
        $b1_arrayAntecedenteCronicos = AntecedentesMedicos::where('tipo_id', 4)
            ->orderBy('nombre', 'ASC')
            ->get();


        // ARRAY DE ID DEL PACIENTE SEGUN TIPO DE ANTECEDENTES
        $b1_arrayIdPacienteAntecedente = PacienteAntecedentes::where('paciente_id', $idpaciente)->get();

        return view('backend.admin.expedientes.buscar.docurecetas.tablas.tablaantecedentespaciente', compact('b1_antecedentes',
            'b1_arrayTipeoSanguineo',
            'b1_arrayAntecedentesMedico', 'b1_arrayIdPacienteAntecedente', 'b1_arrayComplicacionAguda',
            'b1_arrayEnfermedadCronicas', 'b1_arrayAntecedenteCronicos'));
    }


    public function tablaAntropometriaPorPaciente($idpaciente){

        // de todas las consultas obtener donde este el id paciente

        $listaID = DB::table('antropometria AS an')
            ->join('consulta_paciente AS con', 'an.consulta_id', '=', 'con.id')
            ->select('an.id', 'con.paciente_id')
            ->where('con.paciente_id', $idpaciente)
            ->get();

        $pilaIdAntro = array();

        foreach ($listaID as $info){
            array_push($pilaIdAntro, $info->id);
        }

        $bloqueAntropSv = Antropometria::whereIn('id', $pilaIdAntro)
            ->orderBy('fecha', 'DESC')
            ->get();

        foreach ($bloqueAntropSv as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
            $dato->horaFormat = date("h:i A", strtotime($dato->hora));

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();

            $dato->nomusuario = $infoUsuario->nombre;
        }

        return view('backend.admin.expedientes.buscar.docurecetas.tablas.tablaantropometriapaciente', compact('bloqueAntropSv'));
    }


    public function tablaRecetasPorPaciente($idpaciente){

        $arrayRecetas = Recetas::where('paciente_id', $idpaciente)
            ->orderBy('fecha')
            ->get();

        foreach ($arrayRecetas as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
            $dato->fechaProFormat = date("d-m-Y", strtotime($dato->proxima_cita));

            $infoVia = ViaReceta::where('id', $dato->via_id)->first();

            $dato->nombreVia = $infoVia->nombre;

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();
            $dato->nombreusuario = $infoUsuario->nombre;
        }

        return view('backend.admin.expedientes.buscar.docurecetas.tablas.tablarecetapaciente', compact('arrayRecetas'));
    }



    public function tablaCuadroClinicoPorPaciente($idpaciente){

        $bloqueCuadroClinico= DB::table('cuadro_clinico AS cl')
            ->join('consulta_paciente AS con', 'con.id', '=', 'cl.consulta_id')
            ->select('con.fecha_hora', 'cl.diagnostico_id', 'cl.descripcion',
                'cl.diagnostico_id', 'cl.id', 'con.paciente_id', 'con.id AS idconsulta')
            ->where('con.paciente_id', $idpaciente)
            ->orderBy('con.fecha_hora', 'ASC')
            ->get();

        foreach ($bloqueCuadroClinico as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha_hora));

            $infoDiagnostico = Diagnosticos::where('id', $dato->diagnostico_id)->first();

            $dato->nombreDiagnostico = $infoDiagnostico->nombre;
        }

        return view('backend.admin.expedientes.buscar.docurecetas.tablas.tablacuadroclinico', compact('bloqueCuadroClinico'));
    }







}
