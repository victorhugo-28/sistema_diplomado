from fastapi import APIRouter
from core.handlers.cliente.actualizar_cliente_handler import ActualizarClienteHandler
from infrastructure.data.cliente_repository_impl import ClienteRepositoryImpl

router = APIRouter()

@router.put("/clientes/{id}")
def actualizar_cliente(id: int, data: dict):
    handler = ActualizarClienteHandler(ClienteRepositoryImpl())
    return handler.handle(id, data)
