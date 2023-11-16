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
use App\Http\Controllers\Backend\Configuracion\LineasController;
use App\Http\Controllers\Backend\Configuracion\ProveedorController;
use App\Http\Controllers\Backend\Farmacia\FarmaciaController;
use App\Http\Controllers\Backend\Historial\RecetasController;
use App\Http\Controllers\Backend\Expedientes\DocumentoRecetaController;




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
Route::post('/admin/asignaciones/ingresar/paciente/sala',  [AsignacionesController::class,'ingresarPacienteALaSala']);


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

// --- DIAGNOSTICO ---
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



// --- PROFESION ---
Route::get('/admin/profesion/index', [ProfesionController::class,'indexProfesion'])->name('admin.profesion.index');
Route::get('/admin/profesion/tabla/index', [ProfesionController::class,'tablaProfesion']);
Route::post('/admin/profesion/nuevo', [ProfesionController::class, 'nuevaProfesion']);
Route::post('/admin/profesion/informacion', [ProfesionController::class, 'infoProfesion']);
Route::post('/admin/profesion/editar', [ProfesionController::class, 'editarProfesion']);

// --- ESTADO CIVIL ---
Route::get('/admin/estadocivil/index', [EstadoCivilController::class,'indexEstadoCivil'])->name('admin.estadocivil.index');
Route::get('/admin/estadocivil/tabla/index', [EstadoCivilController::class,'tablaEstadoCivil']);
Route::post('/admin/estadocivil/nuevo', [EstadoCivilController::class, 'nuevoEstadoCivil']);
Route::post('/admin/estadocivil/informacion', [EstadoCivilController::class, 'infoEstadoCivil']);
Route::post('/admin/estadocivil/editar', [EstadoCivilController::class, 'editarEstadoCivil']);

// --- MEDICO ---
Route::get('/admin/medico/index', [MedicoController::class,'indexMedico'])->name('admin.medico.index');
Route::get('/admin/medico/tabla/index', [MedicoController::class,'tablaMedico']);
Route::post('/admin/medico/nuevo', [MedicoController::class, 'nuevoMedico']);
Route::post('/admin/medico/informacion', [MedicoController::class, 'infoMedico']);
Route::post('/admin/medico/editar', [MedicoController::class, 'editarMedico']);


