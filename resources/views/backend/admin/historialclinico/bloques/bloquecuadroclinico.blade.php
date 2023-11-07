<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title" style="font-weight: bold">Historial de Cuadros Clinicos</h3>

        <button type="button" style="float: right ;font-weight: bold; background-color: #28a745; color: white !important;"
                onclick="modalCuadroClinico()" class="button button-3d button-rounded button-pill button-small">
            <i class="fas fa-plus"></i>
            Nuevo Cuadro Clínico
        </button>

    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tableCuadroClinico" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Fecha de Consulta</th>
                                <th>Consulta #</th>
                                <th>Tipo Diagnóstico</th>

                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($bloqueCuadroClinico as $dato)
                                <tr>
                                    <td>{{ $dato->fechaFormat }}</td>
                                    <td>{{ $dato->consulta_id }}</td>
                                    <td>{{ $dato->nombreDiagnostico }}</td>

                                    <td>
                                        <button type="button" class="btn btn-success btn-xs" style="color: white" onclick="informacionCuadroClinico({{ $dato->id }})">
                                            <i class="fas fa-print" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" class="btn btn-warning btn-xs" style="color: white" onclick="imprimirCuadroClinico({{ $dato->id }})">
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
        $("#tableCuadroClinico").DataTable({
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


    function modalCuadroClinico(){
        document.getElementById('select-tipo-diagnostico').selectedIndex = 0;
        $("#select-tipo-diagnostico").trigger("change");
        $('#modalNuevoHistoClinico').modal({backdrop: 'static', keyboard: false})
    }

    function guardarNuevoCuadroClinico(){

        let tipoDiagnostico = document.getElementById('select-tipo-diagnostico').value;

        if(tipoDiagnostico === ''){
            toastr.error('Tipo Diagnóstico es requerido');
            return;
        }

        const editorData = varGlobalEditorCuadro.getData();

        if(editorData === ''){
            toastr.error('Descripción es requerida');
            return;
        }else{

            openLoading();

            let idconsulta = {{ $idconsulta }};

            var formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('diagnostico', tipoDiagnostico);
            formData.append('descripcion', editorData);

            axios.post(url+'/historial/nuevo/historialclinico', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');

                        document.getElementById('editorCuadroClinico').value = '';
                        $('#modalNuevoHistoClinico').modal('hide');

                        recargarTablaCuadroClinico();
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
    }

    function recarcarTablaCuadroClinico(){
        let idconsulta = {{ $idconsulta }};
        var rutaCuadroClinico = "{{ URL::to('/admin/historial/bloque/cuadroclinico') }}/" + idconsulta;
        $('#tablaCuadroClinico').load(rutaCuadroClinico);
    }


    function informacionCuadroClinico(id){

        openLoading();

        axios.post(url+'/historial/informacion/historialclinico',{
            'id': id
        })
            .then((response) => {
                closeLoading();
                if(response.data.success === 1){

                    $('#idCuadroClinico-editar').val(response.data.info.id);


                    document.getElementById("select-tipo-diagnostico-editar").options.length = 0;

                    $.each(response.data.arraydiagnostico, function( key, val ){
                        if(response.data.info.diagnostico_id == val.id){
                            $('#select-tipo-diagnostico-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                        }else{
                            $('#select-tipo-diagnostico-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                        }
                    });


                    varGlobalEditorCuadroEditar.setData(response.data.info.descripcion);

                    $('#modalEditarHistoClinico').modal({backdrop: 'static', keyboard: false})

                }else{
                    toastr.error('Información no encontrada');
                }
            })
            .catch((error) => {
                closeLoading();
                toastr.error('Información no encontrada');
            });
    }


    function actualizarCuadroClinico(){

        let tipoDiagnostico = document.getElementById('select-tipo-diagnostico-editar').value;

        if(tipoDiagnostico === ''){
            toastr.error('Tipo Diagnóstico es requerido');
            return;
        }

        const editorData = varGlobalEditorCuadroEditar.getData();

        if(editorData === ''){
            toastr.error('Descripción es requerida');
            return;
        }else{

            openLoading();

            let idCuadro = document.getElementById('idCuadroClinico-editar').value;

            var formData = new FormData();

            formData.append('idCuadro', idCuadro);
            formData.append('diagnostico', tipoDiagnostico);
            formData.append('descripcion', editorData);

            axios.post(url+'/historial/actualizar/historialclinico', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');

                        document.getElementById('editorCuadroClinico-editar').value = '';
                        $('#modalEditarHistoClinico').modal('hide');

                        recargarTablaCuadroClinico();
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



    }






</script>
