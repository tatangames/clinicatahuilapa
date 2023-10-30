<?php

namespace App\Http\Controllers\Backend\Farmacia;

use App\Http\Controllers\Controller;
use App\Models\ArticuloMedicamento;
use App\Models\ContenidoFarmaceutica;
use App\Models\FarmaciaArticulo;
use App\Models\FuenteFinanciamiento;
use App\Models\Linea;
use App\Models\Proveedores;
use App\Models\SubLinea;
use App\Models\TipoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FarmaciaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }



    public function indexRegistroArticulo(){

        $arrayLinea = Linea::orderBy('nombre')->get();
        $arraySubLinea = SubLinea::orderBy('nombre')->get();

        // array envase
        $arrayEnvase = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 1)->orderBy('nombre')->get();

        // array forma farmaceutica
        $arrayFormaFarmaceutica = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 2)->orderBy('nombre')->get();


        // array concentracion
        $arrayConcentracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 3)->orderBy('nombre')->get();


        // array contenido
        $arrayContenido = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 4)->orderBy('nombre')->get();


        // array forma administracion
        $arrayAdministracion = ContenidoFarmaceutica::where('tipo_farmaceutica_id', 5)->orderBy('nombre')->get();


        return view('backend.admin.farmacia.registro.vistaregistro', compact('arrayLinea',
            'arraySubLinea', 'arrayEnvase', 'arrayFormaFarmaceutica', 'arrayConcentracion',
            'arrayContenido', 'arrayAdministracion'));
    }


    public function registrarArticulo(Request $request){


        $regla = array(
            'idLinea' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


        DB::beginTransaction();

        try {

            if(FarmaciaArticulo::where('codigo_articulo', $request->codigoArticulo)->first()){
                return ['success' => 1];
            }


            $articulo = new FarmaciaArticulo();
            $articulo->linea_id = $request->idLinea;
            $articulo->sublinea_id = $request->idSubLinea;
            $articulo->nombre = $request->nombre;
            $articulo->codigo_articulo = $request->codigoArticulo;
            $articulo->existencia_minima = $request->existencia;
            $articulo->save();


            if($request->idLinea == 1){
                // SOLO GUARDAR SI ES TIPO MEDICAMENTOS

                $dato = new ArticuloMedicamento();
                $dato->farmacia_articulo_id = $articulo->id;
                $dato->con_far_envase_id = $request->idEnvase;
                $dato->con_far_forma_id = $request->idFormaFarma;
                $dato->con_far_concentracion_id = $request->idConcentracion;
                $dato->con_far_contenido_id = $request->idContenido;
                $dato->con_far_administra_id = $request->idAdministracion;
                $dato->nombre_generico = $request->nombreGenerico;
                $dato->save();
            }

            DB::commit();
            return ['success' => 2];

        }catch(\Throwable $e){
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




    //*************************************************************

    public function indexIngresoArticulo(){

        $arrayTipoFactura = TipoFactura::orderBy('nombre')->get();
        $arrayFuente = FuenteFinanciamiento::orderBy('nombre')->get();
        $arrayProveedor = Proveedores::orderBy('nombre')->get();

        return view('backend.admin.farmacia.ingreso.vistaingresoinventario', compact('arrayTipoFactura',
            'arrayFuente', 'arrayProveedor'));
    }


    public function buscarMedicamento(Request $request){

        if($request->get('query')){
            $query = $request->get('query');
            $data = FarmaciaArticulo::where('nombre', 'LIKE', "%{$query}%")
                ->orWhere('codigo_articulo', 'LIKE', "%{$query}%")
                ->get();

            foreach ($data as $info){

                if($info->codigo_articulo != null){
                    $info->nombreunido = $info->codigo_articulo . ' - ' . $info->nombre;
                }else{
                    $info->nombreunido = $info->nombre;
                }

                $info->existencia = 200;

                $info->ultimoprecio = "$12.50";


            }

            $output = '<ul class="dropdown-menu" style="display:block; position:relative; overflow: auto; max-height: 300px; width: 550px">';
            $tiene = true;
            foreach($data as $row){

                // si solo hay 1 fila, No mostrara el hr, salto de linea
                if(count($data) == 1){
                    if(!empty($row)){
                        $tiene = false;
                        $output .= '
                 <li onclick="modificarValor(this)" id="'.$row->id.'" data-ultimoprecio="'.$row->ultimoprecio.'" data-existencia="'.$row->existencia.'"><a href="#" style="margin-left: 3px">'.$row->nombreunido.'</a></li>
                ';
                    }
                }

                else{
                    if(!empty($row)){
                        $tiene = false;
                        $output .= '
                 <li onclick="modificarValor(this)" id="'.$row->id.'"  data-ultimoprecio="'.$row->ultimoprecio.'" data-existencia="'.$row->existencia.'"><a href="#" style="margin-left: 3px">'.$row->nombreunido.'</a></li>
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
    }





}
