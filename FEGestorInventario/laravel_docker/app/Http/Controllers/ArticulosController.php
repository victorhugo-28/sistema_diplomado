<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ArticulosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_tipo' => 'required|integer',
            'id_proveedor' => 'required|integer'
        ]);

        try {
            $imagePath = null;

            // Procesar imagen si se subió
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . date('YmdHis') . '.' . $imagen->getClientOriginalExtension();

                $uploadPath = public_path('uploads/articulos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $imagen->move($uploadPath, $nombreImagen);
                $imagePath = 'uploads/articulos/' . $nombreImagen;
            }

            // Generar códigos
            $idTipo = (int)$request->input('id_tipo');
            $codigoGeneral = $this->generarCodigoGeneral($idTipo);
            $codigoArticulo = $this->generarCodigoArticulo();

            // ⭐ CONFIGURAR FECHA Y HORA EN HORARIO BOLIVIANO
            $fechaHoraBolivia = Carbon::now('America/La_Paz');
            $fechaActual = $fechaHoraBolivia->format('Y-m-d');
            $horaActual = $fechaHoraBolivia->format('H:i:s');

            $multipartData = [
                ['name' => 'articulonombre', 'contents' => trim($request->input('nombre'))],
                ['name' => 'articulostock', 'contents' => (int)$request->input('stock')],
                ['name' => 'articulodescripcion', 'contents' => trim($request->input('descripcion', ''))],
                ['name' => 'articulocodigo', 'contents' => $codigoArticulo],
                ['name' => 'articulocodigogener', 'contents' => $codigoGeneral],
                ['name' => 'articulofecha', 'contents' => $fechaActual],
                ['name' => 'articulohora', 'contents' => $horaActual],
                ['name' => 'id_tipo', 'contents' => $idTipo],
                ['name' => 'id_proveedor', 'contents' => (int)$request->input('id_proveedor')],
            ];

            if ($imagePath) {
                $multipartData[] = [
                    'name' => 'articuloimagen',
                    'contents' => fopen(public_path($imagePath), 'r'),
                    'filename' => basename($imagePath),
                ];
            }

            // Enviar petición a la API
            $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'ngrok-skip-browser-warning' => 'true',
                ])
                ->asMultipart()
                ->post('https://3d2ab82f1074.ngrok-free.app/articulos', $multipartData);

            $status = $response->status();
            $body = $response->body();
            $json = $response->json();

            Log::info('Respuesta de la API al crear artículo:', [
                'status' => $status,
                'body' => $body,
                'data_sent' => $multipartData,
                'fecha_hora_bolivia' => $fechaActual . ' ' . $horaActual
            ]);

            if ($response->successful()) {
                return redirect()->route('articulos.mostrar')->with('success', 'Artículo creado correctamente.');
            } else {
                // Eliminar imagen si hubo error
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }

                return back()->withInput()->with('error', "❌ ERROR HTTP $status\n\n📥 RESPUESTA DE LA API:\n" . $body);
            }

        } catch (\Exception $e) {
            // Eliminar imagen si hubo error
            if (isset($imagePath) && $imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            Log::error('Error al crear artículo:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Error interno: ' . $e->getMessage());
        }
    }

    /**
     * Genera código general basado en el ID del tipo
     */
    private function generarCodigoGeneral($idTipo)
    {
        // Mapeo de IDs de tipos a códigos
        $mapeoTipos = [
            1 => 'ELEC',      // Electrónicos
            2 => 'ROPA',      // Ropa y textiles
            3 => 'HOGAR',     // Artículos para el hogar
            4 => 'FERR',      // Ferretería
            5 => 'ALIM',      // Alimentos
            6 => 'MED',       // Medicina
            7 => 'DEP',       // Deportes
            8 => 'JUG',       // Juguetes
            9 => 'LIB',       // Libros
            10 => 'AUTO'      // Automotriz
        ];

        // Si existe en el mapeo, usar ese código
        if (isset($mapeoTipos[$idTipo])) {
            return $mapeoTipos[$idTipo];
        }

        // Si no existe, generar código genérico
        return 'GEN' . str_pad($idTipo, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Genera código único para artículo
     */
    private function generarCodigoArticulo()
    {
        // ⭐ USAR HORARIO BOLIVIANO PARA GENERAR EL CÓDIGO
        $fechaHoraBolivia = Carbon::now('America/La_Paz');
        $timestamp = $fechaHoraBolivia->timestamp;
        $random = rand(100, 999);
        return 'ART-' . $fechaHoraBolivia->format('Ymd') . '-' . ($timestamp % 10000) . $random;
    }

    public function mostrar()
    {
        try {
            // Obtener artículos
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/articulos',
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
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            // Validar respuesta de artículos
            $articulos = [];
            if ($response !== false && !empty($response) && $httpCode == 200) {
                $decodedArticulos = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedArticulos)) {
                    $articulos = $decodedArticulos;
                }
            }

            // Obtener tipos de artículo
            $curlTipos = curl_init();
            curl_setopt_array($curlTipos, array(
                CURLOPT_URL => 'https://3d2ab82f1074.ngrok-free.app/tipos_articulo',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'ngrok-skip-browser-warning: true'
                ],
            ));
            $tiposResponse = curl_exec($curlTipos);
            $httpCodeTipos = curl_getinfo($curlTipos, CURLINFO_HTTP_CODE);
            curl_close($curlTipos);
            
            // Validar y decodificar respuesta de tipos
            $tipos = [];
            if ($tiposResponse !== false && !empty($tiposResponse) && $httpCodeTipos == 200) {
                $decodedTipos = json_decode($tiposResponse, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedTipos)) {
                    $tipos = $decodedTipos;
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
            
            // Crear arrays asociativos para búsqueda rápida
            $tiposMap = [];
            if ($tipos) {
                foreach ($tipos as $tipo) {
                    if (isset($tipo['id']) && isset($tipo['nombre'])) {
                        $tiposMap[$tipo['id']] = $tipo['nombre'];
                    }
                }
            }

            $proveedoresMap = [];
            if ($proveedores) {
                foreach ($proveedores as $proveedor) {
                    if (isset($proveedor['id']) && isset($proveedor['nombre'])) {
                        $proveedoresMap[$proveedor['id']] = $proveedor['nombre'];
                    }
                }
            }

            // Procesar artículos
            if ($articulos) {
                foreach ($articulos as &$articulo) {
                    $articulo['tipo_nombre'] = $tiposMap[$articulo['id_tipo']] ?? 'Sin tipo';
                    $articulo['proveedor_nombre'] = $proveedoresMap[$articulo['id_proveedor']] ?? 'Sin proveedor';
                    
                    // Corregir URL de imagen
                    if (!empty($articulo['imagen'])) {
                        if (!str_starts_with($articulo['imagen'], 'http')) {
                            $baseApiUrl = 'https://3d2ab82f1074.ngrok-free.app';
                            $articulo['imagen'] = $baseApiUrl . '/' . $articulo['imagen'];
                        }
                    }
                    
                    // Formatear fecha
                    if (isset($articulo['fecha']) && isset($articulo['hora'])) {
                        $articulo['fecha'] = $articulo['fecha'] . ' ' . $articulo['hora'];
                    } elseif (isset($articulo['fecha'])) {
                        $articulo['fecha'] = $articulo['fecha'];
                    } else {
                        $articulo['fecha'] = 'Sin fecha';
                    }
                }
            }
            
            return view('articulos.articulo', compact('articulos', 'tipos', 'proveedores'));

        } catch (\Exception $e) {
            Log::error('Error al obtener artículos:', ['exception' => $e->getMessage()]);
            return view('articulos.articulo', [
                'articulos' => [],
                'tipos' => [],
                'proveedores' => []
            ])->with('error', 'Error al cargar los datos: ' . $e->getMessage());
        }
    }
    
    public function editar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_tipo' => 'required|integer',
            'id_proveedor' => 'required|integer'
        ]);
        
        try {
            $imagePath = null;

            // Procesar imagen si se subió
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . date('YmdHis') . '.' . $imagen->getClientOriginalExtension();

                $uploadPath = public_path('uploads/articulos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $imagen->move($uploadPath, $nombreImagen);
                $imagePath = 'uploads/articulos/' . $nombreImagen;
            }

            $multipartData = [
                ['name' => 'idarticulo', 'contents' => $id],
                ['name' => 'articulonombre', 'contents' => trim($request->input('nombre'))],
                ['name' => 'articulostock', 'contents' => (int)$request->input('stock')],
                ['name' => 'articulodescripcion', 'contents' => trim($request->input('descripcion', ''))],
                ['name' => 'id_tipo', 'contents' => (int)$request->input('id_tipo')],
                ['name' => 'id_proveedor', 'contents' => (int)$request->input('id_proveedor')],
                ['name' => 'articulocodigo', 'contents' => $request->input('codigo')],
                ['name' => 'articulocodigogener', 'contents' => $request->input('codigo_gener')],
                ['name' => 'articulofecha', 'contents' => substr($request->input('fecha'), 0, 10)],
                ['name' => 'articulohora', 'contents' => substr($request->input('fecha'), 11)],
            ];

            if ($imagePath) {
                $multipartData[] = [
                    'name' => 'articuloimagen',
                    'contents' => fopen(public_path($imagePath), 'r'),
                    'filename' => basename($imagePath),
                ];
            }

            // Enviar petición a la API
            $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'ngrok-skip-browser-warning' => 'true',
                ])
                ->asMultipart()
                ->put('https://3d2ab82f1074.ngrok-free.app/articulos/' . $id, $multipartData);

            $status = $response->status();
            $body = $response->body();
            $json = $response->json();
            Log::info($multipartData);

            Log::info('Respuesta de la API al editar artículo:', [
                'id' => $id,
                'status' => $status,
                'body' => $body,
                'data_sent' => $multipartData
            ]);

            if ($response->successful()) {
                return redirect()->route('articulos.mostrar')->with('success', 'Artículo actualizado correctamente.');
            } else {
                // Eliminar imagen si hubo error
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }

                return back()->withInput()->with('error', "❌ ERROR HTTP $status\n\n📥 RESPUESTA DE LA API:\n" . $body);
            }

        } catch (\Exception $e) {
            // Eliminar imagen si hubo error
            if (isset($imagePath) && $imagePath && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }

            Log::error('Error al editar artículo:', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Error interno: ' . $e->getMessage());
        }
    }
}