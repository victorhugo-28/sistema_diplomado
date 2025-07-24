from fastapi import FastAPI
from salon_api.endpoints.Usuario import List, Create, Update, Delete
from salon_api.endpoints.Cita import List as CitaList, Create as CitaCreate
from fastapi.middleware.cors import CORSMiddleware
from salon_api.endpoints.Proveedor import List as ProveedorList, Create as ProveedorCreate, Update as ProveedorUpdate, Delete as ProveedorDelete
from salon_api.endpoints.TipoArticulo import List as TipoArticuloList, Create as TipoArticuloCreate, Update as TipoArticuloUpdate, Delete as TipoArticuloDelete
from salon_api.endpoints.Articulo import List as ArticuloList, Create as ArticuloCreate, Update as ArticuloUpdate, Delete as ArticuloDelete
from salon_api.endpoints.Rol import List as RolList, Create as RolCreate, Update as RolUpdate, Delete as RolDelete
from salon_api.endpoints.Compra import List as CompraList, Create as CompraCreate, Update as CompraUpdate, Delete as CompraDelete
from salon_api.endpoints.Cliente import List as ClienteList, Create as ClienteCreate, Update as ClienteUpdate, Delete as ClienteDelete
from salon_api.endpoints.Venta import List as VentaList, Create as VentaCreate, Update as VentaUpdate, Delete as VentaDelete
app = FastAPI()

#se incluyen los endpoints para usuario
app.include_router(List.router)
app.include_router(Create.router)  
app.include_router(Update.router)
app.include_router(Delete.router)
#se incluyen los endpoints para citas
#app.include_router(CitaList.router)
#app.include_router(CitaCreate.router)  

#se incluyen los endpoints para roles
app.include_router(RolList.router)
app.include_router(RolCreate.router)
app.include_router(RolUpdate.router)
app.include_router(RolDelete.router)

#se incluyen los endpoints para proveedores
app.include_router(ProveedorList.router)
app.include_router(ProveedorCreate.router)
app.include_router(ProveedorUpdate.router)
app.include_router(ProveedorDelete.router)

#se incluyen los endpoints para tipo_articulo
app.include_router(TipoArticuloList.router)
app.include_router(TipoArticuloCreate.router)
app.include_router(TipoArticuloUpdate.router)
app.include_router(TipoArticuloDelete.router)

#se incluyen los endpoints para articulos
app.include_router(ArticuloList.router)
app.include_router(ArticuloCreate.router)
app.include_router(ArticuloUpdate.router)
app.include_router(ArticuloDelete.router)

#se incluyen los endpoints para compras
app.include_router(CompraList.router)
app.include_router(CompraCreate.router)
app.include_router(CompraUpdate.router)
app.include_router(CompraDelete.router)


app.include_router(ClienteList.router)
app.include_router(ClienteCreate.router)
app.include_router(ClienteUpdate.router)
app.include_router(ClienteDelete.router)

#se incluyen los endpoints para ventas
app.include_router(VentaList.router)
app.include_router(VentaCreate.router)
app.include_router(VentaUpdate.router)
app.include_router(VentaDelete.router)
# Configuración de CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
if __name__ == "__main__":
    import uvicorn
    uvicorn.run("salon_api.main:app", host="127.0.0.1", port=8000, reload=True)


