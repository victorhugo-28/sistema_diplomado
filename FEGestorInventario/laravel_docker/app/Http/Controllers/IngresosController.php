<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresosController extends Controller
{
    
    public function store(Request $request)
    {
        // Datos para enviar a la API
        $data = [
            "ingresotipo_comprobante" => (int)$request->input('ingresotipo_comprobante'),
            "ingresoserie_comprobante" => (int)$request->input('ingresoserie_comprobante', 0),
            "ingresonumero_comprobante" => (int)$request->input('ingresonumero_comprobante', 0),
            "ingresofecha_hora" => $request->input('ingresofecha_hora', date('Y-m-d')),
            "ingresoimpuesto" => (int)$request->input('ingresoimpuesto', 0),
            "ingresototal_compra" => $request->input('ingresototal_compra', '0.0'),
            "idproveedor" => (int)$request->input('idproveedor'),
            "ingresocondicion" => (int)$request->input('ingresocondicion', 1)
        ];

        $ch = curl_init('https://36482cdcb6d0.ngrok-free.app/compras');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->back()->with('mensaje', 'Ingreso enviado correctamente.');
    }

    public function mostrar()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://36482cdcb6d0.ngrok-free.app/compras',
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

        $ingresos = json_decode($response, true);
        
        // Obtener datos adicionales de proveedores
        if ($ingresos) {
            foreach ($ingresos as &$ingreso) {
                // Obtener nombre del proveedor
                if (isset($ingreso['idproveedor'])) {
                    $curlProveedor = curl_init();
                    curl_setopt_array($curlProveedor, array(
                        CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/compras/{$ingreso['idproveedor']}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    $proveedorResponse = curl_exec($curlProveedor);
                    curl_close($curlProveedor);
                    $proveedor = json_decode($proveedorResponse, true);
                    $ingreso['proveedor_nombre'] = $proveedor['nombre'] ?? 'Sin proveedor';
                }
            }
        }
        
        return view('ingreso', compact('ingresos'));
    }

    public function editar(Request $request, $id)
    {
        $data = [
            "idingreso" => (int) $id,
            "ingresotipo_comprobante" => (int)$request->input('ingresotipo_comprobante'),
            "ingresoserie_comprobante" => (int)$request->input('ingresoserie_comprobante', 0),
            "ingresonumero_comprobante" => (int)$request->input('ingresonumero_comprobante', 0),
            "ingresofecha_hora" => $request->input('ingresofecha_hora'),
            "ingresoimpuesto" => (int)$request->input('ingresoimpuesto', 0),
            "ingresototal_compra" => $request->input('ingresototal_compra'),
            "idproveedor" => (int)$request->input('idproveedor'),
            "ingresocondicion" => (int)$request->input('ingresocondicion', 1)
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://36482cdcb6d0.ngrok-free.app/compras/$id",
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
            return back()->with('error', 'Error al actualizar el ingreso. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    }
}