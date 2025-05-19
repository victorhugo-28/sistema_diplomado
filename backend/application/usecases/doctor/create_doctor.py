from domain.entities.doctor import Doctor
from contracts.doctor_dto import CreateDoctorResponse
from events.doctor_created_event import DoctorCreatedEvent

async def create_doctor(data, repository, event_publisher, logger):
    doctor = Doctor(name=data.name, specialty=data.specialty)
    await repository.save(doctor)

    event = DoctorCreatedEvent(doctor.id, doctor.name)
    event_publisher.publish(event)

    logger.info(f"Doctor created: {doctor.id}")
    return CreateDoctorResponse(id=doctor.id, name=doctor.name, specialty=doctor.specialty)
