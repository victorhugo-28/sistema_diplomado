from fastapi import APIRouter
from infrastructure.data.cita_repository_impl import CitaRepositoryImpl
from core.handlers.cita.listar_citas_handler import ListarCitasHandler

router = APIRouter()

@router.get("/citas")
def listar_citas():
    repo = CitaRepositoryImpl()
    handler = ListarCitasHandler(repo)
    return handler.handle()
