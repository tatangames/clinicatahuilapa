@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloTogglePequeno.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    .widget-user-image2{
        left:50%;margin-left:-45px;
        position:absolute;
        top:80px
    }


    .widget-user-image2>img{
        border:3px solid #fff;
        height:auto;
    }

</style>


<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
                <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;"
                        onclick="vistaAsignaciones()" class="button button-3d button-rounded button-pill button-small">
                    <i class="fas fa-arrow-left"></i>
                    Atras
                </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
                <div class="card-header">
                    <section >
                        <div class="container">
                            <div class="row">

                                <div class="col-md-7">
                                    <div class="card" style="border-radius: 15px;">
                                        <div class="card-body p-4">
                                            <div class="d-flex text-black">
                                                <div >
                                                    @if($infoPaciente->foto == null)
                                                        <img style="margin-left: 15px" alt="Sin Foto" src="{{ asset('images/foto-default.png') }}" width="120px" height="120px" />
                                                    @else
                                                        <img style="margin-left: 15px" alt="Foto Paciente" src="{{ url('storage/archivos/'.$infoPaciente->foto) }}" width="120px" height="120px" />
                                                    @endif
                                                </div>

                                                <div style="margin-left: 15px">
                                                    <h5 style="font-weight: bold">FICHA CLINICA</h5>
                                                    <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
                                                    <p><span class="badge bg-primary" style="font-size: 13px">Fecha Nacimiento:  {{ $miFecha }}</span></p>
                                                    <p><span class="badge bg-primary" style="font-size: 13px; color: white !important;">Expediente #:  {{ $infoPaciente->numero_expediente }}</span></p>
                                                    <p><span class="badge bg-primary" style="font-size: 13px; color: white !important;">Consulta #:  {{ $totalConsulta }}</span></p>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="card" style="border-radius: 15px;">
                                        <div class="card-body p-4">
                                            <div class="row d-flex justify-content-center align-items-center h-100">


                                                <div class="row">
                                                    <div class="col-12" style="padding: 5px 0px;">
                                                        <button type="button" onclick="vistaDatosGenerales();" class="btn btn-warning btn-block waves-effect waves-light" style="color: white">Datos generales
                                                        </button>
                                                    </div>
                                                    <div class="col-12" style="padding: 5px 0px;">
                                                        <button class="btn btn-danger btn-block" type="button" onclick="finalizarConsulta();">Finalizar</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>





                </div>

        </div>
    </section>



    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


                            <!-- Custom Tabs -->
                            <div class="card">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3"></h3>

                                    <div style="float: left; margin: 16px">
                                        <ul class="nav nav-pills ml-auto p-2">

                                            <li style="font-size: 16px; font-weight: bold" class="nav-item">
                                                <a class="nav-link active" href="#tab_1"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/personacard.png') }}" height="25px" width="25px">
                                                        </span>ANTECEDENTES
                                                </a>
                                            </li>

                                            <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_2"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/corazonrojo.png') }}" height="25px" width="25px">
                                                        </span>
                                                    SV + ANTROP
                                                </a>
                                            </li>


                                            <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_3"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/medicamento.png') }}" height="25px" width="25px">
                                                        </span>
                                                    RECETAS
                                                </a>
                                            </li>

                                            <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_4"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/prescripcion.png') }}" height="25px" width="25px">
                                                        </span>
                                                    CUADRO CLINICO
                                                </a>
                                            </li>

                                            <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_5"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/notas.png') }}" height="25px" width="25px">
                                                        </span>
                                                    NOTAS
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>




                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">


                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="card-body">

                                                                <!-- CARGAR TABLA DE HISTORIAL CLINICO -->
                                                                @can('ver.tabla.antecedentes')
                                                                <div id="tablaAntecedentes">
                                                                </div>
                                                                @endcan

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>

                                        <!-- LISTA DE NUEVOS MATERIALES - TABS 2 -->
                                        <div class="tab-pane" id="tab_2">
                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="card-body">

                                                                <!-- CARGAR TABLA ANTROP SV -->

                                                                @can('ver.tabla.antropometria')
                                                                    <div id="tablaAntropSv">
                                                                    </div>
                                                                @endcan

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>



                                        <!-- LISTA DE RECETAS - TABS 3 -->
                                        <div class="tab-pane" id="tab_3">
                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="card-body">

                                                                <!-- CARGAR TABLA RECETAS -->

                                                                @can('ver.tabla.recetas')
                                                                    <div id="tablaRecetas">
                                                                    </div>
                                                                @endcan

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>

                                        <!-- CUADRO CLINICO - TABS 4 -->
                                        <div class="tab-pane" id="tab_4">
                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="card-body">

                                                                <!-- CARGAR TABLA RECETAS -->

                                                                @can('ver.tabla.historialclinico')
                                                                    <div id="tablaCuadroClinico">
                                                                    </div>
                                                                @endcan

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>



                                        <!-- NOTAS - TABS 5 -->
                                        <div class="tab-pane" id="tab_5">
                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="card-body">

                                                                <!-- CARGAR TABLA NOTAS -->

                                                                @can('ver.tabla.notas')
                                                                    <div id="tablaNotas">
                                                                    </div>
                                                                @endcan

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>






                                        <!-- fin - Tabs -->
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- MODAL PARA AGREGAR UN NUEVO HISTORIAL CLINICO -->
    <div class="modal fade" id="modalNuevoHistoClinico">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Historial Clínico</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-historial-clinico">
                        <div class="card-body">

                                <div>
                                    <button type="button" style="float: right; font-weight: bold; background-color: #ffc107; color: white !important;"
                                            onclick="nuevoDiagnosticoExtra()" class="button button-3d button-rounded button-pill button-small">
                                        <i class="fas fa-plus"></i>
                                        Nuevo Tipo Diagnóstico
                                    </button>
                                </div>


                                <div class="form-group col-md-8" style="margin-top: 20px">
                                    <label style="color:#191818">Tipo de Diagnóstico</label>
                                    <br>
                                    <div>
                                        <select class="form-control" id="select-tipo-diagnostico">
                                            <option value="">Seleccionar Opción</option>
                                        @foreach($arrayTipoDiagnostico as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group" style="margin-top: 20px">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Descripción</h3>
                                    </div>
                                    <textarea name="editorCuadroClinico" id="editorCuadroClinico"></textarea>
                                </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarNuevoCuadroClinico()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EDITAR UN NUEVO HISTORIAL CLINICO -->
    <div class="modal fade" id="modalEditarHistoClinico">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Historial Clínico</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-historial-clinico-editar">
                        <div class="card-body">

                            <div class="form-group">
                                <input type="hidden" id="idCuadroClinico-editar">
                            </div>

                            <div class="form-group col-md-6">
                                <label style="color:#191818">Tipo de Diagnóstico</label>
                                <br>
                                <div>
                                    <select class="form-control" id="select-tipo-diagnostico-editar">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Descripción</h3>
                                </div>
                                <textarea name="editorCuadroClinico-editar" id="editorCuadroClinico-editar"></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    @can('boton.actualizar.historial.clinico')
                        <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="actualizarCuadroClinico()">Actualizar</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalExtraDiagnostico">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Tipo de Diagnóstico</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-extradiagnostico">
                        <div class="card-body">

                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Nombre <span style="color: red">*</span></label>
                                </div>
                                <input maxlength="150" id="extranombre-diagnostico-nuevo" class="form-control" autocomplete="off">
                            </div>


                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Descripción (opcional)</label>
                                </div>
                                <input maxlength="800" id="extradescripcion-diagnostico-nuevo" class="form-control" autocomplete="off">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarExtraDiagnostico()">Guardar</button>
                </div>
            </div>
        </div>
    </div>






    <!-- MODAL PARA AGREGAR NOTAS -->
    <div class="modal fade" id="modalNuevaNota">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Notas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-notas-nuevo">
                        <div class="card-body">


                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Fecha</label>
                                </div>
                                <input style="width: 26%;" id="notas-fecha-nuevo" type="date" class="form-control" autocomplete="off">
                            </div>


                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Notas</h3>
                                </div>
                                <textarea style="width: 75%" name="editorNotas" id="editorNotas"></textarea>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="registrarNuevaNota()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR PARA NOTAS -->
    <div class="modal fade" id="modalEditarNota">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Notas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-notas-editar">
                        <div class="card-body">

                            <div>
                                <input type="hidden" id="idnota-editar">
                            </div>



                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Fecha</label>
                                </div>
                                <input style="width: 26%;" id="notas-fecha-editar" type="date" class="form-control" autocomplete="off">
                            </div>


                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Notas</h3>
                                </div>
                                <textarea style="width: 75%" name="editorNotasEditar" id="editorNotasEditar"></textarea>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="registrarNotaEditar()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor5.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {


            let idconsulta = {{ $idconsulta }};

            // TABLA ANTECEDENTES

            var divtablaAntecedentes = document.getElementById('tablaAntecedentes');

            if(divtablaAntecedentes){
                var rutaAntecedente = "{{ URL::to('/admin/historial/bloque/antecedente') }}/" + idconsulta;
                $('#tablaAntecedentes').load(rutaAntecedente);
            }

            var divtablaAntropSv = document.getElementById('tablaAntropSv');

            if(divtablaAntropSv){
                // TABLA ANTROP SV
                var rutaAntrop = "{{ URL::to('/admin/historial/bloque/antropsv') }}/" + idconsulta;
                $('#tablaAntropSv').load(rutaAntrop);
            }


            var divtablaRecetas = document.getElementById('tablaRecetas');

            if(divtablaRecetas){
                // TABLA RECETAS
                var rutaRecetas = "{{ URL::to('/admin/historial/bloque/recetas') }}/" + idconsulta;
                $('#tablaRecetas').load(rutaRecetas);
            }


            var divtablaCuadroClinico = document.getElementById('tablaCuadroClinico');

            if(divtablaCuadroClinico){
                // TABLA CUADRO CLINICO
                var rutaCuadroClinico = "{{ URL::to('/admin/historial/bloque/cuadroclinico') }}/" + idconsulta;
                $('#tablaCuadroClinico').load(rutaCuadroClinico);
            }



            var divtablaNotas = document.getElementById('tablaNotas');

            if(divtablaNotas){
                // TABLA NOTAS
                var rutaNotas = "{{ URL::to('/admin/historial/bloque/notas') }}/" + idconsulta;
                $('#tablaNotas').load(rutaNotas);
            }



            $('#select-tipo-diagnostico').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-tipo-diagnostico-editar').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });

            window.varGlobalEditorCuadro;
            window.varGlobalEditorCuadroEditar;

            ClassicEditor
                .create( document.querySelector( '#editorCuadroClinico' ), {

                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'alignment',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'es'

                })
                .then( editor => {
                    varGlobalEditorCuadro = editor;
                } )
                .catch( error => {
                } );


            ClassicEditor
                .create( document.querySelector( '#editorCuadroClinico-editar' ), {

                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'alignment',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'es'

                })
                .then( editor => {
                    varGlobalEditorCuadroEditar = editor;
                } )
                .catch( error => {
                } );




            window.varGlobalNuevaNota;
            window.varGlobalEditarNota;

            ClassicEditor
                .create( document.querySelector( '#editorNotas' ), {

                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'alignment',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'es'

                })
                .then( editor => {
                    varGlobalNuevaNota = editor;
                } )
                .catch( error => {
                } );



            ClassicEditor
                .create( document.querySelector( '#editorNotasEditar' ), {

                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'alignment',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'es'

                })
                .then( editor => {
                    varGlobalEditarNota = editor;
                } )
                .catch( error => {
                } );


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>



        function recargarVista(){
            location.reload();
        }

        function vistaAtras(){
            history.back();
        }


        function nuevoDiagnosticoExtra(){
            document.getElementById("formulario-extradiagnostico").reset();
            $('#modalExtraDiagnostico').modal('show');
        }


        function guardarExtraDiagnostico(){

            var nombre = document.getElementById('extranombre-diagnostico-nuevo').value;
            var descripcion = document.getElementById('extradescripcion-diagnostico-nuevo').value;

            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();

            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);

            axios.post(url+'/diagnosticos/guardar/getlistado/completo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-tipo-diagnostico").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-tipo-diagnostico').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-tipo-diagnostico").trigger("change");

                        $('#modalExtraDiagnostico').modal('hide');
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }


        function vistaDatosGenerales(){

            let idpaciente = {{ $infoPaciente->id }};

            window.location.href="{{ url('/admin/asignaciones/info/vista/editarpaciente') }}/" + idpaciente;
        }

        function finalizarConsulta(){
            Swal.fire({
                title: '¿Finalizar Consulta?',
                text: '',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    finalizarConsultaPaciente();
                }
            })
        }

        function finalizarConsultaPaciente(){

            openLoading();

            let idconsulta = {{ $idconsulta }};

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);

            axios.post(url+'/asignaciones/finalizar/consulta', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Consulta Finalizada',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                vistaAsignaciones();
                            }
                        })
                    }
                    else{
                        toastr.error('error al guardar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al guardar');
                    closeLoading();
                });
        }

        function vistaAsignaciones(){
            window.history.back();
            window.location.href="{{ url('/admin/asignaciones/vista/index') }}";
        }


        //********************************* BLOQUE 2 ***************************************

        function recargarTablaAntropometria(){
            let idconsulta = {{ $idconsulta }};
            var ruta = "{{ URL::to('/admin/historial/antrometria/paciente-consulta') }}/" + idconsulta;
            $('#tablaAntrometria').load(ruta);
        }

        function recargarTablaCuadroClinico(){
            let idconsulta = {{ $idconsulta }};
            var rutaCuadroClinico = "{{ URL::to('/admin/historial/bloque/cuadroclinico') }}/" + idconsulta;
            $('#tablaCuadroClinico').load(rutaCuadroClinico);
        }


        function valida_numero(e){
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
                return true;
            }

            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }


        //****************






        function guardarAntecedentes(){

            const checkboxes = document.querySelectorAll('input[name="arrayCheckAntecedentes[]"]');
            const datosCheckboxes = [];

            checkboxes.forEach((checkbox) => {
                const estado = checkbox.checked;
                const valorAdicional = checkbox.dataset.valor;

                if(estado){
                    // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                    datosCheckboxes.push({ estado, valorAdicional });
                }
            });



            var notaAntecedenteFamiliar = document.getElementById('text-antecedentes-editar').value;
            var notaAlergia = document.getElementById('text-alergias-editar').value;
            var notaMedicamento = document.getElementById('text-medicamento-actual-editar').value;
            var selectSanguineo = document.getElementById('select-tipeo-sanguineo').value;

            var notaAntecedenteMedico = document.getElementById('notas_antecedente_medicos').value;
            var notaCompliDiabete = document.getElementById('notas_complicacion_diabetes').value;
            var notaEnfermedadCronica = document.getElementById('notas_enfermedad_cronica').value;
            var notaAnteceQuirur = document.getElementById('notas_antecedente_quirurgico').value;
            var notaAnteceOftamolo = document.getElementById('notas_antecedente_quirurgico').value;
            var notaAnteceDeportivo = document.getElementById('notas_antecedente_deportivos').value;


            var datoMenarquia = document.getElementById('dato-menarquia').value;
            var datoCicloMenstr = document.getElementById('dato-ciclomenstrual').value;
            var datoPap = document.getElementById('dato-pap').value;
            var datoMamografia = document.getElementById('dato-mamografia').value;

            var otrosDetalles = document.getElementById('otros-detalles').value;

            // ID PACIENTE
            let id = {{ $infoPaciente->id }};

            openLoading();
            var formData = new FormData();
            formData.append('idpaciente', id);
            formData.append('datocheckbox', JSON.stringify(datosCheckboxes));
            formData.append('textAntecedenteFami', notaAntecedenteFamiliar);
            formData.append('textAlergia', notaAlergia);
            formData.append('textMedicamento', notaMedicamento);
            formData.append('selectSanguineo', selectSanguineo);
            formData.append('notaAnteceMedico', notaAntecedenteMedico);
            formData.append('notaCompliDiabete', notaCompliDiabete);
            formData.append('notaEnfermCronica', notaEnfermedadCronica);
            formData.append('notaAnteceQuirur', notaAnteceQuirur);
            formData.append('notaAnteceOftamo', notaAnteceOftamolo);
            formData.append('notaAnteceDeportivo', notaAnteceDeportivo);
            formData.append('datoMenarquia', datoMenarquia);
            formData.append('datoCicloMenstr', datoCicloMenstr);
            formData.append('datoPap', datoPap);
            formData.append('datoMamografia', datoMamografia);
            formData.append('otrosDetalles', otrosDetalles);

            axios.post(url+'/historial/antecedente/actualizacion', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }




        function vistaNuevaNota(id){
            document.getElementById("formulario-notas-nuevo").reset();

            var fechaNota = new Date();
            document.getElementById('notas-fecha-nuevo').value = fechaNota.toJSON().slice(0,10);

            $('#modalNuevaNota').modal('show');
        }


        function registrarNuevaNota(){
            const editorNota = varGlobalNuevaNota.getData();

            if (editorNota.trim() === '') {
                toastr.error("Nota es requerido");
                return;
            }

            // ID CONSULTA
            let idconsulta = {{ $idconsulta }};
            var fecha = document.getElementById('notas-fecha-nuevo').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('fecha', fecha);
            formData.append('nota', editorNota);

            axios.post(url+'/historial/bloque/registrar/nota', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');
                        $('#modalNuevaNota').modal('hide');
                        recargarTablaNotas();
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }



        function recargarTablaNotas(){
            let idconsulta = {{ $idconsulta }};
            var ruta = "{{ URL::to('/admin/historial/bloque/notas') }}/" + idconsulta;
            $('#tablaNotas').load(ruta);
        }

        function modalBorrarNota(id){
            Swal.fire({
                title: '¿Borrar Nota?',
                text: '',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',

                allowOutsideClick: false,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarNota(id);
                }
            })
        }

        function borrarNota(id){

            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/historial/bloque/notas/borrar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Borrado');


                        recargarTablaNotas();
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }


        function informacionEditarNota(id){
            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/historial/bloque/notas/informacion', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#idnota-editar').val(id);



                        let nota = response.data.info.nota;
                        let fecha = response.data.info.fecha;

                        varGlobalEditarNota.setData(nota);
                        $('#notas-fecha-editar').val(fecha);


                        $('#modalEditarNota').modal('show');
                    }
                    else {
                        toastr.error('Error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al buscar');
                    closeLoading();
                });
        }


        function registrarNotaEditar(){

            const editorNota = varGlobalEditarNota.getData();

            if (editorNota.trim() === '') {
                toastr.error("Nota es requerido");
                return;
            }


            var fecha = document.getElementById('notas-fecha-editar').value;
            var idfila = document.getElementById('idnota-editar').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('idfila', idfila);
            formData.append('fecha', fecha);
            formData.append('nota', editorNota);

            axios.post(url+'/historial/bloque/actualizar/nota', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalEditarNota').modal('hide');
                        recargarTablaNotas();
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }


        function generarReporteNota(id){
            window.open("{{ URL::to('admin/pdf/reporte/notapaciente') }}/" + id);
        }













        function modalAntropometria(){

            $('#modalAntro').modal({backdrop: 'static', keyboard: false})
        }


        function vistaAsignaciones(){
            window.location.href="{{ url('/admin/asignaciones/vista/index') }}";
        }

        function vistaNuevaReceta(){

            let idconsulta = {{ $idconsulta }};
            window.location.href="{{ url('/admin/recetas/vista/general') }}/" + idconsulta;
        }


        function infoEditarReceta(idreceta){

            window.location.href="{{ url('/admin/recetas/vista/paraeditar') }}/" + idreceta;
        }


        function vistaNuevaAntropologia(){
            let idconsulta = {{ $idconsulta }};
            window.location.href="{{ url('/admin/vista/nueva/antropometria') }}/" + idconsulta;
        }






    </script>


@endsection
