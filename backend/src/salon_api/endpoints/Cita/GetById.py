from fastapi import APIRouter, HTTPException
from infrastructure.data.cita_repository_impl import CitaRepositoryImpl
from core.handlers.cita.obtener_cita_por_id_handler import ObtenerCitaPorIdHandler

router = APIRouter()

@router.get("/citas/{id}")
def obtener_cita(id: int):
    handler = ObtenerCitaPorIdHandler(CitaRepositoryImpl())
    try:
        return handler.handle(id)
    except ValueError as e:
        raise HTTPException(status_code=404, detail=str(e))
