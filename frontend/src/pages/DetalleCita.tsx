import React, { useState, useEffect, useCallback } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import {
  Container,
  Typography,
  Grid,
  Paper,
  Button,
  Box,
  CircularProgress,
} from '@mui/material';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Cita } from '../types';
import { citaService } from '../services/citaService';
import { EditarCita } from '../components/EditarCita';

export const DetalleCita: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [cita, setCita] = useState<Cita | null>(null);
  const [loading, setLoading] = useState(true);
  const [editarDialogOpen, setEditarDialogOpen] = useState(false);

  const cargarCita = useCallback(async () => {
    try {
      if (id) {
        const citaData = await citaService.obtenerCita(parseInt(id));
        setCita(citaData);
      }
    } catch (error) {
      console.error('Error al cargar la cita:', error);
    } finally {
      setLoading(false);
    }
  }, [id]);

  useEffect(() => {
    cargarCita();
  }, [cargarCita]);

  const handleEliminar = async () => {
    if (cita && window.confirm('¿Está seguro de que desea eliminar esta cita?')) {
      try {
        await citaService.eliminarCita(cita.id);
        navigate('/citas');
      } catch (err) {
        console.error('Error al eliminar la cita:', err);
      }
    }
  };

  const handleEditarClick = () => {
    setEditarDialogOpen(true);
  };

  const handleEditarClose = () => {
    setEditarDialogOpen(false);
  };

  const handleEditarSuccess = () => {
    cargarCita();
    handleEditarClose();
  };

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="60vh">
        <CircularProgress />
      </Box>
    );
  }

  if (!cita) {
    return (
      <Container>
        <Typography color="error" variant="h6">
          Cita no encontrada
        </Typography>
      </Container>
    );
  }

  return (
    <Container>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Typography variant="h4" component="h1">
          Detalles de la Cita
        </Typography>
        <Box>
          <Button
            variant="outlined"
            color="primary"
            onClick={handleEditarClick}
            sx={{ mr: 2 }}
          >
            Editar
          </Button>
          <Button
            variant="outlined"
            color="error"
            onClick={handleEliminar}
          >
            Eliminar
          </Button>
        </Box>
      </Box>

      <Grid container spacing={3}>
        <Grid item xs={12} md={6}>
          <Paper sx={{ p: 3 }}>
            <Typography variant="h6" gutterBottom>
              Información del Cliente
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Nombre:</strong> {cita.nombre_cliente}
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Email:</strong> {cita.email}
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Teléfono:</strong> {cita.telefono}
            </Typography>
          </Paper>
        </Grid>

        <Grid item xs={12} md={6}>
          <Paper sx={{ p: 3 }}>
            <Typography variant="h6" gutterBottom>
              Detalles de la Cita
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Fecha:</strong>{' '}
              {format(new Date(cita.fecha_hora), 'EEEE, d de MMMM', {
                locale: es,
              })}
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Hora:</strong>{' '}
              {format(new Date(cita.fecha_hora), 'HH:mm', {
                locale: es,
              })}
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Servicio:</strong> {cita.servicio}
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Duración:</strong> {cita.duracion_minutos} minutos
            </Typography>
            <Typography variant="body1" gutterBottom>
              <strong>Estado:</strong>{' '}
              <span style={{ color: cita.estado === 'confirmada' ? 'green' : 'orange' }}>
                {cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1)}
              </span>
            </Typography>
          </Paper>
        </Grid>

        {cita.notas && (
          <Grid item xs={12}>
            <Paper sx={{ p: 3 }}>
              <Typography variant="h6" gutterBottom>
                Notas
              </Typography>
              <Typography variant="body1">{cita.notas}</Typography>
            </Paper>
          </Grid>
        )}
      </Grid>

      <Box sx={{ mt: 2 }}>
        <Button
          variant="outlined"
          color="primary"
          onClick={() => navigate(-1)}
          sx={{ mr: 1 }}
        >
          Volver
        </Button>
      </Box>

      <EditarCita
        cita={cita}
        open={editarDialogOpen}
        onClose={handleEditarClose}
        onSuccess={handleEditarSuccess}
      />
    </Container>
  );
}; 