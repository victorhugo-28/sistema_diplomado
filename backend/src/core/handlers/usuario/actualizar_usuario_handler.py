from core.interfaces.usuario_repository import UsuarioRepositoryInterface
from core.dto.usuario_dto import ActualizarUsuarioDTO
from core.models.usuario import Usuario

class ActualizarUsuarioHandler:
    def __init__(self, repo: UsuarioRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarUsuarioDTO):
        datos = self.repo.actualizar(id, data.nombre, data.correo)
        if not datos:
            raise ValueError("Usuario no encontrado")
        return datos


