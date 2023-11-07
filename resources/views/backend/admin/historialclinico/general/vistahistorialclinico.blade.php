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
                <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;" onclick="recargarVista()" class="button button-3d button-rounded button-pill button-small">
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
                                                    <p><span class="badge bg-primary" style="font-size: 13px; color: white !important;">Expediente #:  {{ $infoPaciente->id }}</span></p>
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

                                                                <div id="tablaAntecedentes">
                                                                </div>

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

                                                                <div id="tablaAntropSv">
                                                                </div>

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

                                                                <div id="tablaRecetas">
                                                                </div>

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

                                                                <div id="tablaCuadroClinico">
                                                                </div>

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
        <div class="modal-dialog modal-lg">
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

                                <div class="form-group col-md-6">
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
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="actualizarCuadroClinico()">Guardar</button>
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

            var rutaAntecedente = "{{ URL::to('/admin/historial/bloque/antecedente') }}/" + idconsulta;
            $('#tablaAntecedentes').load(rutaAntecedente);



            // TABLA ANTROP SV
            var rutaAntrop = "{{ URL::to('/admin/historial/bloque/antropsv') }}/" + idconsulta;
            $('#tablaAntropSv').load(rutaAntrop);


            // TABLA RECETAS
            var rutaRecetas = "{{ URL::to('/admin/historial/bloque/recetas') }}/" + idconsulta;
            $('#tablaRecetas').load(rutaRecetas);


            // TABLA CUADRO CLINICO
            var rutaCuadroClinico = "{{ URL::to('/admin/historial/bloque/cuadroclinico') }}/" + idconsulta;
            $('#tablaCuadroClinico').load(rutaCuadroClinico);


            $('#select-tipo-diagnostico').select2({
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
                closeOnClickOutside: false,
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


        function calcular_imc2_editar(){
            var peso = $('#peso-libra-antro-editar').val();
            $('#peso-kilo-antro-editar').val((peso/2.2046).toFixed(2));

            var estatura = $('#estatura-antro-editar').val();
            var imc = (peso/2.2046)/((estatura/100)*(estatura/100));
            $('#imc-antro-editar').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro-editar').val(resimc);
        }

        function calcular_imc3_editar(){
            var peso = $('#peso-kilo-antro-editar').val();
            $('#peso-libra-antro-editar').val((peso*2.2046).toFixed(2));

            var estatura = $('#estatura-antro-editar').val();
            var imc = (peso)/((estatura/100)*(estatura/100));
            $('#imc-antro-editar').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro-editar').val(resimc);
        }


        function calcular_indice_editar(){
            perimetro_cintura = parseFloat($("#perimetro-cintura-antro-editar").val());
            perimetro_cadera = parseFloat($("#perimetro-cadera-antro-editar").val());
            valor =(parseFloat(perimetro_cintura/perimetro_cadera)).toFixed(2);
            if(perimetro_cintura>0 && perimetro_cadera>0){
                if(valor<0.8){
                    mujer = "Bajo";
                    color_mujer = "green";
                }else if (valor>0.8 && valor<=0.85){
                    mujer = "Moderado";
                    color_mujer = "orange";
                }else if (valor>0.85){
                    mujer = "Alto";
                    color_mujer = "red";
                }
                $("#riesgo-mujer-antro-editar").val(mujer);
                if(valor<0.95){
                    color_hombre = "green";
                    hombre = "Bajo";
                }else if (valor>0.95 && valor<=1){
                    hombre = "Moderado";
                    color_hombre = "orange";
                }else if (valor>1){
                    hombre = "Alto";
                    color_hombre = "red";
                }
                $("#riesgo-hombre-antro-editar").val(hombre);
                $("#riesgo-hombre-antro-editar").css("color",color_hombre);
                $("#riesgo-mujer-antro-editar").css("color",color_mujer);
                $("#riesgo-hombre-antro-editar").css("font-weight","bold");
                $("#riesgo-mujer-antro-editar").css("font-weight","bold");
                $("#icc-antro-editar").val(valor);
            }else{
                $("#icc-antro-editar").val(0);
            }
        }




        function guardarAntropometria(){


            var fecha = document.getElementById('fecha-antro').value;


            var freCardiaca = document.getElementById('frecuencia-cardia-antro').value;
            var freRespiratoria = document.getElementById('frecuencia-respiratoria-antro').value;
            var presionArterial = document.getElementById('presion-arterial-antro').value;
            var temperatura = document.getElementById('temperatura-antro').value;
            var perimetroAbdominal = document.getElementById('perim-abdominal-antro').value;
            var perimetroCefalico = document.getElementById('perimetro-cefalico-antro').value;
            var pesoLibra = document.getElementById('peso-libra-antro').value;
            var pesoKilo = document.getElementById('peso-kilo-antro').value;
            var estatura = document.getElementById('estatura-antro').value;
            var imc = document.getElementById('imc-antro').value;
            var resultadoImc = document.getElementById('resultado-imc-antro').value;
            var glucometria = document.getElementById('glucometria-capilar-antro').value;
            var glicohemoglobina = document.getElementById('glicohemoglobina-antro').value;
            var cetona = document.getElementById('cetona-capilar-antro').value;
            var sp02 = document.getElementById('sp02-antro').value;
            var perimetroCintura = document.getElementById('perimetro-cintura-antro').value;
            var perimetroCadera = document.getElementById('perimetro-cadera-antro').value;
            var icc = document.getElementById('icc-antro').value;
            var riesgoMujer = document.getElementById('riesgo-mujer-antro').value;
            var riesgoHombre = document.getElementById('riesgo-hombre-antro').value;
            var gastoEnergetico = document.getElementById('gasto-energetico-antro').value;
            var otrosDetalles = document.getElementById('otros-detalles-antro').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }


            // ID CONSULTA
            let idconsulta = {{ $idconsulta }};


            openLoading();
            var formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('fecha', fecha);
            formData.append('freCardiaca', freCardiaca);
            formData.append('freRespiratoria', freRespiratoria);
            formData.append('presionArterial', presionArterial);
            formData.append('temperatura', temperatura);
            formData.append('perimetroAbdominal', perimetroAbdominal);
            formData.append('perimetroCefalico', perimetroCefalico);
            formData.append('pesoLibra', pesoLibra);
            formData.append('pesoKilo', pesoKilo);
            formData.append('estatura', estatura);
            formData.append('imc', imc);
            formData.append('resultadoImc', resultadoImc);
            formData.append('glucometria', glucometria);
            formData.append('glicohemoglobina', glicohemoglobina);
            formData.append('cetona', cetona);
            formData.append('sp02', sp02);
            formData.append('perimetroCintura', perimetroCintura);
            formData.append('perimetroCadera', perimetroCadera);
            formData.append('icc', icc);
            formData.append('riesgoMujer', riesgoMujer);
            formData.append('riesgoHombre', riesgoHombre);
            formData.append('gastoEnergetico', gastoEnergetico);
            formData.append('otrosDetalles', otrosDetalles);

            axios.post(url+'/historial/registrar/antropometria', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        Swal.fire({
                            title: 'Error',
                            text: "Ya se encuentra un registro con esta consulta",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista()
                            }
                        })
                    }
                    else if(response.data.success === 2){
                        toastr.success('Registrado correctamente');
                        $('#modalAntro').modal('hide');
                        document.getElementById("btnAntro").style.display = "none";
                        document.getElementById("formulario-antropometria").reset();

                        recargarTablaAntropometria();

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



        function informacionAntropometria(idantro){

            openLoading();

            axios.post(url+'/historial/informacion/antropometria',{
                'id': idantro
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-antro-editar').val(idantro);
                        $('#fecha-antro-editar').val(response.data.info.fecha);
                        $('#frecuencia-cardia-antro-editar').val(response.data.info.frecuencia_cardiaca);
                        $('#frecuencia-respiratoria-antro-editar').val(response.data.info.frecuencia_respiratoria);
                        $('#presion-arterial-antro-editar').val(response.data.info.presion_arterial);
                        $('#temperatura-antro-editar').val(response.data.info.temperatura);
                        $('#perim-abdominal-antro-editar').val(response.data.info.perim_abdominal);
                        $('#perimetro-cefalico-antro-editar').val(response.data.info.perim_cefalico);
                        $('#peso-libra-antro-editar').val(response.data.info.peso_libra);
                        $('#peso-kilo-antro-editar').val(response.data.info.peso_kilo);
                        $('#estatura-antro-editar').val(response.data.info.estatura);
                        $('#imc-antro-editar').val(response.data.info.imc);
                        $('#resultado-imc-antro-editar').val(response.data.info.resultado_imc);
                        $('#glucometria-capilar-antro-editar').val(response.data.info.glucometria_capilar);
                        $('#glicohemoglobina-antro-editar').val(response.data.info.glicohemoglibona_capilar);
                        $('#cetona-capilar-antro-editar').val(response.data.info.cetona_capilar);
                        $('#sp02-antro-editar').val(response.data.info.spo2);
                        $('#perimetro-cintura-antro-editar').val(response.data.info.perim_cintura);
                        $('#perimetro-cadera-antro-editar').val(response.data.info.perim_cadera);
                        $('#icc-antro-editar').val(response.data.info.icc);
                        $('#riesgo-mujer-antro-editar').val(response.data.info.riesgo_mujer);
                        $('#riesgo-hombre-antro-editar').val(response.data.info.riesgo_hombre);
                        $('#gasto-energetico-antro-editar').val(response.data.info.gasto_energetico_basal);
                        $('#otros-detalles-antro-editar').val(response.data.info.nota_adicional);

                        $('#modalAntroEditar').modal('show');
                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });

        }



        function actualizarAntropometria(){

            var idmodal = document.getElementById('id-antro-editar').value;
            var fecha = document.getElementById('fecha-antro-editar').value;

            var freCardiaca = document.getElementById('frecuencia-cardia-antro-editar').value;
            var freRespiratoria = document.getElementById('frecuencia-respiratoria-antro-editar').value;
            var presionArterial = document.getElementById('presion-arterial-antro-editar').value;
            var temperatura = document.getElementById('temperatura-antro-editar').value;
            var perimetroAbdominal = document.getElementById('perim-abdominal-antro-editar').value;
            var perimetroCefalico = document.getElementById('perimetro-cefalico-antro-editar').value;
            var pesoLibra = document.getElementById('peso-libra-antro-editar').value;
            var pesoKilo = document.getElementById('peso-kilo-antro-editar').value;
            var estatura = document.getElementById('estatura-antro-editar').value;
            var imc = document.getElementById('imc-antro-editar').value;
            var resultadoImc = document.getElementById('resultado-imc-antro-editar').value;
            var glucometria = document.getElementById('glucometria-capilar-antro-editar').value;
            var glicohemoglobina = document.getElementById('glicohemoglobina-antro-editar').value;
            var cetona = document.getElementById('cetona-capilar-antro-editar').value;
            var sp02 = document.getElementById('sp02-antro-editar').value;
            var perimetroCintura = document.getElementById('perimetro-cintura-antro-editar').value;
            var perimetroCadera = document.getElementById('perimetro-cadera-antro-editar').value;
            var icc = document.getElementById('icc-antro-editar').value;
            var riesgoMujer = document.getElementById('riesgo-mujer-antro-editar').value;
            var riesgoHombre = document.getElementById('riesgo-hombre-antro-editar').value;
            var gastoEnergetico = document.getElementById('gasto-energetico-antro-editar').value;
            var otrosDetalles = document.getElementById('otros-detalles-antro-editar').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }


            openLoading();
            var formData = new FormData();
            formData.append('idmodal', idmodal);
            formData.append('fecha', fecha);
            formData.append('freCardiaca', freCardiaca);
            formData.append('freRespiratoria', freRespiratoria);
            formData.append('presionArterial', presionArterial);
            formData.append('temperatura', temperatura);
            formData.append('perimetroAbdominal', perimetroAbdominal);
            formData.append('perimetroCefalico', perimetroCefalico);
            formData.append('pesoLibra', pesoLibra);
            formData.append('pesoKilo', pesoKilo);
            formData.append('estatura', estatura);
            formData.append('imc', imc);
            formData.append('resultadoImc', resultadoImc);
            formData.append('glucometria', glucometria);
            formData.append('glicohemoglobina', glicohemoglobina);
            formData.append('cetona', cetona);
            formData.append('sp02', sp02);
            formData.append('perimetroCintura', perimetroCintura);
            formData.append('perimetroCadera', perimetroCadera);
            formData.append('icc', icc);
            formData.append('riesgoMujer', riesgoMujer);
            formData.append('riesgoHombre', riesgoHombre);
            formData.append('gastoEnergetico', gastoEnergetico);
            formData.append('otrosDetalles', otrosDetalles);

            axios.post(url+'/historial/actualizar/antropometria', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalAntroEditar').modal('hide');

                        recargarTablaAntropometria();
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







        //************************* BLOQUE 1 ***********************************





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


        function modalAntropometria(){

            $('#modalAntro').modal({backdrop: 'static', keyboard: false})
        }







        //************************** TAB 3: RECETAS *************************************


        function btnRecargarTablaRecetas(){

            let idconsulta = {{ $idconsulta }};

            // TABLA RECETAS

            var ruta = "{{ URL::to('/admin/historial/recetas/paciente-consulta') }}/" + idconsulta;
            $('#tablaRecetas').load(ruta);
        }







    </script>


@endsection
