from abc import ABC, abstractmethod
from core.models.rol import Rol

class RolRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, rol: Rol):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def actualizar(self, rol: Rol):
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
