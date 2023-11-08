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


                                <div class="form-group " style="margin-top: 5px; margin-left: 30px">
                                    <label style="color: #686868">Fecha de Salida: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha-salida">
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


                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Observaciones:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" id="text-observaciones"></textarea>
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

            var boolHayItems = true;

            inputSalidas.forEach((valor) => {

                boolHayItems = false;

                const nombreMedicamento = valor.dataset.nombremedi;
                const idEntrada = valor.dataset.identrada;
                //const maxCantidad = valor.dataset.maxcantidad;
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
                        "<input name='arrayNombre[]' disabled data-identrada='" + idEntrada + "' value='" + nombreMedicamento + "' class='form-control' type='text'>" +
                        "</td>" +

                        "<td>" +
                        "<input name='arrayCantidad[]' disabled value='" + inputCantidad + "' class='form-control' type='number'>" +
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

                    ocultarElecciones();
                }
            });

            if(boolHayItems){
                toastr.error('Elegir Cantidad de Salida');
            }
        }


        function ocultarElecciones(){

            document.getElementById('select-producto').selectedIndex = 0;
            $("#select-producto").trigger("change");

            document.getElementById('tablaProductos').innerHTML = "";
            document.getElementById('txtSalida').innerHTML = "";
            document.getElementById('btnAgregarFila').style.display = "none";
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

           // var arrayCantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();

            // PARA ELEGIR DE QUE TABLA VERIFICAR
            var arrayCantidad = $("#matriz input[name='arrayCantidad[]']").map(function() {
                return $(this).val();
            }).get();

            var contador = 0;

            for(var a = 0; a < arrayCantidad.length; a++){
                contador = contador + parseInt(arrayCantidad[a]);
            }

            document.getElementById('cantidadTotal').innerHTML = contador;
        }

        function preguntarGuardar(){

            Swal.fire({
                title: '¿Guardar Salida?',
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

            var motivo = document.getElementById('select-motivo').value;
            var fecha = document.getElementById('fecha-salida').value;
            var observaciones = document.getElementById('text-observaciones').value;

            if(motivo === ''){
                toastr.error('Motivo es requerido');
                return;
            }

            if(fecha === ''){
                toastr.error('Fecha Salida es requerido');
                return;
            }

            var nRegistro = $('#matriz > tbody >tr').length;

            if (nRegistro <= 0){
                toastr.error('Productos a Despachar son requeridos');
                return;
            }


            var arrayIdEntrada = $("input[name='arrayNombre[]']").map(function(){return $(this).attr("data-identrada");}).get();
            var arrayCantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();

            var reglaNumeroEntero = /^[0-9]\d*$/;

            // VALIDACIONES DE CADA FILA, RECORRER 1 ELEMENTO YA QUE TODOS TIENEN LA MISMA CANTIDAD DE FILAS

            colorBlancoTabla();

            for(var a = 0; a < arrayIdEntrada.length; a++){

                let idEntrada = arrayIdEntrada[a];
                let cantidadProducto = arrayCantidad[a];


                // identifica si el 0 es tipo number o texto
                if(idEntrada == 0){
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

            }

            openLoading();

            let formData = new FormData();


            const contenedorArray = [];


            for(var i = 0; i < arrayIdEntrada.length; i++){

                let infoIdEntrada = arrayIdEntrada[i];
                let infoCantidad = arrayCantidad[i];

                // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                contenedorArray.push({ infoIdEntrada, infoCantidad});
            }

            formData.append('contenedorArray', JSON.stringify(contenedorArray));
            formData.append('motivo', motivo);
            formData.append('fecha', fecha);
            formData.append('observaciones', observaciones);

            axios.post(url+'/registrar/orden/salida/medicamento', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        let fila = response.data.fila;
                        let cantidadHay = response.data.cantidad;
                        colorRojoTabla(fila-1);

                        Swal.fire({
                            title: 'Cantidad No Disponible',
                            text: "En la Fila #" + fila + " Se supera las Unidades Disponibles. Verificar los Productos Ingresados y sus Cantidades a Retirar. Actualmente hay: " + cantidadHay,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else if(response.data.success === 2){
                        toastr.success('Registrado correctamente');
                        limpiar();
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

            document.getElementById('select-producto').selectedIndex = 0;
            $("#select-producto").trigger("change");

            document.getElementById('select-motivo').selectedIndex = 0;
            $("#select-motivo").trigger("change");

            document.getElementById('tablaProductos').innerHTML = "";
            document.getElementById('txtSalida').innerHTML = "";
            document.getElementById('btnAgregarFila').style.display = "none";

            document.getElementById('fecha-salida').value = "";

            document.getElementById('cantidadTotal').innerHTML = "0";

            $("#matriz tbody tr").remove();
        }



    </script>

@endsection
