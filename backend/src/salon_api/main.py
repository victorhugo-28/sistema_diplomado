from fastapi import FastAPI
from .endpoints.Usuario import List as list_users

app = FastAPI()

app.include_router(list_users.router)

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)
