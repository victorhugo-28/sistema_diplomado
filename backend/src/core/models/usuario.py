# src/core/models/usuario.py
from dataclasses import dataclass

@dataclass
class Usuario:
    id: int
    nombre: str
    correo: str
