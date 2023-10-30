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
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">INGRESO DE PRODUCTO A INVENTARIO</h3>
                </div>
                <div class="card-body">




                    <div class="card-header">
                        <h3 class="card-title" style="color: #005eab; font-weight: bold">Datos de Factura</h3>
                    </div>


                    <section class="content">
                        <div class="container-fluid">

                            <div class="row">
                                <div class="form-group col-md-3" style="margin-top: 5px">
                                    <label class="control-label" style="color: #686868">N° Factura: </label>
                                    <div>
                                        <input type="text" maxlength="50" autocomplete="off" class="form-control" id="numero-factura" >
                                    </div>
                                </div>

                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                        <label style="color: #686868">Tipo de Factura: </label>
                                        <div>
                                            <select class="form-control" id="select-tipofactura">
                                                <option value="">Seleccionar Opción</option>
                                                @foreach($arrayTipoFactura as $item)
                                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                        <label style="color: #686868">Fuente de Financiamiento: </label>
                                        <div >
                                            <select class="form-control" id="select-fuente-financiamiento">
                                                <option value="">Seleccionar Opción</option>
                                                @foreach($arrayFuente as $item)
                                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-4" style="margin-top: 5px">
                                    <label style="color: #686868">Proveedor: </label>
                                    <div >
                                        <select class="form-control" id="select-proveedor">
                                            <option value="">Seleccionar Opción</option>
                                            @foreach($arrayProveedor as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>


                    <div class="card-header" style="margin-top: 25px">
                        <h3 class="card-title" style="color: #005eab; font-weight: bold">Producto a Ingresar</h3>
                    </div>

                    <section class="content" style="margin-top: 15px">
                        <div class="container-fluid">

                            <div class="row">


                                <div class="form-group col-md-6">
                                    <label class="control-label" style="color: #686868">Buscar Producto: </label>

                                    <table class="table" id="matriz-busqueda" data-toggle="table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input id="inputBuscador" data-info='0' autocomplete="off" class='form-control' style='width:100%' onkeyup='buscarMaterial(this)' maxlength='300' type='text'>
                                                <div class='droplista' style='position: absolute; z-index: 9; width: 75% !important;'></div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>



                                <div class="col-md-3" style="margin-top: 11px">
                                    <div class="form-group">
                                        <label class="control-label" style="color: #686868">Existencia: </label>
                                        <div>
                                            <input type="text" disabled autocomplete="off" class="form-control" id="existencia" >
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3" style="margin-top: 11px">
                                    <div class="form-group">
                                        <label class="control-label" style="color: #686868">Ultimo Costo: </label>
                                        <div>
                                            <input type="text" disabled autocomplete="off" class="form-control" id="ultimo-costo" >
                                        </div>
                                    </div>
                                </div>


                            </div>




                        </div>
                    </section>



                    <section class="content">
                        <div class="container-fluid">

                            <div class="row">

                                <div class="form-group col-md-2" style="margin-top: 5px">
                                    <label class="control-label" style="color: #686868">Cantidad: </label>
                                    <div>
                                        <input type="text"  autocomplete="off" class="form-control" id="cantidad" >
                                    </div>
                                </div>

                                <div class="form-group col-md-3" style="margin-top: 5px">
                                    <label class="control-label" style="color: #686868">Lote: </label>
                                    <div>
                                        <input type="text" maxlength="50" autocomplete="off" class="form-control" id="lote" >
                                    </div>
                                </div>


                                <div class="form-group col-md-3" style="margin-top: 5px">
                                    <label class="control-label" style="color: #686868">Fecha de Vencimiento: </label>
                                    <div>
                                        <input type="date" class="form-control" id="fecha-vencimiento">
                                    </div>
                                </div>


                                <div class="form-group col-md-3" style="margin-top: 5px">
                                    <label class="control-label" style="color: #686868">Precio Producto: </label>
                                    <div>
                                        <input type="text" autocomplete="off" class="form-control" id="precio-producto" >
                                    </div>
                                </div>


                            </div>



                        </div>
                    </section>








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
                    <h3 class="card-title">Información de Ingreso</h3>
                </div>

                <table class="table" id="matriz" data-toggle="table" style="margin-right: 15px; margin-left: 15px;">
                    <thead>
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 10%">Producto</th>
                        <th style="width: 6%">Cantidad</th>
                        <th style="width: 6%">Precio</th>
                        <th style="width: 6%">Lote</th>
                        <th style="width: 6%">Fecha V.</th>
                        <th style="width: 5%">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


                <table class="table" id="matriz-totales" data-toggle="table" style="float: right">
                    <thead>
                    <tr style="float: right">
                        <th>Precio Total</th>
                    </tr>
                    </thead>
                    <tbody style="float: right">
                    <td style="width: 125px"> <label type="text" class="form-control" id="precioTotal" >$0.00</label></td>

                    </tbody>
                </table>

            </div>
        </div>
    </section>

    <div class="modal-footer justify-content-between" style="margin-top: 25px;">
        <button type="button" class="btn btn-success" onclick="preguntaGuardar()">Guardar</button>
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

            $('#select-proveedor').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            window.seguroBuscador = true;
            window.txtContenedorGlobal = this;

            $(document).click(function(){
                $(".droplista").hide();
            });

            $(document).ready(function() {
                $('[data-toggle="popover"]').popover({
                    placement: 'top',
                    trigger: 'hover'
                });
            });



            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>


        function buscarMaterial(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-info', 0);
                }

                axios.post(url+'/buscar/nombre/medicamento', {
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

        function preventScroll(){
            window.scrollTo(0, 0);
        }

        function modificarValor(edrop){

            document.querySelector(".dropdown-menu").addEventListener("click", preventScroll);

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-info', edrop.id);

            const existencia = edrop.dataset.existencia;
            const ultimoPrecio = edrop.dataset.ultimoprecio;

            document.getElementById("existencia").value = existencia;
            document.getElementById("ultimo-costo").value = ultimoPrecio;
        }



        function agregarFila(){


            var lote = document.getElementById('lote').value;
            var fechaVenc = document.getElementById('fecha-vencimiento').value;
            var precioProducto = document.getElementById('precio-producto').value;
            var cantidad = document.getElementById('cantidad').value;


            var reglaNumeroEntero = /^[0-9]\d*$/;
            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            //**************

            if(cantidad === ''){
                toastr.error('Cantidad es requerido');
                return;
            }

            if(!cantidad.match(reglaNumeroEntero)) {
                toastr.error('Cantidad es requerido');
                return;
            }

            if(cantidad < 0){
                toastr.error('Cantidad Mínima no debe tener negativos');
                return;
            }

            if(cantidad > 9000000){
                toastr.error('Cantidad máximo debe ser 9 millones');
                return;
            }

            //**************

            if(lote === ''){
                toastr.error('Lote es requerido');
                return;
            }

            if(fechaVenc === ''){
                toastr.error('Fecha Vencimiento es requerido');
                return;
            }


            var inputBuscador = document.querySelector('#inputBuscador');
            var nomProducto = document.getElementById('inputBuscador').value;


            if(inputBuscador.dataset.info == 0){
                toastr.error("Producto es requerido");
                return;
            }


            //*************

            if(precioProducto === ''){
                toastr.error('Precio Producto es requerido');
                return;
            }

            if(!precioProducto.match(reglaNumeroDosDecimal)) {
                toastr.error('Precio Producto debe ser número Decimal (2 decimales)');
                return;
            }

            if(precioProducto <= 0){
                toastr.error('Precio Producto no debe ser negativo o cero');
                return;
            }

            if(precioProducto > 9000000){
                toastr.error('Precio Producto debe ser máximo 9 millones');
                return;
            }


            //**************

            var nFilas = $('#matriz >tbody >tr').length;
            nFilas += 1;

            var markup = "<tr>" +

                "<td>" +
                "<p id='fila" + (nFilas) + "' class='form-control' style='max-width: 65px'>" + (nFilas) + "</p>" +
                "</td>" +

                "<td>" +
                "<input name='arrayNombre[]' disabled data-info='" + inputBuscador.dataset.info + "' value='" + nomProducto + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayCantidad[]' disabled value='" + cantidad + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayPrecio[]' disabled value='" + precioProducto + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayLote[]' disabled value='" + lote + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayFecha[]' disabled value='" + fechaVenc + "' class='form-control' type='text'>" +
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

            $(txtContenedorGlobal).attr('data-info', '0');


            document.getElementById('cantidad').value = '';
            document.getElementById('fecha-vencimiento').value = '';
            document.getElementById('precio-producto').value = '';
            document.getElementById('inputBuscador').value = '';
        }

        function borrarFila(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            setearFila();
        }


        function setearFila(){

            var table = document.getElementById('matriz');
            var conteo = 0;
            for (var r = 1, n = table.rows.length; r < n; r++) {
                conteo +=1;
                var element = table.rows[r].cells[0].children[0];
                document.getElementById(element.id).innerHTML = ""+conteo;
            }

            calcularFilas();
        }


        function calcularFilas(){

            var cantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();
            var precio = $("input[name='arrayPrecio[]']").map(function(){return $(this).val();}).get();

            var precioTotal = 0;

            for(var a = 0; a < cantidad.length; a++){

                let infoCantidad = cantidad[a];
                let infoPrecio = precio[a];

                let multiplicado = infoCantidad * infoPrecio;

                precioTotal += multiplicado;
            }

             let dato = '$' + Number(precioTotal).toFixed(2);

            document.getElementById('precioTotal').innerHTML = dato;
        }


    </script>

@endsection
