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
                    onclick="vistaAtras()" class="button button-3d button-rounded button-pill button-small">
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
                                                <p><span class="badge bg-primary" style="font-size: 13px; color: white !important;">Total de Consultas Realizadas:  {{ $totalConsulta }}</span></p>

                                            </div>



                                        </div>

                                    </div>
                                </div>
                            </div>

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
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor5.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            let idPaciente = {{ $idpaciente }};


            // TABLA ANTECEDENTES

            var rutaAntecedente = "{{ URL::to('/admin/documentoreceta/bloque/antecedentes') }}/" + idPaciente;
            $('#tablaAntecedentes').load(rutaAntecedente);


            // TABLA ANTROPOMETRIA

            var rutaAntropometria = "{{ URL::to('/admin/documentoreceta/bloque/antropometriasv') }}/" + idPaciente;
            $('#tablaAntropSv').load(rutaAntropometria);


            // TABLA RECETAS

            var rutaRecetas = "{{ URL::to('/admin/documentoreceta/bloque/recetas') }}/" + idPaciente;
            $('#tablaRecetas').load(rutaRecetas);


            // TABLA CUADRO CLINICO

            var rutaCuadro = "{{ URL::to('/admin/documentoreceta/bloque/cuadroclinico') }}/" + idPaciente;
            $('#tablaCuadroClinico').load(rutaCuadro);


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargarVista(){
            location.reload();
        }

        function vistaAtras(){
            window.location.href="{{ url('/admin/expediente/vista/buscar') }}";
        }





    </script>


@endsection
