<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class VentasController extends Controller
{
    

public function store(Request $request)
{
    // Validar campos obligatorios
    $request->validate([
        'ventatipo_comprobante' => 'required|integer',
        'ventatotal_venta' => 'required|numeric|min:0',
        'idcliente' => 'required|integer',
        'detalles' => 'required|array|min:1',
        'ventapago_cliente' => 'required|numeric|min:0',
        'detalle_ventadescuento' => 'nullable|numeric|min:0|max:100', // Validar descuento porcentual
    ]);

    // Obtener el porcentaje de descuento desde el formulario
    $descuentoPorcentaje = (float)$request->input('detalle_ventadescuento', 0);
    
    // Calcular subtotal de todos los productos
    $subtotal = 0;
    $detallesOriginales = $request->input('detalles', []);
    
    foreach ($detallesOriginales as $detalle) {
        $cantidad = (float)($detalle['detalle_ventacantidad'] ?? 0);
        $precio = (float)($detalle['detalle_ventaprecio_venta'] ?? 0);
        $subtotal += $cantidad * $precio;
    }
    
    // Calcular descuento total en monto
    $montoDescuentoTotal = $subtotal * ($descuentoPorcentaje / 100);
    
    // Procesar detalles agregando el descuento proporcional a cada producto
    $detallesProcesados = [];
    
    foreach ($detallesOriginales as $detalle) {
        $cantidad = (float)($detalle['detalle_ventacantidad'] ?? 0);
        $precio = (float)($detalle['detalle_ventaprecio_venta'] ?? 0);
        $subtotalProducto = $cantidad * $precio;
        
        // Calcular descuento proporcional para este producto
        if ($subtotal > 0) {
            $proporcionProducto = $subtotalProducto / $subtotal;
            $descuentoProducto = $montoDescuentoTotal * $proporcionProducto;
        } else {
            $descuentoProducto = 0;
        }
        
        // Agregar el detalle con su descuento calculado
        $detallesProcesados[] = [
            'idarticulo' => (int)$detalle['idarticulo'],
            'detalle_ventacantidad' => $cantidad,
            'detalle_ventaprecio_venta' => $precio,
            'detalle_ventadescuento' => round($descuentoProducto, 2), // Descuento proporcional
        ];
    }

    // Obtener serie y número para comprobante
    try {
        $numeroData = $this->obtenerProximoNumero();
    } catch (\Exception $e) {
        $numeroData = ['serie' => '000001', 'numero' => '000001'];
    }
    
    $totalVenta = (float)$request->input('ventatotal_venta');
    $pagoCliente = (float)$request->input('ventapago_cliente');
    $cambio = max(0, $pagoCliente - $totalVenta);

    // Preparar datos para enviar a la API
    $data = [
        'ventatipo_comprobante' => (int)$request->input('ventatipo_comprobante'),
        'ventaserie_comprobante' => $numeroData['serie'],   // String con formato 000001
        'ventanum_comprobante' => $numeroData['numero'],    // String con formato 000001
        'ventafecha_hora' => $this->obtenerFechaHoraActual(),
        'ventaimpuesto' => 13.0,
        'ventatotal_venta' => $totalVenta,
        'idcliente' => (int)$request->input('idcliente'),
        'ventacondicion' => 1,
        'ventapago_cliente' => $pagoCliente,
        'ventacambio' => $cambio,
        'detalles' => $detallesProcesados, // ← Usar detalles con descuento calculado
    ];

    // Log para debug (opcional - remueve en producción)
    Log::info('Venta con descuento:', [
        'descuento_porcentaje' => $descuentoPorcentaje,
        'subtotal_original' => $subtotal,
        'descuento_total' => $montoDescuentoTotal,
        'detalles_procesados' => $detallesProcesados
    ]);

    // Llamada a la API
    try {
        $ch = curl_init('https://3d2ab82f1074.ngrok-free.app/ventas');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'ngrok-skip-browser-warning: true'
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);
        
        if ($curlError) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error de conexión: ' . $curlError);
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            $mensaje = 'Venta creada correctamente.';
            if ($descuentoPorcentaje > 0) {
                $mensaje .= ' Descuento aplicado: ' . $descuentoPorcentaje . '% ($' . number_format($montoDescuentoTotal, 2) . ')';
            }
            return redirect()->back()->with('success', $mensaje);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear venta. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error interno: ' . $e->getMessage());
    }
}
    public function mostrar()
    {
        try {
            // Obtener ventas
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/ventas',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'ngrok-skip-browser-warning: true'
                ],
            ));

            $response = curl_exec($curl);
            $httpCodeVentas = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            // Validar respuesta de ventas
            $ventas = [];
            if ($response !== false && !empty($response) && $httpCodeVentas == 200) {
                $decodedVentas = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedVentas)) {
                    $ventas = $decodedVentas;
                }
            }

            // Obtener clientes
            $curlClientes = curl_init();
            curl_setopt_array($curlClientes, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/clientes',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'ngrok-skip-browser-warning: true'
                ],
            ));
            
            $clientesResponse = curl_exec($curlClientes);
            $httpCodeClientes = curl_getinfo($curlClientes, CURLINFO_HTTP_CODE);
            curl_close($curlClientes);
            
            // Validar y decodificar respuesta de clientes
            $clientes = [];
            if ($clientesResponse !== false && !empty($clientesResponse) && $httpCodeClientes == 200) {
                $decodedClientes = json_decode($clientesResponse, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedClientes)) {
                    $clientes = $decodedClientes;
                }
            }

            // Obtener artículos para los detalles
            $curlArticulos = curl_init();
            curl_setopt_array($curlArticulos, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/articulos',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'ngrok-skip-browser-warning: true'
                ],
            ));
            
            $articulosResponse = curl_exec($curlArticulos);
            $httpCodeArticulos = curl_getinfo($curlArticulos, CURLINFO_HTTP_CODE);
            curl_close($curlArticulos);
            
            // Validar y decodificar respuesta de artículos
            $articulos = [];
            if ($articulosResponse !== false && !empty($articulosResponse) && $httpCodeArticulos == 200) {
                $decodedArticulos = json_decode($articulosResponse, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedArticulos)) {
                    $articulos = $decodedArticulos;
                }
            }

            // Crear array asociativo para búsqueda rápida de clientes
            $clientesMap = [];
            if (is_array($clientes) && count($clientes) > 0) {
                foreach ($clientes as $cliente) {
                    if (is_array($cliente) && isset($cliente['id']) && isset($cliente['nombre'])) {
                        $clientesMap[$cliente['id']] = [
                            'nombre' => $cliente['nombre'],
                            'email' => $cliente['email'] ?? '',
                            'telefono' => $cliente['telefono'] ?? ''
                        ];
                    }
                }
            }

            // Crear array asociativo para búsqueda rápida de artículos
            $articulosMap = [];
            if (is_array($articulos) && count($articulos) > 0) {
                foreach ($articulos as $articulo) {
                    if (is_array($articulo) && isset($articulo['id']) && isset($articulo['nombre'])) {
                        $articulosMap[$articulo['id']] = [
                            'nombre' => $articulo['nombre'],
                            'codigo' => $articulo['codigo'] ?? 'N/A',
                            'descripcion' => $articulo['descripcion'] ?? ''
                        ];
                    }
                }
            }

            // Agregar nombres de clientes y artículos a las ventas
            if (is_array($ventas) && count($ventas) > 0) {
                foreach ($ventas as &$venta) {
                    if (is_array($venta)) {
                        // Agregar información del cliente
                        $venta['cliente_info'] = [
                            'nombre' => 'Sin cliente',
                            'email' => '',
                            'telefono' => ''
                        ];
                        $clienteId = $venta['idcliente'] ?? $venta['cliente'] ?? null;
                        if ($clienteId && isset($clientesMap[$clienteId])) {
                            $venta['cliente_info'] = $clientesMap[$clienteId];
                        }
                        
                        // Formatear tipo de comprobante
                        $tipoComprobante = $venta['ventatipo_comprobante'] ?? $venta['tipo_comprobante'] ?? null;
                        $venta['tipo_comprobante_texto'] = $this->formatearTipoComprobante($tipoComprobante);
                        
                        // Procesar detalles si existen
                        if (isset($venta['detalles']) && is_array($venta['detalles'])) {
                            foreach ($venta['detalles'] as &$detalle) {
                                $articuloId = $detalle['idarticulo'] ?? null;
                                if ($articuloId && isset($articulosMap[$articuloId])) {
                                    $detalle['articulo_info'] = $articulosMap[$articuloId];
                                } else {
                                    $detalle['articulo_info'] = [
                                        'nombre' => 'Artículo no encontrado',
                                        'codigo' => 'N/A',
                                        'descripcion' => ''
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            
            // Pasar todas las variables necesarias a la vista
            return view('ventas.ventas', compact('ventas', 'clientes', 'articulos'));

        } catch (\Exception $e) {
            Log::error('Error al obtener datos:', ['exception' => $e->getMessage()]);
            return view('ventas.ventas', [
                'ventas' => [],
                'clientes' => [],
                'articulos' => []
            ])->with('error', 'Error al cargar los datos: ' . $e->getMessage());
        }
    }

   
    /**
     * Obtener el próximo número de serie y comprobante
     */
    private function obtenerProximoNumero()
{
    try {
        // Obtener la última venta para determinar el próximo número
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/ventas',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'ngrok-skip-browser-warning: true'
            ],
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        $ultimoNumero = 0;

        if ($curlError) {
            Log::error('Error cURL al obtener ventas: ' . $curlError);
        } elseif ($httpCode != 200) {
            Log::error('Error HTTP al obtener ventas. Código: ' . $httpCode);
        } elseif ($response !== false && !empty($response)) {
            $ventas = json_decode($response, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($ventas)) {
                // Buscar el número más alto existente
                foreach ($ventas as $venta) {
                    // Solo revisar el campo ventanum_comprobante que es el número secuencial
                    if (isset($venta['ventanum_comprobante'])) {
                        $numeroComprobante = intval($venta['ventanum_comprobante']);
                        $ultimoNumero = max($ultimoNumero, $numeroComprobante);
                    }
                }
                Log::info('Último número encontrado: ' . $ultimoNumero);
            } else {
                Log::error('Error al decodificar JSON de ventas');
            }
        }

        // Incrementar para el próximo
        $proximoNumero = $ultimoNumero + 1;
        
        Log::info('Próximo número generado: ' . $proximoNumero);
        
        return [
            'serie' => str_pad(1, 6, '0', STR_PAD_LEFT),              // Serie con formato 000001
            'numero' => str_pad($proximoNumero, 6, '0', STR_PAD_LEFT) // Número con formato 000001
        ];

    } catch (\Exception $e) {
        Log::error('Error al obtener próximo número: ' . $e->getMessage());
        
        // En caso de error, devolver valores por defecto
        return [
            'serie' => '000001',
            'numero' => '000001'
        ];
    }
}

    /**
     * Obtener fecha y hora actual del sistema
     */
    private function obtenerFechaHoraActual()
    {
        $dt = new \DateTime('now', new \DateTimeZone('America/La_Paz'));
        return $dt->format('Y-m-d\TH:i:s');
    }

    /**
     * Formatear tipo de comprobante para la API (devuelve texto)
     */
    private function formatearTipoComprobanteParaAPI($tipoComprobante)
    {
        switch ((int)$tipoComprobante) {
            case 1:
                return 'Boleta';
            case 2:
                return 'Factura';
            case 3:
                return 'Nota de Crédito';
            case 4:
                return 'Recibo';
            case 5:
                return 'Guía de Remisión';
            default:
                return 'Factura'; // Por defecto
        }
    }

    /**
     * Formatear tipo de comprobante para mostrar (devuelve texto)
     */
    private function formatearTipoComprobante($tipoComprobante)
    {
        // Si ya es texto, devolverlo tal como está
        if (is_string($tipoComprobante) && !is_numeric($tipoComprobante)) {
            return ucfirst(strtolower($tipoComprobante));
        }
        
        // Si es número, convertirlo a texto
        $tipoNumerico = is_numeric($tipoComprobante) ? (int)$tipoComprobante : null;
        
        switch ($tipoNumerico) {
            case 1:
                return 'Boleta';
            case 2:
                return 'Factura';
            case 3:
                return 'Nota de Crédito';
            case 4:
                return 'Recibo';
            case 5:
                return 'Guía de Remisión';
            default:
                return $tipoComprobante ?: 'No especificado';
        }
    }

    /**
     * Procesar detalles de productos (formato API)
     */
    private function procesarDetalles($detalles)
    {
        $detallesProcesados = [];
        if (is_array($detalles)) {
            foreach ($detalles as $detalle) {
                if (!empty($detalle['idarticulo'])) {
                    $detallesProcesados[] = [
                        'idarticulo' => (int)$detalle['idarticulo'],
                        'detalle_ventacantidad' => (float)$detalle['cantidad'],
                        'detalle_ventaprecio_venta' => number_format((float)$detalle['precio_venta'], 2, '.', ''),
                        'detalle_ventadescuento' => number_format((float)($detalle['descuento'] ?? 0), 2, '.', '')
                    ];
                }
            }
        }
        return $detallesProcesados;
    }
}