from fastapi import APIRouter, Depends
from contracts.doctor_dto import CreateDoctorRequest
from application.usecases.doctor.create_doctor import create_doctor
from infrastructure.repositories.doctor_repository_impl import DoctorRepository
from events.publisher import MessagePublisher
import logging

router = APIRouter()
logger = logging.getLogger("uvicorn")

@router.post("/api/doctors")
async def create(request: CreateDoctorRequest):
    repository = DoctorRepository()
    publisher = MessagePublisher()
    return await create_doctor(request, repository, publisher, logger)
