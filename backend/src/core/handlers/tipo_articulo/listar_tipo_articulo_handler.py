from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface
class ListarTipoArticuloHandler:
    def __init__(self, repo: TipoArticuloRepositoryInterface):
        self.repo = repo

    def handle(self):
        tipos = self.repo.listar_todos()
        return [{"id": t.id, "nombre": t.nombre} for t in tipos]
