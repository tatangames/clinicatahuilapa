@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    #card-header-color {
        background-color: cyan !important;
    }

    .cursor-pointer:hover {
        cursor: pointer;
        color: #401fd2;
        font-weight: bold;
    }

</style>

<div id="divcontenedor" style="display: none">


    <section class="content-header">
        <div class="row mb-2">

        </div>
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ASIGNACIÓN DE SALAS</h3>
                    <p style="float: right; font-weight: bold; font-size: 15px" id="contador">10</p>
                    <img src="{{ asset('images/cronometro2.png') }}" width="35px" height="35px" style="float: right">
                </div>

                <section class="content-header" style="margin-left: 25px">
                    <div class="row mb-2">

                        <button type="button" style="font-weight: bold; background-color: #36b62e; color: white !important;"
                                onclick="modalAgregar()" class="button button-3d button-rounded button-pill button-small">
                            <i class="fas fa-pencil-alt"></i>
                            Nueva Asignación
                        </button>

                    </div>
                </section>


                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row ">


                                        <div class="col-md-6" id="bloque01enfermeria">

                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h3 class="card-title" id="txtConteoEnfermeria">Enfermería ( {{ $conteoEnfermeria }} en Espera )</h3>
                                                    <span class="input-group-btn" style="float: right">
                                                        <span class="btn waves-effect waves-light btn-primary"  onclick="modalTablaEnfermeria()">
                                                            <i class="fa fa-plus" style="color: white">Asignar</i>
                                                        </span>
                                                    </span>

                                                </div>
                                                <div class="card-body">
                                                    <p class="form-control" style="font-weight: bold" id="paciente-enfermeria">{{ $arrayPaciente['salaEnfermeriaPaciente'] }}</p>
                                                </div>

                                                <div class="small-box bg-info">
                                                    <button id="opciones-enfermeria" class="btn btn-info" style="width: 100%;" disabled="" onclick="buscarFichaAdministrativaModal(2)">OPCIONES <i class="fas fa-arrow-circle-right"></i></button>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6" id="bloque02consultoria">

                                            <div class="card card-success">
                                                <div class="card-header">
                                                    <h3 class="card-title" id="txtConteoConsultoria">Consultorio ( {{ $conteoConsultorio }} en Espera )</h3>

                                                    <span class="input-group-btn" style="float: right">
                                                        <span class="btn waves-effect waves-light btn-primary" onclick="modalTablaConsultoria()">
                                                            <i class="fa fa-plus" style="color: white">Asignar</i>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="card-body">
                                                    <p class="form-control" style="font-weight: bold" id="paciente-consultorio">{{ $arrayPaciente['salaConsultorioPaciente'] }}</p>

                                                </div>

                                                <div class="small-box bg-info">
                                                    <button id="opciones-consultorio" class="btn btn-info" style="width: 100%;" disabled="" onclick="buscarFichaAdministrativaModal(1)">OPCIONES <i class="fas fa-arrow-circle-right"></i></button>
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


    <!-- MODAL PARA GUARDAR NUEVA ASIGNACION -->
    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">ASIGNACION A SALA DE ESPERA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <p>Buscar por: nombre, apellido, número de documento</p>
                                        <p>La búsqueda regresa: # Expediente - Nombre y Apellido - Documento</p>
                                        <table class="table" id="matriz-busqueda" data-toggle="table">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input id="repuesto" data-info='0' autocomplete="off" class='form-control' style='width:100%' onkeyup='buscarPaciente(this)' maxlength='400'  type='text'>
                                                    <div class='droplista' style='position: absolute; color: black !important; z-index: 9; width: 75% !important;'></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px">
                                        <label class="control-label">Razón de Uso: </label>
                                        <select id="select-razon" class="form-control">
                                            <option value="">Seleccione una Razón del uso</option>
                                            @foreach($arrayRazonUso as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr>

                                    <div class="form-group" style="margin-top: 30px">
                                        <label class="control-label">Sala de Espera: </label>
                                        <select id="select-salaespera" class="form-control">
                                            <option value="">Seleccione una Sala</option>
                                            @foreach($arraySalaEspera as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="preguntaGuardar()">Guardar</button>
                </div>
            </div>
        </div>
    </div>




    <!-- MODAL TABLA PARA MOSTRAR PACIENTES EN ESPERA PARA LA SALA: ENFERMERIA -->

    <div class="modal fade" id="modalTablaEnfermeria">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">PACIENTES EN ESPERA (ENFERMERIA)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-tabla-enfermeria">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div id="tablaDatatableEnfermeria">
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>





    <!-- MODAL TABLA PARA MOSTRAR PACIENTES EN ESPERA PARA LA SALA: CONSULTORIA -->

    <div class="modal fade" id="modalTablaConsultoriaModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">PACIENTES EN ESPERA (CONSULTORIA)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-tabla-consultoria">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div id="tablaDatatableConsultoria">
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <!-- MODAL EDITAR, UTILIZADO POR ENFERMERIA Y CONSULTORIA -->

    <div class="modal fade" id="modalTablaEditarSalas">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">TRASLADO DE COLA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-editar-traslado">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <input type="hidden" id="id-traslado-cola">
                                    </div>


                                    <div class="form-group">
                                        <label style="color:#191818">Sala actual</label>
                                        <br>
                                        <div>
                                            <select class="form-control" id="select-editar-salaactual">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label style="color:#191818">Razón de uso</label>
                                        <br>
                                        <div>
                                            <select class="form-control" id="select-editar-razonuso">
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarAjustesEditados()">Guardar</button>

                </div>
            </div>
        </div>
    </div>




    <!-- FICHA ADMINISTRATIVA CUANDO UN PACIENTE ESTA EN LA SALA. UTILIZADO PARA LAS 2 SALAS-->

    <div class="modal fade" id="modalFichaAdministrativa">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">FICHA ADMINISTRATIVA DE INGRESO</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-ficha-administrativa">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="card-body">

                                                    <center><h3 id="textofoto"><strong>FOTOGRAFIA</strong></h3></center>
                                                    <div class="list-group mail-list m-t-20" align="center">
                                                        <center><img id="foto-paciente-ficha" class="thumb" alt="" width="120px" height="120px" style="border: 2px solid black;">
                                                        </center>
                                                    </div>
                                                    <h3 class="panel-title m-t-40 m-b-0">
                                                        <center><strong>OPCIONES</strong></center>
                                                    </h3>
                                                    <hr class="m-t-5">
                                                    <div class="list-group b-0 mail-list" id="configuracion">
                                                        <input type="button" onclick="vistaAtencionEnfermeria();" class="btn btn-info btn-block waves-effect waves-light" value="Atencion enfermería">
                                                        <input type="button" onclick="orden_salida();" class="btn btn-primary btn-block waves-effect waves-light" value="Farmacia">
                                                        <input type="button" onclick="historial_medico();" class="btn btn-success btn-block waves-effect waves-light" value="Historial clinico">
                                                        <button type="button" onclick="trasladoPacienteModal();" class="btn btn-warning btn-block waves-effect waves-light" style="color: white">Traslado</button>
                                                        <button type="button" onclick="preguntaLiberarSala()" class="btn btn-danger btn-block waves-effect waves-light">Liberar sala</button>
                                                    </div>
                                            </div>
                                        </div>



                                        <div class="col-md-6">
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <input type="hidden" id="idpacientemodal-dentrosala">
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label col-md-12">PACIENTE </label>
                                                        <input type="text" id="txtNombre" autocomplete="off"
                                                               class="form-control" disabled>
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label col-md-12">HORA ENTRO SALA DE ESPERA</label>
                                                    <input type="text" id="txtHoraEntroEspera" autocomplete="off"
                                                           class="form-control" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-12">HORA ENTRO A SALA</label>
                                                    <input type="text" id="txtHoraEntro" autocomplete="off"
                                                           class="form-control" disabled>
                                                </div>


                                                <div class="form-group">
                                                    <label style="color:#191818">RAZON DE USO</label>
                                                    <br>
                                                    <div>
                                                        <select class="form-control" id="select-dentrosala-razonuso" disabled>
                                                        </select>
                                                    </div>
                                                </div>


                                                <hr>

                                                <div id="razonuso-btn-editar" style="display: block">
                                                <center>
                                                    <button type="button" class="btn btn-warning" style="color: white" onclick="modificarSelectRazonUso()">Editar Razón de uso</button>
                                                </center>
                                                </div>

                                                <div id="razonuso-btn-guardar" style="display: none">
                                                    <center>
                                                        <button type="button" class="btn btn-success" style="color: white" onclick="actualizarSelectRazonUso()">Actualizar</button>
                                                    </center>
                                                </div>


                                            </div>
                                        </div>


                                </div>
                            </div>

                        </section>

                    </form>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>






    <!-- MODAL PARA TRASLADAR UN PACIENTE QUE ESTA DENTRO DE UNA SALA -->

    <div class="modal fade" id="modalTrasladoPaciente">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">TRASLADO DE PACIENTES</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="formulario-infotraslado">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <input type="hidden" id="id-trasladopaciente">
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-12">SALA ACTUAL</label>
                                        <input type="text" id="txtSalaActual-info" autocomplete="off"
                                               class="form-control" disabled>
                                    </div>


                                    <div class="form-group">
                                        <label style="color:#191818">Sala a Asignar</label>
                                        <br>
                                        <div>
                                            <select class="form-control" id="select-sala-asignar">

                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label style="color:#191818">Razón de uso</label>
                                        <br>
                                        <div>
                                            <select class="form-control" id="select-razouso-v2">

                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarNuevoTraslado()">Guardar</button>

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
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/loadingOverlay.js') }}" type="text/javascript"></script>


    <script type="text/javascript">
        $(document).ready(function(){

            $('#select-paciente').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            window.seguroBuscador = true;
            window.txtContenedorGlobal = this;

            window.idPacienteGlobal = 0;

            $(document).click(function(){
                $(".droplista").hide();
            });

            validarBotonOpciones();

            countdown();

            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        // valida botones de Opciones al recargar esta vista
        function validarBotonOpciones(){

            let btnConsultoria = {{ $arrayPaciente['botonOpcionConsultoria'] }};
            let btnEnfermeria = {{ $arrayPaciente['botonOpcionEnfermeria'] }};

            if(btnConsultoria > 0){
                document.getElementById("opciones-consultorio").disabled = false;
            }else{
                document.getElementById("opciones-consultorio").disabled = true;
            }


            if(btnEnfermeria > 0){
                document.getElementById("opciones-enfermeria").disabled = false;
            }else{
                document.getElementById("opciones-enfermeria").disabled = true;
            }
        }

        function recargarPacientesEspera(){
            let ruta = "{{ URL::to('/admin/asignaciones/paciente/esperando') }}";
            $('#tablaDatatable').load(ruta);
        }

        function verificarSalaEspera(){

            if(hayDatos>0){
                // habilitar tabla y cargar datos
                document.getElementById("contenedorSalaEspera").style.display = "block";
            }else{
                document.getElementById("contenedorSalaEspera").style.display = "none";
            }
        }

        function countdown() {
            var seconds = 60;
            function tick() {
                var counter = document.getElementById("contador");
                seconds--;
                counter.innerHTML = "0:" + (seconds < 10 ? "0" : "") + String(seconds);
                if( seconds > 0 ) {
                    setTimeout(tick, 1000);
                } else {
                    recargarPaginaCronometro();
                    countdown();
                }
            }
            tick();
        }

        function recargarPaginaCronometro(){


            var spinHandle = loadingOverlay().activate();

            document.getElementById("bloque01enfermeria").style.display = "none";
            document.getElementById("bloque02consultoria").style.display = "none";

            axios.post(url+'/asignaciones/recargando/cronometro',{

            })
                .then((response) => {
                    loadingOverlay().cancel(spinHandle);
                    if(response.data.success === 1){

                        let btnConsultoria = response.data.arraypaciente['botonOpcionConsultoria'];
                        let btnEnfermeria = response.data.arraypaciente['botonOpcionEnfermeria'];

                        if(btnConsultoria > 0){
                            document.getElementById("opciones-consultorio").disabled = false;
                        }else{
                            document.getElementById("opciones-consultorio").disabled = true;
                        }


                        if(btnEnfermeria > 0){
                            document.getElementById("opciones-enfermeria").disabled = false;
                        }else{
                            document.getElementById("opciones-enfermeria").disabled = true;
                        }

                        let conteoEnfermeria = response.data.conteoEnfermeria;
                        let conteoConsultoria = response.data.conteoConsultorio;

                        let textoEnfermeria = "Enfermería ( " + conteoEnfermeria + " en Espera )";
                        let textoConsultoria = "Consultoria ( " + conteoConsultoria + " en Espera )";

                        document.getElementById("txtConteoEnfermeria").innerHTML = textoEnfermeria;
                        document.getElementById("txtConteoConsultoria").innerHTML = textoConsultoria;

                        document.getElementById("paciente-enfermeria").innerHTML = response.data.arraypaciente['salaEnfermeriaPaciente'];
                        document.getElementById("paciente-consultorio").innerHTML = response.data.arraypaciente['salaConsultorioPaciente'];


                        volverMostrarBloques();

                    }else{
                        loadingOverlay().cancel(spinHandle);
                        volverMostrarBloques();
                        toastr.error('Error al recargar');
                    }
                })
                .catch((error) => {
                    loadingOverlay().cancel(spinHandle);
                    volverMostrarBloques();
                    toastr.error('Error al recargar');
                });
        }

        function volverMostrarBloques(){
            document.getElementById("bloque01enfermeria").style.display = "block";
            document.getElementById("bloque02consultoria").style.display = "block";
        }

        // abre modal para agregar una nueva asignacion
        function modalAgregar(){

            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }


        // buscar paciente en el buscador
        function buscarPaciente(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-info', 0);
                    idPacienteGlobal = 0;
                }

                axios.post(url+'/asignaciones/buscar/paciente', {
                    'query' : texto
                })
                    .then((response) => {

                        seguroBuscador = true;
                        $(row).each(function (index, element) {
                            $(this).find(".droplista").fadeIn();
                            $(this).find(".droplista").html(response.data);
                        });
                    })
                    .catch((error) => {
                        seguroBuscador = true;
                    });
            }
        }

        // utilizado para obtener ID del paciente buscado en el buscador
        function modificarValor(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-info', edrop.id);

            idPacienteGlobal = edrop.id;

            //$(txtContenedorGlobal).data("info");
        }


        // preguntar si guardar la nueva asignacion
        function preguntaGuardar(){

            Swal.fire({
                title: 'Guardar Asignación?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    guardarRegistro();
                }
            })
        }

        // guardar la asignacion y recargar vista completa
        function guardarRegistro(){

            if(idPacienteGlobal === 0){
                toastr.error('Paciente es requerido');
                return;
            }

            var razonUso = document.getElementById('select-razon').value;
            var salaEspera = document.getElementById('select-salaespera').value;

            if(razonUso === ''){
                toastr.error('Razón de uso es requerido');
                return;
            }

            if(salaEspera === ''){
                toastr.error('Sala de espera es requerido');
                return;
            }

            openLoading();

            let formData = new FormData();
            formData.append('idpaciente', idPacienteGlobal);
            formData.append('idrazon', razonUso);
            formData.append('idsalaespera', salaEspera);

            axios.post(url+'/asignaciones/nuevo/registro', formData, {
            })
                .then((response) => {
                    closeLoading();


                    if(response.data.success === 1){

                        let msj = response.data.mensaje;

                        Swal.fire({
                            title: 'NOTA',
                            text: msj,
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })
                    }

                    else if(response.data.success === 2){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargarVista();
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


        function recargarVista(){
            location. reload();
        }



        // abrir modal para ver Tabla de asignacion para enfermeria
        function modalTablaEnfermeria(){

            document.getElementById("formulario-tabla-enfermeria").reset();

            // buscar tabla
            var ruta = "{{ URL::to('/admin/asignaciones/tablamodal/enfermeria') }}";
            $('#tablaDatatableEnfermeria').load(ruta);


            $('#modalTablaEnfermeria').modal('show');
        }


        // abrir modal para ver Tabla de asignacion para consultoria
        function modalTablaConsultoria(){

            document.getElementById("formulario-tabla-consultoria").reset();

            // buscar tabla
            var ruta = "{{ URL::to('/admin/asignaciones/tablamodal/consultoria') }}";
            $('#tablaDatatableConsultoria').load(ruta);

            $('#modalTablaConsultoriaModal').modal('show');
        }



        // Utilizado para las 2 salas
        function infoModalEditarSalas(id){

            openLoading();
            document.getElementById("formulario-editar-traslado").reset();

            axios.post(url+'/asignaciones/informacion/paciente',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-traslado-cola').val(response.data.info.id);


                        document.getElementById("select-editar-salaactual").options.length = 0;
                        document.getElementById("select-editar-razonuso").options.length = 0;

                        $.each(response.data.arraysala, function( key, val ){
                            if(response.data.info.salaespera_id == val.id){
                                $('#select-editar-salaactual').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-editar-salaactual').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });

                        $.each(response.data.arrayrazonuso, function( key, val ){
                            if(response.data.info.motivo_id == val.id){
                                $('#select-editar-razonuso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-editar-razonuso').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });


                        $('#modalTablaEditarSalas').modal('show');
                    }else{
                        toastr.error('información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('información no encontrada');
                });
        }



        // guardar ajustes de model editar
        function guardarAjustesEditados(){

            openLoading();

            var idconsulta = document.getElementById('id-traslado-cola').value;
            var idSala = document.getElementById('select-editar-salaactual').value;
            var idRazonUso = document.getElementById('select-editar-razonuso').value;

            openLoading();

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('idsala', idSala);
            formData.append('idrazonuso', idRazonUso);

            axios.post(url+'/asignaciones/informacion/guardar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Paciente Actualizado',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
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


        function infoModalEliminarPaciente(id){

            Swal.fire({
                title: '¿Finalizar Consulta?',
                text: '',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                closeOnClickOutside: false,
                allowOutsideClick: false,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    finalizarConsultaPaciente(id);
                }
            })
        }


        function finalizarConsultaPaciente(idconsulta){

            openLoading();

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
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
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


        // asignar paciente que esta en espera, a la sala

        function infoAsignarAsalaPaciente(idconsulta){

            openLoading();

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);

            axios.post(url+'/asignaciones/ingresar/paciente/asala', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        let sala = response.data.nombresala;

                        Swal.fire({
                            title: 'Asignado',
                            text: 'El Paciente esta dentro de la Sala: ' + sala,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
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


        function buscarFichaAdministrativaModal(tipoficha){

            // Tipo Ficha
            // 1 CONSULTORIA
            // 2 ENFERMERIA

            // buscara al primer paciente que encuentre

            openLoading();

            let formData = new FormData();
            formData.append('tipoficha', tipoficha);
            axios.post(url+'/asignaciones/info/paciente/dentrosala', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        if(response.data.hayfoto === 1){
                            $('#foto-paciente-ficha').prop("src","{{ url('storage/archivos') }}"+'/'+ response.data.infopaciente.foto);

                            document.getElementById('textofoto').innerHTML = "FOTOGRAFIA";
                        }else{
                            $('#foto-paciente-ficha').prop("src","{{ asset('images/foto-default.png') }}");
                            document.getElementById('textofoto').innerHTML = "SIN FOTOGRAFIA";
                        }

                        let unido = response.data.infopaciente.nombres + " " + response.data.infopaciente.apellidos;

                        $('#idpacientemodal-dentrosala').val(response.data.infoconsulta.id);


                        $('#txtNombre').val(unido);
                        $('#txtHoraEntroEspera').val(response.data.entroespera);
                        $('#txtHoraEntro').val(response.data.horaentro);

                        document.getElementById("select-dentrosala-razonuso").options.length = 0;

                        $.each(response.data.arrayrazonuso, function( key, val ){
                            if(response.data.infoconsulta.motivo_id == val.id){
                                $('#select-dentrosala-razonuso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-dentrosala-razonuso').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });


                        // bloquear botonera de editar o guardar
                        document.getElementById("select-dentrosala-razonuso").disabled = true;
                        document.getElementById("razonuso-btn-editar").style.display = "block";
                        document.getElementById("razonuso-btn-guardar").style.display = "none";

                        $('#modalFichaAdministrativa').modal('show');
                    }
                    else{
                        Swal.fire({
                            title: 'Error',
                            text: 'Información no encontrada, recargar la página',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
                            }
                        })
                    }
                })
                .catch((error) => {
                    toastr.error('error al buscar');
                    closeLoading();
                });
        }



        function modificarSelectRazonUso(){

            document.getElementById("select-dentrosala-razonuso").disabled = false;
            document.getElementById("razonuso-btn-editar").style.display = "none";
            document.getElementById("razonuso-btn-guardar").style.display = "block";
        }


        function actualizarSelectRazonUso(){

            openLoading();

            var idconsulta = document.getElementById('idpacientemodal-dentrosala').value;
            var idRazonUso = document.getElementById('select-dentrosala-razonuso').value;

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('idrazonuso', idRazonUso);

            axios.post(url+'/asignaciones/actualizar/razonuso/paciente', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        toastr.success('Actualizado');

                        document.getElementById("select-dentrosala-razonuso").disabled = true;
                        document.getElementById("razonuso-btn-guardar").style.display = "none";
                        document.getElementById("razonuso-btn-editar").style.display = "block";
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


        function preguntaLiberarSala(){

            Swal.fire({
                title: '¿Liberar Sala?',
                text: '',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                closeOnClickOutside: false,
                allowOutsideClick: false,
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    liberarSala();
                }
            })

        }

        function liberarSala(){

            openLoading();

            var idconsulta = document.getElementById('idpacientemodal-dentrosala').value;

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);


            axios.post(url+'/asignaciones/liberarsala/paciente', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Sala Liberada',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            confirmButtonText: 'Recargar Vista',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
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


        // abre modal para trasladar un paciente
        function trasladoPacienteModal(){

            var idconsulta = document.getElementById('idpacientemodal-dentrosala').value;

            openLoading();

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);
            axios.post(url+'/asignaciones/informacion/paciente/dentrosala', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){


                        $('#txtSalaActual-info').val(response.data.salactual);

                        document.getElementById("select-sala-asignar").options.length = 0;
                        document.getElementById("select-razouso-v2").options.length = 0;


                        $.each(response.data.arraysala, function( key, val ){
                            if(response.data.info.salaespera_id == val.id){
                                $('#select-sala-asignar').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-sala-asignar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });

                        $.each(response.data.arrayrazonuso, function( key, val ){
                            if(response.data.info.motivo_id == val.id){
                                $('#select-razouso-v2').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-razouso-v2').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });


                        $('#modalTrasladoPaciente').modal('show');
                    }
                    else{
                        toastr.error('error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al buscar');
                    closeLoading();
                });
        }


        function guardarNuevoTraslado(){


            openLoading();

            var idconsulta = document.getElementById('idpacientemodal-dentrosala').value;
            var nuevasala = document.getElementById('select-sala-asignar').value;
            var nuevomotivo = document.getElementById('select-razouso-v2').value;

            let formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('nuevasala', nuevasala);
            formData.append('nuevomotivo', nuevomotivo);

            axios.post(url+'/asignaciones/traslado/paciente/reseteo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Traslado Guardado',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            confirmButtonText: 'Recargar Vista',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
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


        function vistaAtencionEnfermeria(){

            var idconsulta = document.getElementById('idpacientemodal-dentrosala').value;
            window.location.href="{{ url('/admin/historial/vista/general') }}/" + idconsulta;
        }





    </script>


@endsection
