from fastapi import FastAPI
from api.doctor import create
from api.doctor import list as list_doctors

app = FastAPI()

app.include_router(create.router)
app.include_router(list_doctors.router)

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)
