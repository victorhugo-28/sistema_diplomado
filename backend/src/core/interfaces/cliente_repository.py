from abc import ABC, abstractmethod
from core.models.cliente import Cliente

class ClienteRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, cliente: Cliente):
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
