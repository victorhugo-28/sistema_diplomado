<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IngresosController extends Controller
{
    public function store(Request $request)
    {
        
        Log::info('Datos recibidos en store:', $request->all());
        // Validación de datos simplificada (removidos campos auto-generados)
        $request->validate([
            'ingresotipo_comprobante' => 'required|integer',
            'idproveedor' => 'required|integer',
            'ingresototal_compra' => 'required|numeric|min:0'
        ]);

        // Generar serie y número automáticamente
        $numeroData = $this->obtenerProximoNumero();
        
        // Datos para enviar a la API
        $data = [
            "ingresotipo_comprobante" => $this->formatearTipoComprobanteParaAPI((int)$request->input('ingresotipo_comprobante')),
            "ingresoserie_comprobante" => $numeroData['serie'], // COM-000001
            "ingresonumero_comprobante" => $numeroData['numero'], // 000001
            "ingresofecha_hora" => $this->obtenerFechaHoraActual(), // Fecha/hora actual
            "ingresoimpuesto" => 13.0, // Fijo al 13% como float
            "ingresototal_compra" => (float)$request->input('ingresototal_compra', 0),
            "idproveedor" => (int)$request->input('idproveedor'),
            "ingresocondicion" => "1", // Siempre activo como string
            "detalles" => $this->procesarDetalles($request->input('detalles', []))
        ];

        // Log para debugging
        Log::info('Datos a enviar a la API:', $data);

        try {
            $ch = curl_init('https://3d2ab82f1074.ngrok-free.app/compras');
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

            // Log de la respuesta
            Log::info('Respuesta de la API:', [
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $curlError
            ]);
            

            // Verificar si hay errores de cURL
            if ($curlError) {
                Log::error('Error de cURL:', ['error' => $curlError]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error de conexión: ' . $curlError);
            }

            // Verificar código de respuesta HTTP
            if ($httpCode >= 200 && $httpCode < 300) {
                return redirect()->back()->with('success', 'Ingreso creado correctamente.');
            } else {
                Log::error('Error HTTP:', [
                    'code' => $httpCode,
                    'response' => $response
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error al crear ingreso. Código: ' . $httpCode . ' | Respuesta: ' . $response);
            }

        } catch (\Exception $e) {
            Log::error('Excepción al crear ingreso:', ['exception' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error interno: ' . $e->getMessage());
        }
    }

    public function mostrar()
{
    try {
        // Obtener ingresos
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/compras',
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
        $httpCodeIngresos = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        // Validar respuesta de ingresos
        $ingresos = [];
        if ($response !== false && !empty($response) && $httpCodeIngresos == 200) {
            $decodedIngresos = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedIngresos)) {
                $ingresos = $decodedIngresos;
                
                // ⭐ NORMALIZAR CAMPOS PARA COMPATIBILIDAD CON LA VISTA
                foreach ($ingresos as &$ingreso) {
                    // Asegurar que tenga un campo 'id' consistente
                    if (!isset($ingreso['id'])) {
                        $ingreso['id'] = $ingreso['idingreso'] ?? $ingreso['idcompra'] ?? null;
                    }
                }
                unset($ingreso); // Limpiar referencia
                
                // ⭐ ORDENAR DE MÁS RECIENTE A MÁS ANTIGUO (COMO VENTAS)
                usort($ingresos, function($a, $b) {
                    // Intentar ordenar por fecha
                    $fechaA = $a['ingresofecha_hora'] ?? $a['fecha_hora'] ?? null;
                    $fechaB = $b['ingresofecha_hora'] ?? $b['fecha_hora'] ?? null;
                    
                    if ($fechaA && $fechaB) {
                        return strtotime($fechaB) - strtotime($fechaA); // Más reciente primero
                    }
                    
                    // Si no hay fechas, ordenar por ID descendente
                    $idA = $a['id'] ?? 0;
                    $idB = $b['id'] ?? 0;
                    
                    return $idB - $idA; // Mayor ID primero
                });
            }
        }

        // Obtener proveedores
        $curlProveedores = curl_init();
        curl_setopt_array($curlProveedores, array(
            CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/proveedores',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'ngrok-skip-browser-warning: true'
            ],
        ));
        
        $proveedoresResponse = curl_exec($curlProveedores);
        $httpCodeProveedores = curl_getinfo($curlProveedores, CURLINFO_HTTP_CODE);
        curl_close($curlProveedores);
        
        // Validar y decodificar respuesta de proveedores
        $proveedores = [];
        if ($proveedoresResponse !== false && !empty($proveedoresResponse) && $httpCodeProveedores == 200) {
            $decodedProveedores = json_decode($proveedoresResponse, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedProveedores)) {
                $proveedores = $decodedProveedores;
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

        // Crear array asociativo para búsqueda rápida de proveedores
        $proveedoresMap = [];
        if (is_array($proveedores) && count($proveedores) > 0) {
            foreach ($proveedores as $proveedor) {
                if (is_array($proveedor) && isset($proveedor['id']) && isset($proveedor['nombre'])) {
                    $proveedoresMap[$proveedor['id']] = $proveedor['nombre'];
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

        // Agregar nombres de proveedores y artículos a los ingresos
        if (is_array($ingresos) && count($ingresos) > 0) {
            foreach ($ingresos as &$ingreso) {
                if (is_array($ingreso)) {
                    // Agregar nombre del proveedor
                    $ingreso['proveedor_nombre'] = 'Sin proveedor';
                    $proveedorId = $ingreso['idproveedor'] ?? $ingreso['proveedor'] ?? null;
                    if ($proveedorId && isset($proveedoresMap[$proveedorId])) {
                        $ingreso['proveedor_nombre'] = $proveedoresMap[$proveedorId];
                    }
                    
                    // Formatear tipo de comprobante
                    $tipoComprobante = $ingreso['ingresotipo_comprobante'] ?? $ingreso['tipo_comprobante'] ?? null;
                    $ingreso['tipo_comprobante_texto'] = $this->formatearTipoComprobante($tipoComprobante);
                    
                    // Procesar detalles si existen
                    if (isset($ingreso['detalles']) && is_array($ingreso['detalles'])) {
                        foreach ($ingreso['detalles'] as &$detalle) {
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
        return view('ingresos.ingreso', compact('ingresos', 'proveedores', 'articulos'));

    } catch (\Exception $e) {
        Log::error('Error al obtener datos:', ['exception' => $e->getMessage()]);
        return view('ingresos.ingreso', [
            'ingresos' => [],
            'proveedores' => [],
            'articulos' => []
        ])->with('error', 'Error al cargar los datos: ' . $e->getMessage());
    }
}

    public function editar(Request $request, $id)
{
    // Validación de campos que sí se pueden editar
    $request->validate([
        'ingresotipo_comprobante' => 'required|string',
        'ingresoserie_comprobante' => 'required|string',
        'ingresonumero_comprobante' => 'required|string',
        'ingresofecha_hora' => 'required|date',
        'ingresoimpuesto' => 'required|numeric|min:0',
        'ingresototal_compra' => 'required|numeric|min:0',
        'idproveedor' => 'required|integer',
        'ingresocondicion' => 'required|string'
    ]);

    $data = [
        "ingresotipo_comprobante" => $request->input('ingresotipo_comprobante'),
        "ingresoserie_comprobante" => $request->input('ingresoserie_comprobante'),
        "ingresonumero_comprobante" => $request->input('ingresonumero_comprobante'),
        "ingresofecha_hora" => $request->input('ingresofecha_hora'),
        "ingresoimpuesto" => (float) $request->input('ingresoimpuesto', 13.0),
        "ingresototal_compra" => (float) $request->input('ingresototal_compra'),
        "idproveedor" => (int) $request->input('idproveedor'),
        "ingresocondicion" => $request->input('ingresocondicion', '1')
    ];

    try {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://3d2ab82f1074.ngrok-free.app/compras/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'ngrok-skip-browser-warning: true'
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        Log::info('📦 Ingreso actualizado:', [
            'id' => $id,
            'data' => $data,
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError
        ]);

        if ($curlError) {
            return back()->withInput()->with('error', 'Error de conexión: ' . $curlError);
        }

        if ($httpCode === 200 || $httpCode === 204) {
            return view('complementos.actualizado');
        } else {
            return back()->withInput()->with('error', 'Error al actualizar el ingreso. Código: ' . $httpCode . ' | Respuesta: ' . $response);
        }

    } catch (\Exception $e) {
        Log::error('❌ Excepción al actualizar ingreso:', ['exception' => $e->getMessage()]);
        return back()->withInput()->with('error', 'Error interno: ' . $e->getMessage());
    }
}

    /**
     * Obtener el próximo número de serie y comprobante
     */
    private function obtenerProximoNumero()
    {
        try {
            // Obtener el último ingreso para determinar el próximo número
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/compras',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'ngrok-skip-browser-warning: true'
                ],
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $ultimoNumero = 0;

            if ($response !== false && !empty($response) && $httpCode == 200) {
                $ingresos = json_decode($response, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($ingresos)) {
                    // Buscar el número más alto existente
                    foreach ($ingresos as $ingreso) {
                        if (isset($ingreso['ingresonumero_comprobante'])) {
                            // Extraer el número de la serie (ej: COM-000005 -> 5)
                            $serie = $ingreso['ingresoserie_comprobante'] ?? '';
                            if (strpos($serie, 'COM-') === 0) {
                                $numero = intval(substr($serie, 4)); // Extraer número después de COM-
                                $ultimoNumero = max($ultimoNumero, $numero);
                            }
                            
                            // También revisar el número de comprobante por si acaso
                            $numeroComprobante = intval($ingreso['ingresonumero_comprobante']);
                            $ultimoNumero = max($ultimoNumero, $numeroComprobante);
                        }
                    }
                }
            }

            // Incrementar para el próximo
            $proximoNumero = $ultimoNumero + 1;
            
            return [
                'serie' => 'COM-' . str_pad($proximoNumero, 6, '0', STR_PAD_LEFT),
                'numero' => str_pad($proximoNumero, 6, '0', STR_PAD_LEFT)
            ];

        } catch (\Exception $e) {
            Log::error('Error al obtener próximo número:', ['exception' => $e->getMessage()]);
            
            // En caso de error, devolver valores por defecto
            return [
                'serie' => 'COM-000001',
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
                        'detalle_ingresocantidad' => (float)$detalle['cantidad'],
                        'detalle_ingresoprecio_compra' => number_format((float)$detalle['precio'], 2, '.', ''),
                        'detalle_ingresoprecio_venta' => number_format((float)($detalle['precio_venta'] ?? $detalle['precio']), 2, '.', '')
                    ];
                }
            }
        }
        return $detallesProcesados;
    }
}