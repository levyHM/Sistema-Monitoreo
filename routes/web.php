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
use App\Http\Controllers\DataClientesController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DataPedidosController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmbarqueController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\CamionetaController;
use App\Http\Controllers\RutaController;
use	App\Http\Controllers\DataRutasController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FaltanteController;
use App\Http\Controllers\ReporteFaltanteController;
use App\Http\Controllers\CatalogoFaltanteController;
use App\Http\Controllers\CatalogoProductoController;
use App\Http\Controllers\RolesPermissionsController;
use App\Http\Controllers\ReporteSolucionesClienteController;
use App\Http\Controllers\CatalogoClienteController;
use App\Http\Controllers\SolucionesController;





Route::get('/', function () {
	return redirect('/dashboard');
})->middleware('auth');
//Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
//Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {

	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	//Factura de CDMX  
	Route::get('factura-cdmx', [FacturaController::class, 'index'])->name('facturas.index');
	Route::post('factura-cdmx', [FacturaController::class, 'store'])->name('facturas-cdmx.store');
	//Factura de Oaxaca 
	Route::get('factura-oaxaca', [FacturaController::class, 'facturasOaxaca'])->name('facturas-oaxaca.index');
	Route::post('factura-oaxaca', [FacturaController::class, 'store'])->name('facturas-oaxaca.store');

	Route::get('factura-xalapa', [FacturaController::class, 'facturasXalapa'])->name('facturas-xalapa.index');
	Route::post('factura-xalapa', [FacturaController::class, 'store'])->name('facturas.store');
	//Pedido de CDMX 
	Route::get('pedidos-cdmx', [PedidosController::class, 'index'])->name('pedidos.index');
	Route::post('pedidos-cdmx', [PedidosController::class, 'store'])->name('pedidos.store');
	//Pedido de Oaxaca  
	Route::get('pedidos-oaxaca', [PedidosController::class, 'pedidosOaxaca'])->name('pedidos-oaxaca.index');
	Route::post('pedidos-oaxaca', [PedidosController::class, 'store'])->name('pedidos.store');
	//Pedido de Oaxaca
	Route::get('pedidos-xalapa', [PedidosController::class, 'pedidosXalapa'])->name('pedidos-xalapa.index');
	Route::post('pedidos-oaxaca', [PedidosController::class, 'store'])->name('pedidos.store');
	//Embarque CDMX
	Route::get('/embarque-cdmx', [PageController::class, 'embarqueCDMX'])->name('embarque-cdmx');
	Route::get('/clientes/buscar', [ClienteController::class, 'buscarClientes'])->name('buscarClientes');
	Route::get('/clientes/obtener', [ClienteController::class, 'obtenerCliente'])->name('obtenerCliente');
	Route::post('embarques/import', [EmbarqueController::class, 'import'])->name('embarques.import');

	Route::get('/embarques', [EmbarqueController::class, 'index'])->name('embarque-cdmx');
	Route::put('/embarques/{id}', [EmbarqueController::class, 'update'])->name('embarques.update');
	Route::post('embarques', [EmbarqueController::class, 'updateEscaner'])->name('embarques.store');

	//Reportes de faltantes
	Route::prefix('reporte-faltante')->group(function () {
		Route::get('/', [ReporteFaltanteController::class, 'index'])->name('reporte.faltante');
		Route::get('/create', [ReporteFaltanteController::class, 'create'])->name('reporte.faltante.create');
		Route::post('/', [ReporteFaltanteController::class, 'store'])->name('reporte.faltante.store');
		Route::get('/{id}/show', [ReporteFaltanteController::class, 'show'])->name('reporte.faltante.show');
		Route::put('/{id}', [ReporteFaltanteController::class, 'update'])->name('reporte.faltante.update');
		Route::put('/{id}/cancelar', [ReporteFaltanteController::class, 'cambiarEstatus'])->name('reporte.faltante.cancelar');
		Route::get('/{id}/edit', [ReporteFaltanteController::class, 'edit'])->name('reporte.faltante.edit');
		Route::put('/{id}/autorizacion', [ReporteFaltanteController::class, 'autorizacionFirma'])->name('reporte.faltante.autorizacion');
	});

	//Rutas para soluciones a clientes
	Route::resource('soluciones', ReporteSolucionesClienteController::class);
	Route::put('/soluciones/{id}/estatus', [ReporteSolucionesClienteController::class, 'cambiarEstatusSolucionesClientes'])->name('soluciones.cambiar.estatus');
	Route::get('/buscar-catalogo', [SolucionesController::class, 'buscarCatalogo'])->name('soluciones.buscar.catalogo');
	Route::post('/soluciones/{id}/firmar', [ReporteSolucionesClienteController::class, 'firmar'])->name('soluciones.firmas');
	// Crear nueva solución según tipo
	Route::get('/soluciones-garantia', [ReporteSolucionesClienteController::class, 'create'])
		->defaults('tipo', 1)
		->name('soluciones.garantia');
	Route::get('/soluciones-devolucion', [ReporteSolucionesClienteController::class, 'create'])
		->defaults('tipo', 2)
		->name('soluciones.devolucion');



	Route::get('/clientes/buscar-cliente', [CatalogoClienteController::class, 'buscar'])->name('clientes.buscar.cliente');

	Route::get('/clientes/buscar', [CatalogoFaltanteController::class, 'buscar'])->name('clientes.buscar');
	//Route::get('/clientes/buscar-faltante', [CatalogoFaltanteController::class, 'buscar'])->name('clientes.buscar.faltante');



	//Administrador conductores
	Route::resource('conductores', ConductorController::class);
	//Administrador camionetas
	Route::resource('camionetas', CamionetaController::class);
	//Route::get('/rutas', [RutaController::class, 'index'])->name('ruta.index');
	Route::resource('rutas', RutaController::class);

	//Usuario 
	Route::get('/user-management', [UserProfileController::class, 'index'])->name('user-management');
	Route::delete('/user-management/{user}', [UserProfileController::class, 'destroy'])->name('user-management.destroy');

	Route::post('/register', [RegisterController::class, 'store'])->name('register.id');
	Route::get('/register', [RegisterController::class, 'create'])->name('register');


	//generar PDF
	Route::get('/recibos/pdf/{id}', [PDFController::class, 'generarRecibo'])->name('pdf.recibos');
	Route::get('faltantes/cdmx/pdf/{id}', [PDFController::class, 'generarFaltante'])->name('pdf.faltantes');
	Route::get('reporte-faltante/pdf/{id}', [PDFController::class, 'generarReporteFaltantePDF'])->name('pdf.reporte_faltante');
	Route::get('/reporte-soluciones/pdf/{id}', [PDFController::class, 'generarPDFSolucionesCliente'])->name('pdf.reporte_soluciones_cliente');



	//recibos
	Route::resource('/recibos', ReciboController::class);
	Route::put('recibos/{recibo}/cancelar-devolucion', [ReciboController::class, 'cancelarDevolucion'])->name('recibos.cancelarDevolucion');
	Route::post('/firmas/{recibo}/firmar', [ReciboController::class, 'firmarRecibo'])->name('firmas.firmar')->middleware('auth');


	Route::prefix('faltantes/cdmx')->name('faltantes.cdmx.')->group(function () {
		Route::get('/', [FaltanteController::class, 'index'])->name('index');
		Route::get('/create', [FaltanteController::class, 'create'])->name('create');
		Route::post('/', [FaltanteController::class, 'store'])->name('store');
		Route::get('/{recibo}/edit', [FaltanteController::class, 'edit'])->name('edit');
		Route::put('/{recibo}', [FaltanteController::class, 'update'])->name('update');
		Route::get('/{recibo}', [FaltanteController::class, 'show'])->name('show');
		Route::put('/{recibo}/cancelar-devolucion', [FaltanteController::class, 'cancelar'])->name('faltantes.cancelar');
		//Route::delete('/{id}', [FaltanteController::class, 'destroy'])->name('destroy');

	});

	// Buscar productos
	Route::get('productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');
	Route::get('/facturas/buscar', [CatalogoProductoController::class, 'buscarFactura'])->name('facturas.buscar');


	//roles y permisos
	Route::prefix('usuarios')->name('usuarios.')->group(function () {
		Route::get('/', [UserProfileController::class, 'index'])->name('index');
		Route::get('/{user}/edit', [UserProfileController::class, 'edit'])->name('edit');
		Route::put('/{user}', [UserProfileController::class, 'update'])->name('update');
		Route::get('/create', [UserProfileController::class, 'create'])->name('create');
		Route::post('/', [UserProfileController::class, 'store'])->name('store');
	});

	Route::prefix('roles')->name('roles.')->group(function () {
		Route::get('/', [RolesPermissionsController::class, 'index'])->name('index');
		Route::get('/create', [RolesPermissionsController::class, 'create'])->name('create');
		Route::post('/', [RolesPermissionsController::class, 'store'])->name('store');
		Route::get('/{id}/edit', [RolesPermissionsController::class, 'edit'])->name('edit');
		Route::put('/{id}', [RolesPermissionsController::class, 'update'])->name('update');
		Route::delete('/{id}', [RolesPermissionsController::class, 'destroy'])->name('destroy');
	});

	// Rutas para permisos
	Route::prefix('permissions')->name('permissions.')->group(function () {
		Route::get('/', [RolesPermissionsController::class, 'permissionsIndex'])->name('index');
		Route::get('/create', [RolesPermissionsController::class, 'permissionsCreate'])->name('create');
		Route::post('/', [RolesPermissionsController::class, 'permissionsStore'])->name('store');
		Route::get('/{id}/edit', [RolesPermissionsController::class, 'permissionsEdit'])->name('edit');
		Route::put('/{id}', [RolesPermissionsController::class, 'permissionsUpdate'])->name('update');
		Route::delete('/{id}', [RolesPermissionsController::class, 'permissionsDestroy'])->name('destroy');
	});


	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	Route::get('/page/copy-data', [DataController::class, 'copyData'])->name('copyData'); // Ruta para copiar y actualizar datos
	Route::get('/page/copy-data-pedidos', [DataPedidosController::class, 'copyData'])->name('copyDataPedidos'); // Ruta para copiar y actualizar datos
	Route::get('/page/copy-data-clientes', [DataClientesController::class, 'copyData'])->name('copyDataClientes'); // Ruta para copiar y actualizar datos
	Route::get('rutas/s', [DataRutasController::class, 'copyData'])->name('copyDataRutas'); // Ruta para copiar y actualizar datos



});
