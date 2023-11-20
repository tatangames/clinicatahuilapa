<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th  style="width: 10%">Fecha Receta</th>
                                <th  style="width: 10%">Fecha Procesada</th>
                                <th  style="width: 16%">Paciente</th>
                                <th  style="width: 16%">Recetado Por</th>
                                <th  style="width: 16%">Descripción</th>

                                <th  style="width: 20%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayRecetas as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }} </td>
                                    <td>{{ $dato->fechaEstadoFormat }} </td>
                                    <td>{{ $dato->nombrepaciente }} </td>
                                    <td>{{ $dato->doctor }} </td>
                                    <td>{{ $dato->descripcion_general }} </td>
                                    <td>

                                        <!-- MUESTRA EN PDF LA RECETA -->

                                        <button class="btn btn-info button-small" style="color: white; margin: 8px; font-weight: bold" onclick="informacionImprimir({{$dato->id}})" title="PDF">IMPRIMIR</button>

                                    </td>
                                </tr>
                            @endforeach

                            <script>
                                closeLoading();
                            </script>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $("#tabla").DataTable({
            columnDefs: [
                { type: 'date-euro', targets: [0,1] } // Suponiendo que la columna de fecha es la primera (índice 0)
            ],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "Todo"]],
            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }

            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        });
    });


</script>
