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


    .cursor-pointer:hover {
        cursor: pointer;
        color: #401fd2;
        font-weight: bold;
    }

</style>


<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
            <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;" onclick="vistaAtras()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-arrow-left"></i>
                Atras
            </button>
        </div>
    </section>


    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Editar Entrada de Artículo</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <section>

                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Fecha:</label>
                                            <input type="date" class="form-control" id="fecha-editar" value="{{ $fechaFormat }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Tipo Factura</label>

                                            <select id="select-tipofactura" class="form-control">
                                                @foreach($arrayTipoFac as $item)

                                                    @if($infoEntrada->tipofactura_id == $item->id)
                                                        <option value="{{$item->id}}" selected>{{ $item->nombre }}</option>

                                                    @else
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endif

                                                @endforeach
                                            </select>

                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Fuente Financiamiento</label>

                                            <select id="select-fuentefina" class="form-control">
                                                @foreach($arrayFuente as $item)
                                                    @if($infoEntrada->fuentefina_id == $item->id)
                                                        <option value="{{$item->id}}" selected>{{ $item->nombre }}</option>
                                                    @else
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>


                                </div>

                            </section>




                            <section>

                                <div class="row">


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Proveedor</label>

                                            <select id="select-proveedor" class="form-control">
                                                @foreach($arrayProvee as $item)
                                                    @if($infoEntrada->proveedor_id == $item->id)
                                                        <option value="{{$item->id}}" selected>{{ $item->nombre }}</option>
                                                    @else
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Número de Factura</label>
                                            <input type="text" class="form-control" maxlength="100" id="numero-factura" value="{{ $infoEntrada->numero_factura }}">
                                        </div>
                                    </div>


                                </div>

                            </section>



                            <hr><br>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="content" style="margin-top: 15px">
        <div class="container-fluid">

            <div class="row">


                <div class="form-group col-md-6">
                    <label class="control-label" style="color: #686868">Buscar Producto: </label>

                    <table class="table" id="matriz-busqueda" data-toggle="table">
                        <tbody>
                        <tr>
                            <td>
                                <input id="inputBuscador" data-idmedicamento='0' autocomplete="off" class='form-control' style='width:100%' onkeyup='buscarMaterial(this)' maxlength='300' type='text'>
                                <div class='droplista' id="midropmenu" style='position: absolute; z-index: 9; width: 75% !important;'></div>
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
                        <input type="text" maxlength="100" autocomplete="off" class="form-control" id="lote" >
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


    <section class="content" style="float: right">
        <div class="container-fluid">

            <div style="margin-right: 30px">
                <button type="button" style="float: right" class="btn btn-success" onclick="agregarFila();">Agregar Medicamento</button>
            </div>

        </div>
    </section>




    <br>
    <hr>



    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Detalle de Artículos</h2>
            </div>
        </div>
    </section>



    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información</h3>
                </div>

                <table class="table" id="matriz" data-toggle="table" style="margin-right: 15px; margin-left: 15px;">
                    <thead>
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 10%">Artículo</th>
                        <th style="width: 6%">Precio</th>
                        <th style="width: 6%">Cantidad</th>
                        <th style="width: 6%">Lote</th>
                        <th style="width: 8%">Fecha Vencimiento</th>
                        <th style="width: 5%">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($arrayDetalle as $item)
                        <tr>
                            <td>
                                <p id="fila" class="form-control" style="max-width: 65px">{{ $item->contador }}</p>
                            </td>
                            <td>
                                <input disabled value="{{ $item->nombre }}" class="form-control" type="text">
                            </td>
                            <td>
                                <input disabled value="${{ $item->precio }}" class="form-control" type="text">
                            </td>
                            <td>
                                <input disabled value="{{ $item->cantidad_fija }}" class="form-control" type="text">
                            </td>

                            <td>
                                <input disabled value="{{ $item->lote }}" class="form-control" type="text">
                            </td>

                            <td>
                                <input disabled value="{{ $item->fechaVencimiento }}" class="form-control" type="text">
                            </td>
                            <td>
                                <button type="button" title="Editar" class="btn btn-warning btn-sm" style="color: white" onclick="infoEditarDecimales({{ $item->id }})">
                                    <i class="fas fa-edit" ></i>&nbsp;
                                </button>
                            </td>
                        </tr>

                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </section>




    <div class="modal-footer justify-content-between float-right" style="margin-top: 25px; margin-bottom: 30px;">
        <button type="button" class="btn btn-success" onclick="preguntarGuardar()">Actualizar Entrada</button>
    </div>




    <div class="modal fade" id="modalEditarDecimales">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-decimales">
                        <div class="card-body">

                            <div>
                                <input id="id-entramedi-decimal" type="hidden">
                            </div>

                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Precio</label>
                                </div>
                                <input id="precio-edicion" class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Lote</label>
                                </div>
                                <input id="lote-edicion" maxlength="100" class="form-control" autocomplete="off">
                            </div>


                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Fecha Vencimiento</label>
                                </div>
                                <input type="date" id="fechavencimiento-edicion" class="form-control" autocomplete="off">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarEdicionFormato()">Actualizar</button>
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

            $('#select-fuentefina').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

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
                    $(e).attr('data-idmedicamento', 0);
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


        function modificarValor(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-idmedicamento', edrop.id);

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
            var inputBuscador = document.querySelector('#inputBuscador');

            var reglaNumeroEntero = /^[0-9]\d*$/;
            var reglaNumeroDiesDecimal = /^([0-9]+\.?[0-9]{0,10})$/;

            //**************

            if(inputBuscador.dataset.idmedicamento == 0){
                toastr.error("Producto es requerido");
                return;
            }

            if(fechaVenc === ''){
                toastr.error('Fecha Vencimiento no es valida');
                return;
            }

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



            var nomProducto = document.getElementById('inputBuscador').value;





            //*************

            if(precioProducto === ''){
                toastr.error('Precio Producto es requerido');
                return;
            }

            if(!precioProducto.match(reglaNumeroDiesDecimal)) {
                toastr.error('Precio Producto debe ser número Decimal (10 decimales)');
                return;
            }

            if(precioProducto < 0){
                toastr.error('Precio Producto no debe ser negativo');
                return;
            }

            if(precioProducto > 9000000){
                toastr.error('Precio Producto debe ser máximo 9 millones');
                return;
            }

            let precioProductoFormat = "$" + Number(precioProducto).toFixed(2);

            //**************



            // Crear un objeto Date a partir del valor del input
            const fecha = new Date(fechaVenc);

            // Obtener el día, mes y año
            const dia = fecha.getDate();
            const mes = fecha.getMonth() + 1; // Los meses comienzan desde 0, así que sumamos 1
            const anio = fecha.getFullYear();

            let fechaFormat = dia + "/" + mes + "/" + anio;


            var nFilas = $('#matriz >tbody >tr').length;
            nFilas += 1;

            var markup = "<tr>" +

                "<td>" +
                "<p id='fila" + (nFilas) + "' class='form-control' style='max-width: 65px'>" + (nFilas) + "</p>" +
                "</td>" +

                "<td>" +
                "<input name='arrayNombre[]' disabled data-idmedicamento='" + inputBuscador.dataset.idmedicamento + "' value='" + nomProducto + "' class='form-control' type='text'>" +
                "</td>" +


                "<td>" +
                "<input name='arrayPrecio[]' data-precio='" + precioProducto + "' disabled value='$" + precioProducto + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayCantidad[]' disabled value='" + cantidad + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayLote[]' disabled value='" + lote + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayFecha[]' data-fecha='" + fechaVenc + "' disabled value='" + fechaFormat + "' class='form-control' type='text'>" +
                "</td>" +


                "<td>" +
                "<button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'>Borrar</button>" +
                "</td>" +

                "</tr>";

            $("#matriz tbody").append(markup);

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


        function preguntarGuardar(){

            Swal.fire({
                title: '¿Actualizar Registro?',
                text: '',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO'
            }).then((result) => {
                if (result.isConfirmed) {
                    registrarMedicamento();
                }
            })
        }


        function registrarMedicamento(){

            var numFactura = document.getElementById('numero-factura').value;
            var tipoFactura = document.getElementById('select-tipofactura').value;
            var fuenteFina = document.getElementById('select-fuentefina').value;
            var proveedor = document.getElementById('select-proveedor').value;
            var fecha = document.getElementById('fecha-editar').value;

            if(fecha === ''){
                toastr.error('Fecha es requerido');
                return;
            }

            if(numFactura === ''){
                toastr.error('Número Factura es requerido');
                return;
            }

            if(tipoFactura === ''){
                toastr.error('Tipo Factura es requerido');
                return;
            }

            if(fuenteFina === ''){
                toastr.error('Fuente Financiamiento es requerido');
                return;
            }

            if(proveedor === ''){
                toastr.error('Proveedor es requerido');
                return;
            }

            var nRegistro = $('#matriz > tbody >tr').length;

            if (nRegistro <= 0){
                toastr.error('Productos a Ingresar son requeridos');
                return;
            }


            var arrayIdMedicamento = $("input[name='arrayNombre[]']").map(function(){return $(this).attr("data-idmedicamento");}).get();
            var arrayCantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();
            var arrayPrecio = $("input[name='arrayPrecio[]']").map(function(){return $(this).attr("data-precio");}).get();
            var arrayLote = $("input[name='arrayLote[]']").map(function(){return $(this).val();}).get();
            var arrayFecha = $("input[name='arrayFecha[]']").map(function(){return $(this).attr("data-fecha");}).get();


            var reglaNumeroEntero = /^[0-9]\d*$/;
            var reglaNumeroDiesDecimal = /^([0-9]+\.?[0-9]{0,10})$/;


            // VALIDACIONES DE CADA FILA, RECORRER 1 ELEMENTO YA QUE TODOS TIENEN LA MISMA CANTIDAD DE FILAS

            colorBlancoTabla();

            for(var a = 0; a < arrayIdMedicamento.length; a++){

                let idMedicamento = arrayIdMedicamento[a];
                let cantidadProducto = arrayCantidad[a];
                let precioProducto = arrayPrecio[a];
                let loteProducto = arrayLote[a];
                let fechaProducto = arrayFecha[a];


                // identifica si el 0 es tipo number o texto
                if(idMedicamento == 0){
                    colorRojoTabla(a);
                    alertaMensaje('info', 'No encontrado', 'En la Fila #' + (a+1) + " El Producto no se encuentra. Por favor borrar la Fila y buscar de nuevo el Producto");
                    return;
                }

                // **** VALIDAR CANTIDAD DE PRODUCTO

                if (cantidadProducto === '') {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad de producto es requerida. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (!cantidadProducto.match(reglaNumeroEntero)) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad debe ser entero y no negativo. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (cantidadProducto <= 0) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad no debe ser negativo. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (cantidadProducto > 9000000) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad máximo 9 millones. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }



                // **** VALIDAR CANTIDAD DE PRODUCTO

                if (precioProducto === '') {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Precio de producto es requerida. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (!precioProducto.match(reglaNumeroDiesDecimal)) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Precio debe ser decimal (10 decimales) y no negativo. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (precioProducto <= 0) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Precio no debe ser negativo. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if (precioProducto > 9000000) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Precio máximo 9 millones. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }


                if(loteProducto === ''){
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Lote de Producto no se encuentra. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }

                if(fechaProducto === ''){
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Fecha de Producto no se encuentra. Por favor borrar la Fila y buscar de nuevo el Producto');
                    return;
                }
            }

            openLoading();

            let formData = new FormData();


            const contenedorArray = [];


            for(var i = 0; i < arrayIdMedicamento.length; i++){

                let infoIdMedicamento = arrayIdMedicamento[i];
                let infoCantidad = arrayCantidad[i];
                let infoPrecio = arrayPrecio[i];
                let infoLote = arrayLote[i];
                let infoFecha = arrayFecha[i];

                // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                contenedorArray.push({ infoIdMedicamento, infoCantidad, infoPrecio, infoLote, infoFecha });
            }

            let identrada = {{ $infoEntrada->id }};

            formData.append('contenedorArray', JSON.stringify(contenedorArray));
            formData.append('numFactura', numFactura);
            formData.append('tipoFactura', tipoFactura);
            formData.append('fuenteFina', fuenteFina);
            formData.append('proveedor', proveedor);
            formData.append('identrada', identrada);
            formData.append('fecha', fecha);


            axios.post(url+'/registrar/actualizar/medicamento', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Entrada Actualizada',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                vistaAtras();
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

        function limpiar(){

            $("#matriz tbody tr").remove();
        }

        function colorRojoTabla(index){
            $("#matriz tr:eq("+(index+1)+")").css('background', '#F1948A');
        }

        function colorBlancoTabla(){
            $("#matriz tbody tr").css('background', 'white');
        }


        function vistaAtras(){
            window.location.href="{{ url('/admin/vista/todos/listado/entradas') }}";
        }


        function infoEditarDecimales(idmedientrada){

            openLoading();
            document.getElementById("formulario-decimales").reset();

            console.log(idmedientrada)

            axios.post(url+'/modificar/entrada/medicamento/detalle',{
                'id': idmedientrada
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarDecimales').modal('show');
                        $('#id-entramedi-decimal').val(idmedientrada);
                        $('#precio-edicion').val(response.data.info.precio);
                        $('#lote-edicion').val(response.data.info.lote);
                        $('#fechavencimiento-edicion').val(response.data.info.fecha_vencimiento);

                    }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }


        function guardarEdicionFormato(){

            var id = document.getElementById('id-entramedi-decimal').value;
            var precioProducto = document.getElementById('precio-edicion').value;
            var lote = document.getElementById('lote-edicion').value;
            var fecha = document.getElementById('fechavencimiento-edicion').value;

            var reglaNumeroDiesDecimal = /^([0-9]+\.?[0-9]{0,10})$/;

            if (precioProducto === '') {
                toastr.error('Precio es requerido');
                return;
            }

            if (!precioProducto.match(reglaNumeroDiesDecimal)) {
                toastr.error('Precio máximo 10 decimales');
                return;
            }

            if (precioProducto <= 0) {
                toastr.error('Precio no puede ser cero o negativo');
                return;
            }

            if (precioProducto > 9000000) {
                toastr.error('Precio no debe ser mayor a 9 millones');
                return;
            }


            if(lote === ''){
                toastr.error('Lote es requerido');
                return;
            }

            if(fecha === ''){
                toastr.error('Fecha de Vencimiento es requerido');
                return;
            }


            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('precio', precioProducto);
            formData.append('lote', lote);
            formData.append('fechavencimiento', fecha);

            axios.post(url+'/actualizar/entrada/medicamento/detalle', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Actualizado',
                            text: '',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            cancelButtonText: 'NO',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargar();
                            }
                        })
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


        function recargar(){
            location.reload();
        }


    </script>


@endsection
