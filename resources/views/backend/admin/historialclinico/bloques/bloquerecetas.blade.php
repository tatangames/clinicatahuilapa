<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title" style="font-weight: bold">Historial de Recetas</h3>

        @if($existeReceta == 0)
        <button type="button" style="float: right ;font-weight: bold; background-color: #28a745; color: white !important;"
                onclick="vistaNuevaReceta()" class="button button-3d button-rounded button-pill button-small">
            <i class="fas fa-plus"></i>
            Nueva Receta
        </button>

        @endif
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tableRecetas" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>FECHA</th>
                                <th>CREADO POR</th>
                                <th>DIAGNOSTICO</th>
                                <th>ESTADO ORDEN</th>

                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayRecetas as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }}</td>
                                    <td>{{ $dato->nombreusuario }}</td>
                                    <td>{{ $dato->descripcion_general }}</td>
                                    <td>

                                        @if($dato->estado == 1)
                                                <span class="badge bg-warning">Pendiente</span>
                                        @elseif($dato->estado == 2)
                                                <span class="badge bg-success">Procesada</span>
                                        @else
                                                <span class="badge bg-danger">Denegada</span>
                                        @endif

                                    </td>


                                    <td>

                                        @if($dato->estado == 1)
                                            <button type="button" class="btn btn-warning btn-xs" style="color: white" onclick="infoEditarReceta({{ $dato->id }})">
                                                <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-info btn-xs" style="color: white" onclick="infoEditarReceta({{ $dato->id }})">
                                                <i class="fas fa-eye" title="Ver"></i>&nbsp; Ver
                                            </button>
                                        @endif


                                        <button type="button" class="btn btn-success btn-xs" style="color: white" onclick="imprimirRecetaMedica({{ $dato->id }})">
                                            <i class="fas fa-print" title="Imprimir"></i>&nbsp; Imprimir
                                        </button>
                                    </td>

                                </tr>
                            @endforeach

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
        $("#tableRecetas").DataTable({
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
