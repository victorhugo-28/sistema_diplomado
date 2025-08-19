from fastapi import APIRouter, HTTPException, UploadFile, File, Form
from core.dto.articulo_dto import ActualizarArticuloDTO
from core.handlers.articulo.actualizar_articulo_handler import ActualizarArticuloHandler
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl
from datetime import datetime
import os

router = APIRouter()
UPLOAD_DIR = os.path.join("uploads", "articulos")

@router.put("/articulos/{id}")
async def actualizar_articulo(
    id: int,
    articulonombre: str | None = Form(None),
    articulostock: int | None = Form(None),
    articulodescripcion: str | None = Form(None),
    articuloimagen: UploadFile | None = File(None),
    articulocodigogener: str | None = Form(None),
    articulocodigo: str | None = Form(None),
    articulofecha: str | None = Form(None),
    articulohora: str | None = Form(None),
    id_tipo: int | None = Form(None),
    id_proveedor: int | None = Form(None),
):
    os.makedirs(UPLOAD_DIR, exist_ok=True)

    ruta_relativa = None
    if articuloimagen:
        ext = os.path.splitext(articuloimagen.filename)[1]
        nombre_archivo = f"{articulocodigo or 'articulo'}_{datetime.now().strftime('%Y%m%d%H%M%S')}{ext}"
        ruta_fisica = os.path.join(UPLOAD_DIR, nombre_archivo)
        with open(ruta_fisica, "wb") as f:
            f.write(await articuloimagen.read())
        ruta_relativa = f"uploads/articulos/{nombre_archivo}"

    # Construir DTO con los campos recibidos
    dto = ActualizarArticuloDTO(
        articulonombre=articulonombre,
        articulostock=articulostock,
        articulodescripcion=articulodescripcion,
        articuloimagen=ruta_relativa,
        articulocodigogener=articulocodigogener,
        articulocodigo=articulocodigo,
        articulofecha=articulofecha,
        articulohora=articulohora,
        id_tipo=id_tipo,
        id_proveedor=id_proveedor
    )

    handler = ActualizarArticuloHandler(ArticuloRepositoryImpl())
    out = handler.handle(id, dto)

    if not out:
        raise HTTPException(status_code=404, detail="Artículo no encontrado")
    return out
