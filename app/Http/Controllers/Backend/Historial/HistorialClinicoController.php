<?php

namespace App\Http\Controllers\Backend\Historial;

use App\Http\Controllers\Controller;
use App\Models\Antecedentes;
use App\Models\AntecedentesMedicos;
use App\Models\Consulta_Paciente;
use App\Models\Paciente;
use Illuminate\Http\Request;

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

        // ARRAY DE ANTECEDENTES MEDICOS
        $arrayAnteceMedico = AntecedentesMedicos::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.historial.general.vistageneralhistorial', compact('infoPaciente',
            'nombreCompleto', 'antecedentes'));
    }

}
