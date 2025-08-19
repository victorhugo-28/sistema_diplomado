<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\IngresosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TipoarticuloController;
use App\Http\Controllers\VentasController;


// clientes
Route::get('/', [ClienteController::class, 'mostrar'])->name('cliente.mostrar');
Route::post('/cliente/crear', [ClienteController::class, 'store'])->name('cliente.store');
//Route::get('/clientes', [ClienteController::class, 'mostrar'])->name('cliente.mostrar');
Route::put('/clientes/{id}', [ClienteController::class, 'editar'])->name('clientes.editar');


// artículos
Route::get('/articulos', [ArticulosController::class, 'mostrar'])->name('articulos.mostrar');
Route::post('/articulos/crear', [ArticulosController::class, 'store'])->name('articulos.store');
Route::put('/articulos/{id}', [ArticulosController::class, 'editar'])->name('articulos.editar');
Route::get('/articulos/{id}', [ArticulosController::class, 'show']);

// ingresos
Route::get('/ingresos', [IngresosController::class, 'mostrar'])->name('ingresos.mostrar');
Route::post('/ingresos/crear', [IngresosController::class, 'store'])->name('ingresos.store');
Route::put('/ingresos/{id}', [IngresosController::class, 'editar'])->name('ingresos.editar');



//proveedores
Route::get('/proveedores', [ProveedorController::class, 'mostrar'])->name('proveedores.mostrar');
Route::post('/proveedores/crear', [ProveedorController::class, 'store'])->name('proveedores.store');
Route::put('/proveedores/{id}', [ProveedorController::class, 'editar'])->name('proveedores.editar');


//articulos
Route::get('/tipos-articulo', [TipoarticuloController::class, 'mostrar'])->name('tipos-articulo.mostrar');
Route::post('/tipos-articulo/crear', [TipoarticuloController::class, 'store'])->name('tipos-articulo.store');
Route::put('/tipos-articulo/{id}', [TipoarticuloController::class, 'editar'])->name('tipos-articulo.editar');

//ventas
Route::get('/ventas', [VentasController::class, 'mostrar'])->name('ventas.mostrar');
Route::post('/ventas/crear', [VentasController::class, 'store'])->name('ventas.store');
