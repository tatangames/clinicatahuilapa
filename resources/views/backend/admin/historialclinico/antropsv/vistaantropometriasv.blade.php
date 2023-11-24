@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloTogglePequeno.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    .widget-user-image2{
        left:50%;margin-left:-45px;
        position:absolute;
        top:80px
    }


    .widget-user-image2>img{
        border:3px solid #fff;
        height:auto;
    }

</style>


<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="container-fluid">
            <button type="button" style="font-weight: bold; background-color: #ffc107; color: white !important;" onclick="vistaHistorialClinico()" class="button button-3d button-rounded button-pill button-small">
                <i class="fas fa-arrow-left"></i>
                Atras
            </button>
        </div>
    </section>


    <section class="content" style="margin-top: 20px">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">FICHA PARA REGISTRAR ANTROPOMETRIA</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <form id="formulario-antropometria">

                                <div class="col-md-5">
                                    <div class="card" style="border-radius: 15px;">
                                        <div class="card-body p-2">
                                            <div class="d-flex text-black">

                                                <div style="margin-left: 15px">
                                                    <h5 style="font-weight: bold">PACIENTE:</h5>
                                                    <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

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


                            </form>

                            <br><br>


                            <label>Otros Detalles:</label>
                            <div class="form-group">
                                <textarea class="form-control" rows="3" id="otros-detalles-antro"></textarea>
                            </div>


                        </div>
                    </div>


                    <div style="float: right; margin-top: 30px">
                        <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" class="button button-rounded button-pill button-small" onclick="saveAntropometriaSv()">Guardar Antropometría</button>
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
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/ckeditor5.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {



            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>


    function vistaHistorialClinico(){
        let idconsulta = {{ $idconsulta }};
        window.location.href="{{ url('/admin/historial/clinico/vista') }}/" + idconsulta;
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



    function saveAntropometriaSv(){

        Swal.fire({
            title: '¿Guardar Antropometria?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                guardarAntropometria();
            }
        })
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


        // ID CONSULTA
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
                        allowOutsideClick: false,
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            vistaHistorialClinico();
                        }
                    })
                }
                else if(response.data.success === 2){

                    document.getElementById("formulario-antropometria").reset();

                    Swal.fire({
                        title: 'Guardado Correctamente',
                        text: "",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        allowOutsideClick: false,
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            vistaHistorialClinico();
                        }
                    })

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


    function calcular_indice_editar(){
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


    </script>


@endsection
