@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table {
        /*Ajustar tablas*/
        table-layout: fixed;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Expediente</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row">

                                        <div class="col-md-6">


                                            <form>
                                                <div class="card-body">

                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label" style="color: #686868">Medico</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" id="select-medico">
                                                                <option value="0">medico 1</option>
                                                                <option value="1">medico 2</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row" style="margin-top: 35px">
                                                        <label class="col-sm-2 col-form-label" style="color: #686868">Nombre</label>
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <input type="text" maxlength="150" autocomplete="off" class="form-control" id="nombre-nuevo">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" style="margin-top: 0px">
                                                        <label class="col-sm-5 col-form-label" style="color: #686868">Fecha de Nacimiento</label>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <input type="date" class="form-control" id="fechanacimiento-nuevo">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>


                                            </form>


                                        </div>


                                        <div class="col-md-6">

                                            <div class="card-body">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Tipo Paciente: </label>
                                                    <div class="col-md-9">
                                                        <select class="form-control" id="select-tipopaciente">
                                                            <option value="0">xxxxx</option>
                                                            <option value="1">xxxxx</option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="form-group row" style="margin-top: 0px">
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Apellido</label>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input type="text" maxlength="150" autocomplete="off" class="form-control" id="apellido-nuevo">
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
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var ruta = "{{ URL::to('/admin/equipos/tabla/index') }}";
            $('#tablaDatatable').load(ruta);

            $('#select-medico').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-tipopaciente').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });

            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        function recargar() {
            var ruta = "{{ url('/admin/equipos/tabla/index') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar() {
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo() {
            var codigo = document.getElementById('codigo-nuevo').value;
            var descripcion = document.getElementById('descripcion-nuevo').value;
            var placa = document.getElementById('placa-nuevo').value;

            if (codigo === '') {
                toastr.error('Codigo es requerido');
                return;
            }
            if (descripcion === '') {
                toastr.error('Descripcion es requerido');
                return;
            }

            if (placa.length > 25) {
                toastr.error('Placa máximo 25 caracteres');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('codigo', codigo);
            formData.append('descripcion', descripcion);
            formData.append('placa', placa);

            axios.post(url + '/equipos/nuevo', formData, {})
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }

        function informacion(id) {
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url + '/equipos/informacion', {
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(response.data.lista.id);
                        $('#codigo-editar').val(response.data.lista.codigo);
                        $('#descripcion-editar').val(response.data.lista.descripcion);
                        $('#placa-editar').val(response.data.lista.placa);

                    } else {
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar() {
            var id = document.getElementById('id-editar').value;
            var codigo = document.getElementById('codigo-editar').value;
            var descripcion = document.getElementById('descripcion-editar').value;
            var placa = document.getElementById('placa-editar').value;

            if (codigo === '') {
                toastr.error('Codigo es requerido');
                return;
            }
            if (descripcion === '') {
                toastr.error('Descripcion es requerido');
                return;
            }


            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('codigo', codigo);
            formData.append('descripcion', descripcion);
            formData.append('placa', placa);

            axios.post(url + '/equipos/editar', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    } else {
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
