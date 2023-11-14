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


    <section class="content-header">
        <div class="container-fluid">
            <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;"
                    onclick="recargarVista()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-arrow-left"></i>
                Atras
            </button>
        </div>
    </section>

<section>



    <div class="row" style="margin-left: 5px; margin-right: 5px">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">FICHA CLINICA</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>

                </div>

                <div class="card-body">

                    <div class="d-flex text-black">
                        <div >
                            @if($infoPaciente->foto == null)
                                <img style="margin-left: 15px" alt="Sin Foto" src="{{ asset('images/foto-default.png') }}" width="120px" height="120px" />
                            @else
                                <img style="margin-left: 15px" alt="Foto Paciente" src="{{ url('storage/archivos/'.$infoPaciente->foto) }}" width="120px" height="120px" />
                            @endif
                        </div>

                        <div>
                            <div style="margin-left: 15px">
                                <h5 style="font-weight: bold">FICHA CLINICA</h5>
                                <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
                                <p><span class="badge bg-primary" style="font-size: 14px">Edad:  {{ $edad }}</span></p>
                                <p><span class="badge bg-primary" style="font-size: 14px">Recetado Por:  {{ $nombreDoctor }}</span></p>
                                <p><span class="badge bg-primary" style="font-size: 14px; color: white !important;">Fecha Receta:  {{ $fechaReceta }}</span></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">LISTA DE MEDICAMENTOS A DESPACHAR</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>

                </div>

                <div class="card-body">
                    @foreach($arrayNombreMedicamento as $info)
                        <li>{{ $info->nombreFormat }}</li>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
