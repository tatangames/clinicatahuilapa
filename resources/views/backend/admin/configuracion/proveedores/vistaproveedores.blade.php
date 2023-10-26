@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>


<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
            <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" onclick="modalAgregar()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-pencil-alt"></i>
                Nuevo Proveedor
            </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Listado</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">


                            <div class="form-group">
                                <label>Tipo de Proveedor:</label>
                                    <select  id="select-tipo-proveedor" class="form-control">
                                        @foreach($listado as $item)
                                            <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                            </div>


                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nombre-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Nombre Comercial</label>
                                <input type="text" maxlength="300" autocomplete="off" class="form-control" id="nombre-comercial-nuevo">
                            </div>


                            <div class="form-group">
                                <label>NRC</label>
                                <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nrc-nuevo">
                            </div>

                            <div class="form-group">
                                <label>NIT</label>
                                <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nit-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" maxlength="500" autocomplete="off" class="form-control" id="direccion-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Contacto de Departamento</label>
                                <input type="text" maxlength="300" autocomplete="off" class="form-control" id="contacto-depa-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Teléfono fijo</label>
                                <input type="text" maxlength="20" autocomplete="off" class="form-control" id="telefono-fijo-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Teléfono Celular</label>
                                <input type="text" maxlength="20" autocomplete="off" class="form-control" id="telefono-celu-nuevo">
                            </div>

                            <div class="form-group">
                                <label>Correo</label>
                                <input type="text" maxlength="150" autocomplete="off" class="form-control" id="correo-nuevo">
                            </div>



                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="nuevo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal editar -->
    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <input type="hidden" id="id-editar">
                                    </div>


                                    <div class="form-group">
                                        <label>Tipo de Proveedor:</label>
                                        <select  id="select-tipo-proveedor-editar" class="form-control">

                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nombre-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre Comercial</label>
                                        <input type="text" maxlength="300" autocomplete="off" class="form-control" id="nombre-comercial-editar">
                                    </div>


                                    <div class="form-group">
                                        <label>NRC</label>
                                        <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nrc-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>NIT</label>
                                        <input type="text" maxlength="100" autocomplete="off" class="form-control" id="nit-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input type="text" maxlength="500" autocomplete="off" class="form-control" id="direccion-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Contacto de Departamento</label>
                                        <input type="text" maxlength="300" autocomplete="off" class="form-control" id="contacto-depa-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Teléfono fijo</label>
                                        <input type="text" maxlength="20" autocomplete="off" class="form-control" id="telefono-fijo-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Teléfono Celular</label>
                                        <input type="text" maxlength="20" autocomplete="off" class="form-control" id="telefono-celu-editar">
                                    </div>

                                    <div class="form-group">
                                        <label>Correo</label>
                                        <input type="text" maxlength="150" autocomplete="off" class="form-control" id="correo-editar">
                                    </div>



                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="editar()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            var ruta = "{{ URL::to('/admin/proveedores/vista/tabla') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ URL::to('/admin/proveedores/vista/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal({backdrop: 'static', keyboard: false})
        }


        function nuevo(){
            var tipo = document.getElementById('select-tipo-proveedor').value;
            var nombre = document.getElementById('nombre-nuevo').value;
            var nombreComercial = document.getElementById('nombre-comercial-nuevo').value;
            var nrc = document.getElementById('nrc-nuevo').value;
            var nit = document.getElementById('nit-nuevo').value;
            var direccion = document.getElementById('direccion-nuevo').value;
            var contactoDepartamento = document.getElementById('contacto-depa-nuevo').value;
            var telefonoFijo = document.getElementById('telefono-fijo-nuevo').value;
            var telefonoCelular = document.getElementById('telefono-celu-nuevo').value;
            var correo = document.getElementById('correo-nuevo').value;


            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('tipo', tipo);
            formData.append('nombre', nombre);
            formData.append('nombreComercial', nombreComercial);
            formData.append('nrc', nrc);
            formData.append('nit', nit);
            formData.append('direccion', direccion);
            formData.append('contactoDepa', contactoDepartamento);
            formData.append('telFijo', telefonoFijo);
            formData.append('telCelular', telefonoCelular);
            formData.append('correo', correo);

            axios.post(url+'/proveedores/registro', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
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

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/proveedores/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);

                        document.getElementById("select-tipo-proveedor-editar").options.length = 0;

                        $.each(response.data.arraytipo, function( key, val ){
                            if(response.data.info.tipo_proveedor_id == val.id){
                                $('#select-tipo-proveedor-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-tipo-proveedor-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });

                        $('#nombre-editar').val(response.data.info.nombre);
                        $('#nombre-comercial-editar').val(response.data.info.nombre_comercial);
                        $('#nrc-editar').val(response.data.info.nrc);
                        $('#nit-editar').val(response.data.info.nit);
                        $('#direccion-editar').val(response.data.info.direccion);
                        $('#contacto-depa-editar').val(response.data.info.departamento_contacto);
                        $('#telefono-fijo-editar').val(response.data.info.telefono_fijo);
                        $('#telefono-celu-editar').val(response.data.info.telefono_celular);
                        $('#correo-editar').val(response.data.info.correo);

                    }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar(){
            var id = document.getElementById('id-editar').value;
            var tipo = document.getElementById('select-tipo-proveedor-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var nombreComercial = document.getElementById('nombre-comercial-editar').value;
            var nrc = document.getElementById('nrc-editar').value;
            var nit = document.getElementById('nit-editar').value;
            var direccion = document.getElementById('direccion-editar').value;
            var contactoDepartamento = document.getElementById('contacto-depa-editar').value;
            var telefonoFijo = document.getElementById('telefono-fijo-editar').value;
            var telefonoCelular = document.getElementById('telefono-celu-editar').value;
            var correo = document.getElementById('correo-editar').value;


            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('tipo', tipo);
            formData.append('nombre', nombre);
            formData.append('nombreComercial', nombreComercial);
            formData.append('nrc', nrc);
            formData.append('nit', nit);
            formData.append('direccion', direccion);
            formData.append('contactoDepa', contactoDepartamento);
            formData.append('telFijo', telefonoFijo);
            formData.append('telCelular', telefonoCelular);
            formData.append('correo', correo);

            axios.post(url+'/proveedores/editar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al guardar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al guardar');
                    closeLoading();
                });
        }

    </script>


@endsection
