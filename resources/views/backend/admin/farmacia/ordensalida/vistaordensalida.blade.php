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

    .cursor-pointer:hover {
        cursor: pointer;
        color: #401fd2;
        font-weight: bold;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">CREAR ORDEN DE SALIDA</h3>
                </div>
                <div class="card-body">

                    <div class="card-header">
                        <h3 class="card-title" style="color: #005eab; font-weight: bold">Datos de Salida</h3>
                    </div>


                    <section class="content" style="margin-left: 30px">
                        <div class="container-fluid">

                            <div class="row">

                                <div class="form-group col-md-5" style="margin-top: 5px">
                                    <label style="color: #686868">Motivo: </label>
                                    <div>
                                        <select class="form-control" id="select-motivo">
                                            <option value="">Seleccionar Opción</option>
                                            @foreach($arrayMotivo as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6" style="margin-top: 5px">
                                    <label style="color: #686868">Producto: </label>
                                    <div >
                                        <select class="form-control" id="select-producto" onchange="cargarTablaProducto()">
                                            <option value="">Seleccionar Opción</option>
                                            @foreach($arrayProducto as $item)
                                                <option value="{{$item->id}}">{{ $item->nombretotal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </section>


                    <div class="card-header" style="margin-top: 25px">
                        <h3 class="card-title" id="txtSalida" style="color: #005eab; font-weight: bold"></h3>
                    </div>

                    <section class="content" style="margin-top: 15px">
                        <div class="container-fluid">


                            <!-- cargara vista de selección para salida -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="tablaProductos">



                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </section>


                    <div id="btnAgregarFila" style="display: none">

                        <br>
                        <hr>

                        <section class="content">
                            <div class="container-fluid">

                                <div style="margin-right: 30px">
                                    <button type="button" style="float: right" class="btn btn-success" onclick="agregarFila();">Agregar a Tabla</button>
                                </div>

                            </div>
                        </section>

                    </div>

                </div>
            </div>
        </div>
    </section>



    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Detalle de Ingreso</h2>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de Salida</h3>
                </div>

                <table class="table" id="matriz" data-toggle="table" style="margin-right: 15px; margin-left: 15px;">
                    <thead>
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 10%">Producto</th>
                        <th style="width: 6%">Cantidad Salida</th>
                        <th style="width: 6%">Fecha Vencimiento</th>
                        <th style="width: 6%">Lote</th>
                        <th style="width: 6%">Fecha Entrada</th>
                        <th style="width: 5%">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


                <table class="table" id="matriz-totales" data-toggle="table" style="float: right">
                    <thead>
                    <tr style="float: right">
                        <th>Salida Total</th>
                    </tr>
                    </thead>
                    <tbody style="float: right">
                    <td style="width: 125px"> <label type="text" class="form-control" id="cantidadTotal" >0</label></td>

                    </tbody>
                </table>

            </div>
        </div>
    </section>

    <div class="modal-footer justify-content-between" style="margin-top: 25px;">
        <button type="button" class="btn btn-success" onclick="preguntarGuardar()">Guardar</button>
    </div>


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

            $('#select-producto').select2({
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


        function cargarTablaProducto(){

            var idProducto = document.getElementById('select-producto').value;

            if(idProducto === ''){
                document.getElementById('tablaProductos').innerHTML = "";
                document.getElementById('txtSalida').innerHTML = "";
                document.getElementById('btnAgregarFila').style.display = "none";
            }else{
                openLoading();
                var ruta = "{{ URL::to('/admin/buscar/producto/salida/farmacia') }}/" + idProducto;
                $('#tablaProductos').load(ruta);
            }
        }


        function colorRojoTabla(index){
            $("#matriz tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        function colorBlancoTabla(){
            $("#matriz tbody tr").css('background', 'white');
        }


        function agregarFila(){


            const inputSalidas = document.querySelectorAll('input[name="arraysalida[]"]');


            inputSalidas.forEach((valor) => {

                const nombreMedicamento = valor.dataset.nombremedi;
                const idEntrada = valor.dataset.identrada;
                const maxCantidad = valor.dataset.maxcantidad;
                const fechaVencimiento = valor.dataset.fechavencimiento;
                const fechaEntrada = valor.dataset.fechaentrada;
                const loteEntrada = valor.dataset.lote;
                const inputCantidad = valor.value;

                if(inputCantidad === '' || inputCantidad == 0){
                    // no hacer nada
                }else{

                    // INGRESAR A TABLA


                    var nFilas = $('#matriz >tbody >tr').length;
                    nFilas += 1;

                    var markup = "<tr>" +

                        "<td>" +
                        "<p id='fila" + (nFilas) + "' class='form-control' style='max-width: 65px'>" + (nFilas) + "</p>" +
                        "</td>" +

                        "<td>" +
                        "<input name='arrayNombre[]' disabled data-idmedicamento='" + idEntrada + "' value='" + nombreMedicamento + "' class='form-control' type='text'>" +
                        "</td>" +

                        "<td>" +
                        "<input name='arrayCantidad[]' disabled value='" + inputCantidad + "' class='form-control' type='text'>" +
                        "</td>" +

                        "<td>" +
                        "<input disabled value='" + fechaVencimiento + "' class='form-control' type='text'>" +
                        "</td>" +

                        "<td>" +
                        "<input disabled value='" + loteEntrada + "' class='form-control' type='text'>" +
                        "</td>" +


                        "<td>" +
                        "<input disabled value='" + fechaEntrada + "' class='form-control' type='text'>" +
                        "</td>" +


                        "<td>" +
                        "<button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'>Borrar</button>" +
                        "</td>" +

                        "</tr>";

                    $("#matriz tbody").append(markup);


                    // CALCULAR TODAS LAS FILAS
                    calcularFilas();


                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Agregado al Detalle',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    $(txtContenedorGlobal).attr('data-idmedicamento', '0');


                    document.getElementById('cantidad').value = '';
                    document.getElementById('fecha-vencimiento').value = '';
                    document.getElementById('precio-producto').value = '';
                    document.getElementById('inputBuscador').value = '';
                    document.getElementById('existencia').value = '';
                    document.getElementById('ultimo-costo').value = '';
                }
            });
        }


        function calcularFilas(){

            var cantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();

            /*var datosTabla1 = $("#tabla1 input[name='arrayCantidad[]']").map(function() {
                return $(this).val();
            }).get();*/

            var cantidadTotal = 0;

            for(var a = 0; a < cantidad.length; a++){
                cantidadTotal += cantidad[a];
            }

            document.getElementById('cantidadTotal').innerHTML = cantidadTotal;
        }




    </script>

@endsection