// --- ANTECEDENTES MEDICOS ---
// se guarda tipo de antecedente y su nombre
Route::get('/admin/antecedentes/medico/index', [MedicoController::class,'indexAntecedentesMedicos'])->name('admin.antecedentes.medico.index');
Route::get('/admin/antecedentes/medico/tabla/index', [MedicoController::class,'tablaAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/nuevo', [MedicoController::class, 'nuevoAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/informacion', [MedicoController::class, 'infoAntecedentesMedico']);
Route::post('/admin/antecedentes/medico/editar', [MedicoController::class, 'editarAntecedentesMedico']);


// --- LINEAS ---
Route::get('/admin/linea/vista', [LineasController::class,'indexVistaLinea'])->name('admin.vista.linea');
Route::get('/admin/linea/tabla', [LineasController::class,'tablaVistaLinea']);
Route::post('/admin/linea/registro', [LineasController::class, 'registroNuevaLinea']);
Route::post('/admin/linea/informacion', [LineasController::class, 'informacionLinea']);
Route::post('/admin/linea/editar', [LineasController::class, 'editarLinea']);



// --- SUB LINEAS ---
Route::get('/admin/sub/linea/vista', [LineasController::class,'indexVistaSubLinea'])->name('admin.vista.sub.linea');
Route::get('/admin/sub/linea/tabla', [LineasController::class,'tablaVistaSubLinea']);
Route::post('/admin/sub/linea/registro', [LineasController::class, 'registroSubNuevaLinea']);
Route::post('/admin/sub/linea/informacion', [LineasController::class, 'informacionSubLinea']);
Route::post('/admin/sub/linea/editar', [LineasController::class, 'editarSubLinea']);


// --- PROVEEDORES ---
Route::get('/admin/proveedores/vista/index', [ProveedorController::class,'indexVistaProveedor'])->name('admin.vista.proveedor');
Route::get('/admin/proveedores/vista/tabla', [ProveedorController::class,'tablaVistaProveedor']);
Route::post('/admin/proveedores/registro', [ProveedorController::class, 'registroNuevoProveedor']);
Route::post('/admin/proveedores/informacion', [ProveedorController::class, 'informacionProveedor']);
Route::post('/admin/proveedores/editar', [ProveedorController::class, 'editarProveedor']);


// --- TIPO DE MEDICAMENTO ---
Route::get('/admin/tipo/medicamento/vista/index', [ProveedorController::class,'indexVistaTipoMedicamento'])->name('admin.vista.tipo.medicamento');
Route::get('/admin/tipo/medicamento/vista/tabla', [ProveedorController::class,'tablaVistaTipoMedicamento']);
Route::post('/admin/tipo/medicamento/registro', [ProveedorController::class, 'registroNuevoTipoMedicamento']);
Route::post('/admin/tipo/medicamento/informacion', [ProveedorController::class, 'informacionTipoMedicamento']);
Route::post('/admin/tipo/medicamento/editar', [ProveedorController::class, 'editarTipoMedicamento']);


// --- REGISTRO PRIMER ARTICULO EN FARMACIA---
Route::get('/admin/farmacia/registrar/articulo/index', [FarmaciaController::class,'indexRegistroArticulo'])->name('admin.farmacia.registrar.articulo');
Route::post('/admin/farmacia/registrar/nuevo/articulo', [FarmaciaController::class, 'registrarArticulo']);


// --- INGRESO DE ARTICULO FARMACIA
Route::get('/admin/farmacia/ingreso/articulo/index', [FarmaciaController::class,'indexIngresoArticulo'])->name('admin.farmacia.ingreso.articulo');
Route::post('/admin/buscar/nombre/medicamento',  [FarmaciaController::class,'buscarMedicamento']);
Route::post('/admin/registrar/nuevo/medicamento',  [FarmaciaController::class,'registrarNuevoMedicamento']);


// --- MOTIVO PARA FARMACIA ---
Route::get('/admin/motivo/farmacia/index', [ProfesionController::class,'indexMotivoFarmacia'])->name('admin.motivo.farmacia.index');
Route::get('/admin/motivo/farmacia/tabla/index', [ProfesionController::class,'tablaMotivoFarmacia']);
Route::post('/admin/motivo/farmacia/nuevo', [ProfesionController::class, 'nuevaMotivoFarmacia']);
Route::post('/admin/motivo/farmacia/informacion', [ProfesionController::class, 'infoMotivoFarmacia']);
Route::post('/admin/motivo/farmacia/editar', [ProfesionController::class, 'editarMotivoFarmacia']);


// --- SALIDA DE MEDICAMENTO POR PARTE DE FARMACIA ---
Route::get('/admin/salida/medicamento/farmacia/index', [FarmaciaController::class,'indexSalidaFarmacia'])->name('admin.salida.farmacia.index');

// --- CARGAR TABLA PARA ELEGIR PRODUCTO PARA SALIDA DE FARMACIA ---
Route::get('/admin/buscar/producto/salida/farmacia/{idproducto}', [FarmaciaController::class,'elegirProductoParaSalida']);
Route::post('/admin/registrar/orden/salida/medicamento', [FarmaciaController::class, 'registrarOrdenSalidaFarmacia']);

// --- SALIDA MEDICAMENTO POR RECETA
Route::get('/admin/salida/medicamento/porreceta/index', [FarmaciaController::class,'indexSalidaFarmaciaPorReceta'])->name('admin.salida.recetas.farmacia.index');
Route::get('/admin/salida/medicamento/porreceta/tabla/{idestado}/{fechainicio}/{fechafin}', [FarmaciaController::class,'tablaSalidaFarmaciaPorReceta']);

// vista salida para procesar la receta
Route::get('/admin/vista/procesar/recetamedica/{idreceta}', [FarmaciaController::class,'vistaRecetaDetalleProcesar']);

// informacion de receta para denegarla
Route::post('/admin/orden/salida/informacion/paradenegar', [FarmaciaController::class, 'infoRecetaParaDenegar']);

// guardar la denegacion de una receta
Route::post('/admin/orden/salida/guardar/denegacion', [FarmaciaController::class, 'guardarDenegacionReceta']);

// guardar salida de receta procesada por farmacia
Route::post('/admin/receta/procesar/guardarsalida', [FarmaciaController::class, 'guardarSalidaProcesadaDeReceta']);


// --- VIA PARA RECETA ---
Route::get('/admin/receta/via/vista', [LineasController::class,'indexVistaViaReceta'])->name('admin.vista.via.receta');
Route::get('/admin/receta/via/tabla', [LineasController::class,'tablaVistaViaReceta']);
Route::post('/admin/receta/via/registro', [LineasController::class, 'registroNuevaViaReceta']);
Route::post('/admin/receta/via/informacion', [LineasController::class, 'informacionViaReceta']);
Route::post('/admin/receta/via/editar', [LineasController::class, 'editarViaReceta']);



// --- HISTORIAL CLINICO ---
Route::get('/admin/historial/clinico/vista/{idconsulta}', [HistorialClinicoController::class, 'indexHistorialClinico']);


// --- BLOQUE ANTECEDENTES ---
// bloque antecedentes
Route::get('/admin/historial/bloque/antecedente/{idconsulta}', [HistorialClinicoController::class, 'bloqueHistorialAntecedente']);

// actualizar listado de checkbox de antecedente del paciente
Route::post('/admin/historial/antecedente/actualizacion', [HistorialClinicoController::class, 'actualizarListadoPacienteAntecedente']);



// --- BLOQUE ANTROP SV ---

// bloque antrop + sv
Route::get('/admin/historial/bloque/antropsv/{idconsulta}', [HistorialClinicoController::class, 'bloqueHistorialAntropSv']);

// vista para registar nueva antrop + sv
Route::get('/admin/vista/nueva/antropologia/{idconsulta}', [HistorialClinicoController::class, 'vistaNuevaAntropologia']);

// registrar formulario de antropometria
Route::post('/admin/historial/registrar/antropometria', [HistorialClinicoController::class, 'registrarAntropometria']);

// vista para editar o ver la antropologia
Route::get('/admin/vista/visualizar/antropologia/{idantro}', [HistorialClinicoController::class, 'vistaVisualizarAntropologia']);

// actualizar antropometria
Route::post('/admin/historial/actualizar/antropometria', [HistorialClinicoController::class, 'actualizarAntropometria']);




// --- BLOQUE CUADRO CLINICO ---

// bloque cuadro clinico
Route::get('/admin/historial/bloque/cuadroclinico/{idconsulta}', [HistorialClinicoController::class, 'bloqueHistorialCuadroClinico']);

// guardar un nuevo historial clinico
Route::post('/admin/historial/nuevo/historialclinico', [HistorialClinicoController::class, 'nuevoHistorialClinico']);

// informacion de un cuadro clinico para editar
Route::post('/admin/historial/informacion/historialclinico', [HistorialClinicoController::class, 'informacionHistorialClinico']);

// actualizar un cuadro clinico
Route::post('/admin/historial/actualizar/historialclinico', [HistorialClinicoController::class, 'actualizarHistorialClinico']);


// --- BLOQUE RECETAS ---

// bloque recetas
Route::get('/admin/historial/bloque/recetas/{idconsulta}', [HistorialClinicoController::class, 'bloqueHistorialRecetas']);


// vista de agregar receta
Route::get('/admin/recetas/vista/general/{idconsulta}', [RecetasController::class, 'indexVistaNuevaReceta']);

// listado de medicamentos por fuente
Route::post('/admin/recetas/medicamentos/porfuente', [RecetasController::class, 'listadoMedicamentosPorFuenteFinan']);

// registar la nueva receta al paciente
Route::post('/admin/recetas/registro/parapaciente', [RecetasController::class, 'registroNuevaRecetaParaPaciente']);

// vista para editar o ver la receta individual
Route::get('/admin/recetas/vista/paraeditar/{idreceta}', [RecetasController::class, 'indexVistaEditarVerReceta']);

// actualizar la receta si es permitido por estado
Route::post('/admin/recetas/actualizar/parapaciente', [RecetasController::class, 'actualizarRecetaMedica']);



// --- DOCUMENTOS Y RECETAS PARA UN PACIENTE UNICAMENTE ---

Route::get('/admin/documentoreceta/vista/{idpaciente}', [DocumentoRecetaController::class, 'indexDocumentosRecetas']);

// antecedentes todos por paciente
Route::get('/admin/documentoreceta/bloque/antecedentes/{idpaciente}', [DocumentoRecetaController::class, 'tablaAntecedentesPorPaciente']);


// antropometria sv todos por paciente
Route::get('/admin/documentoreceta/bloque/antropometriasv/{idpaciente}', [DocumentoRecetaController::class, 'tablaAntropometriaPorPaciente']);

// todas las recetas para un paciente

Route::get('/admin/documentoreceta/bloque/recetas/{idpaciente}', [DocumentoRecetaController::class, 'tablaRecetasPorPaciente']);

// todos los cuadros clinicos de un paciente
Route::get('/admin/documentoreceta/bloque/cuadroclinico/{idpaciente}', [DocumentoRecetaController::class, 'tablaCuadroClinicoPorPaciente']);

// listado de array de diagnosticos
Route::post('/admin/diagnosticos/guardar/getlistado/completo', [DiagnosticoController::class, 'registroExtraDiagnostico']);

// listado de array de vias
Route::post('/admin/vias/guardar/getlistado/completo', [DiagnosticoController::class, 'registroExtraVia']);



