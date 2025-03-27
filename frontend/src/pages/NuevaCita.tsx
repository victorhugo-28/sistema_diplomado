import React from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Container, Typography, Box, Button } from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import { CitaForm } from '../components/CitaForm';
import { CitaCreate, CitaUpdate } from '../types';
import { citaService } from '../services/citaService';

export const NuevaCita: React.FC = () => {
  const navigate = useNavigate();

  const handleSubmit = async (values: CitaCreate | CitaUpdate) => {
    try {
      if ('nombre_cliente' in values) {
        await citaService.crearCita(values as CitaCreate);
        navigate('/citas');
      }
    } catch (error) {
      console.error('Error al crear la cita:', error);
    }
  };

  const initialValues = {
    nombre_cliente: '',
    fecha_hora: '',
    duracion_minutos: 30,
    telefono: '',
    email: '',
    servicio: '',
    notas: ''  // Aseguramos que notas siempre sea string
  };

  return (
    <Container>
      <Box sx={{ display: 'flex', alignItems: 'center', mt: 3, mb: 4 }}>
        <Button
          component={Link}
          to="/dashboard"
          startIcon={<ArrowBackIcon />}
          sx={{ mr: 3 }}
        >
          Volver al Inicio
        </Button>
        <Typography variant="h4">Nueva Cita</Typography>
      </Box>

      <CitaForm
        initialValues={initialValues}
        onSubmit={handleSubmit}
        submitButtonText="Crear Cita"
      />
    </Container>
  );
}; 