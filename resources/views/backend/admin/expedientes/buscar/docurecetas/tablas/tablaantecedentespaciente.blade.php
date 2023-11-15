<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card-body">

                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Antecedentes Familiares:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="5" id="text-antecedentes-editar"> @if($b1_antecedentes != null) {{ $b1_antecedentes->antecedentes_familiares }} @endif </textarea>
                    </div>


                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Alergias:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" id="text-alergias-editar"> @if($b1_antecedentes != null) {{ $b1_antecedentes->alergias }} @endif </textarea>
                    </div>

                    <label class="col-form-label" style="color: #428bca; font-weight: bold; font-size: 20px">Medicamentos actuales:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" id="text-medicamento-actual-editar"> @if($b1_antecedentes != null) {{ $b1_antecedentes->medicamentos_actuales }} @endif </textarea>
                    </div>

                    <div class="col-form-label row" style="margin-top: 35px; margin-left: 2px">
                        <label style="color: #428bca; font-weight: bold; font-size: 18px">Tipeo sanguíneo: </label>
                        <div class="col-md-4">
                            <select class="form-control" id="select-tipeo-sanguineo">
                                <option value="">Seleccionar Opción</option>

                                @if($b1_antecedentes != null)

                                    @foreach($b1_arrayTipeoSanguineo as $item)

                                        @if($b1_antecedentes->tipeo_sanguineo_id == $item->id)
                                            <option value="{{$item->id}}" selected>{{ $item->nombre }}</option>
                                        @else
                                            <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                        @endif

                                    @endforeach

                                @else

                                    @foreach($b1_arrayTipeoSanguineo as $item)
                                        <option value="{{$item->id}}">{{ $item->nombre }}</option>
                                    @endforeach

                                @endif

                            </select>
                        </div>
                    </div>



                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Antecedentes Médicos:</label>


                    <div class="form-group">
                        <div class="row">

                            @foreach($b1_arrayAntecedentesMedico as $item)

                                <div class="col-12 col-md-3">
                                    <label class="switch" style="margin: 4px !important;">

                                        @php $valor1True = false @endphp

                                        @foreach($b1_arrayIdPacienteAntecedente as $valor)
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
                            <textarea class="form-control" id="notas_antecedente_medicos" rows="4">@if($b1_antecedentes != null) {{ $b1_antecedentes->nota_antecedente_medico }} @endif</textarea>
                        </div>
                    </div>



                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Complicaciones Agudas en Diábetes:</label>


                    <div class="form-group">
                        <div class="row">

                            @foreach($b1_arrayComplicacionAguda as $item)

                                <div class="col-12 col-md-3">
                                    <label class="switch" style="margin: 4px !important;">

                                        @php $valor2True = false @endphp

                                        @foreach($b1_arrayIdPacienteAntecedente as $valor)
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
                            <textarea class="form-control" id="notas_complicacion_diabetes" rows="4">@if($b1_antecedentes != null) {{ $b1_antecedentes->nota_complicaciones_diabetes }} @endif</textarea>
                        </div>
                    </div>







                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Enfermedades Crónicas:</label>


                    <div class="form-group">
                        <div class="row">

                            @foreach($b1_arrayEnfermedadCronicas as $item)

                                <div class="col-12 col-md-3">
                                    <label class="switch" style="margin: 4px !important;">

                                        @php $valor3True = false @endphp

                                        @foreach($b1_arrayIdPacienteAntecedente as $valor)
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
                            <textarea class="form-control" id="notas_enfermedad_cronica" rows="4">@if($b1_antecedentes != null) {{ $b1_antecedentes->nota_enfermedades_cronicas }} @endif</textarea>
                        </div>
                    </div>








                    <label class="col-form-label" style="color: #428bca; margin-top: 35px; font-weight: bold; font-size: 20px">Antecedentes Quirúrgicos:</label>


                    <div class="form-group">
                        <div class="row">

                            @foreach($b1_arrayAntecedenteCronicos as $item)

                                <div class="col-12 col-md-3">
                                    <label class="switch" style="margin: 4px !important;">

                                        @php $valor4True = false @endphp

                                        @foreach($b1_arrayIdPacienteAntecedente as $valor)
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
                            <textarea class="form-control" id="notas_antecedente_quirurgico" rows="4">@if($b1_antecedentes != null) {{ $b1_antecedentes->nota_antecedentes_quirurgicos }} @endif</textarea>
                        </div>
                    </div>




                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes Oftalmológicos:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" id="notas_antecedente_oftamologico"> @if($b1_antecedentes != null) {{ $b1_antecedentes->antecedentes_oftalmologicos }} @endif </textarea>
                    </div>


                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes Deportivos:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" id="notas_antecedente_deportivos"> @if($b1_antecedentes != null) {{ $b1_antecedentes->antecedentes_deportivos }} @endif </textarea>
                    </div>


                    <label class="col-form-label" style="color: #428bca;
                                                                    font-weight: bold; font-size: 20px; margin-top: 20px">Antecedentes ginecológicos:</label>


                    <div class="form-group row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Menarquía</label>
                                <input type="text" maxlength="300" class="form-control" id="dato-menarquia" value="@if($b1_antecedentes != null) {{ $b1_antecedentes->menarquia }} @endif">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    Ciclo Menstrual</label>
                                <input type="text" maxlength="300" class="form-control" id="dato-ciclomenstrual" value="@if($b1_antecedentes != null) {{ $b1_antecedentes->ciclo_menstrual }} @endif">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>PAP</label>
                                <input type="text" maxlength="300" class="form-control" id="dato-pap" value="@if($b1_antecedentes != null) {{ $b1_antecedentes->pap }} @else 0000-00-00 @endif" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mamografía</label>
                                <input type="text" maxlength="300" class="form-control" id="dato-mamografia" value="@if($b1_antecedentes != null) {{ $b1_antecedentes->mamografia }} @endif" >
                            </div>
                        </div>

                    </div>



                    <div class="col-12">
                        <br>
                        <label>Notas</label>
                        <div class="input-group">
                            <textarea class="form-control" placeholder="Otros Detalles" id="otros-detalles" rows="4">@if($b1_antecedentes != null) {{ $b1_antecedentes->otros }} @endif</textarea>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>




<script>



</script>
