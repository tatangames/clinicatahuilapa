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
                                <i class="far fa-edit nav-icon"></i>
                                <p>Nuevo Expediente</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">

                    <a href="#" class="nav-link nav-">
                        <i class="far fa-user"></i>
                        <p>
                            Configuración
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.tipo.paciente.nuevo') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Tipo de Paciente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.profesion.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Profesiones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.estadocivil.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Estado Civil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.medico.index') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Medico</p>
                            </a>
                        </li>


                    </ul>





                </li>


            </ul>
        </nav>

    </div>
</aside>
