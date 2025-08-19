# core/handlers/articulo/obtener_articulo_con_detalles_handler.py
from core.interfaces.articulo_repository import ArticuloRepositoryInterface
from core.models.articulo import Articulo

class ObtenerArticuloConDetallesHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id_articulo: int):
        """
        Obtiene un artículo por ID con todos sus detalles de ingreso
        """
        articulo = self.repo.obtener_por_id_con_detalles(id_articulo)
        if not articulo:
            return None
        return articulo