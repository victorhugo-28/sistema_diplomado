<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    
    public function store(Request $request)
    {
        // Datos para enviar a la API
        $data = [
            "ventatipo_comprobante" => (int)$request->input('ventatipo_comprobante'),
            "ventaserie_comprobante" => (int)$request->input('ventaserie_comprobante', 0),
            "ventanum_comprobante" => (int)$request->input('ventanum_comprobante', 0),
            "ventafecha_hora" => $request->input('ventafecha_hora', date('Y-m-d')),
            "ventaimpuesto" => (int)$request->input('ventaimpuesto', 0),
            "ventatotal_venta" => $request->input('ventatotal_venta', '0.0'),
            "idcliente" => (int)$request->input('idcliente'),
            "ventacondicion" => (int)$request->input('ventacondicion', 1),
            "ventapago_cliente" => $request->input('ventapago_cliente') ? (float)$request->input('ventapago_cliente') : null,
            "ventacambio" => $request->input('ventacambio') ? (float)$request->input('ventacambio') : null
        ];

        $ch = curl_init('https://36482cdcb6d0.ngrok-free.app/ventas');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with('mensaje', 'Venta enviada correctamente.');
    }

    public function mostrar()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://36482cdcb6d0.ngrok-free.app/ventas',
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

        $ventas = json_decode($response, true);
        
        // Obtener datos adicionales de clientes
        if ($ventas) {
            foreach ($ventas as &$venta) {
                // Obtener nombre del cliente
                if (isset($venta['idcliente'])) {
                    $curlCliente = curl_init();
                    curl_setopt_array($curlCliente, array(
                        CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/ventas/{$venta['idcliente']}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    $clienteResponse = curl_exec($curlCliente);
                    curl_close($curlCliente);
                    $cliente = json_decode($clienteResponse, true);
                    $venta['cliente_nombre'] = $cliente['nombre'] ?? 'Sin cliente';
                }
            }
        }
        
        return view('ventas', compact('ventas'));
    }

    public function editar(Request $request, $id)
    {
        $data = [
            "idventa" => (int) $id,
            "ventatipo_comprobante" => (int)$request->input('ventatipo_comprobante'),
            "ventaserie_comprobante" => (int)$request->input('ventaserie_comprobante', 0),
            "ventanum_comprobante" => (int)$request->input('ventanum_comprobante', 0),
            "ventafecha_hora" => $request->input('ventafecha_hora'),
            "ventaimpuesto" => (int)$request->input('ventaimpuesto', 0),
            "ventatotal_venta" => $request->input('ventatotal_venta'),
            "idcliente" => (int)$request->input('idcliente'),
            "ventacondicion" => (int)$request->input('ventacondicion', 1),
            "ventapago_cliente" => $request->input('ventapago_cliente') ? (float)$request->input('ventapago_cliente') : null,
            "ventacambio" => $request->input('ventacambio') ? (float)$request->input('ventacambio') : null
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/ventas/$id",
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
            return back()->with('error', 'Error al actualizar la venta. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    }
}