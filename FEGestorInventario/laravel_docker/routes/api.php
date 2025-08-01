<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::post('/prueba', function (Request $request) {
    $cliente = new Clientes();
    $cliente->nombre = $request->input('nombre');
    $cliente->email = $request->input('email');
    $cliente->telefono = $request->input('telefono');
    $cliente->save();

    return response()->json($cliente);
});
