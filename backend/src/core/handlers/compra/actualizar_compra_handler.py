# core/handlers/compra/actualizar_compra_handler.py
from core.interfaces.compra_repository import CompraRepositoryInterface
from core.dto.compra_dto import ActualizarCompraDTO

class ActualizarCompraHandler:
    def __init__(self, repo: CompraRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarCompraDTO):
        cambios = data.dict(exclude_unset=True)
        if not cambios:
            return None
        return self.repo.actualizar(id, cambios)
