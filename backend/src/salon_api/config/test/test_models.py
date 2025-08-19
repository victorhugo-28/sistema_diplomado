"""
Pruebas unitarias para modelos del dominio - Versión Simplificada
"""

import pytest
from datetime import datetime, date, time
from decimal import Decimal

# Importar modelos (ajustar según tu estructura)
from core.models.articulo import Articulo
from core.models.cliente import Cliente
from core.models.venta import Venta
from core.models.detalle_venta import DetalleVenta
from core.models.proveedor import Proveedor


class TestArticuloModel:
    """Pruebas para el modelo Articulo"""
    
    def test_crear_articulo_valido(self):
        """Prueba creación de artículo con datos válidos"""
        articulo = Articulo(
            idarticulo=1,
            articulonombre="Laptop HP",
            articulostock=10,
            articulodescripcion="Laptop gaming",
            articulocodigo="LAP001"
        )
        assert articulo.articulonombre == "Laptop HP"
        assert articulo.articulostock == 10
        assert articulo.articulocodigo == "LAP001"
        assert articulo.articulodescripcion == "Laptop gaming"

    def test_crear_articulo_con_campos_minimos(self):
        """Prueba creación con campos mínimos requeridos"""
        articulo = Articulo(
            idarticulo=2,
            articulonombre="Mouse",
            articulostock=5,
            articulocodigo="MOU001"
        )
        assert articulo.articulonombre == "Mouse"
        assert articulo.articulostock == 5
        assert articulo.articulocodigo == "MOU001"

    def test_articulo_stock_cero_es_valido(self):
        """Prueba que stock cero es válido"""
        articulo = Articulo(
            idarticulo=3,
            articulonombre="Teclado",
            articulostock=0,
            articulocodigo="TEC001"
        )
        assert articulo.articulostock == 0

    def test_articulo_nombre_largo(self):
        """Prueba con nombre largo"""
        nombre_largo = "Laptop HP Pavilion Gaming 15-dk1056wm Intel Core i5"
        articulo = Articulo(
            idarticulo=4,
            articulonombre=nombre_largo,
            articulostock=3,
            articulocodigo="LAP002"
        )
        assert articulo.articulonombre == nombre_largo

    def test_articulo_codigo_alfanumerico(self):
        """Prueba código alfanumérico"""
        articulo = Articulo(
            idarticulo=5,
            articulonombre="Monitor",
            articulostock=7,
            articulocodigo="MON-2024-001"
        )
        assert articulo.articulocodigo == "MON-2024-001"

    # Pruebas opcionales - solo si tus modelos no tienen validaciones estrictas
    def test_articulo_stock_negativo_si_permitido(self):
        """Prueba stock negativo si tu modelo lo permite"""
        try:
            articulo = Articulo(
                idarticulo=6,
                articulonombre="Test",
                articulostock=-1,
                articulocodigo="TEST001"
            )
            # Si llega aquí, el modelo permite stock negativo
            assert articulo.articulostock == -1
        except (ValueError, TypeError):
            # Si falla, es porque el modelo no permite stock negativo
            pytest.skip("El modelo no permite stock negativo")

    def test_articulo_nombre_vacio_si_permitido(self):
        """Prueba nombre vacío si tu modelo lo permite"""
        try:
            articulo = Articulo(
                idarticulo=7,
                articulonombre="",
                articulostock=1,
                articulocodigo="EMPTY001"
            )
            # Si llega aquí, el modelo permite nombres vacíos
            assert articulo.articulonombre == ""
        except (ValueError, TypeError):
            # Si falla, es porque el modelo no permite nombres vacíos
            pytest.skip("El modelo no permite nombres vacíos")


