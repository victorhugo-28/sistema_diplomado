from fastapi import APIRouter
from infrastructure.repositories.doctor_repository_impl import DoctorRepository

router = APIRouter()

@router.get("/api/doctors")
async def list_doctors():
    repository = DoctorRepository()
    return await repository.get_all()
