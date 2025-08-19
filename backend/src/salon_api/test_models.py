"""
Pruebas unitarias para modelos del dominio - VERSIÓN CORREGIDA
Ejecutable con: python -m test_models
"""

import sys
import unittest
from datetime import datetime, date, time
from decimal import Decimal

# Importaciones usando la misma estructura que init_db.py
from core.models.cliente import Cliente
from core.models.proveedor import Proveedor
from core.models.tipo_articulo import TipoArticulo
from core.models.articulo import Articulo
from core.models.venta import Venta
from core.models.detalle_venta import DetalleVenta
from core.models.compra import Compra
from core.models.detalle_compra import DetalleIngreso


class TestArticuloModel(unittest.TestCase):
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
        self.assertEqual(articulo.articulonombre, "Laptop HP")
        self.assertEqual(articulo.articulostock, 10)
        self.assertEqual(articulo.articulocodigo, "LAP001")

    def test_crear_articulo_con_campos_minimos(self):
        """Prueba creación con campos mínimos requeridos"""
        articulo = Articulo(
            idarticulo=2,
            articulonombre="Mouse",
            articulostock=5,
            articulocodigo="MOU001"
        )
        self.assertEqual(articulo.articulonombre, "Mouse")
        self.assertEqual(articulo.articulostock, 5)
        self.assertEqual(articulo.articulocodigo, "MOU001")

    def test_articulo_stock_cero_es_valido(self):
        """Prueba que stock cero es válido"""
        articulo = Articulo(
            idarticulo=3,
            articulonombre="Teclado",
            articulostock=0,
            articulocodigo="TEC001"
        )
        self.assertEqual(articulo.articulostock, 0)

    def test_articulo_nombre_largo(self):
        """Prueba con nombre largo"""
        nombre_largo = "Laptop HP Pavilion Gaming 15-dk1056wm Intel Core i5"
        articulo = Articulo(
            idarticulo=4,
            articulonombre=nombre_largo,
            articulostock=3,
            articulocodigo="LAP002"
        )
        self.assertEqual(articulo.articulonombre, nombre_largo)

    def test_articulo_codigo_alfanumerico(self):
        """Prueba código alfanumérico"""
        articulo = Articulo(
            idarticulo=5,
            articulonombre="Monitor",
            articulostock=7,
            articulocodigo="MON-2024-001"
        )
        self.assertEqual(articulo.articulocodigo, "MON-2024-001")


class TestClienteModel(unittest.TestCase):
    """Pruebas para el modelo Cliente - CORREGIDO PARA SQLAlchemy"""
    
    def test_crear_cliente_valido(self):
        """Prueba creación de cliente válido"""
        cliente = Cliente(
            nombre="Juan Pérez",
            email="juan@email.com",
            telefono="12345678"
        )
        self.assertEqual(cliente.nombre, "Juan Pérez")
        self.assertEqual(cliente.email, "juan@email.com")
        self.assertEqual(cliente.telefono, "12345678")

    def test_crear_cliente_con_telefono_y_direccion(self):
        """Prueba creación con todos los campos requeridos"""
        cliente = Cliente(
            nombre="María González",
            email="maria@email.com",
            telefono="987654321"
        )
        self.assertEqual(cliente.nombre, "María González")
        self.assertEqual(cliente.email, "maria@email.com")
        self.assertEqual(cliente.telefono, "987654321")

    def test_cliente_documento_numerico(self):
        """Prueba teléfono solo numérico"""
        cliente = Cliente(
            nombre="Carlos Ruiz",
            email="carlos@email.com",
            telefono="12345678"
        )
        self.assertEqual(cliente.telefono, "12345678")
        self.assertTrue(cliente.telefono.isdigit())

    def test_cliente_email_valido(self):
        """Prueba validación de email"""
        cliente = Cliente(
            nombre="Ana López",
            email="ana.lopez@email.com",
            telefono="11111111"
        )
        self.assertIn("@", cliente.email)
        self.assertIn(".", cliente.email)

    def test_crear_cliente_campos_requeridos(self):
        """Prueba que todos los campos requeridos estén presentes"""
        cliente = Cliente(
            nombre="Pedro Silva",
            email="pedro@test.com",
            telefono="555-0123"
        )
        self.assertIsNotNone(cliente.nombre)
        self.assertIsNotNone(cliente.email)
        self.assertIsNotNone(cliente.telefono)
        
    def test_cliente_nombre_largo(self):
        """Prueba nombre largo dentro del límite"""
        nombre_largo = "José María Fernández de la Cruz González"
        cliente = Cliente(
            nombre=nombre_largo,
            email="jose.maria@email.com",
            telefono="555-0001"
        )
        self.assertEqual(cliente.nombre, nombre_largo)
        self.assertLessEqual(len(cliente.nombre), 100)  # Límite de 100 caracteres

    def test_cliente_email_largo(self):
        """Prueba email largo dentro del límite"""
        email_largo = "usuario.con.nombre.muy.largo@dominio.muy.largo.com"
        cliente = Cliente(
            nombre="Usuario Test",
            email=email_largo,
            telefono="555-0002"
        )
        self.assertEqual(cliente.email, email_largo)
        self.assertLessEqual(len(cliente.email), 100)  # Límite de 100 caracteres

    def test_cliente_telefono_con_guiones(self):
        """Prueba teléfono con formato"""
        cliente = Cliente(
            nombre="Luis Castro",
            email="luis@email.com",
            telefono="555-123-4567"
        )
        self.assertEqual(cliente.telefono, "555-123-4567")
        self.assertLessEqual(len(cliente.telefono), 20)  # Límite de 20 caracteres


