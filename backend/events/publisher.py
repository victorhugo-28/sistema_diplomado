class MessagePublisher:
    def publish(self, event):
        print(f"Event published: {event.__class__.__name__}, doctor_id={event.doctor_id}")
