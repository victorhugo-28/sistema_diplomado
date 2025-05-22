from pydantic import BaseModel

class UserCreatedEvent(BaseModel):
    id: int
    name: str
    email: str
