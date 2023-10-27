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
                                                    <input type="text" maxlength="25" autocomplete="off" class="form-control" id="celular" >
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Envase: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-envase">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayEnvase as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                        </div>


                                    </div>

                                </section>

                                    <section style="margin-top: 15px">
                                        <div class="row">


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Forma Farmaceutica: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-formafarmaceutica">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayFormaFarmaceutica as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Concentración: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-concentracion">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayConcentracion as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                    </section>


                                    <section style="margin-top: 15px">

                                        <div class="row">


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Contenido: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-contenido">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayContenido as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-md-10" style="color: #686868">Via Administración: </label>
                                                    <div class="col-md-10">
                                                        <select class="form-control" id="select-viaadministracion">
                                                            <option value="">Seleccionar Opción</option>
                                                            @foreach($arrayAdministracion as $item)
                                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                            @endforeach
                                                        </select>
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
                                                <label class="control-label col-md-10" style="color: #686868">Existencia Mínima: </label>
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

            if (idSubLinea === '') {
                toastr.error('Sub Línea es requerido');
                return;
            }


            if (nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }


            var reglaNumeroEntero = /^[0-9]\d*$/;


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


            axios.post(url + '/farmacia/registrar/nuevo/articulo', formData, {})
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
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
        }



    </script>

@endsection
