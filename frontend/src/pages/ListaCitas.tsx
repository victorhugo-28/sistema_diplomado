import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import {
  Container,
  Paper,
  Typography,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Button,
  Box,
  CircularProgress,
  FormControl,
  Select,
  MenuItem
} from '@mui/material';
import AddIcon from '@mui/icons-material/Add';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import { citaService } from '../services/citaService';
import { Cita } from '../types';
import { EditarCita } from '../components/EditarCita';

export const ListaCitas: React.FC = () => {
  const navigate = useNavigate();
  const [citas, setCitas] = useState<Cita[]>([]);
  const [citaSeleccionada, setCitaSeleccionada] = useState<Cita | null>(null);
  const [editarDialogOpen, setEditarDialogOpen] = useState(false);
  const [loading, setLoading] = React.useState(true);
  const [error, setError] = React.useState<string | null>(null);

  const cargarCitas = async () => {
    try {
      const citasData = await citaService.obtenerCitas();
      setCitas(citasData);
    } catch (error) {
      setError('Error al cargar las citas. Por favor, intente nuevamente.');
      console.error('Error al cargar las citas:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    cargarCitas();
  }, []);

  const handleEditarClick = (cita: Cita) => {
    setCitaSeleccionada(cita);
    setEditarDialogOpen(true);
  };

  const handleEditarClose = () => {
    setEditarDialogOpen(false);
    setCitaSeleccionada(null);
  };

  const handleEditarSuccess = () => {
    cargarCitas();
    handleEditarClose();
  };

  const handleEliminarCita = async (citaId: number) => {
    if (window.confirm('¿Está seguro que desea eliminar esta cita?')) {
      try {
        await citaService.eliminarCita(citaId);
        // Recargar las citas después de eliminar
        cargarCitas();
      } catch (error) {
        console.error('Error al eliminar la cita:', error);
      }
    }
  };

  const handleCambiarEstado = async (citaId: number, nuevoEstado: string) => {
    try {
      await citaService.actualizarEstadoCita(citaId, nuevoEstado);
      cargarCitas(); // Recargar las citas para mostrar el nuevo estado
    } catch (error) {
      console.error('Error al actualizar el estado:', error);
    }
  };

  const formatearFecha = (fecha: string) => {
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', {
      weekday: 'long',
      day: 'numeric',
      month: 'long'
    });
  };

  if (loading) {
    return (
      <Container maxWidth="lg" sx={{ mt: 4, mb: 4, textAlign: 'center' }}>
        <CircularProgress />
      </Container>
    );
  }

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
        <Typography variant="h4">Mis Citas</Typography>
      </Box>

      <Box
        sx={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          mb: 3,
        }}
      >
        <Typography variant="h4" component="h1">
          Mis Citas
        </Typography>
        <Button
          variant="contained"
          color="primary"
          startIcon={<AddIcon />}
          onClick={() => navigate('/citas/nueva')}
        >
          Nueva Cita
        </Button>
      </Box>

      {error && (
        <Typography color="error" sx={{ mb: 2 }}>
          {error}
        </Typography>
      )}

      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Nombre</TableCell>
              <TableCell>Fecha</TableCell>
              <TableCell>Hora</TableCell>
              <TableCell>Servicio</TableCell>
              <TableCell>Estado</TableCell>
              <TableCell>Acciones</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {citas.length > 0 ? (
              citas.map((cita) => (
                <TableRow key={cita.id}>
                  <TableCell>{cita.nombre_cliente}</TableCell>
                  <TableCell>{formatearFecha(cita.fecha_hora)}</TableCell>
                  <TableCell>
                    {new Date(cita.fecha_hora).toLocaleTimeString('es-ES', {
                      hour: '2-digit',
                      minute: '2-digit'
                    })}
                  </TableCell>
                  <TableCell>{cita.servicio}</TableCell>
                  <TableCell>
                    <FormControl size="small">
                      <Select
                        value={cita.estado}
                        onChange={(e) => handleCambiarEstado(cita.id, e.target.value)}
                        sx={{ minWidth: 120 }}
                      >
                        <MenuItem value="pendiente">Pendiente</MenuItem>
                        <MenuItem value="confirmada">Confirmada</MenuItem>
                        <MenuItem value="cancelada">Cancelada</MenuItem>
                        <MenuItem value="completada">Completada</MenuItem>
                      </Select>
                    </FormControl>
                  </TableCell>
                  <TableCell>
                    <Box sx={{ display: 'flex', gap: 1 }}>
                      <Button
                        size="small"
                        onClick={() => handleEditarClick(cita)}
                      >
                        Editar
                      </Button>
                      <Button
                        size="small"
                        color="error"
                        onClick={() => handleEliminarCita(cita.id)}
                      >
                        Eliminar
                      </Button>
                      <Button
                        size="small"
                        component={Link}
                        to={`/citas/${cita.id}`}
                      >
                        Ver Detalles
                      </Button>
                    </Box>
                  </TableCell>
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={5} align="center">
                  No tienes citas programadas
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </TableContainer>

      <EditarCita
        cita={citaSeleccionada}
        open={editarDialogOpen}
        onClose={handleEditarClose}
        onSuccess={handleEditarSuccess}
      />
    </Container>
  );
}; 