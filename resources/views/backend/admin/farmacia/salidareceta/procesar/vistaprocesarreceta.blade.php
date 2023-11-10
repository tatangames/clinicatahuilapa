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

    <section class="content">
        <div class="container-fluid">
            <div class="card-header">
                <section >
                    <div class="container">
                        <div class="row">

                            <div class="col-md-7">
                                <div class="card" style="border-radius: 15px;">
                                    <div class="card-body p-4">
                                        <div class="d-flex text-black">
                                            <div >
                                                @if($infoPaciente->foto == null)
                                                    <img style="margin-left: 15px" alt="Sin Foto" src="{{ asset('images/foto-default.png') }}" width="120px" height="120px" />
                                                @else
                                                    <img style="margin-left: 15px" alt="Foto Paciente" src="{{ url('storage/archivos/'.$infoPaciente->foto) }}" width="120px" height="120px" />
                                                @endif
                                            </div>

                                            <div style="margin-left: 15px">
                                                <h5 style="font-weight: bold">FICHA CLINICA</h5>
                                                <p class="" style="color: #2b2a2a;">{{ $nombreCompleto }}</p>
                                                <p><span class="badge bg-primary" style="font-size: 13px">Edad:  {{ $edad }}</span></p>
                                                <p><span class="badge bg-primary" style="font-size: 13px">Recetado Por Doctor/a:  {{ $nombreDoctor }}</span></p>
                                                <p><span class="badge bg-primary" style="font-size: 13px; color: white !important;">Fecha Receta:  {{ $fechaReceta }}</span></p>

                                            </div>



                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>












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




    </script>


@endsection
