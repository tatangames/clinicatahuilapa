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
            <div class="card card-success">
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

                                        <div class="col-md-12">
                                            <div class="card-body">


                                                <div class="form-group row" >
                                                    <label class="col-sm-2 col-form-label" style="color: #686868"># Expediente</label>
                                                    <span class="text-danger">*</span>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input type="text" style="margin-left: 8px" maxlength="100" autocomplete="off"
                                                                   class="form-control" id="numero-expediente-nuevo">
                                                        </div>
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


                                                <div class="form-group row" >
                                                    <label class="col-sm-2 col-form-label" style="color: #686868">Apellido: </label>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input type="text" style="margin-left: 8px" maxlength="150" autocomplete="off"
                                                                   class="form-control" id="apellido-nuevo">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row" style="margin-top: 22px">
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
                                                <select id="select-sexo" class="form-control">
                                                    <option value="">Seleccione...</option>
                                                    <option value="1">Masculino</option>
                                                    <option value="2">Femenino</option>
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
                                                <input type="text" maxlength="10" autocomplete="off"
                                                       class="form-control" id="telefono">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Celular: </label>
                                            <div class="col-md-9">
                                                <input type="text" maxlength="10" autocomplete="off" class="form-control" id="celular" >
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
                                                    <select  id="select-civil" class="form-control">
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
                                                <select id="select-documento" class="form-control">
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
                                                <select  id="select-profesion" class="form-control">
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



                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row ">


                                        <div class="col-md-6">



                                            <div class="form-group">
                                                <label>Subir Foto (Opcional)</label>
                                                <input type="file" id="documento" class="form-control" accept="image/jpeg, image/jpg, image/png"/>
                                            </div>




                                        </div>

                                        <div class="col-md-6">

                                            <div class="col-md-offset-3 col-md-12" align="center">
                                                <button type="button" class="btn btn-success" onclick="registrar();">Registrar Nuevo Expediente</button>
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



    <!-- MODAL FOTOGRAFIA -->

    <div class="modal fade" id="modalFotografia">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">FOTOGRAFIA PACIENTE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-fotografia">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">

                                    <div id="my_camera"></div>

                                    <br/>

                                    <input type=button value="Tomar Captura" onClick="tomarCapturaWebCam()">

                                    <input type="hidden" name="image" class="image-tag">

                                </div>

                                <div class="col-md-6">

                                    <div id="results">La Fotografía aparecera aquí</div>

                                </div>

                                <div class="col-md-12 text-center">

                                    <br/>

                                    <button class="btn btn-success" data-dismiss="modal">Cerrar Vista</button>

                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>



</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/webcam.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#select-profesion').select2({
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

        // VARIABLE GLOBAL PARA ALMACENAR IMAGEN WEBCAM
        let fileImagenWebCam;



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

            // CALCULAR LOS MESES SI ES 0 ANIOS
            if(edad == 0){

                // Supongamos que tienes la fecha de nacimiento en formato YYYY-MM-DD
                var fechaCalcular = new Date(fechaNacimiento);

                // Obtener la fecha actual
                var fechaActual = new Date();

                // Calcular la diferencia en milisegundos
                var diferenciaMilisegundos = fechaActual - fechaCalcular;

                // Convertir la diferencia a meses
                let resultado = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24 * 30.44));
                edad = "Meses: " + resultado;
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

            var numExpediente = document.getElementById('numero-expediente-nuevo').value;


            // subir foto
            var documento = document.getElementById('documento');



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

            if (selectProfesion === '') {
                toastr.error('Profesión es requerido');
                return;
            }

            if(numExpediente === ''){
                toastr.error('# Expediente es Requerido');
                return;
            }

            openLoading();
            var formData = new FormData();


            //formData.append('documento', fileImagenWebCam);

            formData.append('documento', documento.files[0]);
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
            formData.append('numdocumento', numeroDocumento);
            formData.append('correo', correo);
            formData.append('referido', referido);
            formData.append('profesion', selectProfesion);
            formData.append('numexpediente', numExpediente);

            axios.post(url + '/expediente/registro', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {


                        Swal.fire({
                            title: 'Expediente Repetido',
                            text: 'Un Paciente ya tiene Registrado el Expediente: ' + numExpediente,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else if (response.data.success === 2) {
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

            document.getElementById('select-tipopaciente').selectedIndex = 0;
            $("#select-tipopaciente").trigger("change");

            document.getElementById('edad').value = '';
            document.getElementById('select-profesion').selectedIndex = 0;
            $("#select-profesion").trigger("change");

            document.getElementById('select-civil').selectedIndex = 0;
            $("#select-civil").trigger("change");

            document.getElementById('select-documento').selectedIndex = 0;
            $("#select-documento").trigger("change");

            btnBorrarFoto();
        }


        function abrirModalFoto(){

            Webcam.set({
                width: 490,
                height: 350,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            Webcam.attach( '#my_camera' );
            $('#modalFotografia').modal('show');
        }


        function tomarCapturaWebCam(){

            Webcam.snap( function(dataURI) {
            const blob = dataURItoBlob(dataURI);

            // Crear un objeto File a partir del Blob
            const file = new File([blob], "nombre_del_archivo.jpg", { type: blob.type });

            fileImagenWebCam = file;

                $(".image-tag").val(dataURI);

                document.getElementById('results').innerHTML = '<img id="idFotoWebCam" src="'+dataURI+'"/> <center>' +
                    '<button onclick="btnBorrarFoto()" id="idBtnBorrarFoto" style="margin-top: 15px" class="btn btn-danger" type="button">Borrar Foto</button></center>';
            });
        }


        // convertir URI de imagen webcam a un archivo BLOB
        function dataURItoBlob(dataURI) {
            // Separar el encabezado del contenido de la data URI
            const byteString = atob(dataURI.split(',')[1]);

            // Obtener el tipo de contenido del archivo desde la data URI
            const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

            // Convertir la cadena de bytes en un array de enteros sin signo
            const arrayBuffer = new ArrayBuffer(byteString.length);
            const uint8Array = new Uint8Array(arrayBuffer);

            for (let i = 0; i < byteString.length; i++) {
                uint8Array[i] = byteString.charCodeAt(i);
            }

            // Crear un objeto Blob a partir del array de enteros sin signo
            return new Blob([arrayBuffer], { type: mimeString });
        }



        // borrar fotografia del modal
        function btnBorrarFoto(){

            var elemento = document.getElementById('idFotoWebCam');

            if (elemento) {
                document.getElementById('idFotoWebCam').removeAttribute('src');
                document.getElementById('idBtnBorrarFoto').style.display = "none";
            }

            fileImagenWebCam = null;
        }



    </script>

@endsection
