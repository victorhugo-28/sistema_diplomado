class DoctorCreatedEvent:
    def __init__(self, doctor_id, name):
        self.doctor_id = doctor_id
        self.name = name
