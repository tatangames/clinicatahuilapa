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

    <section class="content" style="margin-top: 30px">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">ORDEN DE SALIDA RECETA MÉDICA</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                            <div class=" col-md-2">
                                <label>Estados</label>
                                <select class="form-control" id="select-estado" onchange="verificarEstado()">
                                    <option value="1">Pendiente</option>
                                    <option value="2">Procesado</option>
                                    <option value="3">Denegado</option>
                                </select>
                            </div>

                            <div class=" col-md-2">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha-inicio" autocomplete="off" onchange="verificarEstado()">
                            </div>

                            <div class=" col-md-2">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha-fin" autocomplete="off" onchange="verificarEstado()">
                            </div>
                    </div>

                    <div id="tablaDatatable" style="margin-top: 20px">
                    </div>

                </div>
            </div>
        </div>
    </section>



    <div class="modal fade" id="modalDenegar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Denegar Receta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-denegar">
                        <div class="card-body">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Paciente</label>
                                    <input type="text" class="form-control" disabled id="paciente-denegar" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Recetado Por</label>
                                    <input type="text" class="form-control" disabled id="doctor-denegar" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="hidden" id="id-denegar">
                                    <input type="text" class="form-control" maxlength="500"  class="form-control" id="descripcion-denegar" autocomplete="off">
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarDenegado()">Guardar</button>
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

    <script type="text/javascript">
        $(document).ready(function(){



            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function verificarEstado(){

            let estado = document.getElementById("select-estado").value;
            let fechainicio = document.getElementById("fecha-inicio").value;
            let fechafin = document.getElementById("fecha-fin").value;

            if(fechainicio === ''){
                document.getElementById("tablaDatatable").innerHTML = "";
                return;
            }

            if(fechafin === ''){
                document.getElementById("tablaDatatable").innerHTML = "";
                return;
            }

            openLoading();

            // CARGAR TABLA
            var ruta = "{{ URL::to('/admin/salida/medicamento/porreceta/tabla') }}/" + estado + "/" + fechainicio + "/" + fechafin;
            $('#tablaDatatable').load(ruta);
        }


        function procesarRecetaMedica(idreceta){
            window.location.href="{{ url('/admin/vista/procesar/recetamedica') }}/" + idreceta;
        }


        function infoDenegarReceta(idreceta){

            openLoading();
            document.getElementById("formulario-denegar").reset();

            axios.post(url+'/orden/salida/informacion/paradenegar',{
                'id': idreceta
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-denegar').val(idreceta);
                        $('#paciente-denegar').val(response.data.paciente);
                        $('#doctor-denegar').val(response.data.doctor);

                        $('#modalDenegar').modal('show');
                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }


        function guardarDenegado(){

            let idreceta = document.getElementById("id-denegar").value;
            let descripcion = document.getElementById("descripcion-denegar").value;

            if(descripcion === ''){
                toastr.error('Descripción es requerida');
                return;
            }

            openLoading();

            console.log(idreceta);

            var formData = new FormData();
            formData.append('id', idreceta);
            formData.append('descripcion', descripcion);

            axios.post(url+'/orden/salida/guardar/denegacion', formData, {
            })
                .then((response) => {
                    closeLoading();
                    $('#modalDenegar').modal('hide');


                    if(response.data.success === 1){

                        // ya esta procesada

                        Swal.fire({
                            title: 'Error',
                            text: 'La Receta ya estaba Procesada',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                verificarEstado();
                            }
                        })

                    }
                    else if(response.data.success === 2) {

                        // ya esta denegada
                        Swal.fire({
                            title: 'Error',
                            text: 'La Receta ya estaba Denegada',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                verificarEstado();
                            }
                        })
                    }

                    else if(response.data.success === 3) {

                        toastr.success('Receta Actualizada');
                        verificarEstado();
                    }
                    else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }




    </script>


@endsection
