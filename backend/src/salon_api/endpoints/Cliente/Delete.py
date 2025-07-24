from fastapi import APIRouter
from core.handlers.cliente.eliminar_cliente_handler import EliminarClienteHandler
from infrastructure.data.cliente_repository_impl import ClienteRepositoryImpl

router = APIRouter()

@router.delete("/clientes/{id}")
def eliminar_cliente(id: int):
    handler = EliminarClienteHandler(ClienteRepositoryImpl())
    return {"eliminado": handler.handle(id)}
