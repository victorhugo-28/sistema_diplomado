from abc import ABC, abstractmethod
from core.models.compra import Compra

class CompraRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, compra: Compra):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def actualizar(self, id: int, data: dict):
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
