from core.interfaces.compra_repository import CompraRepositoryInterface

class ListarCompraHandler:
    def __init__(self, repo: CompraRepositoryInterface):
        self.repo = repo

    def handle(self):
        compras = self.repo.listar_todos()
        return [
            {
                "id": c.idingreso,
                "tipo_comprobante": c.ingresotipo_comprobante,
                "serie": c.ingresoserie_comprobante,
                "numero": c.ingresonumero_comprobante,
                "fecha": c.ingresofecha_hora,
                "impuesto": c.ingresoimpuesto,
                "total": c.ingresototal_compra,
                "proveedor": c.idproveedor,
                "condicion": c.ingresocondicion
            } for c in compras
        ]
