<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight" style="color: white">PANEL DE CONTROL</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">


                @can('sidebar.roles.y.permisos')
                    <li class="nav-item">

                        <a href="#" class="nav-link nav-">
                            <i class="far fa-edit"></i>
                            <p>
                                Roles y Permisos
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.permisos.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan


                    <a href="{{ route('admin.asignaciones.vista') }}" target="frameprincipal" class="nav-link">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            Asignaciones
                        </p>
                    </a>



                <li class="nav-item">

                    <a href="#" class="nav-link nav-">
                        <i class="far fa-user"></i>
                        <p>
                            Expediente
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.expediente.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Expediente</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.expediente.buscar') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buscar Expediente</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">

                    <a href="#" class="nav-link nav-">
                        <i class="fa fa-cog"></i>
                        <p>
                            Configuración
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.tipo.paciente.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de Paciente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.profesion.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profesiones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.estadocivil.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Estado Civil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.medico.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Medico</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.antecedentes.medico.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Antecedentes Medico</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.motivo.consulta.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Motivo Consulta</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.tipo.documento.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de Documento</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.tipo.diagnostico.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de Diagnostico</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.vista.linea') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Línea</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.vista.sub.linea') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sub Línea</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.vista.proveedor') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Proveedores</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.vista.tipo.medicamento') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo Medicamento</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.motivo.farmacia.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Motivo Farmacia</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.vista.via.receta') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vía Receta</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.tipo.paciente.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de Paciente</p>
                            </a>
                        </li>


                    </ul>
                </li>



                    <li class="nav-item">

                        <a href="#" class="nav-link nav-">
                            <i class="far fa-hospital"></i>
                            <p>
                                Farmacia
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('admin.farmacia.registrar.articulo') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Registrar artículo</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.farmacia.ingreso.articulo') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ingresar artículo</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.salida.farmacia.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Salida Manual</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.salida.recetas.farmacia.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Orden de Salida</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.existencias.farmacia.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Existencias</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.catalogo.farmacia.index') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Catálogo</p>
                                </a>
                            </li>


                        </ul>
                    </li>




            </ul>
        </nav>

    </div>
</aside>
