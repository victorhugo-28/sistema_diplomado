from core.interfaces.usuario_repository import UsuarioRepositoryInterface
from core.dto.usuario_dto import CrearUsuarioDTO
from core.models.usuario import Usuario

class CrearUsuarioHandler:
    def __init__(self, repo: UsuarioRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearUsuarioDTO):
        nuevo_usuario = Usuario(nombre=data.nombre, correo=data.correo)
        self.repo.crear(nuevo_usuario)
        return nuevo_usuario
        
