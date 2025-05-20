# src/infrastructure/data/usuario_repository_impl.py
from core.interfaces.usuario_repository import UsuarioRepository
from core.models.usuario import Usuario

class UsuarioRepositoryImpl(UsuarioRepository):
    def listar_usuarios(self):
        # Simulación de base de datos
        return [
            Usuario(id=1, nombre="Juan", correo="juan@mail.com"),
            Usuario(id=2, nombre="Ana", correo="ana@mail.com")
        ]
