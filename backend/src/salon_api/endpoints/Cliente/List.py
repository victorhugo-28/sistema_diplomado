from fastapi import APIRouter
from core.handlers.cliente.listar_cliente_handler import ListarClienteHandler
from infrastructure.data.cliente_repository_impl import ClienteRepositoryImpl

router = APIRouter()

@router.get("/clientes")
def listar_clientes():
    handler = ListarClienteHandler(ClienteRepositoryImpl())
    return handler.handle()
