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
                    <h3 class="card-title">AGREGAR ARTICULO</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <form id="formulario-articulo">

                                <section class="content">
                                    <div class="container-fluid">
                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Línea: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-linea" onchange="verificarLinea()">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayLinea as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">sub Línea: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-sublinea">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arraySubLinea as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </section>




                                <section style="margin-top: 25px">

                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-10" style="color: #686868">Código de Artículo: </label>
                                                <div class="col-md-10">
                                                    <input type="text" maxlength="300" autocomplete="off"
                                                           class="form-control" id="codigo-articulo">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-10" style="color: #686868">Nombre/Descripción: </label>
                                                <div class="col-md-10">
                                                    <input type="text" maxlength="300" autocomplete="off" class="form-control" id="nombre-descripcion" >
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                </section>



                               <!-- BLOQUE PARA LINEA MEDICAMENTO -->

                                <div id="bloque-medicamentos" style="display: none">

                                    <section style="margin-top: 25px">

                                    <hr>

                                    <div class="row">


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-10" style="color: #686868">Nombre Generico: </label>
                                                <div class="col-md-10">
                                                    <input type="text" maxlength="300" autocomplete="off" class="form-control" id="nombre-generico" >
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">

                                                <div class="form-group">
                                                    <label style="color: #686868">Envase: </label>

                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control" id="select-envase">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayEnvase as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                            <button type="button" class="btn" style="background-color: #ffa616" onclick="verModalExtraInformacion(1)"><i class="fas fa-plus" style="color: white"></i></button>
                                                    </div>




                                                </div>
                                        </div>


                                    </div>

                                </section>

                                    <section style="margin-top: 15px">
                                        <div class="row">


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label style="color: #686868">Forma Farmaceutica: </label>


                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control" id="select-formafarmaceutica">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayFormaFarmaceutica as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn" style="background-color: #ffa616" onclick="verModalExtraInformacion(2)"><i class="fas fa-plus" style="color: white"></i></button>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label style="color: #686868">Concentración: </label>

                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control" id="select-concentracion">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayConcentracion as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn" style="background-color: #ffa616" onclick="verModalExtraInformacion(3)"><i class="fas fa-plus" style="color: white"></i></button>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                    </section>


                                    <section style="margin-top: 15px">

                                        <div class="row">


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label style="color: #686868">Contenido: </label>


                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control" id="select-contenido">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayContenido as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn" style="background-color: #ffa616" onclick="verModalExtraInformacion(4)"><i class="fas fa-plus" style="color: white"></i></button>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Via Administración: </label>

                                                    <div class="col-md-10 input-group">
                                                        <select class="form-control" id="select-viaadministracion">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayAdministracion as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn" style="background-color: #ffa616" onclick="verModalExtraInformacion(5)"><i class="fas fa-plus" style="color: white"></i></button>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                    </section>




                                </div>
                                <!-- END BLOQUE PARA LINEA MEDICAMENTO -->

                                <section style="margin-top: 15px">

                                    <div class="row">

                                        <!-- EXSITENCIA MINIMA PARA NOTIFICAR QUE SE ACABA-->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-10" style="color: #686868">Existencia Mínima (para ser notificado): </label>
                                                <div class="col-md-10">
                                                    <input type="number" onkeypress="return valida_numero(event);" autocomplete="off" class="form-control" id="existencia-minima" >
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </section>


                            </form>

                            <br>
                            <hr>


                            <section class="content">
                                <div class="container-fluid">

                                        <div style="margin-right: 30px">
                                            <button type="button" style="float: right" class="btn btn-success" onclick="registrar();">Registrar Artículo</button>

                                        </div>

                                </div>
                            </section>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <div class="modal fade" id="modalExtraInformacion">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="txtTituloExtra"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-extra-datos">
                        <div class="card-body">

                            <div>
                                <input id="idtipo-extra-info" type="hidden">
                            </div>

                            <div class="form-group" style="margin-top: 20px">
                                <div class="box-header with-border">
                                    <label>Nombre</label>
                                </div>
                                <input maxlength="300" id="extranombre-via-nuevo" class="form-control" autocomplete="off">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarExtraInformacion()">Guardar</button>
                </div>
            </div>
        </div>
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

            $('#select-linea').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-sublinea').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-envase').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-formafarmaceutica').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-concentracion').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });


            $('#select-contenido').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            $('#select-viaadministracion').select2({
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

        function verModalExtraInformacion(idtipo){

            document.getElementById("formulario-extra-datos").reset();

            if(idtipo == 1){
                document.getElementById("txtTituloExtra").innerHTML = "Registrar Tipo: Envase";
            }else if(idtipo == 2){
                document.getElementById("txtTituloExtra").innerHTML = "Registrar Tipo: Forma Farmaceutica";
            }else if(idtipo == 3){
                document.getElementById("txtTituloExtra").innerHTML = "Registrar Tipo: Concentración";
            }else if(idtipo == 4){
                document.getElementById("txtTituloExtra").innerHTML = "Registrar Tipo: Contenido";
            }else{
                document.getElementById("txtTituloExtra").innerHTML = "Registrar Tipo: Via Administración";
            }

            $('#idtipo-extra-info').val(idtipo);

            $('#modalExtraInformacion').modal('show');
        }


        function guardarExtraInformacion(){

            var idtipo = document.getElementById("idtipo-extra-info").value;
            var nombre = document.getElementById("extranombre-via-nuevo").value;

            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }


            openLoading();
            var formData = new FormData();
            formData.append('idtipo', idtipo);
            formData.append('nombre', nombre);

            axios.post(url+'/guardar/contenidofarma/get/listado', formData, {
            })
                .then((response) => {
                    closeLoading();

                    // 1- ENVASE
                    // 2- FORMA FARMACEUTICA
                    // 3- CONCENTRACION
                    // 4- CONTENIDO
                    // 5- VIA ADMINISTRACION

                    if(response.data.success === 1){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-envase").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-envase').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-envase").trigger("change");

                        $('#modalExtraInformacion').modal('hide');
                    }


                    else if(response.data.success === 2){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-formafarmaceutica").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-formafarmaceutica').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-formafarmaceutica").trigger("change");

                        $('#modalExtraInformacion').modal('hide');
                    }

                    else if(response.data.success === 3){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-concentracion").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-concentracion').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-concentracion").trigger("change");

                        $('#modalExtraInformacion').modal('hide');
                    }


                    else if(response.data.success === 4){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-contenido").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-contenido').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-contenido").trigger("change");

                        $('#modalExtraInformacion').modal('hide');
                    }


                    if(response.data.success === 5){
                        toastr.success('Guardado correctamente');

                        document.getElementById("select-viaadministracion").options.length = 0;

                        $.each(response.data.lista, function( key, val ){
                            $('#select-viaadministracion').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        });
                        $("#select-viaadministracion").trigger("change");

                        $('#modalExtraInformacion').modal('hide');
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







        function verificarLinea(){
            var id = document.getElementById('select-linea').value;

            if(id == 1){
                document.getElementById('bloque-medicamentos').style.display = "block";
            }else{
                document.getElementById('bloque-medicamentos').style.display = "none";
            }
        }

        function valida_numero(e){
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
                return true;
            }

            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }


        function registrar(){

            var idLinea = document.getElementById('select-linea').value;
            var idSubLinea = document.getElementById('select-sublinea').value;
            var codigoArticulo = document.getElementById('codigo-articulo').value;
            var nombre = document.getElementById('nombre-descripcion').value;

            var existenciaMinima = document.getElementById('existencia-minima').value;
            var nombreGenerico = document.getElementById('nombre-generico').value;

            // bloque medicamentos
            var idEnvase = document.getElementById('select-envase').value;
            var idFormaFarmace = document.getElementById('select-formafarmaceutica').value;
            var idConcentracion = document.getElementById('select-concentracion').value;
            var idContenido = document.getElementById('select-contenido').value;
            var idAdministracion = document.getElementById('select-viaadministracion').value;


            if (idLinea === '') {
                toastr.error('Línea es requerido');
                return;
            }


            if (nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }


            var reglaNumeroEntero = /^[0-9]\d*$/;

            if(existenciaMinima === ''){
                existenciaMinima = 0;
            }else{
                if(!existenciaMinima.match(reglaNumeroEntero)) {
                    toastr.error('Existencia Mínima es requerido');
                    return;
                }

                if(existenciaMinima < 0){
                    toastr.error('Existencia Mínima no debe tener negativos');
                    return;
                }

                if(existenciaMinima > 9000000){
                    toastr.error('Existencia Mínima máximo debe ser 9 millones');
                    return;
                }
            }

            openLoading();
            var formData = new FormData();

            formData.append('idLinea', idLinea);
            formData.append('idSubLinea', idSubLinea);
            formData.append('codigoArticulo', codigoArticulo);
            formData.append('nombre', nombre);
            formData.append('existencia', existenciaMinima);
            formData.append('idEnvase', idEnvase);
            formData.append('idFormaFarma', idFormaFarmace);
            formData.append('idConcentracion', idConcentracion);
            formData.append('idContenido', idContenido);
            formData.append('idAdministracion', idAdministracion);
            formData.append('nombreGenerico', nombreGenerico);

            axios.post(url + '/farmacia/registrar/nuevo/articulo', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {

                        Swal.fire({
                            title: 'Código Repetido',
                            text: "El Código de Artículo ya se encuentra registrado",
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
                    else if (response.data.success === 2) {
                        toastr.success('Registrado correctamente');
                        borrarCampos();
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }


        function borrarCampos(){
            document.getElementById("formulario-articulo").reset();

            document.getElementById('select-linea').selectedIndex = 0;
            $("#select-linea").trigger("change");
            document.getElementById('select-sublinea').selectedIndex = 0;
            $("#select-sublinea").trigger("change");
            document.getElementById('bloque-medicamentos').style.display = "none";


            document.getElementById('select-envase').selectedIndex = 0;
            $("#select-envase").trigger("change");


            document.getElementById('select-formafarmaceutica').selectedIndex = 0;
            $("#select-formafarmaceutica").trigger("change");

            document.getElementById('select-concentracion').selectedIndex = 0;
            $("#select-concentracion").trigger("change");

            document.getElementById('select-contenido').selectedIndex = 0;
            $("#select-contenido").trigger("change");

            document.getElementById('select-viaadministracion').selectedIndex = 0;
            $("#select-viaadministracion").trigger("change");


        }



    </script>

@endsection
