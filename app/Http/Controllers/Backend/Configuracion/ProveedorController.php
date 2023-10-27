<?php

namespace App\Http\Controllers\Backend\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\ContenidoFarmaceutica;
use App\Models\Proveedores;
use App\Models\TipoFarmaceutica;
use App\Models\TipoProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function indexVistaProveedor(){

        $listado = TipoProveedor::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.proveedores.vistaproveedores', compact('listado'));
    }

    public function tablaVistaProveedor(){

        $arrayProveedores = Proveedores::orderBy('nombre')->get();

        foreach ($arrayProveedores as $dato){

            $infoTipo = TipoProveedor::where('id', $dato->tipo_proveedor_id)->first();

            $dato->tipoproveedor = $infoTipo->nombre;
        }

        return view('backend.admin.configuracion.proveedores.tablaproveedores', compact('arrayProveedores'));
    }


    public function registroNuevoProveedor(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new Proveedores();
        $dato->tipo_proveedor_id = $request->tipo;
        $dato->nombre = $request->nombre;
        $dato->nombre_comercial = $request->nombreComercial;
        $dato->nrc = $request->nrc;
        $dato->nit = $request->nit;
        $dato->direccion = $request->direccion;
        $dato->departamento_contacto = $request->contactoDepa;
        $dato->telefono_fijo = $request->telFijo;
        $dato->telefono_celular = $request->telCelular;
        $dato->correo = $request->correo;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionProveedor(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoProveedor = Proveedores::where('id', $request->id)->first()){

            $arrayTipos = TipoProveedor::orderBy('nombre')->get();

            return ['success' => 1, 'info' => $infoProveedor, 'arraytipo' => $arrayTipos];
        }else{
            return ['success' => 2];
        }
    }


    public function editarProveedor(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        Proveedores::where('id', $request->id)->update([
            'tipo_proveedor_id' => $request->tipo,
            'nombre' => $request->nombre,
            'nombre_comercial' => $request->nombreComercial,
            'nrc' => $request->nrc,
            'nit' => $request->nit,
            'direccion' => $request->direccion,
            'departamento_contacto' => $request->contactoDepa,
            'telefono_fijo' => $request->telFijo,
            'telefono_celular' => $request->telCelular,
            'correo' => $request->correo,
        ]);

        return ['success' => 1];
    }








    //************************ TIPOS DE MEDICAMENTO ***********************************************




    public function indexVistaTipoMedicamento(){

        $listado = TipoFarmaceutica::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.tipofarmaceutica.vistatipofarmaceutica', compact('listado'));
    }

    public function tablaVistaTipoMedicamento(){

        $arrayFarmaceutica = ContenidoFarmaceutica::orderBy('nombre')->get();

        foreach ($arrayFarmaceutica as $dato){

            $infoTipo = TipoFarmaceutica::where('id', $dato->tipo_farmaceutica_id)->first();

            $dato->tipomedicamento = $infoTipo->nombre;
        }

        return view('backend.admin.configuracion.tipofarmaceutica.tablatipofarmaceutica', compact('arrayFarmaceutica'));
    }


    public function registroNuevoTipoMedicamento(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $dato = new ContenidoFarmaceutica();
        $dato->tipo_farmaceutica_id = $request->tipo;
        $dato->nombre = $request->nombre;

        if($dato->save()){
            return ['success' => 1];
        }else{
            return ['success' => 99];
        }
    }


    public function informacionTipoMedicamento(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        if($infoProveedor = ContenidoFarmaceutica::where('id', $request->id)->first()){

            $arrayTipos = TipoFarmaceutica::orderBy('nombre')->get();

            return ['success' => 1, 'info' => $infoProveedor, 'arraytipo' => $arrayTipos];
        }else{
            return ['success' => 2];
        }
    }


    public function editarTipoMedicamento(Request $request){

        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        ContenidoFarmaceutica::where('id', $request->id)->update([
            'tipo_farmaceutica_id' => $request->tipo,
            'nombre' => $request->nombre,
        ]);

        return ['success' => 1];
    }





}
