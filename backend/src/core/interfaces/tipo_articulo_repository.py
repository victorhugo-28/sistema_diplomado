from abc import ABC, abstractmethod
from core.models.tipo_articulo import TipoArticulo

class TipoArticuloRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, tipo: TipoArticulo):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def actualizar(self, id: int, nombre: str):
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
