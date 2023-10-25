<?php

namespace App\Http\Controllers\Backend\Historial;

use App\Http\Controllers\Controller;
use App\Models\Antecedentes;
use App\Models\AntecedentesMedicos;
use App\Models\Antropometria;
use App\Models\Consulta_Paciente;
use App\Models\Paciente;
use App\Models\PacienteAntecedentes;
use App\Models\TipeoSanguineo;
use App\Models\Usuario;
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


        $btnAntro = 0;
        // verificar si ya tiene 1 ampometria, para ocultar boton
        if(Antropometria::where('consulta_id', $idconsulta)->first()){
            $btnAntro = 1;
        }


        return view('backend.admin.historial.general.vistageneralhistorial', compact('infoPaciente',
            'nombreCompleto', 'antecedentes', 'arrayTipeoSanguineo',
            'arrayAntecedentesMedico', 'arrayIdPacienteAntecedente', 'arrayComplicacionAguda',
        'arrayEnfermedadCronicas', 'arrayAntecedenteCronicos', 'idconsulta', 'btnAntro'));
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


    public function tablaAntrometriaPaciente($idconsulta){


        $lista = Antropometria::where('consulta_id', $idconsulta)
            ->orderBy('fecha', 'DESC')
            ->get();

        foreach ($lista as $dato){

            $dato->fechaFormat = date("d-m-Y", strtotime($dato->fecha));
            $dato->horaFormat = date("h:i A", strtotime($dato->hora));

            $infoUsuario = Usuario::where('id', $dato->usuario_id)->first();

            $dato->nomusuario = $infoUsuario->nombre;
        }


        return view('backend.admin.historial.antropometria.tablaantropometria', compact('lista'));
    }


    public function registrarAntropometria(Request $request){


        $regla = array(
            'fecha' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

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
            return ['success' => 1];

        }catch(\Throwable $e){
            DB::rollback();
            Log::info('error ' . $e);
            return ['success' => 99];
        }

    }



    public function informacionAntropometria(Request $request){


        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Antropometria::where('id', $request->id)->first()){





            return ['success' => 1];
        }else{
            return ['success' => 2];
        }

    }



}
