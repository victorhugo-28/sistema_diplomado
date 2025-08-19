# salon_api/endpoints/venta/create.py (ajusta la ruta real)
from fastapi import APIRouter, HTTPException
from core.dto.venta_dto import CrearVentaDTO
from core.handlers.venta.crear_venta_handler import CrearVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl
from core.services.stock_service import StockService

router = APIRouter()

@router.post("/ventas")
def crear_venta(data: CrearVentaDTO):
    try:
        print(f"Creando venta con datos: {data.dict()}")
        
        # Verificar stock ANTES de crear la venta
        stock_service = StockService()
        articulos_cantidad = [(detalle.idarticulo, detalle.detalle_ventacantidad) for detalle in data.detalles]
        
        es_valido, mensaje = stock_service.verificar_disponibilidad_multiple(articulos_cantidad)
        if not es_valido:
            raise HTTPException(status_code=400, detail=f"Stock insuficiente: {mensaje}")
        
        # Preparar datos para la venta principal
        venta_data = {
            "ventatipo_comprobante": data.ventatipo_comprobante,
            "ventaserie_comprobante": data.ventaserie_comprobante,
            "ventanum_comprobante": data.ventanum_comprobante,
            "ventafecha_hora": data.ventafecha_hora.strftime('%Y-%m-%d %H:%M:%S'),  # Convertir a string para SQLite
            "ventaimpuesto": data.ventaimpuesto,
            "ventatotal_venta": data.ventatotal_venta,
            "idcliente": data.idcliente,
            "ventacondicion": data.ventacondicion,
            "ventapago_cliente": data.ventapago_cliente,
            "ventacambio": data.ventacambio
        }
        
        # Crear venta usando SQL puro
        repo = VentaRepositoryImpl()
        venta_creada = repo.crear_venta_con_detalles(venta_data, [detalle.dict() for detalle in data.detalles])
        
        if not venta_creada:
            raise HTTPException(status_code=400, detail="Error al crear la venta")
        
        # Descontar stock DESPUÉS de crear la venta exitosamente
        stock_descontado = stock_service.descontar_stock_multiple(articulos_cantidad)
        if not stock_descontado:
            # Si falla el descuento de stock, eliminar la venta creada
            repo.eliminar(venta_creada.get('idventa'))
            raise HTTPException(status_code=500, detail="Error al descontar stock. Venta cancelada.")
        
        return {
            "message": "Venta creada exitosamente",
            "venta_id": venta_creada.get('idventa'),
            "total": venta_creada.get('ventatotal_venta'),
            "cliente_id": venta_creada.get('idcliente'),
            "detalles_count": len(data.detalles)
        }
        
    except HTTPException:
        raise  # Re-lanzar HTTPException sin modificar
    except Exception as e:
        print(f"Error al crear venta: {e}")
        raise HTTPException(status_code=500, detail=f"Error interno: {str(e)}")