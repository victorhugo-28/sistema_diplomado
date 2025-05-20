# src/core/interface/usuario_repository.py
from abc import ABC, abstractmethod
from typing import List
from core.models.usuario import Usuario

class UsuarioRepository(ABC):
    @abstractmethod
    def listar_usuarios(self) -> List[Usuario]:
        pass
