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
                                                    <a class="nav-link active" href="#tab_1"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/personacard.png') }}" height="25px" width="25px">
                                                        </span>ANTECEDENTES
                                                    </a>
                                                </li>

                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_2"  data-toggle="tab">
                                                        <span>
                                                            <img class="manImg" src="{{ asset('images/corazonrojo.png') }}" height="25px" width="25px">
                                                        </span>
                                                        SV + ANTROP
                                                    </a>
                                                </li>



                                                <li style="margin-left: 15px; font-weight: bold; font-size: 16px" class="nav-item"><a class="nav-link" href="#tab_3"  data-toggle="tab">
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
                                                                                    <label class="switch" style="margin: 4px !important;">

                                                                                    @php $valor1True = false @endphp

                                                                                    @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                @php $valor1True = true @endphp
                                                                                            @endif
                                                                                    @endforeach

                                                                                    @if($valor1True)
                                                                                        <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                    @else
                                                                                        <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                    @endif

                                                                                        <div class="slider round">
                                                                                        </div>

                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label>
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

                                                                                        @php $valor2True = false @endphp

                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                @php $valor2True = true @endphp
                                                                                            @endif
                                                                                        @endforeach

                                                                                        @if($valor2True)
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                        @else
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                        @endif

                                                                                        <div class="slider round">
                                                                                        </div>

                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label>
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

                                                                                        @php $valor3True = false @endphp

                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                @php $valor3True = true @endphp
                                                                                            @endif
                                                                                        @endforeach

                                                                                        @if($valor3True)
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                        @else
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                        @endif

                                                                                        <div class="slider round">
                                                                                        </div>

                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label>
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

                                                                                        @php $valor4True = false @endphp

                                                                                        @foreach($arrayIdPacienteAntecedente as $valor)
                                                                                            @if($item->id == $valor->antecedente_medico_id)
                                                                                                @php $valor4True = true @endphp
                                                                                            @endif
                                                                                        @endforeach

                                                                                        @if($valor4True)
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' checked name="arrayCheckAntecedentes[]">
                                                                                        @else
                                                                                            <input type="checkbox" data-valor='{{ $item->id }}' name="arrayCheckAntecedentes[]">
                                                                                        @endif

                                                                                        <div class="slider round">
                                                                                        </div>

                                                                                    </label>
                                                                                    <label>{{ $item->nombre }}</label>
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
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-menarquia" value="@if($antecedentes != null) {{ $antecedentes->menarquia }} @endif">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>
                                                                                    Ciclo Menstrual</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-ciclomenstrual" value="@if($antecedentes != null) {{ $antecedentes->ciclo_menstrual }} @endif">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>PAP</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-pap" value="@if($antecedentes != null) {{ $antecedentes->pap }} @else 0000-00-00 @endif" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Mamografía</label>
                                                                                <input type="text" maxlength="300" class="form-control" id="dato-mamografia" value="@if($antecedentes != null) {{ $antecedentes->mamografia }} @endif" >
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

                                                    <div class="card card-default">
                                                        <div class="card-header">
                                                            <h3 class="card-title" style="font-weight: bold">Historial de Antrometria</h3>
                                                            @if($btnAntro == 0)
                                                            <button type="button" style="float: right ;font-weight: bold; background-color: #28a745; color: white !important;"
                                                                    onclick="modalAntropometria()" id="btnAntro" class="button button-3d button-rounded button-pill button-small">
                                                                <i class="fas fa-plus"></i>
                                                                Nuevo Antropometría
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="card-body">


                                                        <!-- CARGAR TABLA DE ANTROPOMETRIA -->


                                                        <div id="tablaAntrometria">
                                                        </div>


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




    <div class="modal fade" id="modalAntro">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Antropometría</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-antropometria">
                        <center><div class="card-body">

                            <div class="row">

                                <div class="form-group col-md-3">
                                    <label>Fecha <span style="color: red">*</span></label>
                                    <input type="date" class="form-control" id="fecha-antro" autocomplete="off">
                                </div>


                                <div class="form-group col-md-3">
                                    <label>Frecuencia Cardiaca (lpm):</label>
                                    <input type="text" maxlength="150" class="form-control" id="frecuencia-cardia-antro" autocomplete="off">
                                </div>


                                <div class="form-group col-md-3">
                                    <label>Frecuencia Respiratoria (rpm):</label>
                                    <input type="text" maxlength="150" class="form-control" id="frecuencia-respiratoria-antro" autocomplete="off">
                                </div>

                            </div>


                                <br><br>

                            <div class="row">

                                <div class="form-group col-md-3">
                                    <label>Presion Arterial (mmHg):</label>
                                    <input type="text" maxlength="150" class="form-control" id="presion-arterial-antro" autocomplete="off">
                                </div>


                                <div class="form-group col-md-3">
                                    <label>Temperatura (°C):</label>
                                    <input type="text" class="form-control" id="temperatura-antro" autocomplete="off">
                                </div>


                                <div class="form-group col-md-3">
                                    <label>Perím. Abdominal (cm):</label>
                                    <input type="text" class="form-control" id="perim-abdominal-antro" value="N/A" autocomplete="off">
                                </div>


                            </div>





                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>Perím. Cefálico (cm):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" value="N/A" id="perimetro-cefalico-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Peso (lb):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc2();"  class="form-control" id="peso-libra-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Peso (kg):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc3();"  class="form-control" id="peso-kilo-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Estatura (cm):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc2();"  class="form-control" id="estatura-antro" autocomplete="off">
                                    </div>


                                </div>



                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>IMC:</label>
                                        <input type="text" disabled class="form-control" id="imc-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Resultado del IMC:</label>
                                        <input type="text" disabled class="form-control" id="resultado-imc-antro" autocomplete="off">
                                    </div>

                                </div>




                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>Glucometria Capilar:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="glucometria-capilar-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Glicohemoglobina Capilar:</label>
                                        <input type="text" onkeypress="return valida_numero(event);"  class="form-control" id="glicohemoglobina-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Cetonas Capilares:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="cetona-capilar-antro" autocomplete="off">
                                    </div>


                                </div>






                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>SpO2:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="sp02-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Perimetro de Cintura (CM):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice();"  class="form-control" id="perimetro-cintura-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Perimetro de Cadera (CM):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice();" class="form-control" id="perimetro-cadera-antro" autocomplete="off">
                                    </div>


                                </div>



                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>ICC :</label>
                                        <input type="text" disabled class="form-control" id="icc-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Riesgo Mujer:</label>
                                        <input type="text" disabled  class="form-control" id="riesgo-mujer-antro" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Riesgo Hombre:</label>
                                        <input type="text" disabled class="form-control" id="riesgo-hombre-antro" autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Gasto Energético Basal:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice();"
                                               class="form-control" id="gasto-energetico-antro" autocomplete="off">
                                    </div>


                                </div>

                                <br><br>


                                    <label>Otros Detalles:</label>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" id="otros-detalles-antro"></textarea>
                                    </div>







                            </div>
                        </center>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="guardarAntropometria()">Guardar Antropometría</button>
                </div>
            </div>
        </div>
    </div>




    <!-- MODAL EDITAR ANTROPOMETRIA -->


    <div class="modal fade" id="modalAntroEditar">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Antropometría</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-antropometria-editar">
                        <center><div class="card-body">

                                <div class="row">

                                    <div class="">
                                        <input type="hidden" class="form-control" id="id-antro-editar">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Fecha <span style="color: red">*</span></label>
                                        <input type="date" class="form-control" id="fecha-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Frecuencia Cardiaca (lpm):</label>
                                        <input type="text" maxlength="150" class="form-control" id="frecuencia-cardia-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Frecuencia Respiratoria (rpm):</label>
                                        <input type="text" maxlength="150" class="form-control" id="frecuencia-respiratoria-antro-editar" autocomplete="off">
                                    </div>

                                </div>


                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>Presion Arterial (mmHg):</label>
                                        <input type="text" maxlength="150" class="form-control" id="presion-arterial-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Temperatura (°C):</label>
                                        <input type="text" class="form-control" id="temperatura-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Perím. Abdominal (cm):</label>
                                        <input type="text" class="form-control" id="perim-abdominal-antro-editar" value="N/A" autocomplete="off">
                                    </div>


                                </div>





                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>Perím. Cefálico (cm):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" value="N/A" id="perimetro-cefalico-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Peso (lb):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc2_editar();"  class="form-control" id="peso-libra-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Peso (kg):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc3_editar();"  class="form-control" id="peso-kilo-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Estatura (cm):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onkeyup="calcular_imc2();"  class="form-control" id="estatura-antro-editar" autocomplete="off">
                                    </div>


                                </div>



                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>IMC:</label>
                                        <input type="text" disabled class="form-control" id="imc-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Resultado del IMC:</label>
                                        <input type="text" disabled class="form-control" id="resultado-imc-antro-editar" autocomplete="off">
                                    </div>

                                </div>




                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>Glucometria Capilar:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="glucometria-capilar-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Glicohemoglobina Capilar:</label>
                                        <input type="text" onkeypress="return valida_numero(event);"  class="form-control" id="glicohemoglobina-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Cetonas Capilares:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="cetona-capilar-antro-editar" autocomplete="off">
                                    </div>


                                </div>






                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>SpO2:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" class="form-control" id="sp02-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Perimetro de Cintura (CM):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice_editar();"  class="form-control" id="perimetro-cintura-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Perimetro de Cadera (CM):</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice_editar();" class="form-control" id="perimetro-cadera-antro-editar" autocomplete="off">
                                    </div>


                                </div>



                                <br><br>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <label>ICC :</label>
                                        <input type="text" disabled class="form-control" id="icc-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Riesgo Mujer:</label>
                                        <input type="text" disabled  class="form-control" id="riesgo-mujer-antro-editar" autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label>Riesgo Hombre:</label>
                                        <input type="text" disabled class="form-control" id="riesgo-hombre-antro-editar" autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Gasto Energético Basal:</label>
                                        <input type="text" onkeypress="return valida_numero(event);" onchange="calcular_indice_editar();"
                                               class="form-control" id="gasto-energetico-antro-editar" autocomplete="off">
                                    </div>


                                </div>

                                <br><br>


                                <label>Otros Detalles:</label>
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" id="otros-detalles-antro-editar"></textarea>
                                </div>



                            </div>
                        </center>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="actualizarAntropometria()">Actualizar Antropometría</button>
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

            let idconsulta = {{ $idconsulta }};

            var ruta = "{{ URL::to('/admin/historial/antrometria/paciente-consulta') }}/" + idconsulta;
            $('#tablaAntrometria').load(ruta);


            var fecha = new Date();
            $('#fecha-antro').val(fecha.toJSON().slice(0,10));


            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        function recargarVista(){
            location.reload();
        }


        //********************************* BLOQUE 2 ***************************************

        function recargarTablaAntropometria(){
            let idconsulta = {{ $idconsulta }};
            var ruta = "{{ URL::to('/admin/historial/antrometria/paciente-consulta') }}/" + idconsulta;
            $('#tablaAntrometria').load(ruta);
        }

        function calcular_indice(){
            perimetro_cintura = parseFloat($("#perimetro-cintura-antro").val());
            perimetro_cadera = parseFloat($("#perimetro-cadera-antro").val());
            valor =(parseFloat(perimetro_cintura/perimetro_cadera)).toFixed(2);
            if(perimetro_cintura>0 && perimetro_cadera>0){
                if(valor<0.8){
                    mujer = "Bajo";
                    color_mujer = "green";
                }else if (valor>0.8 && valor<=0.85){
                    mujer = "Moderado";
                    color_mujer = "orange";
                }else if (valor>0.85){
                    mujer = "Alto";
                    color_mujer = "red";
                }
                $("#riesgo-mujer-antro").val(mujer);
                if(valor<0.95){
                    color_hombre = "green";
                    hombre = "Bajo";
                }else if (valor>0.95 && valor<=1){
                    hombre = "Moderado";
                    color_hombre = "orange";
                }else if (valor>1){
                    hombre = "Alto";
                    color_hombre = "red";
                }
                $("#riesgo-hombre-antro").val(hombre);
                $("#riesgo-hombre-antro").css("color",color_hombre);
                $("#riesgo-mujer-antro").css("color",color_mujer);
                $("#riesgo-hombre-antro").css("font-weight","bold");
                $("#riesgo-mujer-antro").css("font-weight","bold");
                $("#icc-antro").val(valor);
            }else{
                $("#icc-antro").val(0);
            }
        }



        function valida_numero(e){
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
                return true;
            }

            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }


        function calcular_imc2(){
            var peso = $('#peso-libra-antro').val();
            $('#peso-kilo-antro').val((peso/2.2046).toFixed(2));

            var estatura = $('#estatura-antro').val();
            var imc = (peso/2.2046)/((estatura/100)*(estatura/100));
            $('#imc-antro').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro').val(resimc);
        }

        function calcular_imc3(){
            var peso = $('#peso-kilo-antro').val();
            $('#peso-libra-antro').val((peso*2.2046).toFixed(2));

            var estatura = $('#estatura-antro').val();
            var imc = (peso)/((estatura/100)*(estatura/100));
            $('#imc-antro').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro').val(resimc);
        }


        //****************


        function calcular_imc2_editar(){
            var peso = $('#peso-libra-antro-editar').val();
            $('#peso-kilo-antro-editar').val((peso/2.2046).toFixed(2));

            var estatura = $('#estatura-antro-editar').val();
            var imc = (peso/2.2046)/((estatura/100)*(estatura/100));
            $('#imc-antro-editar').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro-editar').val(resimc);
        }

        function calcular_imc3_editar(){
            var peso = $('#peso-kilo-antro-editar').val();
            $('#peso-libra-antro-editar').val((peso*2.2046).toFixed(2));

            var estatura = $('#estatura-antro-editar').val();
            var imc = (peso)/((estatura/100)*(estatura/100));
            $('#imc-antro-editar').val(imc.toFixed(2));
            if(imc.toFixed(2) < 16){
                var resimc= "Delgadez severa";
            }
            else if(imc.toFixed(2) >= 16 && imc.toFixed(2) <= 16.99){
                var resimc= "Delgadez moderada";
            }
            else if(imc.toFixed(2) >= 1 && imc.toFixed(2) <= 18.49){
                var resimc= "Delgadez leve";
            }
            else if(imc.toFixed(2) >= 18.5 && imc.toFixed(2) <= 24.99){
                var resimc= "Normal";
            }
            else if(imc.toFixed(2) >= 25 && imc.toFixed(2) <= 29.99){
                var resimc= "Preobeso";
            }
            else if(imc.toFixed(2) >= 30 && imc.toFixed(2) <= 34.99){
                var resimc= "Obesidad leve";
            }
            else if(imc.toFixed(2) >= 35 && imc.toFixed(2) <= 39.99){
                var resimc= "Obesidad media";
            }
            else if(imc.toFixed(2) >=40){
                var resimc= "Obesidad mórbida";
            }
            $('#resultado-imc-antro-editar').val(resimc);
        }


        function calcular_indice_editar(){
            perimetro_cintura = parseFloat($("#perimetro-cintura-antro-editar").val());
            perimetro_cadera = parseFloat($("#perimetro-cadera-antro-editar").val());
            valor =(parseFloat(perimetro_cintura/perimetro_cadera)).toFixed(2);
            if(perimetro_cintura>0 && perimetro_cadera>0){
                if(valor<0.8){
                    mujer = "Bajo";
                    color_mujer = "green";
                }else if (valor>0.8 && valor<=0.85){
                    mujer = "Moderado";
                    color_mujer = "orange";
                }else if (valor>0.85){
                    mujer = "Alto";
                    color_mujer = "red";
                }
                $("#riesgo-mujer-antro-editar").val(mujer);
                if(valor<0.95){
                    color_hombre = "green";
                    hombre = "Bajo";
                }else if (valor>0.95 && valor<=1){
                    hombre = "Moderado";
                    color_hombre = "orange";
                }else if (valor>1){
                    hombre = "Alto";
                    color_hombre = "red";
                }
                $("#riesgo-hombre-antro-editar").val(hombre);
                $("#riesgo-hombre-antro-editar").css("color",color_hombre);
                $("#riesgo-mujer-antro-editar").css("color",color_mujer);
                $("#riesgo-hombre-antro-editar").css("font-weight","bold");
                $("#riesgo-mujer-antro-editar").css("font-weight","bold");
                $("#icc-antro-editar").val(valor);
            }else{
                $("#icc-antro-editar").val(0);
            }
        }




        function guardarAntropometria(){


            var fecha = document.getElementById('fecha-antro').value;


            var freCardiaca = document.getElementById('frecuencia-cardia-antro').value;
            var freRespiratoria = document.getElementById('frecuencia-respiratoria-antro').value;
            var presionArterial = document.getElementById('presion-arterial-antro').value;
            var temperatura = document.getElementById('temperatura-antro').value;
            var perimetroAbdominal = document.getElementById('perim-abdominal-antro').value;
            var perimetroCefalico = document.getElementById('perimetro-cefalico-antro').value;
            var pesoLibra = document.getElementById('peso-libra-antro').value;
            var pesoKilo = document.getElementById('peso-kilo-antro').value;
            var estatura = document.getElementById('estatura-antro').value;
            var imc = document.getElementById('imc-antro').value;
            var resultadoImc = document.getElementById('resultado-imc-antro').value;
            var glucometria = document.getElementById('glucometria-capilar-antro').value;
            var glicohemoglobina = document.getElementById('glicohemoglobina-antro').value;
            var cetona = document.getElementById('cetona-capilar-antro').value;
            var sp02 = document.getElementById('sp02-antro').value;
            var perimetroCintura = document.getElementById('perimetro-cintura-antro').value;
            var perimetroCadera = document.getElementById('perimetro-cadera-antro').value;
            var icc = document.getElementById('icc-antro').value;
            var riesgoMujer = document.getElementById('riesgo-mujer-antro').value;
            var riesgoHombre = document.getElementById('riesgo-hombre-antro').value;
            var gastoEnergetico = document.getElementById('gasto-energetico-antro').value;
            var otrosDetalles = document.getElementById('otros-detalles-antro').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }


            // ID PACIENTE
            let idconsulta = {{ $idconsulta }};


            openLoading();
            var formData = new FormData();
            formData.append('idconsulta', idconsulta);
            formData.append('fecha', fecha);
            formData.append('freCardiaca', freCardiaca);
            formData.append('freRespiratoria', freRespiratoria);
            formData.append('presionArterial', presionArterial);
            formData.append('temperatura', temperatura);
            formData.append('perimetroAbdominal', perimetroAbdominal);
            formData.append('perimetroCefalico', perimetroCefalico);
            formData.append('pesoLibra', pesoLibra);
            formData.append('pesoKilo', pesoKilo);
            formData.append('estatura', estatura);
            formData.append('imc', imc);
            formData.append('resultadoImc', resultadoImc);
            formData.append('glucometria', glucometria);
            formData.append('glicohemoglobina', glicohemoglobina);
            formData.append('cetona', cetona);
            formData.append('sp02', sp02);
            formData.append('perimetroCintura', perimetroCintura);
            formData.append('perimetroCadera', perimetroCadera);
            formData.append('icc', icc);
            formData.append('riesgoMujer', riesgoMujer);
            formData.append('riesgoHombre', riesgoHombre);
            formData.append('gastoEnergetico', gastoEnergetico);
            formData.append('otrosDetalles', otrosDetalles);

            axios.post(url+'/historial/registrar/antropometria', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        Swal.fire({
                            title: 'Error',
                            text: "Ya se encuentra un registro con esta consulta",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                recargarVista()
                            }
                        })
                    }
                    else if(response.data.success === 2){
                        toastr.success('Registrado correctamente');
                        $('#modalAntro').modal('hide');
                        document.getElementById("btnAntro").style.display = "none";
                        document.getElementById("formulario-antropometria").reset();

                        recargarTablaAntropometria();

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



        function informacionAntropometria(idantro){

            openLoading();



            axios.post(url+'/historial/informacion/antropometria',{
                'id': idantro
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#id-antro-editar').val(idantro);
                        $('#fecha-antro-editar').val(response.data.info.fecha);
                        $('#frecuencia-cardia-antro-editar').val(response.data.info.frecuencia_cardiaca);
                        $('#frecuencia-respiratoria-antro-editar').val(response.data.info.frecuencia_respiratoria);
                        $('#presion-arterial-antro-editar').val(response.data.info.presion_arterial);
                        $('#temperatura-antro-editar').val(response.data.info.temperatura);
                        $('#perim-abdominal-antro-editar').val(response.data.info.perim_abdominal);
                        $('#perimetro-cefalico-antro-editar').val(response.data.info.perim_cefalico);
                        $('#peso-libra-antro-editar').val(response.data.info.peso_libra);
                        $('#peso-kilo-antro-editar').val(response.data.info.peso_kilo);
                        $('#estatura-antro-editar').val(response.data.info.estatura);
                        $('#imc-antro-editar').val(response.data.info.imc);
                        $('#resultado-imc-antro-editar').val(response.data.info.resultado_imc);
                        $('#glucometria-capilar-antro-editar').val(response.data.info.glucometria_capilar);
                        $('#glicohemoglobina-antro-editar').val(response.data.info.glicohemoglibona_capilar);
                        $('#cetona-capilar-antro-editar').val(response.data.info.cetona_capilar);
                        $('#sp02-antro-editar').val(response.data.info.spo2);
                        $('#perimetro-cintura-antro-editar').val(response.data.info.perim_cintura);
                        $('#perimetro-cadera-antro-editar').val(response.data.info.perim_cadera);
                        $('#icc-antro-editar').val(response.data.info.icc);
                        $('#riesgo-mujer-antro-editar').val(response.data.info.riesgo_mujer);
                        $('#riesgo-hombre-antro-editar').val(response.data.info.riesgo_hombre);
                        $('#gasto-energetico-antro-editar').val(response.data.info.gasto_energetico_basal);
                        $('#otros-detalles-antro-editar').val(response.data.info.nota_adicional);

                        $('#modalAntroEditar').modal('show');
                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });

        }



        function actualizarAntropometria(){

            var idmodal = document.getElementById('id-antro-editar').value;
            var fecha = document.getElementById('fecha-antro-editar').value;

            var freCardiaca = document.getElementById('frecuencia-cardia-antro-editar').value;
            var freRespiratoria = document.getElementById('frecuencia-respiratoria-antro-editar').value;
            var presionArterial = document.getElementById('presion-arterial-antro-editar').value;
            var temperatura = document.getElementById('temperatura-antro-editar').value;
            var perimetroAbdominal = document.getElementById('perim-abdominal-antro-editar').value;
            var perimetroCefalico = document.getElementById('perimetro-cefalico-antro-editar').value;
            var pesoLibra = document.getElementById('peso-libra-antro-editar').value;
            var pesoKilo = document.getElementById('peso-kilo-antro-editar').value;
            var estatura = document.getElementById('estatura-antro-editar').value;
            var imc = document.getElementById('imc-antro-editar').value;
            var resultadoImc = document.getElementById('resultado-imc-antro-editar').value;
            var glucometria = document.getElementById('glucometria-capilar-antro-editar').value;
            var glicohemoglobina = document.getElementById('glicohemoglobina-antro-editar').value;
            var cetona = document.getElementById('cetona-capilar-antro-editar').value;
            var sp02 = document.getElementById('sp02-antro-editar').value;
            var perimetroCintura = document.getElementById('perimetro-cintura-antro-editar').value;
            var perimetroCadera = document.getElementById('perimetro-cadera-antro-editar').value;
            var icc = document.getElementById('icc-antro-editar').value;
            var riesgoMujer = document.getElementById('riesgo-mujer-antro-editar').value;
            var riesgoHombre = document.getElementById('riesgo-hombre-antro-editar').value;
            var gastoEnergetico = document.getElementById('gasto-energetico-antro-editar').value;
            var otrosDetalles = document.getElementById('otros-detalles-antro-editar').value;

            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }


            openLoading();
            var formData = new FormData();
            formData.append('idmodal', idmodal);
            formData.append('fecha', fecha);
            formData.append('freCardiaca', freCardiaca);
            formData.append('freRespiratoria', freRespiratoria);
            formData.append('presionArterial', presionArterial);
            formData.append('temperatura', temperatura);
            formData.append('perimetroAbdominal', perimetroAbdominal);
            formData.append('perimetroCefalico', perimetroCefalico);
            formData.append('pesoLibra', pesoLibra);
            formData.append('pesoKilo', pesoKilo);
            formData.append('estatura', estatura);
            formData.append('imc', imc);
            formData.append('resultadoImc', resultadoImc);
            formData.append('glucometria', glucometria);
            formData.append('glicohemoglobina', glicohemoglobina);
            formData.append('cetona', cetona);
            formData.append('sp02', sp02);
            formData.append('perimetroCintura', perimetroCintura);
            formData.append('perimetroCadera', perimetroCadera);
            formData.append('icc', icc);
            formData.append('riesgoMujer', riesgoMujer);
            formData.append('riesgoHombre', riesgoHombre);
            formData.append('gastoEnergetico', gastoEnergetico);
            formData.append('otrosDetalles', otrosDetalles);

            axios.post(url+'/historial/actualizar/antropometria', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.success('Actualizado correctamente');
                        $('#modalAntroEditar').modal('hide');

                        recargarTablaAntropometria();
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







        //************************* BLOQUE 1 ***********************************





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

            var notaAntecedenteMedico = document.getElementById('notas_antecedente_medicos').value;
            var notaCompliDiabete = document.getElementById('notas_complicacion_diabetes').value;
            var notaEnfermedadCronica = document.getElementById('notas_enfermedad_cronica').value;
            var notaAnteceQuirur = document.getElementById('notas_antecedente_quirurgico').value;
            var notaAnteceOftamolo = document.getElementById('notas_antecedente_quirurgico').value;
            var notaAnteceDeportivo = document.getElementById('notas_antecedente_deportivos').value;


            var datoMenarquia = document.getElementById('dato-menarquia').value;
            var datoCicloMenstr = document.getElementById('dato-ciclomenstrual').value;
            var datoPap = document.getElementById('dato-pap').value;
            var datoMamografia = document.getElementById('dato-mamografia').value;

            var otrosDetalles = document.getElementById('otros-detalles').value;

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
            formData.append('notaAnteceMedico', notaAntecedenteMedico);
            formData.append('notaCompliDiabete', notaCompliDiabete);
            formData.append('notaEnfermCronica', notaEnfermedadCronica);
            formData.append('notaAnteceQuirur', notaAnteceQuirur);
            formData.append('notaAnteceOftamo', notaAnteceOftamolo);
            formData.append('notaAnteceDeportivo', notaAnteceDeportivo);
            formData.append('datoMenarquia', datoMenarquia);
            formData.append('datoCicloMenstr', datoCicloMenstr);
            formData.append('datoPap', datoPap);
            formData.append('datoMamografia', datoMamografia);
            formData.append('otrosDetalles', otrosDetalles);

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


        function modalAntropometria(){

            $('#modalAntro').modal({backdrop: 'static', keyboard: false})
        }







    </script>


@endsection
