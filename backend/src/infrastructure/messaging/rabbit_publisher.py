import pika
import json

class RabbitPublisher:
    def __init__(self, host='localhost'):
        self.connection = pika.BlockingConnection(pika.ConnectionParameters(host))
        self.channel = self.connection.channel()

    def publish(self, queue, message):
        self.channel.queue_declare(queue=queue)
        self.channel.basic_publish(
            exchange='',
            routing_key=queue,
            body=json.dumps(message)
        )
