<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Backend\Perfil\PerfilController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Controles\ControlController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Expedientes\ExpedientesController;
use App\Http\Controllers\Backend\Configuracion\ProfesionController;
use App\Http\Controllers\Backend\Configuracion\EstadoCivilController;
use App\Http\Controllers\Backend\Configuracion\MedicoController;
use App\Http\Controllers\Backend\Configuracion\NuevoPacienteController;
use App\Http\Controllers\Backend\Configuracion\TipoDocumentoController;
use App\Http\Controllers\Backend\Configuracion\DiagnosticoController;
use App\Http\Controllers\Backend\Configuracion\MotivoConsultaController;
use App\Http\Controllers\Backend\Asignaciones\AsignacionesController;
use App\Http\Controllers\Backend\Historial\HistorialClinicoController;




Route::get('/', [LoginController::class,'index'])->name('login');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// --- CONTROL WEB ---
Route::get('/panel', [ControlController::class,'indexRedireccionamiento'])->name('admin.panel');

// --- ROLES ---
Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');
Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

// --- PERMISOS ---
Route::get('/admin/permisos/index', [PermisoController::class,'index'])->name('admin.permisos.index');
Route::get('/admin/permisos/tabla', [PermisoController::class,'tablaUsuarios']);
Route::post('/admin/permisos/nuevo-usuario', [PermisoController::class, 'nuevoUsuario']);
Route::post('/admin/permisos/info-usuario', [PermisoController::class, 'infoUsuario']);
Route::post('/admin/permisos/editar-usuario', [PermisoController::class, 'editarUsuario']);
Route::post('/admin/permisos/nuevo-rol', [PermisoController::class, 'nuevoRol']);
Route::post('/admin/permisos/extra-nuevo', [PermisoController::class, 'nuevoPermisoExtra']);
Route::post('/admin/permisos/extra-borrar', [PermisoController::class, 'borrarPermisoGlobal']);

