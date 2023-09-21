<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Backend\Perfil\PerfilController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Controles\ControlController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Expedientes\ExpedientesController;
use App\Http\Controllers\Backend\Configuracion\NuevoPacienteController;
use App\Http\Controllers\Backend\Configuracion\TipoDocumentoController;
use App\Http\Controllers\Backend\Configuracion\DiagnosticoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
Route::get('/admin/vista/nuevo/expediente', [ExpedientesController::class,'indexNuevoExpediente'])->name('admin.expediente.nuevo');





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










/// AQUI CREE PARA ABAJO





