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
                    <h3 class="card-title">HISTORIAL DE ENTRADAS DE ARTICULOS</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class=" col-md-2">
                            <label>Estados</label>
                            <select class="form-control" id="select-fuente" onchange="verificarEstado()">
                                <option value="0">Seleccionar Todos</option>
                                @foreach($arrayFuente as $item)
                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class=" col-md-2">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha-inicio" autocomplete="off" onchange="verificarEstado()">
                        </div>

                        <div class=" col-md-2">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha-fin" autocomplete="off" onchange="verificarEstado()">
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

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function verificarEstado(){

            let estado = document.getElementById("select-fuente").value;
            let fechainicio = document.getElementById("fecha-inicio").value;
            let fechafin = document.getElementById("fecha-fin").value;

            if(fechainicio === ''){
                document.getElementById("tablaDatatable").innerHTML = "";
                return;
            }

            if(fechafin === ''){
                document.getElementById("tablaDatatable").innerHTML = "";
                return;
            }

            openLoading();

            // CARGAR TABLA
            var ruta = "{{ URL::to('/admin/historial/reporte/entradas/tabla/') }}/" + estado + "/" + fechainicio + "/" + fechafin;
            $('#tablaDatatable').load(ruta);
        }



        function infoVerLista(identrada){

            window.location.href="{{ url('/admin/historial/entrada/listado') }}/" + identrada;
        }



    </script>


@endsection
