<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function crear()
    {
        return view('clientes.clientes');
    }

    public function store(Request $request)
    {
        // Aquí usaremos cURL para mandar los datos a tu API externa
        $data = [
            "nombre" => $request->input('nombre'),
            "email" => $request->input('email'),
            "telefono" => $request->input('telefono')
        ];

        $ch = curl_init('https://3d2ab82f1074.ngrok-free.app/clientes'); // Asegúrate de que sea tu endpoint
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with('mensaje', 'Cliente enviado correctamente.');
    }
    public function mostrar()
    {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/clientes',
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

    $clientes = json_decode($response, true);
    
    return view('clientes.clientes', compact('clientes'));
    }
    public function editar(Request $request, $id)
    {
        $data = [
            "id"        => (int) $id, // El backend espera el campo 'id' en el body
            "nombre"    => $request->input('nombre'),
            "email"     => $request->input('email'),
            "telefono"  => $request->input('telefono')
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://3d2ab82f1074.ngrok-free.app/clientes/$id",
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
            return redirect()->route('cliente.mostrar')->with('success', 'Cliente actualizado correctamente.');
        } else {
            return back()->with('error', 'Error al actualizar el cliente. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    }
    




}
