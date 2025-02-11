<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DataPedidosController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\EmpacadoController;

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	 
    Route::get('factura-cdmx', [FacturaController::class, 'index'])->name('facturas.index');
	Route::post('factura-cdmx', [FacturaController::class, 'store'])->name('facturas.store');

	Route::get('factura-oaxaca', [FacturaController::class, 'facturasOaxaca'])->name('facturas-oaxaca.index');
	Route::post('factura-oaxaca', [FacturaController::class, 'storeOaxaca'])->name('facturas-oaxaca.store');

	Route::get('factura-xalapa', [FacturaController::class, 'facturasXalapa'])->name('facturas-xalapa.index');
	Route::post('factura-xalapa', [FacturaController::class, 'storeXalapa'])->name('facturas-xalapa.store');

	Route::get('pedidos', [PedidosController::class, 'index'])->name('pedidos.index');
	Route::post('pedidos', [PedidosController::class, 'store'])->name('pedidos.store'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	Route::get('/page/copy-data', [DataController::class, 'copyData'])->name('copyData'); // Ruta para copiar y actualizar datos
	Route::get('/page/copy-data-pedidos', [DataPedidosController::class, 'copyData'])->name('copyDataPedidos'); // Ruta para copiar y actualizar datos

});