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
                                <th  style="width: 10%"># Consulta</th>
                                <th  style="width: 16%">Paciente</th>
                                <th  style="width: 16%">Recetado Por</th>

                                <th  style="width: 20%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayRecetas as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }} </td>
                                    <td>{{ $dato->consulta_id }} </td>
                                    <td>{{ $dato->nombrepaciente }} </td>
                                    <td>{{ $dato->doctor }} </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" style="color: white" onclick="imprimirRecetaMedica({{ $dato->id }})">
                                            <i class="fas fa-print" title="Imprimir"></i>&nbsp; Imprimir
                                        </button>

                                        <button type="button" class="btn btn-success btn-xs" style="color: white; margin: 5px" onclick="procesarRecetaMedica({{ $dato->id }})">
                                            <i class="fas fa-list" title="Imprimir"></i>&nbsp; Procesar
                                        </button>


                                        @if($dato->btnRetornar == 1)
                                            <button type="button" class="btn btn-dark btn-xs" style="color: white; margin: 5px" onclick="retornarPaciente({{ $dato->id }})">
                                                <i class="fas fa-list" title="Retornar"></i>&nbsp; Retornar
                                            </button>
                                        @endif


                                        <button type="button" class="btn btn-danger btn-xs" style="color: white; margin: 5px" onclick="infoDenegarReceta({{ $dato->id }})">
                                            <i class="fas fa-trash" title="Denegar"></i>&nbsp; Denegar
                                        </button>
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
                { type: 'date-euro', targets: 0 } // Suponiendo que la columna de fecha es la primera (índice 0)
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


    function imprimirRecetaMedica(idreceta){
        window.open("{{ URL::to('admin/reporte/receta/paciente') }}/" + idreceta);
    }


</script>
