<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tablaEnfermeria" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>HORA</th>
                                <th>PACIENTE</th>
                                <th>RAZON USO</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayTablaEnfermeria as $dato)
                                <tr>
                                    <td>{{ $dato->horaFormat }}</td>
                                    <td>{{ $dato->nombrepaciente }}</td>
                                    <td>{{ $dato->razonUso }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="infoAsignarAsalaPaciente({{ $dato->id }})">
                                          Asignar
                                        </button>

                                        <button type="button" class="btn btn-danger btn-xs" onclick="infoModalEliminarPaciente({{ $dato->id }})">
                                          Eliminar
                                        </button>

                                        <button type="button" class="btn btn-warning btn-xs" onclick="infoModalEditarSalas({{ $dato->id }})">
                                          Editar
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
        $("#tablaEnfermeria").DataTable({
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
