<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BodegaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\TrasladoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login'); 
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*Route::middleware(['auth'])->group(function () {
    Route::resource('admin/users', App\Http\Controllers\Admin\UserController::class);
});*/

Auth::routes();

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
});

Route::resource('bodegas', BodegaController::class);
Route::get('/productos/buscar', [ProductoController::class, 'buscar'])
    ->name('productos.buscar');
Route::resource('productos', ProductoController::class);
Route::resource('facturas', FacturaController::class);
Route::post('facturas/{factura}/cancelar', [FacturaController::class, 'cancelar'])
    ->name('facturas.cancelar');
Route::resource('inventarios', InventarioController::class);
//Route::get('/traslados/{movimiento}', [TrasladoController::class, 'show'])
    //->name('traslados.show');
Route::resource('traslados', TrasladoController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('facturas/manual/create', [FacturaController::class, 'createManual'])
        ->name('facturas.createManual');
    Route::post('facturas/manual/store', [FacturaController::class, 'storeManual'])
        ->name('facturas.storeManual');
});
Route::post('facturas/xml', 
    [FacturaController::class, 'cargarXml']
)->name('facturas.cargarXml');