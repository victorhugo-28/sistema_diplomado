from core.interfaces.articulo_repository import ArticuloRepositoryInterface
class ListarArticuloHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self):
        articulos = self.repo.listar_todos()
        return [
            {
                "id": a.idarticulo,
                "nombre": a.articulonombre,
                "stock": a.articulostock,
                "descripcion": a.articulodescripcion,
                "imagen": a.articuloimagen,
                "codigo": a.articulocodigo,
                "codigo_gener": a.articulocodigogener,
                "fecha": a.articulofecha,
                "hora": a.articulohora,
                "id_tipo": a.id_tipo,
                "id_proveedor": a.id_proveedor
            } for a in articulos
        ]
