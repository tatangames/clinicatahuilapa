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
                    <h3 class="card-title">HISTORIAL DE ENTRADAS - LISTADO</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class=" col-md-2">
                            <label>Fecha Entrada: {{ $fechaentrada }}</label>
                        </div>
                    </div>

                    <div id="tablaDatatable" style="margin-top: 20px">
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

            let identrada = {{ $identrada }};

            var ruta = "{{ URL::to('/admin/historial/entrada/listado/tabla') }}/" + identrada;
            $('#tablaDatatable').load(ruta);


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>



    </script>


@endsection
