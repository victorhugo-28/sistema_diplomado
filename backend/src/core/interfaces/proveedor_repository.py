from abc import ABC, abstractmethod
from core.models.proveedor import Proveedor

class ProveedorRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self) -> list[Proveedor]:
        pass

    @abstractmethod
    def crear(self, proveedor: Proveedor) -> Proveedor:
        pass

    @abstractmethod
    def obtener_por_id(self, id: int) -> Proveedor:
        pass

    @abstractmethod
    def actualizar(self, proveedor: Proveedor) -> Proveedor:
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
