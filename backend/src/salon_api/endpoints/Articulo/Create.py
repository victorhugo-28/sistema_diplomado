from fastapi import APIRouter, UploadFile, File, Form, HTTPException
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl
from pathlib import Path
from datetime import datetime
import os
import shutil

router = APIRouter()

ABS_UPLOADS = Path(r"E:\diplomado\GestionDeCitas\backend\src\uploads")
ABS_ARTICULOS = ABS_UPLOADS / "articulos"
ABS_ARTICULOS.mkdir(parents=True, exist_ok=True)

@router.post("/articulos")
async def crear_articulo(
    articulonombre: str = Form(...),
    articulostock: int = Form(...),
    articulodescripcion: str = Form(...),
    articuloimagen: UploadFile = File(...),
    articulocodigogener: str = Form(...),
    articulocodigo: str = Form(...),
    articulofecha: str = Form(...),
    articulohora: str = Form(...),
    id_tipo: int = Form(...),
    id_proveedor: int = Form(...)
):
    try:
        ext = os.path.splitext(articuloimagen.filename)[1]
        nombre = f"{articulocodigo}_{datetime.now():%Y%m%d%H%M%S}{ext}"
        destino = ABS_ARTICULOS / nombre

        articuloimagen.file.seek(0)

        with destino.open("wb") as buffer:
            shutil.copyfileobj(articuloimagen.file, buffer)
        await articuloimagen.close()

        url_publica = f"/uploads/articulos/{nombre}"
        
        data = {
            "articulonombre": articulonombre,
            "articulostock": articulostock,
            "articulodescripcion": articulodescripcion,
            "articuloimagen": f"uploads/articulos/{nombre}",
            "articulocodigogener": articulocodigogener,
            "articulocodigo": articulocodigo,
            "articulofecha": articulofecha, 
            "articulohora": articulohora, 
            "id_tipo": id_tipo,
            "id_proveedor": id_proveedor,
        }

        print(f"Datos preparados para crear artículo: {data}")

        repo = ArticuloRepositoryImpl()
        articulo_creado = repo.crear(data)

        if not articulo_creado:
            raise HTTPException(status_code=400, detail="Error al crear el artículo")

        return {
            "id": articulo_creado['idarticulo'],
            "nombre": articulo_creado['articulonombre'],
            "stock": articulo_creado['articulostock'],
            "descripcion": articulo_creado['articulodescripcion'],
            "codigo": articulo_creado['articulocodigo'],
            "imagen_url": url_publica, 
            "imagen_path": articulo_creado['articuloimagen'],
            "fecha": articulo_creado['articulofecha'],
            "hora": articulo_creado['articulohora'],
            "id_tipo": articulo_creado['id_tipo'],
            "id_proveedor": articulo_creado['id_proveedor']
        }

    except Exception as e:
        print(f"Error al crear artículo: {e}")
        raise HTTPException(status_code=500, detail=f"Error interno: {str(e)}")