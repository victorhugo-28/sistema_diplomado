<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoarticuloController extends Controller
{
    
    public function store(Request $request)
    {
        // Datos para enviar a la API
        $data = [
            "nombre" => $request->input('nombre')
        ];

        $ch = curl_init('https://36482cdcb6d0.ngrok-free.app/tipos_articulo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with('mensaje', 'Tipo de artículo enviado correctamente.');
    }

    public function mostrar()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://36482cdcb6d0.ngrok-free.app/tipos_articulo',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $tipos = json_decode($response, true);
        
        return view('tipoproducto', compact('tipos'));
    }

    public function editar(Request $request, $id)
    {
        $data = [
            "id" => (int) $id,
            "nombre" => $request->input('nombre')
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/tipos_articulo/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 204) {
            return view('actualizado');
        } else {
            return back()->with('error', 'Error al actualizar el tipo de artículo. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    }
}