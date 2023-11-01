
@foreach($arraySalidas as $dato)

    <div class="col-md-12" >
        <div class="card card-primary">
            <div class="card-header">
                <div class="form-group">

                    <div class="row" >
                        <div class="col-md-3">
                            <p>Fecha de Vencimiento: <span style="font-weight: bold">{{ $dato->fechaVencimiento }}</span> </p>
                        </div>
                        <div class="col-md-3">
                            <p>Número Factura: <span style="font-weight: bold">{{ $dato->numero_factura }}</span> </p>
                        </div>
                        <div class="col-md-3">
                            <p>LOTE: <span style="font-weight: bold">{{ $dato->lote }}</span> </p>
                        </div>
                        <div class="col-md-3">
                            <p>Fecha de Entrada: <span style="font-weight: bold">{{ $dato->fechaEntrada }}</span> </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Precio: <span style="font-weight: bold">{{ $dato->precio }}</span> </p>
                        </div>

                        <div class="col-md-3">
                            <p>Cantidad Disponible: <span style="font-weight: bold">{{ $dato->cantidad }}</span> </p>
                        </div>
                    </div>

                    <!-- DATOS PARA SABER CUAL VA A RETIRAR -->

                </div>
            </div>

            <div class="arraycolor">
                <div class="card-body row">
                    <span style="font-weight: bold">Ingresar Cantidad </span>
                    <input class="form-control" style="margin-left: 20px; width: 200px" name="arraysalida[]"
                           type="number" onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                           data-identrada="{{ $dato->identradadetalle }}" data-nombremedi="{{ $dato->nombre }}"
                           data-maxcantidad="{{ $dato->cantidad }}" data-fechavencimiento="{{ $dato->fechaVencimiento }}"
                           data-fechaentrada="{{ $dato->fechaEntrada }}" data-lote="{{ $dato->lote }}"
                           value="" min="1" max="{{ $dato->cantidad }}" step="1" oninput="verificarInputCantidad(this)"/>
                </div>
            </div>
        </div>
    </div>





@endforeach


<script type="text/javascript">
    $(document).ready(function(){

        closeLoading();

        let conteo = {{ $conteo }};

        document.getElementById("btnAgregarFila").style.display = "block";

        if(conteo === 0){
            // NO HAY PRODUCTOS

            toastr.info('Sin Existencias de Producto');
            document.getElementById('txtSalida').innerHTML = "";
            document.getElementById('btnAgregarFila').style.display = "none";
        }else{
            document.getElementById('txtSalida').innerHTML = "Productos a Despachar";
            document.getElementById('btnAgregarFila').style.display = "block";
        }

    });

    function verificarInputCantidad(inputElement){

        var valor = inputElement.value;
        var maxcantidad = inputElement.getAttribute('data-maxcantidad');

        if(valor === ''){
            // nada
        }else{
            var cantidadInput = parseInt(valor);
            var cantidadMaxima = parseInt(maxcantidad);

            if(cantidadInput > cantidadMaxima){
                toastr.info('Máximo Unidades: ' + maxcantidad);
                inputElement.value = maxcantidad;
            }
        }
    }



</script>

