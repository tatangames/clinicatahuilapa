<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title" style="font-weight: bold">Historial de Notas</h3>

        <button type="button" style="float: right ;font-weight: bold; background-color: #28a745; color: white !important;"
                onclick="vistaNuevaNota()" class="button button-3d button-rounded button-pill button-small">
            <i class="fas fa-plus"></i>
            Nueva Nota
        </button>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tableNotas" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>FECHA</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayNotas as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }}</td>

                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs" style="color: white" onclick="informacionEditarNota({{ $dato->id }})">
                                            <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" class="btn btn-danger btn-xs" style="color: white" onclick="modalBorrarNota({{ $dato->id }})">
                                            <i class="fas fa-trash" title="Borrar"></i>&nbsp; Borrar
                                        </button>

                                        <button type="button" class="btn btn-success btn-xs" style="color: white" onclick="generarReporteNota({{ $dato->id }})">
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
        // Añadir el tipo de datos personalizado para fechas en formato d-m-y
        $.fn.dataTable.ext.type.order['date-dmy-pre'] = function (d) {
            // Divide la fecha por el guion
            var parts = d.split('-');
            // Retorna en formato YYYYMMDD
            return parts[2] + parts[1] + parts[0];
        };


        $("#tableNotas").DataTable({
            columnDefs: [
                { type: 'date-dmy', targets: 0 } // La columna de fecha es la primera (índice 0)
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


    function imprimirNotaPaciente(idreceta){
        window.open("{{ URL::to('admin/') }}/" + idreceta);
    }


</script>
