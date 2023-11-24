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
                    onclick="volverAtras()" class="button button-3d button-rounded button-pill button-small">
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
                    <h3 class="card-title">FICHA CLINICA - PROCESAR RECETA</h3>
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



    </div>
</section>


    <section>

        <p></p>

        <div class="row" style="margin-left: 5px; margin-right: 5px">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">LISTADO DE MEDICAMENTOS A DESPACHAR</h3>

                    </div>

                    <div class="card-body">


                        <table class="table" id="matriz" data-toggle="table" style="margin-right: 15px; margin-left: 15px;">
                            <thead>
                            <tr>
                                <th style="width: 3%">#</th>
                                <th style="width: 10%">Medicamento</th>
                                <th style="width: 6%">Lote</th>
                                <th style="width: 6%">Cantidad Retirar</th>
                                <th style="width: 6%">Cantidad Bodega</th>
                                <th style="width: 6%">Fecha Vencimiento</th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach($arrayNombreMedicamento as $item)
                                @if($item->cantidadRetirar > $item->cantidadActual)
                                    <tr style="background-color: #ab2b2b">
                                @else
                                    <tr>
                                @endif

                                    <td>
                                        <p id="fila" class="form-control" style="max-width: 65px">{{ $item->contador }}</p>
                                    </td>
                                    <td>
                                        <input disabled value="{{ $item->nombreFormat }}" class="form-control" type="text">
                                    </td>
                                    <td>
                                        <input disabled value="{{ $item->lote }}" class="form-control" type="text">
                                    </td>
                                    <td>
                                        <input disabled value="{{ $item->cantidadRetirar }}" class="form-control" type="text">
                                    </td>

                                    <td>
                                        <input disabled value="{{ $item->cantidadActual }}" class="form-control" type="text">
                                    </td>

                                    <td>
                                        <input disabled value="{{ $item->fechaVencimiento }}" class="form-control" type="text">
                                    </td>

                                </tr>

                            @endforeach

                            </tbody>
                        </table>



                    </div>
                </div>
            </div>

        </div>
    </section>


    <section>

        <div class="col-md-8" style="margin-left: 25px">
            <div class="form-group">
                <label class="control-label col-md-3">Notas Adicionales: </label>
                <div class="col-md-9">
                    <textarea id="text-notas" cols="17" rows="4" class="form-control"></textarea>
                </div>
            </div>
        </div>


    </section>


    <div class="modal-footer float-right" style="margin-top: 25px; margin-bottom: 35px">
        <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="preguntarGuardarSalida()">VERIFICAR SALIDA</button>
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

        function preguntarGuardarSalida(){

            Swal.fire({
                title: 'Guardar Salida',
                text: '',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                cancelButtonText: 'NO',
                confirmButtonText: 'SI'
            }).then((result) => {
                if (result.isConfirmed) {
                    verificarSalida();
                }
            })

        }

        function verificarSalida(){

            openLoading();

            let idreceta = {{ $idreceta }};

            var formData = new FormData();
            formData.append('idreceta', idreceta);

            axios.post(url+'/receta/procesar/guardarsalida', formData, {
            })
                .then((response) => {
                    closeLoading();

                    // ESTADO CAMBIO ASI QUE REGRESAR ATRAS

                    if(response.data.success === 1){

                        Swal.fire({
                            title: 'Estado de Receta Cambio',
                            text: "Revisar de Nuevo",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                volverAtras();
                            }
                        })
                    }

                    // NO HAY FUCIENTE MEDICAMENTO PARA X MEDICINA
                    else if(response.data.success === 2) {

                        let infoNombre = response.data.nombre;
                        let infoCantidadHay = response.data.cantidadhay;
                        let infoLote = response.data.lote;
                        let infoFechaVenc = response.data.fechavencimiento;
                        let infoCantidadSalida = response.data.cantidadsalida;

                        Swal.fire({
                            title: 'Cantidad Insuficiente',
                            html: "El Medicamento: " + infoNombre + "<br>"
                                + "LOTE: "+ infoLote +"<br>"
                                + "Fecha de Vencimiento: "+ infoFechaVenc +"<br>"
                                + "Donde la Cantidad Actual es: "+ infoCantidadHay +"<br>"
                                + "Y se quiere Despachar, la cantidad de: "+ infoCantidadSalida +"<br>"
                            ,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista();
                            }
                        })

                    }
                    else if(response.data.success === 3){

                        Swal.fire({
                            title: 'Receta Procesada',
                            text: "",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                volverAtras();
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

        function volverAtras(){
            window.location.href="{{ url('/admin/salida/medicamento/porreceta/index') }}";
        }


    </script>


@endsection
