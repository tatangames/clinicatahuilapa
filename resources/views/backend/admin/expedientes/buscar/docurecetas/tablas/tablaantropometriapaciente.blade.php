<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title" style="font-weight: bold">Historial de Antropología</h3>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>FECHA</th>
                                <th>HORA</th>
                                <th>CREADA POR</th>
                                <th>F.C</th>
                                <th>T/A</th>
                                <th>PESO LB</th>
                                <th>PESO KG</th>
                                <th>TALLA</th>

                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($bloqueAntropSv as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }}</td>
                                    <td>{{ $dato->horaFormat }}</td>
                                    <td>{{ $dato->nomusuario }}</td>
                                    <td>{{ $dato->frecuencia_cardiaca }}</td>
                                    <td>{{ $dato->presion_arterial }}</td>
                                    <td>{{ $dato->peso_libra }}</td>
                                    <td>{{ $dato->peso_kilo }}</td>
                                    <td>{{ $dato->estatura }}</td>

                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="vistaVisualizarAntropologia({{ $dato->id }})">
                                            <i class="fas fa-eye" title="Ver"></i>&nbsp; Ver
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


    function vistaVisualizarAntropologia(idantrop){
        window.location.href="{{ url('/admin/vista/visualizar/antropologia') }}/" + idantrop;
    }


</script>
