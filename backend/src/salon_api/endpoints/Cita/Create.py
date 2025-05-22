from fastapi import APIRouter
from core.dto.cita_dto import CrearCitaDTO
from infrastructure.data.cita_repository_impl import CitaRepositoryImpl
from core.handlers.cita.crear_cita_handler import CrearCitaHandler

router = APIRouter()

@router.post("/citas")
def crear_cita(data: CrearCitaDTO):
    print("Datos recibidos:", data)

    repo = CitaRepositoryImpl()
    handler = CrearCitaHandler(repo)
    return handler.handle(data)
