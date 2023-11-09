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
            <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;" onclick="vistaHistorialClinico()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-arrow-left"></i>
                Atras
            </button>
        </div>
    </section>


    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">FICHA PARA REGISTRAR ANTROPOLOGIA</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-5">
                                <div class="card" style="border-radius: 15px;">
                                    <div class="card-body p-2">
                                        <div class="d-flex text-black">

                                            <div style="margin-left: 15px">
                                                <h5 style="font-weight: bold">PACIENTE:</h5>
                                                <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
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





    <div class="modal-footer justify-content-between float-right" style="margin-top: 25px; margin-bottom: 30px; >
        <button type="button" class="btn btn-success" onclick="preguntarGuardar()">Registar Médica</button>
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



            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>


    function vistaHistorialClinico(){
        let idconsulta = {{ $idconsulta }};
        window.location.href="{{ url('/admin/historial/clinico/vista') }}/" + idconsulta;
    }



    </script>


@endsection
