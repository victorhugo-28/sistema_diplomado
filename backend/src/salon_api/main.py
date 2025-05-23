from fastapi import FastAPI
from salon_api.endpoints.Usuario import List, Create, Update, Delete
from salon_api.endpoints.Cita import List as CitaList, Create as CitaCreate
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

#se incluyen los endpoints para usuario
app.include_router(List.router)
app.include_router(Create.router)  
app.include_router(Update.router)
app.include_router(Delete.router)
#se incluyen los endpoints para citas
app.include_router(CitaList.router)
app.include_router(CitaCreate.router)  

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)

