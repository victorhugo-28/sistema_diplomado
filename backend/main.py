from fastapi import FastAPI, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from typing import List, Optional
from datetime import datetime, timedelta
from fastapi.middleware.cors import CORSMiddleware

from . import models, schemas
from .database import engine, get_db
from .auth import router as auth_router
from .auth.utils import get_current_user

# Crear las tablas en la base de datos
models.Base.metadata.create_all(bind=engine)

app = FastAPI(title="Sistema de Gestión de Citas", 
              description="API para gestionar citas de clientes",
              version="1.0.0")
# Configurar CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:3000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
    expose_headers=["*"],
    max_age=3600,
)


# Incluir el router de autenticación
app.include_router(auth_router.router)

@app.post("/citas/", response_model=schemas.CitaResponse, status_code=201, tags=["citas"])
def crear_cita(cita: schemas.CitaCreate, db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Crear una nueva cita en el sistema"""
    db_cita = models.Cita(**cita.dict())
    db.add(db_cita)
    db.commit()
    db.refresh(db_cita)
    return db_cita

@app.get("/citas/", response_model=List[schemas.CitaResponse], tags=["citas"])
def listar_citas(
    skip: int = 0, 
    limit: int = 100, 
    estado: Optional[str] = None,
    fecha_inicio: Optional[datetime] = None,
    fecha_fin: Optional[datetime] = None,
    db: Session = Depends(get_db),
    current_user = Depends(get_current_user)
):
    """Listar todas las citas con filtros opcionales"""
    query = db.query(models.Cita)
    
    if estado:
        query = query.filter(models.Cita.estado == estado)
    
    if fecha_inicio:
        query = query.filter(models.Cita.fecha_hora >= fecha_inicio)
    
    if fecha_fin:
        query = query.filter(models.Cita.fecha_hora <= fecha_fin)
    
    return query.offset(skip).limit(limit).all()

@app.get("/citas/{cita_id}", response_model=schemas.CitaResponse, tags=["citas"])
def obtener_cita(cita_id: int, db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Obtener una cita por su ID"""
    db_cita = db.query(models.Cita).filter(models.Cita.id == cita_id).first()
    if db_cita is None:
        raise HTTPException(status_code=404, detail="Cita no encontrada")
    return db_cita

@app.put("/citas/{cita_id}", response_model=schemas.CitaResponse, tags=["citas"])
def actualizar_cita(cita_id: int, cita: schemas.CitaUpdate, db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Actualizar una cita existente"""
    db_cita = db.query(models.Cita).filter(models.Cita.id == cita_id).first()
    if db_cita is None:
        raise HTTPException(status_code=404, detail="Cita no encontrada")
    
    # Actualizar solo los campos proporcionados
    cita_data = cita.dict(exclude_unset=True)
    for key, value in cita_data.items():
        setattr(db_cita, key, value)
    
    db.commit()
    db.refresh(db_cita)
    return db_cita

@app.delete("/citas/{cita_id}", status_code=204, tags=["citas"])
def eliminar_cita(cita_id: int, db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Eliminar una cita"""
    db_cita = db.query(models.Cita).filter(models.Cita.id == cita_id).first()
    if db_cita is None:
        raise HTTPException(status_code=404, detail="Cita no encontrada")
    
    db.delete(db_cita)
    db.commit()
    return None

@app.get("/citas/proximas/{dias}", response_model=List[schemas.CitaResponse], tags=["citas"])
def obtener_citas_proximas(dias: int = 7, db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Obtener citas programadas para los próximos días"""
    fecha_actual = datetime.now()
    fecha_limite = fecha_actual + timedelta(days=dias)
    
    return db.query(models.Cita).filter(
        models.Cita.fecha_hora >= fecha_actual,
        models.Cita.fecha_hora <= fecha_limite,
        models.Cita.estado != "cancelada"
    ).order_by(models.Cita.fecha_hora).all()

@app.put("/citas/{cita_id}/estado", response_model=schemas.CitaResponse, tags=["citas"])
def actualizar_estado_cita(cita_id: int, estado: str = Query(..., description="Nuevo estado de la cita"), db: Session = Depends(get_db), current_user = Depends(get_current_user)):
    """Actualizar el estado de una cita (pendiente, confirmada, cancelada, completada)"""
    estados_validos = ["pendiente", "confirmada", "cancelada", "completada"]
    if estado not in estados_validos:
        raise HTTPException(status_code=400, detail=f"Estado no válido. Debe ser uno de: {', '.join(estados_validos)}")
    
    db_cita = db.query(models.Cita).filter(models.Cita.id == cita_id).first()
    if db_cita is None:
        raise HTTPException(status_code=404, detail="Cita no encontrada")
    
    db_cita.estado = estado
    db.commit()
    db.refresh(db_cita)
    return db_cita