</section>


    <section>

        <p></p>

        <div class="row" style="margin-left: 5px; margin-right: 5px">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">SELECCIONAR SALIDA</h3>

                    </div>

                    <div class="card-body">






                        @foreach($arrayDetalle as $material)

                            <!-- CONTENEDOR PADRE -->
                            <div class="material-bloque" data-nombre="{{ $material->nombre }}" data-iddetalle="{{ $material->id }}" data-cantidad="{{ $material->cantidad }}">

                                <p style="font-weight: bold">Medicamento: <span style="font-weight: normal!important;">{{ $material->nombre }}</span></p>
                                <p style="font-weight: bold">Nombre Generico: <span style="font-weight: normal!important;">{{ $material->nombreGenerico }}</span></p>
                                <p style="font-weight: bold">Cantidad Solitada: <span style="font-weight: normal!important;">{{ $material->cantidad }}</span></p>


                                @if($material->conteo == 0)

                                    <span class="badge bg-danger"> NO HAY MEDICAMENTO SOLICITADO </span>
                                    <br><hr style="height: 2px; background-color: #000; border: none"><br>

                                @else

                                    @foreach($material->listadetalle as $bloqueAdicional)

                                        <div class="bloque-adicional">
                                            <div class="col-md-12">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <div class="form-group">

                                                            <div class="row" >

                                                                <div class="col-sm-3 border-right">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header">{{ $bloqueAdicional->fechaVencimiento }}</h5>
                                                                        <span class="description-text1">Fecha de Vencimiento</span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3 border-right">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header">{{ $bloqueAdicional->cantidad }}</h5>
                                                                        <span class="description-text1">Cantidad Disponible</span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3 border-right">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header">{{ $bloqueAdicional->lote }}</h5>
                                                                        <span class="description-text1">LOTE</span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-3 border-right">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header">{{ $bloqueAdicional->fechaEntrada }}</h5>
                                                                        <span class="description-text1">Fecha de Entrada</span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>


                                                    <div class="card-body row col-md-4">
                                                        <span style="font-weight: bold">Ingresar Cantidad </span>

                                                        <input onchange="calcularInput(this);" style="font-weight: bold; border: 1px solid black" onkeypress="return valida_numero(event);" type="number" class="form-control miInputColor" name="{{$material->id}}cantidad"
                                                               data-idreceta="{{ $material->id }}"  data-cantimaxima="{{ $bloqueAdicional->cantidadMaxima }}"
                                                               data-cantimaximaPadre="{{ $material->cantidad }}"
                                                               data-identradadetalle="{{ $bloqueAdicional->identradadetalle }}"
                                                               min="0" max="{{ $bloqueAdicional->cantidadMaxima }}" >

                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                    @endforeach



                                        <div class="row float-right">
                                            <label id="{{ $material->id }}label" data-iddetalle="{{ $material->id }}" disabled>Total: 0</label>
                                        </div>

                                    <br><hr style="height: 2px; background-color: #000; border: none"><br>
                                @endif

                            </div> <!-- END CONTENDOR PADRE -->

                        @endforeach




                    </div>
                </div>
            </div>

        </div>
    </section>


    <div class="modal-footer float-right" style="margin-top: 25px; margin-bottom: 35px">
        <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="verificarSalida()">VERIFICAR SALIDA</button>
    </div>





    <div class="modal fade" id="modalCantiSuperada">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cantidad Superada</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">

                                    <label style="font-weight: normal" id="label-cantidad-excedida"></label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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

    <script type="text/javascript">
        $(document).ready(function(){

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargarVista(){
            location. reload();
        }

        function verificarSalida(){

            // SETEAR TODOS LOS INPUT DE LA CLASE miInputColor A COLOR NEGRO

            var elementos = document.querySelectorAll('input.' + 'miInputColor');
            elementos.forEach(function(elemento) {
                elemento.style.border = '1px solid black';
            });

           // RECORRER CADA BLOQUE PADRE Y SUS INPUT DINAMICOS

            var minimoUnaSalida = true;


            $('.material-bloque').each(function () {
                var $bloque = $(this);
                var nombreMedicamento = $bloque.data('nombre');
                var cantidadRequeridaMaxima = $bloque.data('cantidad');
                var iddetalle = $bloque.data('iddetalle');
                var unido = iddetalle + "cantidad";

                var sumarCantidad = 0;

                var elementos = document.querySelectorAll('input[name="' + unido + '"]');

                elementos.forEach(function (elemento) {
                    if(elemento.value !== ''){
                        if(parseInt(elemento.value) > 0){
                            minimoUnaSalida = false;
                        }
                        sumarCantidad = sumarCantidad + parseInt(elemento.value);
                    }
                });

                if(sumarCantidad > parseInt(cantidadRequeridaMaxima)){

                    // COLOCAR EN ROJO ESOS INPUT DEL MEDICAMENTO ESPECIFICADO

                    elementos.forEach(function (elemento) {
                        elemento.style.border = '1px solid red';
                    });

                    let texto = "<strong>" + "El Medicamento: " + "</strong>" + nombreMedicamento + "<br>"
                        + "<strong>" + "Solicita la Receta: " + "</strong>" + cantidadRequeridaMaxima + " Unidades" +"<br>"
                        + "<strong>" + "Y se quiere Retirar, la cantidad de: " + "</strong>" + sumarCantidad + " Unidades " + "<br>"
                        + "<strong>" + "Porfavor bajar las unidades a las Solicitadas " + "</strong>" + "<br>"

                    document.getElementById("label-cantidad-excedida").innerHTML = texto;

                    $('#modalCantiSuperada').modal('show');
                    return false;
                }
            });

            if(minimoUnaSalida){
                Swal.fire({
                    title: 'Cantidad es Requerida',
                    text: 'No se ha elegido ninguna cantidad para algun Medicamento',
                    icon: 'error',
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


            // AGREGARLOS A UN ARRAY PARA ENVIAR EL SERVIDOR

            openLoading();

            let idReceta = {{ $idreceta }};

            const datosArray = [];

            $('.material-bloque').each(function () {
                var $bloque = $(this);
                var iddetalle = $bloque.data('iddetalle');
                var unido = iddetalle + "cantidad";

                var elementos = document.querySelectorAll('input[name="' + unido + '"]');

                elementos.forEach(function (elemento) {
                    if(elemento.value !== ''){

                        var idEntradaDetalle = elemento.getAttribute('data-identradadetalle');

                        if(elemento.value !== ''){

                            let salida = parseInt(elemento.value);

                            if(salida !== 0){
                                // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                                datosArray.push({ idEntradaDetalle, salida });
                            }
                        }
                    }
                });
            });


            var formData = new FormData();
            formData.append('idreceta', idReceta);
            formData.append('contenedorArray', JSON.stringify(datosArray));


            axios.post(url+'/receta/procesar/guardarsalida', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
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



        function calcularInput(input){

            var idReceta = input.getAttribute('data-idreceta');
            var cantidadMaximaPadre = input.getAttribute('data-cantimaximaPadre');
            var cantidadMaximaInput = input.getAttribute('data-cantimaxima');

            var unido = idReceta + "cantidad";

            // SI EL INPUT INGRESADO ES MAYOR AL MAXIMO DEL BLOQUE, CAMBIARLO AL MAXIMO DEL BLOQUE
            if(parseInt(input.value) > cantidadMaximaInput){
                input.value = cantidadMaximaInput;
            }

            // SUMAR CANTIDAD PARA SETEAR EL LABEL
            var sumarCantidad = 0;

            var elementos = document.querySelectorAll('input[name="' + unido + '"]');

            elementos.forEach(function (elemento) {

                if(elemento.value !== ''){
                    sumarCantidad = sumarCantidad + parseInt(elemento.value);
                }
            });

            let unidoLabel = idReceta + "label";
            document.getElementById(unidoLabel).innerHTML = "Total: " + sumarCantidad;

            if(sumarCantidad > cantidadMaximaPadre){
                // rojo
                document.getElementById(unidoLabel).style.color = "red";
            }else{
                // negro
                document.getElementById(unidoLabel).style.color = "black";
            }
        }




    </script>


@endsection
