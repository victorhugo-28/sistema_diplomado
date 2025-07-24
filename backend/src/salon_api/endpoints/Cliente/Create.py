from fastapi import APIRouter
from core.dto.cliente_dto import CrearClienteDTO
from core.handlers.cliente.crear_cliente_handler import CrearClienteHandler
from infrastructure.data.cliente_repository_impl import ClienteRepositoryImpl

router = APIRouter()

@router.post("/clientes")
def crear_cliente(data: CrearClienteDTO):
    handler = CrearClienteHandler(ClienteRepositoryImpl())
    cliente = handler.handle(data)
    return {
        "id": cliente.id,
        "nombre": cliente.nombre,
        "email": cliente.email,
        "telefono": cliente.telefono
    }