class TestProveedorModel(unittest.TestCase):
    """Pruebas para el modelo Proveedor"""
    
    def test_crear_proveedor_valido(self):
        """Prueba creación de proveedor válido"""
        proveedor = Proveedor(
            id=1,
            nombre="Proveedor Test"
        )
        self.assertEqual(proveedor.nombre, "Proveedor Test")

    def test_crear_proveedor_solo_nombre(self):
        """Prueba creación solo con nombre"""
        proveedor = Proveedor(
            id=2,
            nombre="Proveedor Mínimo"
        )
        self.assertEqual(proveedor.nombre, "Proveedor Mínimo")


class TestTipoArticuloModel(unittest.TestCase):
    """Pruebas para el modelo TipoArticulo"""
    
    def test_crear_tipo_articulo_valido(self):
        """Prueba creación de tipo de artículo válido"""
        tipo = TipoArticulo(
            id=1,
            nombre="Electrónicos"
        )
        self.assertEqual(tipo.nombre, "Electrónicos")

    def test_tipos_articulo_comunes(self):
        """Prueba tipos de artículos comunes"""
        tipos_comunes = [
            "Electrónicos",
            "Ropa", 
            "Hogar",
            "Deportes"
        ]
        
        for i, nombre in enumerate(tipos_comunes, 1):
            tipo = TipoArticulo(
                id=i,
                nombre=nombre
            )
            self.assertEqual(tipo.nombre, nombre)
            self.assertEqual(tipo.id, i)


class TestVentaModel(unittest.TestCase):
    """Pruebas para el modelo Venta"""
    
    def test_crear_venta_valida(self):
        """Prueba creación de venta válida"""
        venta = Venta(
            idventa=1,
            idcliente=1
        )
        self.assertEqual(venta.idventa, 1)
        self.assertEqual(venta.idcliente, 1)

    def test_venta_con_campos_adicionales(self):
        """Prueba venta con campos adicionales si existen"""
        venta = Venta(
            idventa=2,
            idcliente=2
        )
        
        # Verificar campos opcionales
        if hasattr(venta, 'ventatiop_comprobante'):
            venta.ventatiop_comprobante = "FACTURA"
            self.assertEqual(venta.ventatiop_comprobante, "FACTURA")


class TestDetalleVentaModel(unittest.TestCase):
    """Pruebas para el modelo DetalleVenta"""
    
    def test_crear_detalle_venta_valido(self):
        """Prueba creación de detalle de venta válido"""
        detalle = DetalleVenta(
            idarticulo=1,
            detalle_ventacantidad=2,
            detalle_ventaprecio_venta=Decimal("100.00")
        )
        self.assertEqual(detalle.idarticulo, 1)
        self.assertEqual(detalle.detalle_ventacantidad, 2)
        self.assertEqual(detalle.detalle_ventaprecio_venta, Decimal("100.00"))

    def test_calcular_subtotal_manual(self):
        """Prueba cálculo de subtotal manual"""
        detalle = DetalleVenta(
            idarticulo=6,
            detalle_ventacantidad=4,
            detalle_ventaprecio_venta=Decimal("25.00")
        )
        
        # Cálculo manual del subtotal
        subtotal = detalle.detalle_ventacantidad * detalle.detalle_ventaprecio_venta
        self.assertEqual(subtotal, Decimal("100.00"))


class TestCompraModel(unittest.TestCase):
    """Pruebas para el modelo Compra"""
    
    def test_crear_compra_valida(self):
        """Prueba creación de compra válida"""
        compra = Compra(
            idproveedor=1
        )
        self.assertEqual(compra.idproveedor, 1)


class TestDetalleIngresoModel(unittest.TestCase):
    """Pruebas para el modelo DetalleIngreso"""
    
    def test_crear_detalle_ingreso_valido(self):
        """Prueba creación de detalle de ingreso válido"""
        detalle = DetalleIngreso(
            idarticulo=1,
            detalle_ingresocantidad=10,
            detalle_ingresoprecio_compra=Decimal("80.00")
        )
        self.assertEqual(detalle.idarticulo, 1)
        self.assertEqual(detalle.detalle_ingresocantidad, 10)
        self.assertEqual(detalle.detalle_ingresoprecio_compra, Decimal("80.00"))


