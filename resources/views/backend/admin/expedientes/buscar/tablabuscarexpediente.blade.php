<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th># Exp</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Documento</th>
                                <th>Profesión</th>
                                <th>Médico de Cabecera</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayExpedientes as $dato)
                                <tr>
                                    <td>{{ $dato->id }}</td>
                                    <td>{{ $dato->nombres }}</td>
                                    <td>{{ $dato->apellidos }}</td>
                                    <td>{{ $dato->num_documento }}</td>
                                    <td>{{ $dato->profesion }}</td>
                                    <td>{{ $dato->medico }}</td>

                                    <td>

                                        <div style="text-align:center;">


                                            <button type="button" class="btn btn-success btn-sm" onclick="informacion({{ $dato->id }})">
                                                <i class="fas fa-file" title="Documentos y Recetas"></i>&nbsp;
                                            </button>

                                            <button type="button" class="btn btn-primary btn-sm" onclick="informacion({{ $dato->id }})">
                                                <i class="fas fa-list-alt" title="Datos Generales"></i>&nbsp;
                                            </button>

                                            <button type="button" class="btn btn-warning btn-sm" onclick="informacion({{ $dato->id }})">
                                                <i class="fas fa-file-alt" title="Subir Documento"></i>&nbsp;
                                            </button>

                                            <button type="button" class="btn btn-secondary btn-sm" onclick="informacion({{ $dato->id }})">
                                                <i class="fas fa-image" title="Subir Imagen"></i>&nbsp;
                                            </button>

                                        </div>
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
        $("#tabla").DataTable({
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
