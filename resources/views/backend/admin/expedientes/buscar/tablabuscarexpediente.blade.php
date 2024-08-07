<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 5%"># Exp</th>
                                <th style="width: 12%">Nombre</th>
                                <th style="width: 10%">Apellido</th>
                                <th style="width: 10%">Tipo Doc.</th>
                                <th style="width: 10%">Documento</th>
                                <th style="width: 10%">Profesión</th>
                                <th style="width: 10%">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($arrayExpedientes as $dato)
                                <tr>
                                    <td>{{ $dato->numero_expediente }}</td>
                                    <td>{{ $dato->nombres }}</td>
                                    <td>{{ $dato->apellidos }}</td>
                                    <td>{{ $dato->tipoDoc }}</td>
                                    <td>{{ $dato->num_documento }}</td>
                                    <td>{{ $dato->profesion }}</td>

                                    <td>

                                        <div style="text-align:center;">


                                            <button type="button"  title="Documentos y Recetas" class="btn btn-success btn-sm" onclick="infoDocumentoReceta({{ $dato->id }})">
                                                <i class="fas fa-file"></i>&nbsp;
                                            </button>

                                            <button type="button" title="Datos Generales" class="btn btn-primary btn-sm" onclick="infoEditarPaciente({{ $dato->id }})">
                                                <i class="fas fa-user" ></i>&nbsp;
                                            </button>

                                            <button type="button" title="Ficha General" class="btn btn-warning btn-sm" style="color: white" onclick="infoImpresion({{ $dato->id }})">
                                                <i class="fas fa-print" ></i>&nbsp;
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <script>
                            closeLoading();
                        </script>
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
