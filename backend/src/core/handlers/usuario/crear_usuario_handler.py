from core.interfaces.usuario_repository import UsuarioRepositoryInterface
from core.dto.usuario_dto import CrearUsuarioDTO
from core.models.usuario import Usuario
from core.events.user_created_event import UserCreatedEvent
from infrastructure.messaging.rabbit_publisher import RabbitPublisher

class CrearUsuarioHandler:
    def __init__(self, repo: UsuarioRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearUsuarioDTO):
        nuevo_usuario = Usuario(nombre=data.nombre, correo=data.correo)
        self.repo.crear(nuevo_usuario)

        event = UserCreatedEvent(
            id=nuevo_usuario.id,
            name=nuevo_usuario.nombre,
            email=nuevo_usuario.correo
        )
        # RabbitPublisher().publish("user_created", event.dict())

        return nuevo_usuario
