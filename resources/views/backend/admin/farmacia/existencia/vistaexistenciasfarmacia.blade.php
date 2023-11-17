@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Existencias De Artículos</h3>
                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-3">
                            <label>Fuente de Financiamiento</label>
                            <select class="form-control" id="select-fuentefina" onchange="verificarEstado()">
                                <option value="0">Seleccionar Todos</option>
                                @foreach($arrayFuenteFina as $item)
                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Línea</label>
                            <select class="form-control" id="select-linea" onchange="verificarEstado()">
                                <option value="0">Seleccionar Todos</option>
                                @foreach($arrayLinea as $item)
                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
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

            var ruta = "{{ URL::to('/admin/existencia/farmacia/tabla') }}/" + "0" + "/" + "0";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function verificarEstado(){

            let idFuente = document.getElementById("select-fuentefina").value;
            let idLinea = document.getElementById("select-linea").value;

            openLoading();

            var ruta = "{{ URL::to('/admin/existencia/farmacia/tabla') }}/" + idFuente + "/" + idLinea;
            $('#tablaDatatable').load(ruta);
        }


        function informacion(idarticulo){

            window.location.href="{{ url('/admin/existencia/individual/entrada/detalle') }}/" + idarticulo;
        }


    </script>


@endsection
