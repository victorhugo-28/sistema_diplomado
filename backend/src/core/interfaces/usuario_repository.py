from abc import ABC, abstractmethod
from core.models.usuario import Usuario

class UsuarioRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, usuario: Usuario):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def actualizar(self, usuario: Usuario):
        pass
    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass
