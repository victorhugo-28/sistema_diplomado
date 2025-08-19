"""
Excepción base del dominio
"""

class DomainException(Exception):
    """
    Excepción base para todas las excepciones del dominio.
    Todas las excepciones específicas del negocio deben heredar de esta clase.
    """
    
    def __init__(self, message: str, error_code: str = None, details: dict = None):
        self.message = message
        self.error_code = error_code or self.__class__.__name__
        self.details = details or {}
        super().__init__(self.message)
    
    def __str__(self):
        return f"{self.error_code}: {self.message}"
    
    def __repr__(self):
        return f"{self.__class__.__name__}(message='{self.message}', error_code='{self.error_code}')"
    
    def to_dict(self):
        """Convierte la excepción a diccionario para serialización"""
        return {
            'error_code': self.error_code,
            'message': self.message,
            'details': self.details
        }