from abc import ABC, abstractmethod
from core.models.venta import Venta

class VentaRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, venta: Venta):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def actualizar(self, venta: Venta):
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
