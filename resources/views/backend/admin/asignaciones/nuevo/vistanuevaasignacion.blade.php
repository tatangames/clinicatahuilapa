@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
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
        <div class="row mb-2">
            <div class="col-sm-6">

            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Paciente</li>
                    <li class="breadcrumb-item active">Listado de Pacientes</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">TODOS LOS EXPEDIENTES</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">








                            <div class="" aria-expanded="true">
                                <div class="panel-body">
                                    <div id="div_salas">
                                        <div class=" col-12 col-sm-3 col-md-3 col-lg-3">
                                            <h3 style="font-weight: bold;">Sala de espera</h3>
                                            <div class="col-md-12 white-box" style="border-radius: 15px;">
                                                <div class="col-md-12">
                                                    <div class="text-center" style="margin-bottom: 10px; padding-left: 0px;align-items: center; ">
                                                        <h3> Sin Pacientes </h3>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        <script>
                                            function eliminar_cola2(paciente,razon,sala,servicio,id_asignacion_cola){
                                                $.ajax({
                                                    url: 'Index/eliminar_cola',
                                                    type: "POST",
                                                    data: {paciente:paciente,razon:razon,sala:sala,servicio:servicio,id_asignacion_cola:id_asignacion_cola},
                                                    beforeSend: function(){
                                                        $('#modal').html(statement_loading());
                                                    },
                                                    complete: function(){
                                                        $('#modal').html();
                                                    },
                                                    success: function (response) {
                                                        if(response == 0){
                                                            swal({
                                                                title: 'Exito!',
                                                                text: 'Paciente eliminado de cola!',
                                                                type: 'success',
                                                                confirmButtonColor: '#ff9933'
                                                            }).then( function() {
                                                                cerrar_modal();cargar_salas(); });
                                                        }else {
                                                            swal({
                                                                title: 'Oops...',
                                                                text: response,
                                                                type: 'error',
                                                                confirmButtonColor: '#4fb7fe'
                                                            }).done();
                                                            cerrar_modal();
                                                        }
                                                    }
                                                });
                                            }
                                            function trasladar_cola2(id_asignacion_cola){
                                                $.ajax({
                                                    url: '../enfermeria/Index/trasladar_cola',
                                                    type: "POST",
                                                    data: {id_asignacion_cola:id_asignacion_cola},
                                                    beforeSend: function(){
                                                        $('#modal').html(statement_loading());
                                                        $('#modal').modal({backdrop: 'static', keyboard: false});
                                                    },
                                                    complete: function(){
                                                        $('#modal').html();
                                                    },
                                                    success: function (response) {
                                                        $('#modal').html(response);
                                                    }
                                                });
                                            }
                                        </script>
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-9">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-4 col-xs-12">
                                                    <div class="input-group m-t-10" style="cursor:pointer;">
                                                        <span class="btn btn-info btn-block" onclick="ver_cola(0,2)" style="background:#03a9f3">En espera 0</span>
                                                        <span class="input-group-btn">
                <span class="btn waves-effect waves-light btn-warning" onclick="opciones_sala('si',2)"><i class="fa fa-plus"></i></span>
              </span>
                                                    </div>

                                                    <div style="background:#00cc66; min-height: 205px;">
                                                        <div style="padding: 0px; text-align: center;">
                                                            <!--<a href="https://samucloud.app/clinicatahuilapa/clinica/historial_clinico?id=">-->

                                                            <a>
                                                                <div class="user-header">
                                                                    <h5 class="text-black" style="margin: 0px; padding: 5px;"><strong>Enfermeria</strong></h5>
                                                                </div>
                                                            </a><div class="user-content"><a>
                                                                </a><a href="javascript:;"><img onclick="" alt="img" width="200px" height="200px" class="thumb-lg img-circle" src="https://samucloud.app/clinicatahuilapa/assets/images/iconos/Consultorio_icon.png"></a>
                                                            </div>
                                                            <div class="user-footer">
                                                                <h5 class="text-black"><strong>Paciente: (No asignado)</strong></h5>
                                                                <h5 class="text-black"><strong>Médico: (No asignado)</strong></h5>
                                                                <h5 class="text-black"><strong>Asignado Por: (No asignado)</strong></h5>
                                                            </div>

                                                            <button class="btn btn-info" onclick="opciones_sala(0,2);" style="width: 100%;" disabled="">Opciones</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-4 col-xs-12">
                                                    <div class="input-group m-t-10" style="cursor:pointer;">
                                                        <span class="btn btn-info btn-block" onclick="ver_cola(5,3)" style="background:#ab47bc">En espera 5</span>
                                                        <span class="input-group-btn">
                <span class="btn waves-effect waves-light btn-warning" onclick="opciones_sala('si',3)"><i class="fa fa-plus"></i></span>
              </span>
                                                    </div>

                                                    <div style="background:#00cc66; min-height: 205px;">
                                                        <div style="padding: 0px; text-align: center;">
                                                            <!--<a href="https://samucloud.app/clinicatahuilapa/clinica/historial_clinico?id=">-->

                                                            <a>
                                                                <div class="user-header">
                                                                    <h5 class="text-black" style="margin: 0px; padding: 5px;"><strong>Consultorio</strong></h5>
                                                                </div>
                                                            </a><div class="user-content"><a>
                                                                </a><a href="javascript:;"><img onclick="" alt="img" width="200px" height="200px" class="thumb-lg img-circle" src="https://samucloud.app/clinicatahuilapa/assets/images/iconos/Consultorio_icon.png"></a>
                                                            </div>
                                                            <div class="user-footer">
                                                                <h5 class="text-black"><strong>Paciente: (No asignado)</strong></h5>
                                                                <h5 class="text-black"><strong>Médico: (No asignado)</strong></h5>
                                                                <h5 class="text-black"><strong>Asignado Por: (No asignado)</strong></h5>
                                                            </div>

                                                            <button class="btn btn-info" onclick="opciones_sala(0,3);" style="width: 100%;" disabled="">Opciones</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function atender_paciente(idasignacion_sala=0,idexpediente=0){
                                                $.ajax({
                                                    url: 'Index/atender_paciente',
                                                    type: "POST",
                                                    data: {idasignacion_sala:idasignacion_sala},
                                                    success: function (response) {
                                                        window.location.href = base_url+"clinica/historial_clinico?id="+idexpediente;
                                                    }
                                                });
                                            }
                                        </script>
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

        function recargar(){
            var ruta = "{{ URL::to('/admin/expediente/tabla/buscar') }}";
            $('#tablaDatatable').load(ruta);
        }



    </script>


@endsection