// --- PERFIL ---
Route::get('/admin/editar-perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('admin.perfil');
Route::post('/admin/editar-perfil/actualizar', [PerfilController::class, 'editarUsuario']);

// --- SIN PERMISOS VISTA 403 ---
Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');


// --- EXPEDIENTES ---

// nuevo expediente
Route::get('/admin/expediente/vista/nuevo', [ExpedientesController::class,'indexNuevoExpediente'])->name('admin.expediente.nuevo');
Route::post('/admin/expediente/registro', [ExpedientesController::class, 'nuevoExpediente']);
Route::post('/admin/expediente/actualizar', [ExpedientesController::class, 'actualizarExpediente']);

// buscar expediente
Route::get('/admin/expediente/vista/buscar', [ExpedientesController::class,'indexBuscarExpediente'])->name('admin.expediente.buscar');
Route::get('/admin/expediente/tabla/buscar', [ExpedientesController::class,'tablaBuscarExpediente']);



// --- ASIGNACIONES ---

Route::get('/admin/asignaciones/vista/index', [AsignacionesController::class,'indexAsignaciones'])->name('admin.asignaciones.vista');
Route::post('/admin/asignaciones/buscar/paciente',  [AsignacionesController::class,'buscadorPaciente']);
Route::post('/admin/asignaciones/nuevo/registro',  [AsignacionesController::class,'nuevoRegistro']);
Route::get('/admin/asignaciones/paciente/esperando', [AsignacionesController::class,'tablaPacientesEnEspera']);
Route::get('/admin/asignaciones/tablamodal/enfermeria', [AsignacionesController::class, 'tablaModalEnfermeria']);
Route::get('/admin/asignaciones/tablamodal/consultoria', [AsignacionesController::class, 'tablaModalConsultoria']);
Route::post('/admin/asignaciones/informacion/paciente',  [AsignacionesController::class,'informacionPaciente']);
Route::post('/admin/asignaciones/informacion/guardar',  [AsignacionesController::class,'guardarInformacionEditadaPaciente']);
Route::post('/admin/asignaciones/finalizar/consulta',  [AsignacionesController::class,'finalizarConsultaPaciente']);
Route::post('/admin/asignaciones/ingresar/paciente/asala',  [AsignacionesController::class,'ingresarPacienteALaSala']);

// EDITAR PACIENTE
Route::get('/admin/asignaciones/info/vista/editarpaciente/{idpaciente}', [AsignacionesController::class,'vistaEditarPaciente']);

// Informacion del paciente que esta dentro de la sala, informacion para el modal.
// Ficha Administrativa
Route::post('/admin/asignaciones/info/paciente/dentrosala',  [AsignacionesController::class,'informacionPacienteDentroDeSala']);

// actualizar razon de uso del paciente dentro de la ficha administrativa
Route::post('/admin/asignaciones/actualizar/razonuso/paciente',  [AsignacionesController::class,'actualizarRazonUsoPaciente']);

// liberar sala de paciente
Route::post('/admin/asignaciones/liberarsala/paciente',  [AsignacionesController::class,'liberarSalaPaciente']);

// informacion paciente que esta dentro de una sala y se trasladara a sala de espera de x sala
Route::post('/admin/asignaciones/informacion/paciente/dentrosala',  [AsignacionesController::class,'informacionPacienteDentroSala']);

// trasladar paciente a nueva sala, pero se ira a sala de espera primero
Route::post('/admin/asignaciones/traslado/paciente/reseteo',  [AsignacionesController::class,'reseteoTrasladoPacienteNuevaSala']);

// recarga por cronometro
Route::post('/admin/asignaciones/recargando/cronometro',  [AsignacionesController::class,'recargandoVistaCronometro']);






// --- CONFIGURACIONES ---

// nuevo tipo de paciente

Route::get('/admin/tipopaciente/vista', [NuevoPacienteController::class,'indexNuevoTipoPaciente'])->name('admin.tipo.paciente.nuevo');
Route::get('/admin/tipopaciente/tabla', [NuevoPacienteController::class,'tablaNuevoTipoPaciente']);
Route::post('/admin/tipopaciente/registro', [NuevoPacienteController::class, 'registroNuevoTipoPaciente']);
Route::post('/admin/tipopaciente/informacion', [NuevoPacienteController::class, 'informacionNuevoTipoPaciente']);
Route::post('/admin/tipopaciente/editar', [NuevoPacienteController::class, 'editarNuevoTipoPaciente']);


// --- TIPO DE DOCUMENTO ---

// nuevo tipo de documento

Route::get('/admin/tipodocumento/vista', [TipoDocumentoController::class,'indexNuevoTipoDocumento'])->name('admin.tipo.documento.nuevo');
Route::get('/admin/tipodocumento/tabla', [TipoDocumentoController::class,'tablaNuevoTipoDocumento']);
Route::post('/admin/tipodocumento/registro', [TipoDocumentoController::class, 'registroNuevoTipoDocumento']);
Route::post('/admin/tipodocumento/informacion', [TipoDocumentoController::class, 'informacionNuevoTipoDocumento']);
Route::post('/admin/tipodocumento/editar', [TipoDocumentoController::class, 'editarNuevoTipoDocumento']);


Route::get('/admin/diagnostico/vista', [DiagnosticoController::class,'indexNuevoTipoDiagnostico'])->name('admin.tipo.diagnostico.nuevo');
Route::get('/admin/diagnostico/tabla', [DiagnosticoController::class,'tablaNuevoTipoDiagnostico']);
Route::post('/admin/diagnostico/registro', [DiagnosticoController::class, 'registroNuevoTipoDiagnostico']);
Route::post('/admin/diagnostico/informacion', [DiagnosticoController::class, 'informacionNuevoTipoDiagnostico']);
Route::post('/admin/diagnostico/editar', [DiagnosticoController::class, 'editarNuevoTipoDiagnostico']);


// --- MOTIVO DE CONSULTA ---
Route::get('/admin/motivoconsulta/index', [MotivoConsultaController::class,'indexMotivoConsulta'])->name('admin.motivo.consulta.index');
Route::get('/admin/motivoconsulta/tabla/index', [MotivoConsultaController::class,'tablaMotivoConsulta']);
Route::post('/admin/motivoconsulta/nuevo', [MotivoConsultaController::class, 'nuevoMotivoConsulta']);
Route::post('/admin/motivoconsulta/informacion', [MotivoConsultaController::class, 'infoMotivoConsulta']);
Route::post('/admin/motivoconsulta/editar', [MotivoConsultaController::class, 'editarMotivoConsulta']);


///
//PROFESION
// retorna vista de Profesion
Route::get('/admin/profesion/index', [ProfesionController::class,'indexProfesion'])->name('admin.profesion.index');
// retorna tabla de Profesion
Route::get('/admin/profesion/tabla/index', [ProfesionController::class,'tablaProfesion']);
// registrar una nueva Profesion
Route::post('/admin/profesion/nuevo', [ProfesionController::class, 'nuevaProfesion']);
// obtener información de una Profesion
Route::post('/admin/profesion/informacion', [ProfesionController::class, 'infoProfesion']);
// editar una Profesion
Route::post('/admin/profesion/editar', [ProfesionController::class, 'editarProfesion']);

//ESTADO CIVIL
// retorna vista de Estado Civil
Route::get('/admin/estadocivil/index', [EstadoCivilController::class,'indexEstadoCivil'])->name('admin.estadocivil.index');
// retorna tabla de Estado Civil
Route::get('/admin/estadocivil/tabla/index', [EstadoCivilController::class,'tablaEstadoCivil']);
// registrar un nuevo Estado Civil
Route::post('/admin/estadocivil/nuevo', [EstadoCivilController::class, 'nuevoEstadoCivil']);
// obtener información de un Estado Civil
Route::post('/admin/estadocivil/informacion', [EstadoCivilController::class, 'infoEstadoCivil']);
// editar un Estado Civil
Route::post('/admin/estadocivil/editar', [EstadoCivilController::class, 'editarEstadoCivil']);

//MEDICO
// retorna vista de medico
Route::get('/admin/medico/index', [MedicoController::class,'indexMedico'])->name('admin.medico.index');
// retorna tabla de un Médico
Route::get('/admin/medico/tabla/index', [MedicoController::class,'tablaMedico']);
// registrar una nueva medico
Route::post('/admin/medico/nuevo', [MedicoController::class, 'nuevoMedico']);
// obtener información de un Médico
Route::post('/admin/medico/informacion', [MedicoController::class, 'infoMedico']);
// editar una medico
Route::post('/admin/medico/editar', [MedicoController::class, 'editarMedico']);


// ANTECEDENTES MEDICOS
// se guarda tipo de antecedente y su nombre
Route::get('/admin/antecedentes/medico/index', [MedicoController::class,'indexAntecedentesMedicos'])->name('admin.antecedentes.medico.index');
Route::get('/admin/antecedentes/medico/tabla/index', [MedicoController::class,'tablaAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/nuevo', [MedicoController::class, 'nuevoAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/informacion', [MedicoController::class, 'infoAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/editar', [MedicoController::class, 'editarAntecedentesMedico']);



// HISTORIAL CLINICO

// vista general de historial clinico
Route::get('/admin/historial/vista/general/{idconsulta}', [HistorialClinicoController::class, 'indexVistaGeneralHistorial']);

// actualizar listado de checkbox de antecedente del paciente
Route::post('/admin/historial/antecedente/actualizacion', [HistorialClinicoController::class, 'actualizarListadoPacienteAntecedente']);

// cargar tabla de antrometria por paciente
Route::get('/admin/historial/antrometria/paciente-consulta/{idconsulta}', [HistorialClinicoController::class, 'tablaAntrometriaPaciente']);

// registrar formulario de antropometria
Route::post('/admin/historial/registrar/antropometria', [HistorialClinicoController::class, 'registrarAntropometria']);

// informacion de una antropometria
Route::post('/admin/historial/informacion/antropometria', [HistorialClinicoController::class, 'informacionAntropometria']);

// actualizar antropometria
Route::post('/admin/historial/actualizar/antropometria', [HistorialClinicoController::class, 'actualizarAntropometria']);





















