from models import DoctorModel
from database import SessionLocal
from domain.entities.doctor import Doctor
from sqlalchemy.future import select

class DoctorRepository:
    async def save(self, doctor: Doctor):
        async with SessionLocal() as session:
            db_doctor = DoctorModel(
                id=doctor.id,
                name=doctor.name,
                specialty=doctor.specialty,
                created_at=doctor.created_at
            )
            session.add(db_doctor)
            await session.commit()
            return doctor
    # Get all doctors
    async def get_all(self):
        async with SessionLocal() as session:
            result = await session.execute(select(DoctorModel))
            doctors = result.scalars().all()
            return [
                {
                    "id": d.id,
                    "name": d.name,
                    "specialty": d.specialty,
                    "created_at": d.created_at.isoformat(),
                }
                for d in doctors
            ]