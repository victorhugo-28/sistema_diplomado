import React, { useEffect, useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import {
  Container,
  Grid,
  Paper,
  Typography,
  Button,
  Box,
  Card,
  CardContent,
  CircularProgress,
  FormControl,
  Select,
  MenuItem
} from '@mui/material';
import AddIcon from '@mui/icons-material/Add';
import EventIcon from '@mui/icons-material/Event';
import { useAuth } from '../contexts/AuthContext';
import { citaService } from '../services/citaService';
import { Cita } from '../types';
import { EditarCita } from '../components/EditarCita';

export const Dashboard: React.FC = () => {
  const navigate = useNavigate();
  const { user } = useAuth();
  const [citas, setCitas] = useState<Cita[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [citaSeleccionada, setCitaSeleccionada] = useState<Cita | null>(null);
  const [editarDialogOpen, setEditarDialogOpen] = useState(false);

  const cargarCitas = async () => {
    try {
      const citasData = await citaService.obtenerCitasProximas();
      setCitas(citasData);
    } catch (error) {
      setError('Error al cargar las citas');
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    cargarCitas();
  }, []);

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="60vh">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Container>
        <Typography color="error" variant="h6">
          {error}
        </Typography>
      </Container>
    );
  }

  const citasPendientes = citas.filter((cita) => cita.estado === 'pendiente').length;
  const citasConfirmadas = citas.filter((cita) => cita.estado === 'confirmada').length;

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
      cargarCitas(); // Recargar las citas
    } catch (error) {
      console.error('Error al actualizar el estado:', error);
    }
  };

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      <Grid container spacing={3}>
        <Grid item xs={12}>
          <Box
            sx={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              mb: 3,
            }}
          >
            <Typography variant="h4" component="h1">
              Bienvenido, {user?.nombre}
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
        </Grid>

        <Grid item xs={12} md={8}>
          <Paper sx={{ p: 2 }}>
            <Typography variant="h6" gutterBottom>
              Próximas Citas
            </Typography>
            {citas.length === 0 ? (
              <Typography color="text.secondary">
                No hay citas programadas para los próximos días
              </Typography>
            ) : (
              citas.map((cita) => (
                <Card key={cita.id} sx={{ mb: 2 }}>
                  <CardContent>
                    <Typography variant="h6">{cita.nombre_cliente}</Typography>
                    <Typography>
                      {`${cita.fecha_hora}`}
                    </Typography>
                    <Box sx={{ display: 'flex', alignItems: 'center', mt: 2 }}>
                      <FormControl size="small" sx={{ minWidth: 120, mr: 2 }}>
                        <Select
                          value={cita.estado}
                          onChange={(e) => handleCambiarEstado(cita.id, e.target.value)}
                        >
                          <MenuItem value="pendiente">Pendiente</MenuItem>
                          <MenuItem value="confirmada">Confirmada</MenuItem>
                          <MenuItem value="cancelada">Cancelada</MenuItem>
                          <MenuItem value="completada">Completada</MenuItem>
                        </Select>
                      </FormControl>

                      <Button
                        variant="outlined"
                        color="primary"
                        onClick={() => handleEditarClick(cita)}
                        sx={{ mr: 1 }}
                      >
                        Editar
                      </Button>
                      <Button
                        variant="outlined"
                        color="error"
                        onClick={() => handleEliminarCita(cita.id)}
                      >
                        Eliminar
                      </Button>
                    </Box>
                  </CardContent>
                </Card>
              ))
            )}
          </Paper>
        </Grid>

        <Grid item xs={12} md={4}>
          <Paper sx={{ p: 2 }}>
            <Typography variant="h6" gutterBottom>
              Resumen
            </Typography>
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
              <EventIcon sx={{ mr: 1 }} />
              <Typography>
                Citas Pendientes: {citasPendientes}
              </Typography>
            </Box>
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
              <EventIcon sx={{ mr: 1 }} />
              <Typography>
                Citas Confirmadas: {citasConfirmadas}
              </Typography>
            </Box>
            <Button
              variant="outlined"
              fullWidth
              onClick={() => navigate('/citas')}
              sx={{ mt: 2 }}
            >
              Ver Todas las Citas
            </Button>
          </Paper>
        </Grid>
      </Grid>

      <EditarCita
        cita={citaSeleccionada}
        open={editarDialogOpen}
        onClose={handleEditarClose}
        onSuccess={handleEditarSuccess}
      />
    </Container>
  );
}; 