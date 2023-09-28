@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    #card-header-color {
        background-color: cyan !important;
    }

</style>

<div id="divcontenedor" style="display: none">


    <section class="content-header">
        <div class="row mb-2">

        </div>
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ASIGNACIÓN DE SALAS</h3>
                </div>

                <section class="content-header" style="margin-left: 25px">
                    <div class="row mb-2">

                        <button type="button" style="font-weight: bold; background-color: #36b62e; color: white !important;"
                                onclick="modalAgregar()" class="button button-3d button-rounded button-pill button-small">
                            <i class="fas fa-pencil-alt"></i>
                            Nueva Asignación
                        </button>

                    </div>
                </section>


                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row ">


                                        <div class="col-md-6">

                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h3 class="card-title">En Espera 0</h3>
                                                </div>
                                                <div class="card-body">
                                                    <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg">
                                                    <br>
                                                    <input class="form-control" type="text" placeholder="Default input">
                                                    <br>
                                                    <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="card card-success">
                                                <div class="card-header">
                                                    <h3 class="card-title">En Espera 0</h3>
                                                </div>
                                                <div class="card-body">
                                                    <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg">
                                                    <br>
                                                    <input class="form-control" type="text" placeholder="Default input">
                                                    <br>
                                                    <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm">
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </section>


                            <section style="margin-top: 25px">


                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title" style="color: white; font-weight: bold">Sala de espera</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>

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


                            </section>





                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center">
                    <h4 class="modal-title" style="color: darkred; font-weight: bold; text-align: center">ASIGNACION A SALA DE ESPERA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <p>Buscar por: nombre, apellido, número de documento</p>
                                        <p>La búsqueda regresa: # Expediente - Nombre y Apellido - Documento</p>
                                        <table class="table" id="matriz-busqueda" data-toggle="table">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input id="repuesto" data-info='0' autocomplete="off" class='form-control' style='width:100%' onkeyup='buscarPaciente(this)' maxlength='400'  type='text'>
                                                    <div class='droplista' style='position: absolute; color: black !important; z-index: 9; width: 75% !important;'></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px">
                                        <label class="control-label">Razón de Uso: </label>
                                        <select id="select-razon" class="form-control">
                                            <option value="">Seleccione una Razón del uso</option>
                                            @foreach($arrayRazonUso as $item)
                                                <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="preguntaGuardar()">Guardar</button>
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
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $('#select-paciente').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function () {
                        return "Búsqueda no encontrada";
                    }
                },
            });

            window.seguroBuscador = true;
            window.txtContenedorGlobal = this;

            window.idPacienteGlobal = 0;

            $(document).click(function(){
                $(".droplista").hide();
            });


            var ruta = "{{ URL::to('/admin/asignaciones/paciente/esperando') }}";
            $('#tablaDatatable').load(ruta);

            verificarSalaEspera();


            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        function verificarSalaEspera(){

            var hayDatos = {{ $hayPacientes }};

            if(hayDatos>0){
                // habilitar tabla y cargar datos
                document.getElementById("divcontenedor").style.display = "block";
            }else{
                document.getElementById("divcontenedor").style.display = "hidden";
            }

        }

        function recargar(){
            var ruta = "{{ URL::to('/admin/xx') }}";
            $('#tablaDatatable').load(ruta);
        }


        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }



        function buscarPaciente(e){

            // seguro para evitar errores de busqueda continua
            if(seguroBuscador){
                seguroBuscador = false;

                var row = $(e).closest('tr');
                txtContenedorGlobal = e;

                let texto = e.value;

                if(texto === ''){
                    // si se limpia el input, setear el atributo id
                    $(e).attr('data-info', 0);
                    idPacienteGlobal = 0;
                }

                axios.post(url+'/asignaciones/buscar/paciente', {
                    'query' : texto
                })
                    .then((response) => {

                        seguroBuscador = true;
                        $(row).each(function (index, element) {
                            $(this).find(".droplista").fadeIn();
                            $(this).find(".droplista").html(response.data);
                        });
                    })
                    .catch((error) => {
                        seguroBuscador = true;
                    });
            }
        }

        function modificarValor(edrop){

            // obtener texto del li
            let texto = $(edrop).text();
            // setear el input de la descripcion
            $(txtContenedorGlobal).val(texto);

            // agregar el id al atributo del input descripcion
            $(txtContenedorGlobal).attr('data-info', edrop.id);

            idPacienteGlobal = edrop.id;

            //$(txtContenedorGlobal).data("info");
        }


        function preguntaGuardar(){

            Swal.fire({
                title: 'Guardar Asignación?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    guardarRegistro();
                }
            })
        }

        function guardarRegistro(){

            console.log('id es: ' + idPacienteGlobal);

            if(idPacienteGlobal === 0){
                toastr.error('Paciente es requerido');
                return;
            }

            var razonUso = document.getElementById('select-razon').value;

            if(razonUso === ''){
                toastr.error('Razón de uso es requerido');
                return;
            }

            openLoading();

            let formData = new FormData();
            formData.append('idpaciente', idPacienteGlobal);
            formData.append('idrazon', razonUso);

            axios.post(url+'/asignaciones/nuevo/registro', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        borrarDatos();
                    }
                    else{
                        toastr.error('error al guardar');
                    }
                })
                .catch((error) => {
                    toastr.error('error al guardar');
                    closeLoading();
                });
        }


        function borrarDatos(){

            document.getElementById('select-razon').selectedIndex = 0;
            idPacienteGlobal = 0;
            $("#matriz tbody tr").remove();
            document.getElementById('repuesto').value = '';
        }




    </script>


@endsection