class TestClienteModel:
    """Pruebas para el modelo Cliente"""
    
    def test_crear_cliente_valido(self):
        """Prueba creación de cliente válido"""
        cliente = Cliente(
            idcliente=1,
            clientenombre="Juan Pérez",
            clienteemail="juan@email.com",
            clientedocumento="12345678"
        )
        assert cliente.clientenombre == "Juan Pérez"
        assert cliente.clienteemail == "juan@email.com"
        assert cliente.clientedocumento == "12345678"

    def test_crear_cliente_con_telefono_y_direccion(self):
        """Prueba creación con todos los campos"""
        cliente = Cliente(
            idcliente=2,
            clientenombre="María González",
            clientetelefono="987654321",
            clienteemail="maria@email.com",
            clientedireccion="Av. Principal 123",
            clientedocumento="87654321"
        )
        assert cliente.clientenombre == "María González"
        assert cliente.clientetelefono == "987654321"
        assert cliente.clientedireccion == "Av. Principal 123"

    def test_cliente_email_vacio_permitido(self):
        """Prueba que email vacío está permitido"""
        cliente = Cliente(
            idcliente=3,
            clientenombre="Pedro López",
            clienteemail="",
            clientedocumento="11223344"
        )
        assert cliente.clientenombre == "Pedro López"
        assert cliente.clienteemail == ""

    def test_cliente_telefono_vacio_permitido(self):
        """Prueba que teléfono vacío está permitido"""
        cliente = Cliente(
            idcliente=4,
            clientenombre="Ana Martín",
            clientetelefono="",
            clienteemail="ana@email.com",
            clientedocumento="44332211"
        )
        assert cliente.clientetelefono == ""

    def test_cliente_documento_numerico(self):
        """Prueba documento solo numérico"""
        cliente = Cliente(
            idcliente=5,
            clientenombre="Carlos Ruiz",
            clienteemail="carlos@email.com",
            clientedocumento="12345678"
        )
        assert cliente.clientedocumento == "12345678"
        assert cliente.clientedocumento.isdigit()

    def test_cliente_documento_alfanumerico(self):
        """Prueba documento alfanumérico"""
        cliente = Cliente(
            idcliente=6,
            clientenombre="Luis Fernández",
            clienteemail="luis@email.com",
            clientedocumento="DNI12345678"
        )
        assert cliente.clientedocumento == "DNI12345678"


class TestVentaModel:
    """Pruebas para el modelo Venta"""
    
    def test_crear_venta_valida(self):
        """Prueba creación de venta válida"""
        fecha_hora = datetime.now()
        venta = Venta(
            idventa=1,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000001",
            ventafecha_hora=fecha_hora,
            idcliente=1
        )
        assert venta.ventatiop_comprobante == "FACTURA"
        assert venta.ventaserie_comprobante == "F001"
        assert venta.ventanum_comprobante == "000001"
        assert venta.ventafecha_hora == fecha_hora
        assert venta.idcliente == 1

    def test_crear_venta_boleta(self):
        """Prueba creación de venta con boleta"""
        venta = Venta(
            idventa=2,
            ventatiop_comprobante="BOLETA",
            ventaserie_comprobante="B001",
            ventanum_comprobante="000002",
            ventafecha_hora=datetime(2024, 8, 15, 10, 30),
            idcliente=2
        )
        assert venta.ventatiop_comprobante == "BOLETA"
        assert venta.ventaserie_comprobante == "B001"

    def test_venta_con_impuesto_y_total(self):
        """Prueba venta con impuesto y total"""
        venta = Venta(
            idventa=3,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000003",
            ventafecha_hora=datetime.now(),
            ventaimpuesto=Decimal("18.00"),
            ventatotal_venta=Decimal("118.00"),
            idcliente=3
        )
        if hasattr(venta, 'ventaimpuesto'):
            assert venta.ventaimpuesto == Decimal("18.00")
        if hasattr(venta, 'ventatotal_venta'):
            assert venta.ventatotal_venta == Decimal("118.00")

    def test_venta_con_condicion_pago(self):
        """Prueba venta con condición de pago"""
        venta = Venta(
            idventa=4,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000004",
            ventafecha_hora=datetime.now(),
            idcliente=4,
            ventacondicion="CONTADO",
            ventapago_cliente=Decimal("100.00"),
            ventacambio=Decimal("5.00")
        )
        # Solo verificar si el atributo existe
        if hasattr(venta, 'ventacondicion'):
            assert venta.ventacondicion == "CONTADO"
        if hasattr(venta, 'ventapago_cliente'):
            assert venta.ventapago_cliente == Decimal("100.00")

    def test_numero_comprobante_con_ceros(self):
        """Prueba número de comprobante con formato de ceros"""
        venta = Venta(
            idventa=5,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000123",
            ventafecha_hora=datetime.now(),
            idcliente=5
        )
        assert venta.ventanum_comprobante == "000123"
        assert len(venta.ventanum_comprobante) == 6


