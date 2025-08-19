# core/interfaces/compra_repository.py
from abc import ABC, abstractmethod
from typing import List, Optional
from core.models.compra import Compra

class CompraRepositoryInterface(ABC):
    
    @abstractmethod
    def listar_todos(self) -> List[Compra]:
        pass
    
    @abstractmethod
    def crear(self, compra: Compra) -> Compra:
        pass
    
    @abstractmethod
    def obtener_por_id(self, id: int) -> Optional[Compra]:
        pass
    
    @abstractmethod
    def actualizar(self, id: int, cambios: dict) -> Optional[Compra]:
        pass
    
    @abstractmethod
    def listar_con_detalles(self, limit: int = 50, offset: int = 0) -> List[Compra]:
        pass
    
    @abstractmethod
    def obtener_por_id_con_detalles(self, id: int) -> Optional[Compra]:
        pass
    
    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass