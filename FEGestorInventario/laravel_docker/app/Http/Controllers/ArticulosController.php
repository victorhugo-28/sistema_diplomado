<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticulosController extends Controller
{
    
    public function store(Request $request)
    {
        // Datos para enviar a la API
        $data = [
            "articulonombre" => $request->input('articulonombre'),
            "articulostock" => (int)$request->input('articulostock', 0),
            "articulodescripcion" => $request->input('articulodescripcion'),
            "articuloimagen" => $request->input('articuloimagen'),
            "articulocodigogener" => $request->input('articulocodigogener'),
            "articulocodigo" => $request->input('articulocodigo'),
            "articulofecha" => $request->input('articulofecha', date('Y-m-d')),
            "articulohora" => $request->input('articulohora', date('H:i:s')),
            "id_tipo" => $request->input('id_tipo') ? (int)$request->input('id_tipo') : null,
            "id_proveedor" => $request->input('id_proveedor') ? (int)$request->input('id_proveedor') : null
        ];

        $ch = curl_init('https://36482cdcb6d0.ngrok-free.app/articulos');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with('mensaje', 'Artículo enviado correctamente.');
    }

    public function mostrar()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://36482cdcb6d0.ngrok-free.app/articulos',
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

        $articulos = json_decode($response, true);
        
        // Obtener datos adicionales de tipos y proveedores
        if ($articulos) {
            foreach ($articulos as &$articulo) {
                // Obtener nombre del tipo
                if (isset($articulo['id_tipo'])) {
                    $curlTipo = curl_init();
                    curl_setopt_array($curlTipo, array(
                        CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/articulos/{$articulo['id_tipo']}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    $tipoResponse = curl_exec($curlTipo);
                    curl_close($curlTipo);
                    $tipo = json_decode($tipoResponse, true);
                    $articulo['tipo_nombre'] = $tipo['nombre'] ?? 'Sin tipo';
                }

                // Obtener nombre del proveedor
                if (isset($articulo['id_proveedor'])) {
                    $curlProveedor = curl_init();
                    curl_setopt_array($curlProveedor, array(
                        CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/articulos/{$articulo['id_proveedor']}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    $proveedorResponse = curl_exec($curlProveedor);
                    curl_close($curlProveedor);
                    $proveedor = json_decode($proveedorResponse, true);
                    $articulo['proveedor_nombre'] = $proveedor['nombre'] ?? 'Sin proveedor';
                }
            }
        }
        
        return view('articulo', compact('articulos'));
    }

    public function editar(Request $request, $id)
    {
        $data = [
            "idarticulo" => (int) $id,
            "articulonombre" => $request->input('articulonombre'),
            "articulostock" => (int)$request->input('articulostock', 0),
            "articulodescripcion" => $request->input('articulodescripcion'),
            "articuloimagen" => $request->input('articuloimagen'),
            "articulocodigogener" => $request->input('articulocodigogener'),
            "articulocodigo" => $request->input('articulocodigo'),
            "articulofecha" => $request->input('articulofecha'),
            "articulohora" => $request->input('articulohora'),
            "id_tipo" => $request->input('id_tipo') ? (int)$request->input('id_tipo') : null,
            "id_proveedor" => $request->input('id_proveedor') ? (int)$request->input('id_proveedor') : null
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/articulos/$id",
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
            return back()->with('error', 'Error al actualizar el artículo. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    }
}