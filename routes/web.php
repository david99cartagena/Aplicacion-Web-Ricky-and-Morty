<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se definen las rutas accesibles desde el navegador.
| Incluyen autenticación, vistas y manejo de tareas.
*/

// ✅ Redirección raíz al login (solo una vez)
Route::get('/', function () {
    return redirect('/index.html');
});

// ✅ Login
Route::post('/login', [AuthController::class, 'loginWeb']);

// ✅ Registro
Route::post('/register', [AuthController::class, 'register']);

// ✅ Dashboard para usuario autenticado
Route::middleware(['auth'])->group(function () {

    // Vista principal del dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    })->name('dashboard');

    // Vista de tareas (para usuarios y admin)
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
});
