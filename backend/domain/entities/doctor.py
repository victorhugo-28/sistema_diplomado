from uuid import uuid4
from datetime import datetime

class Doctor:
    def __init__(self, name: str, specialty: str):
        self.id = str(uuid4())
        self.name = name
        self.specialty = specialty
        self.created_at = datetime.utcnow()