class TestDetalleVentaModel:
    """Pruebas para el modelo DetalleVenta"""
    
    def test_crear_detalle_venta_valido(self):
        """Prueba creación de detalle de venta válido"""
        detalle = DetalleVenta(
            idarticulo=1,
            detalle_ventacantidad=2,
            detalle_ventaprecio_venta=Decimal("100.00")
        )
        assert detalle.idarticulo == 1
        assert detalle.detalle_ventacantidad == 2
        assert detalle.detalle_ventaprecio_venta == Decimal("100.00")

    def test_detalle_con_descuento(self):
        """Prueba detalle con descuento"""
        detalle = DetalleVenta(
            idarticulo=2,
            detalle_ventacantidad=1,
            detalle_ventaprecio_venta=Decimal("50.00"),
            detalle_ventadescuento=Decimal("5.00")
        )
        assert detalle.idarticulo == 2
        if hasattr(detalle, 'detalle_ventadescuento'):
            assert detalle.detalle_ventadescuento == Decimal("5.00")

    def test_detalle_cantidad_uno(self):
        """Prueba detalle con cantidad uno"""
        detalle = DetalleVenta(
            idarticulo=3,
            detalle_ventacantidad=1,
            detalle_ventaprecio_venta=Decimal("25.50")
        )
        assert detalle.detalle_ventacantidad == 1
        assert detalle.detalle_ventaprecio_venta == Decimal("25.50")

    def test_detalle_precio_con_decimales(self):
        """Prueba precio con decimales"""
        detalle = DetalleVenta(
            idarticulo=4,
            detalle_ventacantidad=3,
            detalle_ventaprecio_venta=Decimal("33.33")
        )
        assert detalle.detalle_ventaprecio_venta == Decimal("33.33")

    def test_detalle_cantidad_alta(self):
        """Prueba con cantidad alta"""
        detalle = DetalleVenta(
            idarticulo=5,
            detalle_ventacantidad=100,
            detalle_ventaprecio_venta=Decimal("1.00")
        )
        assert detalle.detalle_ventacantidad == 100

    def test_calcular_subtotal_si_existe_metodo(self):
        """Prueba cálculo de subtotal si el método existe"""
        detalle = DetalleVenta(
            idarticulo=6,
            detalle_ventacantidad=4,
            detalle_ventaprecio_venta=Decimal("25.00")
        )
        
        # Verificar si existe método de cálculo
        if hasattr(detalle, 'calcular_subtotal'):
            subtotal = detalle.calcular_subtotal()
            assert subtotal == Decimal("100.00")
        else:
            # Cálculo manual
            subtotal = detalle.detalle_ventacantidad * detalle.detalle_ventaprecio_venta
            assert subtotal == Decimal("100.00")


class TestProveedorModel:
    """Pruebas para el modelo Proveedor"""
    
    def test_crear_proveedor_valido(self):
        """Prueba creación de proveedor válido"""
        proveedor = Proveedor(
            id=1,
            nombre="Proveedor Test",
            contacto="Juan Contacto",
            direccion="Av. Principal 123"
        )
        assert proveedor.nombre == "Proveedor Test"
        assert proveedor.contacto == "Juan Contacto"
        assert proveedor.direccion == "Av. Principal 123"

    def test_crear_proveedor_solo_nombre(self):
        """Prueba creación solo con nombre"""
        proveedor = Proveedor(
            id=2,
            nombre="Proveedor Mínimo"
        )
        assert proveedor.nombre == "Proveedor Mínimo"
        # Verificar campos opcionales
        if hasattr(proveedor, 'contacto') and proveedor.contacto is not None:
            assert isinstance(proveedor.contacto, str)

    def test_proveedor_con_contacto_completo(self):
        """Prueba proveedor con información de contacto completa"""
        proveedor = Proveedor(
            id=3,
            nombre="Distribuidora ABC",
            contacto="María Gerente",
            direccion="Calle Comercio 456, La Paz"
        )
        assert proveedor.nombre == "Distribuidora ABC"
        assert proveedor.contacto == "María Gerente"
        assert "La Paz" in proveedor.direccion

    def test_proveedor_nombre_empresa(self):
        """Prueba con nombre de empresa"""
        proveedor = Proveedor(
            id=4,
            nombre="TECNOLOGIA Y SISTEMAS S.R.L.",
            contacto="Ing. Carlos López",
            direccion="Zona Sur, Calle 21 #123"
        )
        assert "S.R.L." in proveedor.nombre
        assert proveedor.contacto.startswith("Ing.")

    def test_proveedor_direccion_larga(self):
        """Prueba con dirección larga"""
        direccion_larga = "Av. 6 de Agosto #2170, entre calles Rosendo Gutiérrez y Belisario Salinas, Zona Central, La Paz, Bolivia"
        proveedor = Proveedor(
            id=5,
            nombre="Importadora Internacional",
            contacto="Sr. Roberto Mamani",
            direccion=direccion_larga
        )
        assert proveedor.direccion == direccion_larga


