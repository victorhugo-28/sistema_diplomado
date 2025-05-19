from pydantic import BaseModel

class CreateDoctorRequest(BaseModel):
    name: str
    specialty: str

class CreateDoctorResponse(BaseModel):
    id: str
    name: str
    specialty: str
