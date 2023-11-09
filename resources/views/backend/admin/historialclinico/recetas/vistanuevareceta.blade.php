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
            <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;" onclick="recargarVista()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-arrow-left"></i>
                Atras
            </button>
        </div>
    </section>


    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">FICHA PARA RECETA</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-5">
                                <div class="card" style="border-radius: 15px;">
                                    <div class="card-body p-2">
                                        <div class="d-flex text-black">

                                            <div style="margin-left: 15px">
                                                <h5 style="font-weight: bold">RECETA PARA PACIENTE:</h5>
                                                <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                                <section>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha:</label>
                                                <input type="date" class="form-control" id="fecha" value="{{ $fechaActual }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Diagnóstico</label>

                                                <select id="select-dianostico" class="form-control">
                                                    <option value="">Seleccionar Opción</option>
                                                    @foreach($arrayDiagnostico as $item)
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Vía</label>

                                                <select id="select-via" class="form-control">
                                                    <option value="">Seleccionar Opción</option>
                                                    @foreach($arrayVia as $item)
                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                    </div>

                                </section>




                            <section>

                                <div class="row">

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Indicaciones generales:</label>

                                            <textarea class="form-control" id="text-indicacion-general" rows="3" cols="5"></textarea>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Proxima Cita:</label>
                                            <input type="date" class="form-control" id="proxima-cita">
                                        </div>
                                    </div>

                                </div>

                            </section>



                            <hr><br>

                            <label style="color: #5b7073">MEDICAMENTOS</label>


                            <section>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Fuente Financiamiento</label>

                                            <select id="select-fuente" class="form-control" onchange="cargarTablaProducto()">
                                                <option value="">Seleccionar Opción</option>
                                                @foreach($arrayFuente as $item)
                                                    <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Indicaciones Medicamento:</label>
                                            <textarea type="text" class="form-control" id="indicacion-medicamento"></textarea>
                                        </div>
                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Medicamento</label>
                                            <select id="select-medicamento" class="form-control" onchange="getNombreGenerico()">
                                                <option value="" disabled selected>Seleccionar Fuente Financiamiento</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Nombre Generico</label>
                                            <input type="text" class="form-control" disabled id="nombre-generico">
                                        </div>
                                    </div>


                                </div>


                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Cantidad</label>
                                            <input type="number" min="0" max="100" class="form-control" id="cantidad">
                                        </div>
                                    </div>

                                </div>



                            </section>

                            <br>

                            <section class="content" style="float: right">
                                <div class="container-fluid">

                                    <div style="margin-right: 30px">
                                        <button type="button" style="float: right" class="btn btn-success" onclick="agregarFila();">Agregar Medicamento</button>
                                    </div>

                                </div>
                            </section>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Detalle de Medicamento</h2>
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
                        <th style="width: 10%">Medicamento</th>
                        <th style="width: 6%">Nombre Generico</th>
                        <th style="width: 6%">Cantidad</th>
                        <th style="width: 6%">Indicaciones</th>
                        <th style="width: 5%">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>



    <div class="modal-footer justify-content-between float-right" style="margin-top: 25px; margin-bottom: 30px; display: none" id="bloqueGuardarTabla">
        <button type="button" class="btn btn-success" onclick="preguntarGuardar()">Guardar Receta Médica</button>
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

            $('#select-dianostico').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-via').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-medicamento').select2({
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


        function recargarVista(){
            location.reload();
        }

        function vistaAtras(){
            history.back();
        }


        function cargarTablaProducto(){

            var idFuente = document.getElementById('select-fuente').value;

            document.getElementById("nombre-generico").value = "";

            if(idFuente === ''){
                document.getElementById("select-medicamento").options.length = 0;
                $('#select-medicamento').append('<option value="" selected disabled>Seleccionar Fuente Financiamiento</option>');
            }else{
                openLoading();
                let formData = new FormData();

                formData.append('idfuente', idFuente);

                axios.post(url+'/recetas/medicamentos/porfuente', formData, {
                })
                    .then((response) => {
                        closeLoading();
                        if(response.data.success === 1){

                            document.getElementById("select-medicamento").options.length = 0;

                            if(response.data.hayfilas){
                                $('#select-medicamento').append('<option value="" data-generico="" data-cantitotal="" data-nombre="" selected>Seleccionar Medicamento</option>');
                                $.each(response.data.dataArray, function( key, val ){
                                    $('#select-medicamento').append('<option value="' +val.id +'" data-generico="' +val.nombreGenerico +'" data-cantitotal="' +val.cantidadTotal +'" data-nombre="' +val.nombre +'">'+val.nombretotal+'</option>');
                                });
                            }else{
                                $('#select-medicamento').append('<option value="" data-generico="" data-cantitotal="" data-nombre="">Sin Medicamentos</option>');
                            }

                        }else{
                            toastr.error('Información no encontrada');
                        }

                    })
                    .catch((error) => {
                        closeLoading();
                        toastr.error('Información no encontrada');
                    });

            }
        }


        function getNombreGenerico(){

            var miSelect = document.getElementById("select-medicamento");
            var opcionSeleccionada = miSelect.options[miSelect.selectedIndex];
            var dataInfoValue = opcionSeleccionada.getAttribute("data-generico");
            document.getElementById("nombre-generico").value = dataInfoValue;
        }


        function agregarFila(){


            let idmedicamento = document.getElementById("select-medicamento").value;
            let indicacionesTexto = document.getElementById("indicacion-medicamento").value;
            let cantidadSalida = document.getElementById("cantidad").value;
            let nombreGenerico = document.getElementById("nombre-generico").value;

            if(idmedicamento === ''){
                toastr.error('Medicamento es requerido');
                return;
            }

            if(indicacionesTexto === ''){
                toastr.error('Indicaciones para Medicamento es requerido');
                return;
            }

            var reglaNumeroEntero = /^[0-9]\d*$/;

            if(cantidadSalida === ''){
                toastr.error('Cantidad a Retirar es requerido');
                return;
            }

            if(!cantidadSalida.match(reglaNumeroEntero)) {
                toastr.error('Cantidad a Retirar es requerido');
                return;
            }

            if(cantidadSalida < 0){
                toastr.error('Cantidad a Retirar no debe tener negativos');
                return;
            }

            if(cantidadSalida > 9000000){
                toastr.error('Cantidad a Retirar máximo debe ser 9 millones');
                return;
            }


            // VERIFICAR MAXIMO A RETIRAR
            var miSelect = document.getElementById("select-medicamento");
            var opcionSeleccionada = miSelect.options[miSelect.selectedIndex];
            var dataInfoCantidad = opcionSeleccionada.getAttribute("data-cantitotal");
            var dataInfoNombre = opcionSeleccionada.getAttribute("data-nombre");

            let totalHay = parseInt(dataInfoCantidad);
            let totalSalida = parseInt(cantidadSalida);

            if(totalSalida > totalHay){
                Swal.fire({
                    title: 'Cantidad Excedida',
                    text: 'Actualmente hay Disponible: ' + totalHay,
                    icon: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    allowOutsideClick: false,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {

                    }
                })

                return;
            }


            // INGRESAR A TABLA


            var nFilas = $('#matriz >tbody >tr').length;
            nFilas += 1;

            var markup = "<tr>" +

                "<td>" +
                "<p id='fila" + (nFilas) + "' class='form-control' style='max-width: 65px'>" + (nFilas) + "</p>" +
                "</td>" +

                "<td>" +
                "<input name='arrayNombre[]' disabled data-idmedicamento='" + idmedicamento + "' value='" + dataInfoNombre + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input disabled value='" + nombreGenerico + "' class='form-control' type='text'>" +
                "</td>" +

                "<td>" +
                "<input name='arrayCantidad[]' disabled value='" + cantidadSalida + "' class='form-control' type='number'>" +
                "</td>" +

                "<td>" +
                "<textarea name='arrayIndicacion[]'  class='form-control' type='text'>" + indicacionesTexto +"</textarea>" +
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

            document.getElementById("indicacion-medicamento").value = "";
            document.getElementById("cantidad").value = "";
            document.getElementById("bloqueGuardarTabla").style.display = "block";
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

            if(conteo == 0){
                document.getElementById("bloqueGuardarTabla").style.display = "none";
            }
        }


        function preguntarGuardar(){

            Swal.fire({
                title: '¿Guardar Receta?',
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

            var fecha = document.getElementById('fecha').value;
            var diagnostico = document.getElementById('select-dianostico').value;
            var via = document.getElementById('select-via').value;
            var indicacionGeneral = document.getElementById('text-indicacion-general').value;
            var proximaCita = document.getElementById('proxima-cita').value;


            if(fecha === ''){
                toastr.error('Fecha es requerido');
                return;
            }

            if(diagnostico === ''){
                toastr.error('Diagnóstico es requerido');
                return;
            }

            if(via === ''){
                toastr.error('Vía es requerido');
                return;
            }


            var nRegistro = $('#matriz > tbody >tr').length;

            if (nRegistro <= 0){
                toastr.error('Lista de Medicamentos son requeridos');
                return;
            }


            var arrayIdMedicamentos = $("input[name='arrayNombre[]']").map(function(){return $(this).attr("data-idmedicamento");}).get();
            var arrayCantidad = $("input[name='arrayCantidad[]']").map(function(){return $(this).val();}).get();
            var arrayDeTextareas = $("#matriz textarea[name='arrayIndicacion[]']").map(function(){
                return $(this).val();
            }).get();


            var reglaNumeroEntero = /^[0-9]\d*$/;

            // VALIDACIONES DE CADA FILA, RECORRER 1 ELEMENTO YA QUE TODOS TIENEN LA MISMA CANTIDAD DE FILAS

            colorBlancoTabla();

            for(var a = 0; a < arrayIdMedicamentos.length; a++){

                let infoIdMedic = arrayIdMedicamentos[a];
                let infoCantidad = arrayCantidad[a];
                let infoIndicaciones = arrayDeTextareas[a];

                if(infoIdMedic == ''){
                    colorRojoTabla(a);
                    alertaMensaje('info', 'No encontrado', 'En la Fila #' + (a+1) + " El Medicamento no se encuentra. Por favor borrar la Fila y agregar de nuevo el Medicamento");
                    return;
                }

                // **** VALIDAR CANTIDAD DE PRODUCTO

                if (infoCantidad === '') {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad de Medicamento es requerida. Por favor borrar la Fila y buscar de nuevo el Medicamento');
                    return;
                }

                if (!infoCantidad.match(reglaNumeroEntero)) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad debe ser entero y no negativo. Por favor borrar la Fila y buscar de nuevo el Medicamento');
                    return;
                }

                if (infoCantidad <= 0) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad no debe ser negativo. Por favor borrar la Fila y buscar de nuevo el Medicamento');
                    return;
                }

                if (infoCantidad > 9000000) {
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Cantidad máximo 9 millones. Por favor borrar la Fila y buscar de nuevo el Medicamento');
                    return;
                }


                if(infoIndicaciones === ''){
                    colorRojoTabla(a);
                    toastr.error('Fila #' + (a + 1) + ' Descripción para el Medicamento es requerido');
                    return;
                }
            }

            openLoading();

            let idconsulta = {{ $idconsulta }};

            let formData = new FormData();

            const contenedorArray = [];


            for(var i = 0; i < arrayIdMedicamentos.length; i++){

                let infoIdMedicamento = arrayIdMedicamentos[i];
                let infoCantidad = arrayCantidad[i];
                let infoIndicacion = arrayDeTextareas[i];

                // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                contenedorArray.push({ infoIdMedicamento, infoCantidad, infoIndicacion});
            }

            formData.append('contenedorArray', JSON.stringify(contenedorArray));
            formData.append('idconsulta', idconsulta);
            formData.append('fecha', fecha);
            formData.append('diagnostico', diagnostico);
            formData.append('via', via);
            formData.append('indicacionGeneral', indicacionGeneral);
            formData.append('proximaCita', proximaCita);


            axios.post(url+'/recetas/registro/parapaciente', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        Swal.fire({
                            title: "Receta Ya Registrada",
                            text: "Para esta consulta ya se ha registrado una Receta",
                            icon: 'error',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                salirVistaHistorialClinico();
                            }
                        })

                    }
                    else if(response.data.success === 2){
                        Swal.fire({
                            title: "Receta Registrada",
                            text: "",
                            icon: 'success',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                salirVistaHistorialClinico();
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

        function colorBlancoTabla(){
            $("#matriz tbody tr").css('background', 'white');
        }

        function colorRojoTabla(index){
            $("#matriz tr:eq("+(index+1)+")").css('background', '#F1948A');
        }


        function salirVistaHistorialClinico(){

            let idconsulta = {{ $idconsulta }};
            window.location.href="{{ url('/admin/historial/clinico/vista') }}/" + idconsulta;
        }



    </script>


@endsection
