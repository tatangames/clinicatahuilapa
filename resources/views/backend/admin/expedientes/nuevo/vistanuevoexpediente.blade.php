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

                            <form id="formulario">

                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="card-body">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Medico: </label>
                                                    <span class="text-danger">*</span>
                                                    <div class="col-md-9">
                                                        <select class="form-control" id="select-medico">
                                                            @foreach($arrayMedicos as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }} {{ $item->apellido }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="form-group row" style="margin-top: 35px">
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Nombre: </label>
                                                    <span class="text-danger">*</span>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input type="text" maxlength="150" autocomplete="off"
                                                                   class="form-control" id="nombre-nuevo">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


                                        </div>


                                        <div class="col-md-6">

                                            <div class="card-body">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Tipo
                                                        Paciente: </label>
                                                    <span class="text-danger">*</span>
                                                    <div class="col-md-9">
                                                        <select class="form-control" id="select-tipopaciente">
                                                            @foreach($arrayTipoPaciente as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="form-group row" >
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Apellido: </label>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input type="text" maxlength="150" autocomplete="off"
                                                                   class="form-control" id="apellido-nuevo">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </section>


                            <section>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Fecha nacimiento:<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-7">
                                                <input type="date" id="fecha-nacimiento" class="form-control"
                                                       onchange="calcular_edad();">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Edad: </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="edad" value=""
                                                       disabled="disabled">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Sexo: <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <select name="sexo" id="select-sexo" class="form-control">
                                                    <option value="">Seleccione...</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>



                            <section style="margin-top: 25px">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Teléfono: </label>
                                            <div class="col-md-7">
                                                <input type="text" maxlength="25" autocomplete="off"
                                                       class="form-control" id="telefono">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Celular: </label>
                                            <div class="col-md-9">
                                                <input type="text" maxlength="25" autocomplete="off" class="form-control" id="celular" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Dirección: </label>
                                            <div class="col-md-9">
                                                <textarea maxlength="500" id="direccion" cols="17" rows="2" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>



                            <section style="margin-top: 25px">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Estado civil: <span
                                                    class="text-danger">*</span> </label>
                                                <div class="col-md-7">
                                                    <select name="sexo" id="select-civil" class="form-control">
                                                        @foreach($arrayEstadoCivil as $item)
                                                            <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-6">Tipo de Documento: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-md-9">
                                                <select name="sexo" id="select-documento" class="form-control">
                                                    @foreach($arrayTipoDocumento as $item)
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Num. Documento:
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-7">
                                                <input type="text" maxlength="100" autocomplete="off"
                                                       class="form-control" id="numero-documento">
                                            </div>
                                        </div>
                                    </div>



                                </div>

                            </section>



                            <section style="margin-top: 25px">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Profesión:  </label>
                                            <div class="col-md-7">
                                                <select name="sexo" id="select-profesion" class="form-control">
                                                    @foreach($arrayProfesion as $item)
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-6">Correo electrónico:  </label>
                                            <div class="col-md-9">
                                                <input type="text" maxlength="150" autocomplete="off" class="form-control" id="correo" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Referido por: </label>
                                            <div class="col-md-9">
                                                <input type="text" maxlength="300" autocomplete="off" class="form-control" id="referido" >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>

                            </form>

                            <br>
                            <hr>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-12" align="center">
                                                <button type="button" class="btn btn-success" onclick="registrar();">Registrar</button>
                                            </div>
                                        </div>
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
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#select-medico').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-tipopaciente').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        function calcular_edad() {

            var fechaNacimiento = document.getElementById('fecha-nacimiento').value;

            if(fechaNacimiento === ''){
                return;
            }


            var hoy = new Date();
            var cumpleanos = new Date(fechaNacimiento);
            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
            var m = hoy.getMonth() - cumpleanos.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                edad--;
            }

            var inputEdad = document.getElementById("edad");
            inputEdad.value = edad;
        }

        function modalAgregar() {
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function registrar() {
            Swal.fire({
                title: 'Registrar Paciente',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    nuevoPaciente();
                }
            })
        }


        function nuevoPaciente(){

            var selectMedico = document.getElementById('select-medico').value; //*
            var nombre = document.getElementById('nombre-nuevo').value; //*
            var selectTipoPaciente = document.getElementById('select-tipopaciente').value; //*
            var apellido = document.getElementById('apellido-nuevo').value;
            var fechaNacimiento = document.getElementById('fecha-nacimiento').value; //*
            var selectSexo = document.getElementById('select-sexo').value; //*
            var telefono = document.getElementById('telefono').value;
            var celular = document.getElementById('celular').value;
            var direccion = document.getElementById('direccion').value;
            var selectCivil = document.getElementById('select-civil').value; //*
            var selectDocumento = document.getElementById('select-documento').value; //*
            var numeroDocumento = document.getElementById('numero-documento').value; //*
            var correo = document.getElementById('correo').value;
            var referido = document.getElementById('referido').value;
            var selectProfesion = document.getElementById('select-profesion').value; //*


            if (selectMedico === '') {
                toastr.error('Médico es requerido');
                return;
            }

            if (nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            if (selectTipoPaciente === '') {
                toastr.error('Tipo de Paciente es requerido');
                return;
            }

            if (fechaNacimiento === '') {
                toastr.error('Fecha de nacimiento es requerido');
                return;
            }

            if (selectSexo === '') {
                toastr.error('Sexo del paciente es requerido');
                return;
            }

            if (selectCivil === '') {
                toastr.error('Estado civil es requerido');
                return;
            }

            if (selectDocumento === '') {
                toastr.error('Tipo de documento es requerido');
                return;
            }

            if (numeroDocumento === '') {
                toastr.error('Número de documento es requerido');
                return;
            }

            if (selectProfesion === '') {
                toastr.error('Profesión es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('medico', selectMedico);
            formData.append('nombre', nombre);
            formData.append('tipopaciente', selectTipoPaciente);
            formData.append('apellido', apellido);
            formData.append('fechanacimiento', fechaNacimiento);
            formData.append('sexopaciente', selectSexo);
            formData.append('telefono', telefono);
            formData.append('celular', celular);
            formData.append('direccion', direccion);
            formData.append('estadocivil', selectCivil);
            formData.append('tipodocumento', selectDocumento);
            formData.append('documento', numeroDocumento);
            formData.append('correo', correo);
            formData.append('referido', referido);
            formData.append('profesion', selectProfesion);


            axios.post(url + '/expediente/registro', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Registrado correctamente');
                        borrarCampos();
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }

        function borrarCampos(){

            document.getElementById("formulario").reset();

            document.getElementById('select-medico').selectedIndex = 0;
            document.getElementById('select-tipopaciente').selectedIndex = 0;
            document.getElementById('edad').value = '';
            document.getElementById('select-profesion').selectedIndex = 0;
            document.getElementById('select-civil').selectedIndex = 0;
            document.getElementById('select-documento').selectedIndex = 0;

        }


    </script>

@endsection
