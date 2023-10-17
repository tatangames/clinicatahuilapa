<?php

namespace App\Http\Controllers\Backend\Expedientes;

use App\Http\Controllers\Controller;
use App\Models\Estado_Civil;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Profesion;
use App\Models\Tipo_Documento;
use App\Models\Tipo_Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExpedientesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexNuevoExpediente(){

        $arrayTipoPaciente = Tipo_Paciente::orderBy('nombre')->get();

        $arrayEstadoCivil = Estado_Civil::orderBy('nombre')->get();

        $arrayTipoDocumento = Tipo_Documento::orderBy('nombre')->get();

        $arrayProfesion = Profesion::orderBy('nombre')->get();

        return view('backend.admin.expedientes.nuevo.vistanuevoexpediente', compact('arrayTipoPaciente',
        'arrayEstadoCivil', 'arrayTipoDocumento', 'arrayProfesion'));
    }


    public function calcularEdad(Request $request){

        $edad = Carbon::parse($request->fecha)->age;

        return ['success' => 1, 'edad' => $edad];
    }



    public function nuevoExpediente(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {



            if ($request->hasFile('documento')) {

                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena . $tiempo;
                $nombre = str_replace(' ', '_', $union);

                $extension = '.' . $request->documento->getClientOriginalExtension();
                $nomDocumento = $nombre . strtolower($extension);
                $avatar = $request->file('documento');
                $archivo = Storage::disk('archivos')->put($nomDocumento, \File::get($avatar));


                if($archivo){

                    if($request->sexopaciente == 1){
                        $genero = "M";
                    }else{
                        $genero = "F";
                    }

                    $detalle = new Paciente();
                    $detalle->tipo_id = $request->tipopaciente;
                    $detalle->estado_civil_id = $request->estadocivil;
                    $detalle->tipo_documento_id = $request->tipodocumento;
                    $detalle->profesion_id = $request->profesion;
                    $detalle->nombres = $request->nombre;
                    $detalle->apellidos = $request->apellido;
                    $detalle->fecha_nacimiento = $request->fechanacimiento;
                    $detalle->sexo = $genero;
                    $detalle->referido_por = $request->referido;
                    $detalle->num_documento = $request->documento;
                    $detalle->correo = $request->correo;
                    $detalle->celular = $request->celular;
                    $detalle->telefono = $request->telefono;
                    $detalle->direccion = $request->direccion;
                    $detalle->foto = $nomDocumento;
                    $detalle->save();

                    DB::commit();
                    return ['success' => 1];

                }else{
                    return ['success' => 99];
                }

            }else{

                if($request->sexopaciente == 1){
                    $genero = "M";
                }else{
                    $genero = "F";
                }

                $detalle = new Paciente();
                $detalle->tipo_id = $request->tipopaciente;
                $detalle->estado_civil_id = $request->estadocivil;
                $detalle->tipo_documento_id = $request->tipodocumento;
                $detalle->profesion_id = $request->profesion;
                $detalle->nombres = $request->nombre;
                $detalle->apellidos = $request->apellido;
                $detalle->fecha_nacimiento = $request->fechanacimiento;
                $detalle->sexo = $genero;
                $detalle->referido_por = $request->referido;
                $detalle->num_documento = $request->documento;
                $detalle->correo = $request->correo;
                $detalle->celular = $request->celular;
                $detalle->telefono = $request->telefono;
                $detalle->direccion = $request->direccion;
                $detalle->save();

                DB::commit();
                return ['success' => 1];
            }

        }catch(\Throwable $e){
            DB::rollback();
            Log::info('error expediente: ' . $e);
            return ['success' => 99];
        }
    }




    // ********************************************


    public function indexBuscarExpediente(){

        return view('backend.admin.expedientes.buscar.vistabuscarexpediente');
    }

    public function tablaBuscarExpediente(){

        $arrayExpedientes = Paciente::orderBy('nombres')->get();

        foreach ($arrayExpedientes as $dato){

            if($infoProfesion = Profesion::where('id', $dato->profesion_id)->first()){
                $dato->profesion = $infoProfesion->nombre;
            }else{
                $dato->profesion = '';
            }
        }

        return view('backend.admin.expedientes.buscar.tablabuscarexpediente', compact('arrayExpedientes'));
    }






}
