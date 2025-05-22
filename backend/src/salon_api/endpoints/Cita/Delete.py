from fastapi import APIRouter, HTTPException
from infrastructure.data.cita_repository_impl import CitaRepositoryImpl
from core.handlers.cita.eliminar_cita_handler import EliminarCitaHandler

router = APIRouter()

@router.delete("/citas/{id}")
def eliminar_cita(id: int):
    handler = EliminarCitaHandler(CitaRepositoryImpl())
    try:
        return handler.handle(id)
    except ValueError as e:
        raise HTTPException(status_code=404, detail=str(e))
