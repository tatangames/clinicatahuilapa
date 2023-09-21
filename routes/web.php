<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Backend\Perfil\PerfilController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Controles\ControlController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Expedientes\ExpedientesController;
use App\Http\Controllers\Backend\Configuracion\ConfiguracionController;
use App\Http\Controllers\Backend\Configuracion\ProfesionController;
use App\Http\Controllers\Backend\Configuracion\EstadoCivilController;
use App\Http\Controllers\Backend\Configuracion\MedicoController;
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

Route::get('/admin/vista/nuevo/tipopaciente', [ConfiguracionController::class,'indexNuevoTipoPaciente'])->name('admin.tipo.paciente.nuevo');














/// AQUI CREE PARA ABAJO
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


