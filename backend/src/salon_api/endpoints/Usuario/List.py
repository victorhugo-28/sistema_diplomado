# src/salon_api/endpoints/Usuario/List.py

from fastapi import APIRouter

router = APIRouter()

@router.get("/list")
def listar_usuarios():
    return [{"id": 1, "nombre": "Juan"}, {"id": 2, "nombre": "Ana"}]
