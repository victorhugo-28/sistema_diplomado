# models.py

from sqlalchemy import Column, String, DateTime
from database import Base
from datetime import datetime

class DoctorModel(Base):
    __tablename__ = "doctors"

    id = Column(String, primary_key=True, index=True)
    name = Column(String, nullable=False)
    specialty = Column(String, nullable=False)
    created_at = Column(DateTime, default=datetime.utcnow)
