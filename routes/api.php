<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API.
| Se agrupan con middlewares para proteger endpoints según roles.
|
*/

// ✅ Rutas públicas (registro y login)
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// ✅ Rutas protegidas con token JWT (middleware personalizado: isUserAuth)
Route::middleware(['isUserAuth'])->group(function () {

    // 🔐 Ruta para obtener el usuario autenticado
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    // ✅ Rutas solo para ADMIN
    Route::middleware(['isAdmin'])->group(function () {

        // CRUD tareas
        Route::apiResource('/tareas', TareaController::class)->names([
            'index'   => 'api.tareas.index',
            'store'   => 'api.tareas.store',
            'update'  => 'api.tareas.update',
            'destroy' => 'api.tareas.destroy',
            'show'    => 'api.tareas.show',
        ]);

        // Asociar personaje a tarea
        Route::put('/tareas/{id}/personaje', [TareaController::class, 'asociarPersonaje'])->name('api.tareas.asociarPersonaje');
    });
});
