from core.dto.cita_dto import CrearCitaDTO
from core.models.cita import Cita
from core.interfaces.cita_repository import CitaRepositoryInterface

class CrearCitaHandler:
    def __init__(self, repo: CitaRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearCitaDTO):
        nueva_cita = Cita(
            fecha=data.fecha,
            descripcion=data.descripcion,
            usuario_id=data.usuario_id
        )
        cita = self.repo.crear(nueva_cita)
        return {
            "id": cita.id,
            "fecha": cita.fecha.isoformat(),
            "descripcion": cita.descripcion,
            "usuario_id": cita.usuario_id
        }
