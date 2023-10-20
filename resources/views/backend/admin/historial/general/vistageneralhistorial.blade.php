@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>


<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
            <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" onclick="recargarVista()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-pencil-alt"></i>
                recargar
            </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><span style="font-weight: bold"> Paciente: </span> {{ $nombreCompleto }}</h3>
                </div>
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
                                                    <a class="nav-link active" href="#tab_1" onclick="mostrarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/personacard.png') }}" height="25px" width="25px">
                                                        </span>ANTECEDENTES
                                                    </a>
                                                </li>

                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_2" onclick="ocultarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/corazonrojo.png') }}" height="25px" width="25px">
                                                        </span>
                                                        SV + ANTROP
                                                    </a>
                                                </li>



                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_3" onclick="ocultarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/chequelista.png') }}" height="25px" width="25px">
                                                        </span>
                                                        RECETAS Y PLAN Tx
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


                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Antecedentes Familiares:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="5" id="antecedentes-editar"> @if($antecedentes != null) {{ $antecedentes->antecedentes_familiares }} @endif </textarea>
                                                                    </div>


                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Alergias:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="alergias-editar"> @if($antecedentes != null) {{ $antecedentes->alergias }} @endif </textarea>
                                                                    </div>

                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Medicamentos actuales:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="alergias-editar"> @if($antecedentes != null) {{ $antecedentes->medicamentos_actuales }} @endif </textarea>
                                                                    </div>

                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Tipeo sanguíneo:</label>
                                                                    <select id="select-tiposanguineo" class="form-control">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="">Seleccione...</option>
                                                                    </select>


                                                                    <select name="tipeo" id="tipeo" class="form-control">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="1">A +</option><option value="2">A -</option><option value="3">B + </option><option value="4">B -</option><option value="5">O +</option><option value="6">O -</option><option value="7">AB +</option><option value="8">AB -</option>                            </select>


                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </section>









                                            </div>

                                            <!-- LISTA DE NUEVOS MATERIALES - TABS 2 -->
                                            <div class="tab-pane" id="tab_2">

                                                <form>
                                                    <div class="card-body">

                                                        <table class="table" id="matrizMateriales" style="border: 80px" data-toggle="table">
                                                            <thead>
                                                            <tr>
                                                                <th style="width: 30%; text-align: center">Descripción</th>
                                                                <th style="width: 20%; text-align: left">Unidad de Medida</th>
                                                                <th style="width: 15%; text-align: center">Costo</th>
                                                                <th style="width: 15%; text-align: center">Cantidad</th>
                                                                <th style="width: 10%; text-align: center">Periodo</th>

                                                                <th style="width: 10%; text-align: center">Opciones</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitud()">Agregar Solicitud de Material</button>
                                                        <br>

                                                    </div>

                                                </form>

                                            </div>

                                            <!-- LISTA DE PROYECTOS - TABS 3 -->

                                            <div class="tab-pane" id="tab_3">

                                                <form>
                                                    <div class="card-body">

                                                        <table class="table" id="matrizProyectos" style="border: 80px" data-toggle="table">
                                                            <thead>
                                                            <tr>
                                                                <th style="width: 30%; text-align: center">Descripción</th>
                                                                <th style="width: 10%; text-align: center">Monto ($)</th>
                                                                <th style="width: 10%; text-align: center">Opciones</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitudProyecto()">Agregar Solicitud de Proyecto</button>
                                                        <br>

                                                    </div>

                                                </form>
                                            </div>


                                            <!-- fin - Tabs -->
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="btn-group-vertical" id="bloque-codigo" style="width: 175px !important;">
                                        <label style="margin-left: 5px">Tipo según Color </label>
                                        <button type="button" class="btn btn-info" style="background: #c5c6c8; color: black !important; font-weight: bold">RUBRO</button>
                                        <button type="button" class="btn btn-info" style="background: #b0c2f2; color: black !important; font-weight: bold">CUENTA</button>
                                        <button type="button" class="btn btn-info" style="background: #b0f2c2; color: black !important; font-weight: bold">OBJETO ESPECÍFICO</button>
                                    </div>

                                    <button type="button" onclick="verificar()" style="font-weight: bold; background-color: #28a745; color: white !important;"
                                            class="button button-rounded button-pill button-small float-right">Guardar</button>
                                </div>











                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>







</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){



            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargarVista(){
            location.reload();
        }

        function recargar(){
            var ruta = "{{ URL::to('/admin/diagnostico/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }


        function nuevo(){
            var nombre = document.getElementById('nombre-nuevo').value;
            var descripcion = document.getElementById('descripcion-nuevo').value;

            if(nombre === ''){
                toastr.error('Tipo de Paciente es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);

            axios.post(url+'/diagnostico/registro', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
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

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/diagnostico/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#nombre-editar').val(response.data.info.nombre);
                        $('#descripcion-editar').val(response.data.info.descripcion);

                    }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar(){
            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var descripcion = document.getElementById('descripcion-editar').value;

            if(nombre === ''){
                toastr.error('Diagnostico es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);

            axios.post(url+'/diagnostico/editar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }

    </script>


@endsection
