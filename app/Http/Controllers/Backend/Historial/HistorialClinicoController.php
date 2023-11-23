<?php

namespace App\Http\Controllers\Backend\Historial;

use App\Http\Controllers\Controller;
use App\Models\Antecedentes;
use App\Models\AntecedentesMedicos;
use App\Models\Antropometria;
use App\Models\Consulta_Paciente;
use App\Models\CuadroClinico;
use App\Models\Diagnosticos;
use App\Models\FuenteFinanciamiento;
use App\Models\Paciente;
use App\Models\PacienteAntecedentes;
use App\Models\Recetas;
use App\Models\TipeoSanguineo;
use App\Models\Usuario;
use App\Models\ViaReceta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HistorialClinicoController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function indexVistaGeneralHistorial($idconsulta){


        $infoConsulta = Consulta_Paciente::where('id', $idconsulta)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $antecedentes = null;
        // buscar si paciente tiene antecedentes
        if($infoAntecedente = Antecedentes::where('paciente_id', $infoPaciente->id)->first()){
            $antecedentes = $infoAntecedente;
        }


        // ARRAY TIPEO SANGUINEO
        $arrayTipeoSanguineo = TipeoSanguineo::orderBy('nombre', 'ASC')->get();



        // ARRAY DE ANTECEDENTES MEDICOS
        $arrayAntecedentesMedico = AntecedentesMedicos::where('tipo_id', 1)
        ->orderBy('nombre', 'ASC')
            ->get();

        // ARRAY COMPLICACIONES AGUDAS
        $arrayComplicacionAguda = AntecedentesMedicos::where('tipo_id', 2)
        ->orderBy('nombre', 'ASC')
            ->get();


        // ARRAY ENFERMEDADES CRONICAS
        $arrayEnfermedadCronicas = AntecedentesMedicos::where('tipo_id', 3)
            ->orderBy('nombre', 'ASC')
            ->get();


        // ARRAY ANTECEDENTES CRONICOS
        $arrayAntecedenteCronicos = AntecedentesMedicos::where('tipo_id', 4)
            ->orderBy('nombre', 'ASC')
            ->get();


            // ARRAY DE ID DEL PACIENTE SEGUN TIPO DE ANTECEDENTES
        $arrayIdPacienteAntecedente = PacienteAntecedentes::where('paciente_id', $infoPaciente->id)->get();





        return view('backend.admin.historial.general.vistageneralhistorial', compact('infoPaciente',
            'nombreCompleto', 'antecedentes', 'arrayTipeoSanguineo',
            'arrayAntecedentesMedico', 'arrayIdPacienteAntecedente', 'arrayComplicacionAguda',
        'arrayEnfermedadCronicas', 'arrayAntecedenteCronicos', 'idconsulta',));
    }




    public function actualizarListadoPacienteAntecedente(Request $request){

        DB::beginTransaction();

        try {

            PacienteAntecedentes::where('paciente_id', $request->idpaciente)->delete();

            if($request->datocheckbox != null) {

                // Obtiene los datos enviados desde el formulario como una cadena JSON y luego decódificala
                $datosCheckboxes = json_decode($request->datocheckbox, true); // El segundo argumento convierte el resultado en un arreglo

                // Puedes recorrer $datosCheckboxes utilizando un foreach
                foreach ($datosCheckboxes as $datoCheckbox) {
                    // no queremos estado, ya que viene filtrado solo por Checkbox True
                    //$estado = $datoCheckbox['estado'];
                    $valorAdicional = $datoCheckbox['valorAdicional'];

                    $nuevoDato = new PacienteAntecedentes();
                    $nuevoDato->paciente_id = $request->idpaciente;
                    $nuevoDato->antecedente_medico_id = $valorAdicional;
                    $nuevoDato->save();
                }
            }

            if(Antecedentes::where('paciente_id', $request->idpaciente)->first()){

                // Actualizar

                Antecedentes::where('paciente_id', $request->idpaciente)->update([
                    'tipeo_sanguineo_id' => $request->selectSanguineo,
                    'antecedentes_familiares' => $request->textAntecedenteFami,
                    'alergias' => $request->textAlergia,
                    'medicamentos_actuales' => $request->textMedicamento,
                    'nota_antecedente_medico' => $request->notaAnteceMedico,
                    'nota_complicaciones_diabetes' => $request->notaCompliDiabete,
                    'nota_enfermedades_cronicas' => $request->notaEnfermCronica,
                    'nota_antecedentes_quirurgicos' => $request->notaAnteceQuirur,
                    'antecedentes_oftalmologicos' => $request->notaAnteceOftamo,
                    'antecedentes_deportivos' => $request->notaAnteceDeportivo,
                    'menarquia' => $request->datoMenarquia,
                    'ciclo_menstrual' => $request->datoCicloMenstr,
                    'pap' => $request->datoPap,
                    'mamografia' => $request->datoMamografia,
                    'otros' => $request->otrosDetalles,
                ]);


            }else{

                // Crear
                $crearAntece = new Antecedentes();
                $crearAntece->paciente_id = $request->idpaciente;
                $crearAntece->tipeo_sanguineo_id = $request->selectSanguineo;
                $crearAntece->antecedentes_familiares = $request->textAntecedenteFami;
                $crearAntece->alergias = $request->textAlergia;
                $crearAntece->medicamentos_actuales = $request->textMedicamento;
                $crearAntece->nota_antecedente_medico = $request->notaAnteceMedico;
                $crearAntece->nota_complicaciones_diabetes = $request->notaCompliDiabete;
                $crearAntece->nota_enfermedades_cronicas = $request->notaEnfermCronica;
                $crearAntece->nota_antecedentes_quirurgicos = $request->notaAnteceQuirur;
                $crearAntece->antecedentes_oftalmologicos = $request->notaAnteceOftamo;
                $crearAntece->antecedentes_deportivos = $request->notaAnteceDeportivo;
                $crearAntece->menarquia = $request->datoMenarquia;
                $crearAntece->ciclo_menstrual = $request->datoCicloMenstr;
                $crearAntece->pap = $request->datoPap;
                $crearAntece->mamografia = $request->datoMamografia;
                $crearAntece->otros = $request->otrosDetalles;
                $crearAntece->save();
            }

            DB::commit();
            return ['success' => 1];
        }
        catch(\Throwable $e){
            DB::rollback();
            Log::info('error: ' . $e);
            return ['success' => 99];
        }
    }




    public function registrarAntropometria(Request $request){

        $regla = array(
            'fecha' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if(Antropometria::where('consulta_id', $request->idconsulta)->first()){
            return ['success' => 1];
        }

        DB::beginTransaction();

        try {

            $idusuario = Auth::id();

            $horaCarbon = Carbon::parse(Carbon::now());

            $dato = new Antropometria();
            $dato->consulta_id = $request->idconsulta;
            $dato->usuario_id = $idusuario;
            $dato->fecha = $request->fecha;
            $dato->hora = $horaCarbon;
            $dato->frecuencia_cardiaca = $request->freCardiaca;
            $dato->frecuencia_respiratoria = $request->freRespiratoria;
            $dato->presion_arterial = $request->presionArterial;
            $dato->temperatura = $request->temperatura;
            $dato->perim_abdominal = $request->perimetroAbdominal;
            $dato->perim_cefalico = $request->perimetroCefalico;
            $dato->peso_libra = $request->pesoLibra;
            $dato->peso_kilo = $request->pesoKilo;
            $dato->estatura = $request->estatura;
            $dato->imc = $request->imc;
            $dato->resultado_imc = $request->resultadoImc;
            $dato->glucometria_capilar = $request->glucometria;
            $dato->glicohemoglibona_capilar = $request->glicohemoglobina;
            $dato->cetona_capilar = $request->cetona;
            $dato->spo2 = $request->sp02;
            $dato->perim_cintura = $request->perimetroCintura;
            $dato->perim_cadera = $request->perimetroCadera;
            $dato->icc = $request->icc;
            $dato->riesgo_mujer = $request->riesgoMujer;
            $dato->riesgo_hombre = $request->riesgoHombre;
            $dato->gasto_energetico_basal = $request->gastoEnergetico;
            $dato->nota_adicional = $request->otrosDetalles;
            $dato->save();

            DB::commit();
            return ['success' => 2];

        }catch(\Throwable $e){
            DB::rollback();

            return ['success' => 99];
        }
    }



    public function actualizarAntropometria(Request $request){

        $regla = array(
            'fecha' => 'required',
            'idmodal' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Antropometria::where('id', $request->idmodal)->update([
            'fecha' => $request->fecha,
            'frecuencia_cardiaca' => $request->freCardiaca,
            'frecuencia_respiratoria' => $request->freRespiratoria,
            'presion_arterial' => $request->presionArterial,
            'temperatura' => $request->temperatura,
            'perim_abdominal' => $request->perimetroAbdominal,
            'perim_cefalico' => $request->perimetroCefalico,
            'peso_libra' => $request->pesoLibra,
            'peso_kilo' => $request->pesoKilo,
            'estatura' => $request->estatura,
            'imc' => $request->imc,
            'resultado_imc' => $request->resultadoImc,
            'glucometria_capilar' => $request->glucometria,
            'glicohemoglibona_capilar' => $request->glicohemoglobina,
            'cetona_capilar' => $request->cetona,
            'spo2' => $request->sp02,
            'perim_cintura' => $request->perimetroCintura,
            'perim_cadera' => $request->perimetroCadera,
            'icc' => $request->icc,
            'riesgo_mujer' => $request->riesgoMujer,
            'riesgo_hombre' => $request->riesgoHombre,
            'gasto_energetico_basal' => $request->gastoEnergetico,
            'nota_adicional' => $request->otrosDetalles,
        ]);

       return ['success' => 1];
    }






    // *** Historial Clinico


    public function indexHistorialClinico($idconsulta){

        $infoConsulta = Consulta_Paciente::where('id', $idconsulta)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $edad = Carbon::parse($infoPaciente->fecha_nacimiento)->age;

        $miFecha = date("d-m-Y", strtotime($infoPaciente->fecha_nacimiento));

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos . " (" . $edad . " Años)";


        // CONTEO DIRECTO
        $totalConsulta = Consulta_Paciente::where('paciente_id', $infoConsulta->paciente_id)->count();

        $arrayTipoDiagnostico = Diagnosticos::orderBy('nombre')->get();

        return view('backend.admin.historialclinico.general.vistahistorialclinico', compact('idconsulta',
            'infoPaciente', 'nombreCompleto', 'miFecha', 'totalConsulta', 'arrayTipoDiagnostico'));
    }




    // BLOQUE ANTECEDENTES
    public function bloqueHistorialAntecedente($idconsulta){

        $infoConsulta = Consulta_Paciente::where('id', $idconsulta)->first();
        $b1_infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $b1_antecedentes = null;
        // buscar si paciente tiene antecedentes
        if($infoAntecedente = Antecedentes::where('paciente_id', $b1_infoPaciente->id)->first()){
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
        $b1_arrayIdPacienteAntecedente = PacienteAntecedentes::where('paciente_id', $b1_infoPaciente->id)->get();


        $b1_btnAntro = 0;
        // verificar si ya tiene 1 ampometria, para ocultar boton
        if(Antropometria::where('consulta_id', $idconsulta)->first()){
            $b1_btnAntro = 1;
        }


        return view('backend.admin.historialclinico.bloques.bloqueantecedentes', compact('b1_antecedentes', 'b1_arrayTipeoSanguineo',
            'b1_arrayAntecedentesMedico', 'b1_arrayIdPacienteAntecedente', 'b1_arrayComplicacionAguda',
            'b1_arrayEnfermedadCronicas', 'b1_arrayAntecedenteCronicos', 'b1_btnAntro', 'b1_infoPaciente'));
    }



    public function bloqueHistorialAntropSv($idconsulta){

        $bloqueAntropSv = Antropometria::where('consulta_id', $idconsulta)
            ->orderBy('fecha', 'DESC')
            ->get();

        foreach ($bloqueAntropSv as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
            $dato->horaFormat = date("h:i A", strtotime($dato->hora));

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();

            $dato->nomusuario = $infoUsuario->nombre;
        }

        $btnAntrosV = 0;
        // verificar si ya tiene 1 ampometria, para ocultar boton
        if(Antropometria::where('consulta_id', $idconsulta)->first()){
            $btnAntrosV = 1;
        }

        return view('backend.admin.historialclinico.bloques.bloqueantropsv', compact('bloqueAntropSv',
            'btnAntrosV'));
    }

    public function vistaNuevaAntropologia($idconsulta){

        $nombreCompleto = "pepe";

        return view('backend.admin.historialclinico.antropsv.vistaantropologiasv', compact('idconsulta', 'nombreCompleto'));
    }



    function bloqueHistorialRecetas($idconsulta){

        $infoConsulta = Consulta_Paciente::where('id', $idconsulta)->first();
        $arrayRecetas = Recetas::where('paciente_id', $infoConsulta->paciente_id)
            ->orderBy('fecha')
            ->get();

        foreach ($arrayRecetas as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
            $dato->fechaProFormat = date("d-m-Y", strtotime($dato->proxima_cita));

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();
            $dato->nombreusuario = $infoUsuario->nombre;
        }

        // mostrar boton
        $existeReceta = 0;
        if(Recetas::where('consulta_id', $idconsulta)->first()){
            $existeReceta = 1;
        }

        return view('backend.admin.historialclinico.bloques.bloquerecetas', compact('arrayRecetas', 'existeReceta'));
    }


    public function bloqueHistorialCuadroClinico($idconsulta){

        $bloqueCuadroClinico = DB::table('cuadro_clinico AS cl')
            ->join('consulta_paciente AS con', 'con.id', '=', 'cl.consulta_id')
            ->select('con.fecha_hora', 'cl.diagnostico_id', 'cl.descripcion',
                'cl.consulta_id', 'cl.diagnostico_id', 'cl.id', 'cl.usuario_id')
            ->where('cl.consulta_id', $idconsulta)
            ->orderBy('con.fecha_hora', 'ASC')
            ->get();

        foreach ($bloqueCuadroClinico as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha_hora));
            $infoDiagnostico = Diagnosticos::where('id', $dato->diagnostico_id)->first();
            $dato->nombreDiagnostico = $infoDiagnostico->nombre;

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();
            $dato->nombreUsuario = $infoUsuario->nombre;
        }

        $haycuadro = 0;
        if(CuadroClinico::where('consulta_id', $idconsulta)->first()){
            $haycuadro = 1;
        }

        return view('backend.admin.historialclinico.bloques.bloquecuadroclinico', compact('bloqueCuadroClinico', 'idconsulta', 'haycuadro'));
    }


    public function nuevoHistorialClinico(Request $request){

        $regla = array(
            'idconsulta' => 'required',
            'diagnostico' => 'required'
        );

        // descripcion

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();

        try {

            $usuario = auth()->user();

            $dato = new CuadroClinico();
            $dato->consulta_id = $request->idconsulta;
            $dato->diagnostico_id = $request->diagnostico;
            $dato->descripcion = $request->descripcion;
            $dato->usuario_id = $usuario->id;
            $dato->save();

            DB::commit();
            return ['success' => 1];

        }catch(\Throwable $e){
            DB::rollback();

            return ['success' => 99];
        }
    }


    public function informacionHistorialClinico(Request $request){

        $regla = array(
            'id' => 'required' // id de cuadro clinico
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = CuadroClinico::where('id', $request->id)->first()){

            $arrayDiagnostico = Diagnosticos::orderBy('nombre', 'ASC')->get();

            return ['success' => 1, 'info' => $info, 'arraydiagnostico' => $arrayDiagnostico];
        }else{
            return ['success' => 2];
        }
    }


    public function actualizarHistorialClinico(Request $request){

        $regla = array(
            'idCuadro' => 'required',
            'diagnostico' => 'required',
            'descripcion' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(CuadroClinico::where('id', $request->idCuadro)->first()){

            CuadroClinico::where('id', $request->idCuadro)->update([
                'diagnostico_id' => $request->diagnostico,
                'descripcion' => $request->descripcion
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }




    public function vistaVisualizarAntropologia($idantrop){

        $infoAntrop = Antropometria::where('id', $idantrop)->first();
        $infoConsulta = Consulta_Paciente::where('id', $infoAntrop->consulta_id)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $idconsulta = $infoConsulta->id;

        return view('backend.admin.historialclinico.antropsv.vistaeditarantropologiasv', compact('idantrop',
        'nombreCompleto', 'idconsulta', 'infoAntrop'));
    }


    public function vistaVisualizarAntropologiaExpedientes($idantrop){

        $infoAntrop = Antropometria::where('id', $idantrop)->first();
        $infoConsulta = Consulta_Paciente::where('id', $infoAntrop->consulta_id)->first();
        $infoPaciente = Paciente::where('id', $infoConsulta->paciente_id)->first();

        $nombreCompleto = $infoPaciente->nombres . " " . $infoPaciente->apellidos;

        $idconsulta = $infoConsulta->id;

        return view('backend.admin.expedientes.buscar.docurecetas.vistaverantropologia', compact('idantrop',
            'nombreCompleto', 'idconsulta', 'infoAntrop'));
    }


}