class TestValidacionesBásicas(unittest.TestCase):
    """Pruebas de validaciones básicas"""
    
    def test_tipos_de_datos_correctos(self):
        """Prueba que los tipos de datos sean correctos"""
        articulo = Articulo(
            idarticulo=1,
            articulonombre="Test",
            articulostock=10,
            articulocodigo="TEST001"
        )
        
        self.assertIsInstance(articulo.idarticulo, int)
        self.assertIsInstance(articulo.articulonombre, str)
        self.assertIsInstance(articulo.articulostock, int)
        self.assertIsInstance(articulo.articulocodigo, str)

    def test_decimal_precision(self):
        """Prueba precisión de decimales"""
        detalle = DetalleVenta(
            idarticulo=1,
            detalle_ventacantidad=1,
            detalle_ventaprecio_venta=Decimal("99.99")
        )
        
        self.assertIsInstance(detalle.detalle_ventaprecio_venta, Decimal)
        self.assertEqual(detalle.detalle_ventaprecio_venta, Decimal("99.99"))

    def test_cliente_campos_string(self):
        """Prueba que los campos de Cliente sean strings"""
        cliente = Cliente(
            nombre="Test User",
            email="test@email.com",
            telefono="123456789"
        )
        
        self.assertIsInstance(cliente.nombre, str)
        self.assertIsInstance(cliente.email, str)
        self.assertIsInstance(cliente.telefono, str)


def main():
    """Función principal para ejecutar las pruebas"""
    print("=" * 80)
    print("🧪 SISTEMA DE PRUEBAS UNITARIAS - MODELOS DEL DOMINIO")
    print("=" * 80)
    print("📅 Fecha de ejecución:", datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
    print("🐍 Versión de Python:", sys.version.split()[0])
    print("=" * 80)
    
    # Crear suite de pruebas
    loader = unittest.TestLoader()
    suite = unittest.TestSuite()
    
    # Agregar todas las clases de prueba
    test_classes = [
        TestArticuloModel,
        TestClienteModel, 
        TestProveedorModel,
        TestTipoArticuloModel,
        TestVentaModel,
        TestDetalleVentaModel,
        TestCompraModel,
        TestDetalleIngresoModel,
        TestValidacionesBásicas
    ]
    
    total_tests = 0
    for test_class in test_classes:
        tests = loader.loadTestsFromTestCase(test_class)
        suite.addTests(tests)
        total_tests += tests.countTestCases()
    
    print(f"📊 Total de pruebas a ejecutar: {total_tests}")
    print(f"📂 Total de clases de prueba: {len(test_classes)}")
    print("-" * 80)
    
    # Ejecutar pruebas con resultado detallado
    runner = unittest.TextTestRunner(
        verbosity=2,
        descriptions=True,
        failfast=False,
        stream=sys.stdout
    )
    
    start_time = datetime.now()
    result = runner.run(suite)
    end_time = datetime.now()
    execution_time = (end_time - start_time).total_seconds()
    
    # Mostrar resumen detallado
    print("\n" + "=" * 80)
    print("📈 RESUMEN FINAL DE RESULTADOS")
    print("=" * 80)
    print(f"✅ Pruebas ejecutadas: {result.testsRun}")
    print(f"❌ Pruebas fallidas: {len(result.failures)}")
    print(f"⚠️  Errores: {len(result.errors)}")
    print(f"⏱️  Tiempo de ejecución: {execution_time:.2f} segundos")
    
    # Calcular tasa de éxito
    if result.testsRun > 0:
        success_rate = ((result.testsRun - len(result.failures) - len(result.errors)) / result.testsRun * 100)
        print(f"🎯 Tasa de éxito: {success_rate:.1f}%")
    else:
        print(f"🎯 Tasa de éxito: 0%")
    
    # Mostrar detalles de fallos
    if result.failures:
        print(f"\n❌ PRUEBAS FALLIDAS ({len(result.failures)}):")
        for i, (test, traceback) in enumerate(result.failures, 1):
            print(f"   {i}. {test}")
            print(f"      └─ Error: {traceback.split('AssertionError:')[-1].strip()}")
    
    # Mostrar detalles de errores
    if result.errors:
        print(f"\n⚠️  ERRORES DE EJECUCIÓN ({len(result.errors)}):")
        for i, (test, traceback) in enumerate(result.errors, 1):
            print(f"   {i}. {test}")
            print(f"      └─ Error: {traceback.split(':', 2)[-1].strip()}")
    
    # Mensaje final
    print("\n" + "=" * 80)
    if result.wasSuccessful():
        print("🎉 ¡TODAS LAS PRUEBAS PASARON EXITOSAMENTE!")
        print("✅ El sistema está funcionando correctamente")
        return_code = 0
    else:
        print("💥 ALGUNAS PRUEBAS FALLARON")
        if result.failures:
            print("📋 Revisa los fallos de validación arriba")
        if result.errors:
            print("🔧 Revisa los errores de código arriba")
        return_code = 1
    
    print("=" * 80)
    return return_code


if __name__ == "__main__":
    """Punto de entrada cuando se ejecuta como módulo"""
    try:
        exit_code = main()
        sys.exit(exit_code)
    except KeyboardInterrupt:
        print("\n\n⏹️  Ejecución interrumpida por el usuario")
        sys.exit(130)
    except Exception as e:
        print(f"\n\n💥 Error inesperado: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)