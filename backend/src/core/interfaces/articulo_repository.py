from abc import ABC, abstractmethod
from core.models.articulo import Articulo

class ArticuloRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, articulo: Articulo):
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
