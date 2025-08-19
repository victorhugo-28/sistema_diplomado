# core/interfaces/detalle_venta_repository.py
from abc import ABC, abstractmethod
from core.models.detalle_venta import DetalleVenta
from typing import List

class DetalleVentaRepositoryInterface(ABC):
    @abstractmethod
    def listar_todos(self):
        pass

    @abstractmethod
    def crear(self, detalle_venta: DetalleVenta):
        pass

    @abstractmethod
    def obtener_por_id(self, id: int):
        pass

    @abstractmethod
    def obtener_por_venta_id(self, venta_id: int) -> List[DetalleVenta]:
        pass

    @abstractmethod
    def actualizar(self, id: int, cambios: dict):
        pass

    @abstractmethod
    def eliminar(self, id: int) -> bool:
        pass

    @abstractmethod
    def eliminar_por_venta_id(self, venta_id: int) -> bool:
        pass