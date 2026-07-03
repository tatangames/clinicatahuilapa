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


    .swal2-popup.custom-swal {
        width: 80% !important;
        max-width: 800px !important;
    }

    /* Checkbox existencia */
    .custom-existencia-check {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
        background: #f8f9fa;
        border: 2px solid #ced4da;
        border-radius: 30px;
        padding: 6px 16px 6px 8px;
        transition: all 0.25s ease;
        width: fit-content;
    }
    .custom-existencia-check:hover {
        border-color: #28a745;
        background: #eafaf1;
    }
    .custom-existencia-check.activo {
        border-color: #28a745;
        background: #eafaf1;
    }
    .check-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s ease;
        flex-shrink: 0;
    }
    .custom-existencia-check.activo .check-icon {
        background: #28a745;
    }
    .check-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        transition: color 0.25s;
    }
    .custom-existencia-check.activo .check-label {
        color: #28a745;
    }

</style>

<div id="divcontenedor" style="display: none">

    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">REPORTES</h3>
                </div>
                <div class="card-body">

                    <section class="content" style="margin-left: 30px">
                        <div class="container-fluid">
                            <p style="font-weight: bold">Este reporte no hace calculos con la Fecha</p>
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label style="color: #686868">Desde: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha-desde">
                                </div>

                                <div class="form-group col-md-2" >
                                    <label style="color: #686868">Hasta: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha-hasta">
                                </div>

                                <div class="form-group" style="margin-top: 30px">
                                    <button type="button" class="btn btn-success form-control" onclick="verificar()">Generar</button>
                                </div>

                            </div>

                        </div>
                    </section>



                    <section class="content" style="margin-left: 30px; margin-top: 30px">
                        <div class="container-fluid">
                            <p style="font-weight: bold">Este reporte toma en cuenta las Fechas para columnas (Entregado Total y Total Desca. Fechas)</p>
                            <div class="row align-items-end">

                                <div class="form-group col-md-2">
                                    <label style="color: #686868">Desde: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha2-desde">
                                </div>

                                <div class="form-group col-md-2">
                                    <label style="color: #686868">Hasta: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="fecha2-hasta">
                                </div>

                                {{-- CHECKBOX EXISTENCIA --}}
                                <div class="form-group col-md-3" style="margin-bottom: 16px">
                                    <div class="custom-existencia-check" id="checkExistencia" onclick="toggleCheck()" title="Solo mostrar artículos con existencia mayor a 0">
                                        <div class="check-icon" id="checkIcon">
                                            <i class="fas fa-check" id="checkMark" style="display:none; color:#fff; font-size:13px;"></i>
                                        </div>
                                        <span class="check-label" id="checkLabel">Solo Existencia &gt; 0</span>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 0px; margin-bottom: 16px">
                                    <button type="button" class="btn btn-success" onclick="verificar2()">Generar</button>
                                </div>

                            </div>
                        </div>
                    </section>


                    {{-- NUEVO REPORTE: CONTROL DE ENTRADAS/SALIDAS POR PERIODO --}}
                    <section class="content" style="margin-left: 30px; margin-top: 30px">
                        <div class="container-fluid">
                            <p style="font-weight: bold">Reporte con Saldo Inicial, Entradas, Salidas y Saldo Final según el período seleccionado</p>
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label style="color: #686868">Desde: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="periodo-desde">
                                </div>

                                <div class="form-group col-md-2">
                                    <label style="color: #686868">Hasta: </label>
                                    <input type="date" autocomplete="off" class="form-control" id="periodo-hasta">
                                </div>

                                <div class="form-group" style="margin-top: 30px">
                                    <button type="button" class="btn btn-success form-control" onclick="pdfPeriodosFarmacia()">Generar</button>
                                </div>

                            </div>

                        </div>
                    </section>


                    <p>EXPLICACIÓN CADA COLUMNA</p>
                    <div class="form-group col-md-1" style="margin-top: 30px">
                        <button type="button" class="btn btn-success form-control" onclick="explicacionColumna()">VER</button>
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


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>


        function verificar(){

            let fechaDesde = document.getElementById("fecha-desde").value;
            let fechaHasta = document.getElementById("fecha-hasta").value;

            if(fechaDesde === ''){
                toastr.error('Fecha Desde es requerido');
                return;
            }

            if(fechaHasta === ''){
                toastr.error('Fecha Hasta es requerido');
                return;
            }

            var fecha1 = new Date(fechaDesde);
            var fecha2 = new Date(fechaHasta);

            if (fecha1 > fecha2) {
                toastr.error('Fecha Desde no puede ser mayor que Fecha Hasta')
                return;
            }

            window.open("{{ URL::to('admin/pdf/reporte/final') }}/" + fechaDesde + "/" + fechaHasta);
        }


        let soloExistencia = false;

        function toggleCheck() {
            soloExistencia = !soloExistencia;
            const wrapper = document.getElementById('checkExistencia');
            const mark    = document.getElementById('checkMark');
            if (soloExistencia) {
                wrapper.classList.add('activo');
                mark.style.display = 'inline';
            } else {
                wrapper.classList.remove('activo');
                mark.style.display = 'none';
            }
        }

        function verificar2(){
            let fechaDesde = document.getElementById("fecha2-desde").value;
            let fechaHasta = document.getElementById("fecha2-hasta").value;

            if(fechaDesde === ''){
                toastr.error('Fecha Desde es requerido');
                return;
            }
            if(fechaHasta === ''){
                toastr.error('Fecha Hasta es requerido');
                return;
            }

            var fecha1 = new Date(fechaDesde);
            var fecha2 = new Date(fechaHasta);

            if (fecha1 > fecha2) {
                toastr.error('Fecha Desde no puede ser mayor que Fecha Hasta');
                return;
            }

            let filtro = soloExistencia ? '1' : '0';
            window.open("{{ URL::to('admin/pdf/reporte/finalv2') }}/" + fechaDesde + "/" + fechaHasta + "/" + filtro);
        }


        // NUEVO REPORTE: Control de Entradas/Salidas por Periodo
        function pdfPeriodosFarmacia(){
            let fechaDesde = document.getElementById("periodo-desde").value;
            let fechaHasta = document.getElementById("periodo-hasta").value;

            if(fechaDesde === ''){
                toastr.error('Fecha Desde es requerido');
                return;
            }
            if(fechaHasta === ''){
                toastr.error('Fecha Hasta es requerido');
                return;
            }

            var fecha1 = new Date(fechaDesde);
            var fecha2 = new Date(fechaHasta);

            if (fecha1 > fecha2) {
                toastr.error('Fecha Desde no puede ser mayor que Fecha Hasta');
                return;
            }

            window.open("{{ URL::to('admin/pdf/reporte/inicial/final') }}/" + fechaDesde + "/" + fechaHasta);
        }


        function explicacionColumna(){

            let mensaje = "COSTO: Precio unitario del medicamento (costo normal)<br>" +
                "COSTO DONA: Precio unitario del medicamento por donación<br>" +
                "CANTIDAD INICIAL: Cantidad con la que ingresó el lote al sistema<br>" +
                "ENTREGADO: Cantidad total entregada acumulada hasta la fecha final del intervalo<br>" +
                "ENTREGADO TOTAL: Cantidad entregada únicamente dentro del rango de fechas del reporte<br>" +
                "EXISTENCIA: Cantidad disponible en bodega (Cantidad Inicial - Entregado acumulado hasta fecha final)<br>" +
                "TOTAL DESCARGADO: Costo * Entregado (acumulado hasta fecha final, no solo el rango)<br>" +
                "TOTAL DESCARGADO DONAC: Costo Dona * Entregado (acumulado hasta fecha final, no solo el rango)<br>" +
                "TOTAL DESCA. FECHAS: Costo * Entregado Total (solo dentro del rango de fechas)<br>" +
                "TOTAL DESCA. DONA FECHAS: Costo Dona * Entregado Total (solo dentro del rango de fechas)<br>" +
                "TOTAL EXISTENCIA: Costo * Existencia (cantidad disponible en bodega)<br>" +
                "TOTAL EXISTENCIA DONA: Costo Dona * Existencia (cantidad disponible en bodega)";

            Swal.fire({
                title: 'Información Columnas',
                html: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                confirmButtonText: 'Aceptar',
                customClass: {
                    popup: 'custom-swal',  // Asegúrate de que esta clase esté bien definida
                    title: 'swal-title',   // Si también quieres personalizar el título
                    htmlContainer: 'swal-html-container' // Si quieres personalizar el contenedor del texto
                }
            });

        }

    </script>

@endsection
