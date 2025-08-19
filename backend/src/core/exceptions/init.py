"""
Módulo de excepciones del dominio
"""

# Excepción base
from .base_exception import DomainException

# Excepciones específicas
from .business_exception import (
    BusinessException,
    InsufficientStockException,
    InvalidPriceException,
    InvalidQuantityException
)

from .validation_exception import (
    ValidationException,
    InvalidEmailException,
    InvalidDocumentException,
    RequiredFieldException
)

from .not_found_exception import (
    NotFoundException,
    ArticuloNotFoundException,
    ClienteNotFoundException,
    VentaNotFoundException
)

__all__ = [
    # Base
    'DomainException',
    
    # Business
    'BusinessException',
    'InsufficientStockException',
    'InvalidPriceException',
    'InvalidQuantityException',
    
    # Validation
    'ValidationException',
    'InvalidEmailException',
    'InvalidDocumentException',
    'RequiredFieldException',
    
    # Not Found
    'NotFoundException',
    'ArticuloNotFoundException',
    'ClienteNotFoundException',
    'VentaNotFoundException',
]