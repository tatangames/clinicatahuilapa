@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloTogglePequeno.css') }}" type="text/css" rel="stylesheet" />
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
            <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" onclick="recargarVista()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-pencil-alt"></i>
                recargar
            </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><span style="font-weight: bold"> Paciente: </span> {{ $nombreCompleto }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">






                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header d-flex p-0">
                                        <h3 class="card-title p-3"></h3>

                                        <div style="float: left; margin: 16px">
                                            <ul class="nav nav-pills ml-auto p-2">
                                                <li style="font-size: 16px; font-weight: bold" class="nav-item">
                                                    <a class="nav-link active" href="#tab_1" onclick="mostrarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/personacard.png') }}" height="25px" width="25px">
                                                        </span>ANTECEDENTES
                                                    </a>
                                                </li>

                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_2" onclick="ocultarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/corazonrojo.png') }}" height="25px" width="25px">
                                                        </span>
                                                        SV + ANTROP
                                                    </a>
                                                </li>



                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_3" onclick="ocultarBloque()" data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/chequelista.png') }}" height="25px" width="25px">
                                                        </span>
                                                        RECETAS Y PLAN Tx
                                                    </a>
                                                </li>





                                            </ul>
                                        </div>


                                    </div>







                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">



                                                <section class="content">
                                                    <div class="container-fluid">
                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="card-body">


                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Antecedentes Familiares:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="5" id="text-antecedentes-editar"> @if($antecedentes != null) {{ $antecedentes->antecedentes_familiares }} @endif </textarea>
                                                                    </div>


                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Alergias:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="text-alergias-editar"> @if($antecedentes != null) {{ $antecedentes->alergias }} @endif </textarea>
                                                                    </div>

                                                                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Medicamentos actuales:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="text-medicamento-actual-editar"> @if($antecedentes != null) {{ $antecedentes->medicamentos_actuales }} @endif </textarea>
                                                                    </div>

                                                                    <div class="col-form-label row" style="margin-top: 35px; margin-left: 2px">
                                                                        <label style="color: #428bca; font-weight: bold; font-size: 18px">Tipeo sanguíneo: </label>
                                                                        <div class="col-md-4">
                                                                            <select class="form-control" id="select-tipeo-sanguineo">
                                                                                <option value="">Seleccionar Opción</option>

                                                                                @if($antecedentes != null)

                                                                                    @foreach($arrayTipeoSanguineo as $item)

                                                                                        @if($antecedentes->tipeo_sanguineo_id == $item->id)
                                                                                            <option value="{{$item->id}}" selected>{{ $item->nombre }}</option>
                                                                                        @else
                                                                                            <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                                                        @endif

                                                                                    @endforeach

                                                                                @else

                                                                                    @foreach($arrayTipeoSanguineo as $item)
                                                                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                                                                    @endforeach

                                                                                @endif

                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Antecedentes Médicos:</label>


                                                                    <div class="form-group">
                                                                        <div class="row">

                                                                            @foreach($arrayAntecedentesMedico as $item)

                                                                            <div class="col-12 col-md-3">

                                                                                    {{ $valorTrue = false }}

                                                                                    @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                {{ $valorTrue = true }}
                                                                                            @endif
                                                                                    @endforeach

                                                                                    <label class="switch" style="margin: 4px !important;">

                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                            <div class="slider round">
                                                                                            </div>

                                                                                    </label>


                                                                                <label>{{ $item->nombre }}</label><br>
                                                                            </div>

                                                                            @endforeach

                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12">
                                                                        <br>
                                                                        <label>Notas de antecedentes médicos</label>
                                                                        <div class="input-group">
                                                                            <textarea class="form-control" id="notas_antecedente_medicos" rows="4">@if($antecedentes != null) {{ $antecedentes->nota_antecedente_medico }} @endif</textarea>
                                                                        </div>
                                                                    </div>








                                                                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Complicaciones Agudas en Diábetes:</label>


                                                                    <div class="form-group">
                                                                        <div class="row">

                                                                            @foreach($arrayComplicacionAguda as $item)

                                                                                <div class="col-12 col-md-3">
                                                                                    <label class="switch" style="margin: 4px !important;">



                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                            @else
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                            @endif
                                                                                        @endforeach



                                                                                        <div class="slider round">
                                                                                        </div>
                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label><br>
                                                                                </div>

                                                                            @endforeach

                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12">
                                                                        <br>
                                                                        <label>Notas</label>
                                                                        <div class="input-group">
                                                                            <textarea class="form-control" id="notas_complicacion_diabetes" rows="4">@if($antecedentes != null) {{ $antecedentes->nota_complicaciones_diabetes }} @endif</textarea>
                                                                        </div>
                                                                    </div>







                                                                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Enfermedades Crónicas:</label>


                                                                    <div class="form-group">
                                                                        <div class="row">

                                                                            @foreach($arrayEnfermedadCronicas as $item)

                                                                                <div class="col-12 col-md-3">
                                                                                    <label class="switch" style="margin: 4px !important;">


                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                            @else
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                            @endif
                                                                                        @endforeach



                                                                                        <div class="slider round">
                                                                                        </div>
                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label><br>
                                                                                </div>

                                                                            @endforeach

                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12">
                                                                        <br>
                                                                        <label>Notas</label>
                                                                        <div class="input-group">
                                                                            <textarea class="form-control" id="notas_enfermedad_cronica" rows="4">@if($antecedentes != null) {{ $antecedentes->nota_enfermedades_cronicas }} @endif</textarea>
                                                                        </div>
                                                                    </div>








                                                                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Antecedentes Quirúrgicos:</label>


                                                                    <div class="form-group">
                                                                        <div class="row">

                                                                            @foreach($arrayAntecedenteCronicos as $item)

                                                                                <div class="col-12 col-md-3">
                                                                                    <label class="switch" style="margin: 4px !important;">

                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                            @else
                                                                                                <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                            @endif
                                                                                        @endforeach

                                                                                        <div class="slider round">
                                                                                        </div>
                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label><br>
                                                                                </div>

                                                                            @endforeach

                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12">
                                                                        <br>
                                                                        <label>Notas</label>
                                                                        <div class="input-group">
                                                                            <textarea class="form-control" id="notas_antecedente_quirurgico" rows="4">@if($antecedentes != null) {{ $antecedentes->nota_antecedentes_quirurgicos }} @endif</textarea>
                                                                        </div>
                                                                    </div>




                                                                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes Oftalmológicos:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="notas_antecedente_oftamologico"> @if($antecedentes != null) {{ $antecedentes->antecedentes_oftalmologicos }} @endif </textarea>
                                                                    </div>


                                                                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes Deportivos:</label>
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="notas_antecedente_deportivos"> @if($antecedentes != null) {{ $antecedentes->antecedentes_deportivos }} @endif </textarea>
                                                                    </div>


                                                                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes ginecológicos:</label>


                                                                    <div class="form-group row">

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Menarquía</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-menarquia"> @if($antecedentes != null) {{ $antecedentes->menarquia }} @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>
                                                                                    Ciclo Menstrual</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-ciclomenstrual"> @if($antecedentes != null) {{ $antecedentes->ciclo_menstrual }} @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>PAP</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-pap" value="0000-00-00"> @if($antecedentes != null) {{ $antecedentes->pap }} @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Mamografía</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-mamografia" value="0000-00-00"> @if($antecedentes != null) {{ $antecedentes->mamografia }} @endif
                                                                            </div>
                                                                        </div>

                                                                    </div>



                                                                    <div class="col-12">
                                                                        <br>
                                                                        <label>Notas</label>
                                                                        <div class="input-group">
                                                                            <textarea class="form-control" placeholder="Otros Detalles" id="otros-detalles" rows="4">@if($antecedentes != null) {{ $antecedentes->otros }} @endif</textarea>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>




                                                <div class="card-footer">
                                                    <button type="button" onclick="guardarAntecedentes()" style="font-weight: bold; background-color: #28a745; color: white !important;"
                                                            class="button button-rounded button-pill button-small float-right">Guardar Antecedentes</button>
                                                </div>




                                            </div>

                                            <!-- LISTA DE NUEVOS MATERIALES - TABS 2 -->
                                            <div class="tab-pane" id="tab_2">

                                                <form>
                                                    <div class="card-body">

                                                        <table class="table" id="matrizMateriales" style="border: 80px" data-toggle="table">
                                                            <thead>
                                                            <tr>
                                                                <th style="width: 30%; text-align: center">Descripción</th>
                                                                <th style="width: 20%; text-align: left">Unidad de Medida</th>
                                                                <th style="width: 15%; text-align: center">Costo</th>
                                                                <th style="width: 15%; text-align: center">Cantidad</th>
                                                                <th style="width: 10%; text-align: center">Periodo</th>

                                                                <th style="width: 10%; text-align: center">Opciones</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitud()">Agregar Solicitud de Material</button>
                                                        <br>

                                                    </div>

                                                </form>

                                            </div>

                                            <!-- LISTA DE PROYECTOS - TABS 3 -->

                                            <div class="tab-pane" id="tab_3">

                                                <form>
                                                    <div class="card-body">

                                                        <table class="table" id="matrizProyectos" style="border: 80px" data-toggle="table">
                                                            <thead>
                                                            <tr>
                                                                <th style="width: 30%; text-align: center">Descripción</th>
                                                                <th style="width: 10%; text-align: center">Monto ($)</th>
                                                                <th style="width: 10%; text-align: center">Opciones</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitudProyecto()">Agregar Solicitud de Proyecto</button>
                                                        <br>

                                                    </div>

                                                </form>
                                            </div>


                                            <!-- fin - Tabs -->
                                        </div>
                                    </div>
                                </div>









                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>







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



            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargarVista(){
            location.reload();
        }


        function guardarAntecedentes(){

            const checkboxes = document.querySelectorAll('input[name="arrayCheckAntecedentes[]"]');
            const datosCheckboxes = [];

            checkboxes.forEach((checkbox) => {
                const estado = checkbox.checked;
                const valorAdicional = checkbox.dataset.valor;

                if(estado){
                    // ESTOS NOMBRES SE UTILIZAN EN CONTROLADOR
                    datosCheckboxes.push({ estado, valorAdicional });
                }
            });




            var notaAntecedenteFamiliar = document.getElementById('text-antecedentes-editar').value;
            var notaAlergia = document.getElementById('text-alergias-editar').value;
            var notaMedicamento = document.getElementById('text-medicamento-actual-editar').value;

            var selectSanguineo = document.getElementById('select-tipeo-sanguineo').value;

            // ID PACIENTE
            let id = {{ $infoPaciente->id }};

            openLoading();
            var formData = new FormData();
            formData.append('idpaciente', id);
            formData.append('datocheckbox', JSON.stringify(datosCheckboxes));
            formData.append('textAntecedenteFami', notaAntecedenteFamiliar);
            formData.append('textAlergia', notaAlergia);
            formData.append('textMedicamento', notaMedicamento);
            formData.append('selectSanguineo', selectSanguineo);


            axios.post(url+'/historial/antecedente/actualizacion', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
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








    </script>


@endsection
