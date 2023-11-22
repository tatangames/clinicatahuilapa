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
                    <h3 class="card-title">REPORTE DE SALIDA MANUAL</h3>
                </div>
                <div class="card-body">

                    <section class="content" style="margin-left: 30px">
                        <div class="container-fluid">

                            <div class="row">

                                <div class="form-group col-md-2" >
                                    <label style="color: #686868">Desde: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha-desde">
                                </div>

                                <div class="form-group col-md-2" >
                                    <label style="color: #686868">Hasta: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha-hasta">
                                </div>

                                <div class="form-group col-md-1" style="margin-top: 30px">
                                    <button type="button" class="btn btn-success form-control" onclick="verificar()">Generar</button>
                                </div>

                            </div>

                        </div>
                    </section>

                </div>
            </div>
        </div>
    </section>


</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>


        function verificar(){

            let fechaDesde = document.getElementById("fecha-desde").value;
            let fechaHasta = document.getElementById("fecha-hasta").value;

            if(fechaDesde === ''){
                toastr.error('Fecha Desde es requerido');
                return;
            }

            if(fechaHasta === ''){
                toastr.error('Fecha Hasta es requerido');
                return;
            }


            window.open("{{ URL::to('admin/reporte/salida/manual') }}/" + fechaDesde + "/" + fechaHasta);
        }



    </script>

@endsection
