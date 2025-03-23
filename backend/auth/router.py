from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlalchemy.orm import Session

from ..database import get_db
from . import schemas, models, utils

router = APIRouter(prefix="/auth", tags=["autenticación"])

@router.post("/register", response_model=schemas.UsuarioResponse, status_code=status.HTTP_201_CREATED)
def registrar_usuario(usuario: schemas.UsuarioCreate, db: Session = Depends(get_db)):
    # Verificar si el email ya existe
    db_usuario = db.query(models.Usuario).filter(models.Usuario.email == usuario.email).first()
    if db_usuario:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="El email ya está registrado"
        )
    
    # Crear nuevo usuario con contraseña hasheada
    hashed_password = utils.get_password_hash(usuario.password)
    db_usuario = models.Usuario(
        email=usuario.email,
        nombre=usuario.nombre,
        hashed_password=hashed_password
    )
    
    db.add(db_usuario)
    db.commit()
    db.refresh(db_usuario)
    
    return db_usuario

@router.post("/login", response_model=schemas.Token)
def login(form_data: OAuth2PasswordRequestForm = Depends(), db: Session = Depends(get_db)):
    # Buscar usuario por email
    usuario = db.query(models.Usuario).filter(models.Usuario.email == form_data.username).first()
    
    # Verificar si el usuario existe y la contraseña es correcta
    if not usuario or not utils.verificar_password(form_data.password, usuario.hashed_password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credenciales incorrectas",
            headers={"WWW-Authenticate": "Bearer"}
        )
    
    # Verificar si el usuario está activo
    if not usuario.es_activo:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Usuario inactivo"
        )
    
    # Crear token de acceso
    access_token = utils.crear_access_token(
        data={"sub": usuario.email, "id": usuario.id}
    )
    
    return {"access_token": access_token, "token_type": "bearer"}

@router.get("/me", response_model=schemas.UsuarioResponse)
def obtener_usuario_actual(current_user = Depends(utils.get_current_user)):
    return current_user