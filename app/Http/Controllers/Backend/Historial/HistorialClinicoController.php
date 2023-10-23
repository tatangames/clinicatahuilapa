<?php

namespace App\Http\Controllers\Backend\Historial;

use App\Http\Controllers\Controller;
use App\Models\Antecedentes;
use App\Models\AntecedentesMedicos;
use App\Models\Consulta_Paciente;
use App\Models\Paciente;
use App\Models\PacienteAntecedentes;
use App\Models\TipeoSanguineo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $notaAntecedenteMedico = "sdfsdfds";



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
        'arrayEnfermedadCronicas', 'arrayAntecedenteCronicos'));
    }




    public function actualizarListadoPacienteAntecedente(Request $request){





        // FORM DATA

        // idpaciente
        // datocheckbox
        // textAntecedenteFami
        // textAlergia
        // textMedicamento
        // selectSanguineo


        DB::beginTransaction();

        try {

            PacienteAntecedentes::where('id', $request->idpaciente)->delete();

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
                    /*'nota_antecedente_medico' => $request->xxx,
                    'nota_complicaciones_diabetes' => $request->xxx,
                    'nota_enfermedades_cronicas' => $request->xxx,
                    'nota_antecedentes_quirurgicos' => $request->xxx,
                    'antecedentes_oftalmologicos' => $request->xxx,
                    'antecedentes_deportivos' => $request->xxx,
                    'menarquia' => $request->xxx,
                    'ciclo_menstrual' => $request->xxx,
                    'pap' => $request->xxx,
                    'mamografia' => $request->xxx,
                    'otros' => $request->xxx,*/
                ]);

                // idpaciente
                // datocheckbox
                // textAntecedenteFami
                // textAlergia
                // textMedicamento
                // selectSanguineo


            }else{

                // Crear
                $crearAntece = new Antecedentes();
                $crearAntece->paciente_id = $request->idpaciente;
                $crearAntece->tipeo_sanguineo_id = $request->selectSanguineo;
                $crearAntece->antecedentes_familiares = $request->textAntecedenteFami;
                $crearAntece->alergias = $request->textAlergia;
                $crearAntece->medicamentos_actuales = $request->textMedicamento;
                $crearAntece->nota_antecedente_medico = null;
                $crearAntece->nota_complicaciones_diabetes = null;
                $crearAntece->nota_enfermedades_cronicas = null;
                $crearAntece->nota_antecedentes_quirurgicos = null;
                $crearAntece->antecedentes_oftalmologicos = null;
                $crearAntece->antecedentes_deportivos = null;
                $crearAntece->menarquia = null;
                $crearAntece->ciclo_menstrual = null;
                $crearAntece->pap = null;
                $crearAntece->mamografia = null;
                $crearAntece->otros = null;
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




}
