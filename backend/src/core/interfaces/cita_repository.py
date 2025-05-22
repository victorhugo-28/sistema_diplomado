from abc import ABC, abstractmethod
from core.models.cita import Cita

class CitaRepositoryInterface(ABC):
    @abstractmethod
    def listar_todas(self): pass

    @abstractmethod
    def obtener_por_id(self, id: int): pass

    @abstractmethod
    def crear(self, cita: Cita): pass

    @abstractmethod
    def actualizar(self, cita: Cita): pass

    @abstractmethod
    def eliminar(self, id: int): pass