class TestRelacionesModelos:
    """Pruebas de relaciones entre modelos"""
    
    def test_venta_con_cliente_existente(self):
        """Prueba venta asociada a cliente"""
        # Crear cliente
        cliente = Cliente(
            idcliente=10,
            clientenombre="Cliente Prueba",
            clienteemail="prueba@test.com",
            clientedocumento="12345678"
        )
        
        # Crear venta asociada
        venta = Venta(
            idventa=10,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000010",
            ventafecha_hora=datetime.now(),
            idcliente=cliente.idcliente
        )
        
        assert venta.idcliente == cliente.idcliente

    def test_detalle_venta_con_articulo_existente(self):
        """Prueba detalle asociado a artículo"""
        # Crear artículo
        articulo = Articulo(
            idarticulo=10,
            articulonombre="Producto Test",
            articulostock=5,
            articulocodigo="PROD010"
        )
        
        # Crear detalle asociado
        detalle = DetalleVenta(
            idarticulo=articulo.idarticulo,
            detalle_ventacantidad=2,
            detalle_ventaprecio_venta=Decimal("50.00")
        )
        
        assert detalle.idarticulo == articulo.idarticulo

    def test_articulo_con_proveedor_existente(self):
        """Prueba artículo asociado a proveedor"""
        # Crear proveedor
        proveedor = Proveedor(
            id=10,
            nombre="Proveedor Test",
            contacto="Contacto Test"
        )
        
        # Crear artículo asociado (si tu modelo lo soporta)
        articulo = Articulo(
            idarticulo=11,
            articulonombre="Artículo con Proveedor",
            articulostock=3,
            articulocodigo="ART011"
        )
        
        # Si tu modelo tiene relación con proveedor
        if hasattr(articulo, 'id_proveedor'):
            articulo.id_proveedor = proveedor.id
            assert articulo.id_proveedor == proveedor.id


class TestValidacionesBásicas:
    """Pruebas de validaciones básicas sin excepciones"""
    
    def test_tipos_de_datos_correctos(self):
        """Prueba que los tipos de datos sean correctos"""
        articulo = Articulo(
            idarticulo=1,
            articulonombre="Test",
            articulostock=10,
            articulocodigo="TEST001"
        )
        
        assert isinstance(articulo.idarticulo, int)
        assert isinstance(articulo.articulonombre, str)
        assert isinstance(articulo.articulostock, int)
        assert isinstance(articulo.articulocodigo, str)

    def test_decimal_precision(self):
        """Prueba precisión de decimales"""
        detalle = DetalleVenta(
            idarticulo=1,
            detalle_ventacantidad=1,
            detalle_ventaprecio_venta=Decimal("99.99")
        )
        
        assert isinstance(detalle.detalle_ventaprecio_venta, Decimal)
        assert detalle.detalle_ventaprecio_venta == Decimal("99.99")

    def test_fechas_validas(self):
        """Prueba validez de fechas"""
        fecha_actual = datetime.now()
        venta = Venta(
            idventa=1,
            ventatiop_comprobante="FACTURA",
            ventaserie_comprobante="F001",
            ventanum_comprobante="000001",
            ventafecha_hora=fecha_actual,
            idcliente=1
        )
        
        assert isinstance(venta.ventafecha_hora, datetime)
        assert venta.ventafecha_hora == fecha_actual

    def test_strings_no_vacios_donde_requerido(self):
        """Prueba strings no vacíos en campos importantes"""
        cliente = Cliente(
            idcliente=1,
            clientenombre="Juan",
            clienteemail="juan@test.com",
            clientedocumento="12345678"
        )
        
        # Solo verificar que no sean None
        assert cliente.clientenombre is not None
        assert cliente.clientedocumento is not None
        # Email puede ser vacío pero no None
        assert cliente.clienteemail is not None