<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Medico;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // retorna vista de Medicos
    public function indexMedico(){
        $usuarios = Usuario::orderBy('id', 'ASC')->get();

        return view('backend.admin.configuracion.medico.vistamedico', compact('usuarios'));
    }

    // retorna tabla de Medicos
    public function tablaMedico(){
        $lista = Medico::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.medico.tablamedico', compact('lista'));
    }

    // registrar un nuevo Médico
    public function nuevoMedico(Request $request){

        $regla = array(
            'usuario_id' => 'required',
            'nombre' => 'required',
            'apellido' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Medico::where('usuario_id', $request->usuario_id)->first()){
            return ['success' => 1];
        }


        $dato = new Medico();
        $dato->usuario_id = $request->usuario_id;
        $dato->nombre = $request->nombre;
        $dato->apellido = $request->apellido;
        $dato->telefono = $request->telefono;

        if($dato->save()){
            return ['success' => 2];
        }else{
            return ['success' => 99];
        }
    }

    // obtener información de un Médico
    public function infoMedico(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Medico::where('id', $request->id)->first()){

            $usuarionombre = Usuario::orderBy('nombre')->get();
            return ['success' => 1, 'lista' => $lista, 'idrr' => $lista->usuario_id, 'rr' => $usuarionombre];
        }else{
            return ['success' => 2];
        }
    }

    // editar un medico
    public function editarMedico(Request $request){

        $regla = array(
            'id' => 'required',
            'usuario_id' => 'required',
            'nombre' => 'required',
            'apellido' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Medico::where('usuario_id', $request->usuario_id)
            ->where('id', '!=', $request->id)->first()){
            return ['success' => 1];
        }

        if(Medico::where('id', $request->id)->first()){

            Medico::where('id', $request->id)->update([
                'usuario_id' => $request->usuario_id,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono
            ]);

            return ['success' => 2];
        }else{
            return ['success' => 99];
        }
    }
}
