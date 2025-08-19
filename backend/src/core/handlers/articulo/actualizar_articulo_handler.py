# core/handlers/articulo/actualizar_articulo_handler.py
from core.interfaces.articulo_repository import ArticuloRepositoryInterface
from core.dto.articulo_dto import ActualizarArticuloDTO

class ActualizarArticuloHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarArticuloDTO):
        cambios = data.dict(exclude_unset=True)
        if not cambios:
            return None  # o lanza ValueError("No hay campos para actualizar")
        # Si tu repo espera dict (recomendado):
        return self.repo.actualizar(id, cambios)
        # Si tu repo espera args sueltos, ajusta aquí y en el repo.
