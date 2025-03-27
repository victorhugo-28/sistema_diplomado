import axios from 'axios';
import { Cita, CitaCreate, CitaUpdate } from '../types';
import { API_URL } from '../config/index';

// Configurar el interceptor para agregar el token a todas las peticiones
axios.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const citaService = {
  async obtenerCitas(): Promise<Cita[]> {
    const response = await axios.get(`${API_URL}/citas`);
    return response.data;
  },

  async obtenerCita(id: number): Promise<Cita> {
    const response = await axios.get(`${API_URL}/citas/${id}`);
    return response.data;
  },

  async crearCita(cita: CitaCreate): Promise<Cita> {
    const response = await axios.post(`${API_URL}/citas`, cita);
    return response.data;
  },

  async actualizarCita(id: number, cita: CitaUpdate): Promise<Cita> {
    const response = await axios.put(`${API_URL}/citas/${id}`, cita);
    return response.data;
  },

  async eliminarCita(id: number): Promise<void> {
    await axios.delete(`${API_URL}/citas/${id}`);
  },

  async cambiarEstadoCita(id: number, estado: string): Promise<Cita> {
    const response = await axios.patch(`${API_URL}/citas/${id}/estado`, {
      estado,
    });
    return response.data;
  },

  async listarCitas(
    skip: number = 0,
    limit: number = 100,
    estado?: string,
    fechaInicio?: string,
    fechaFin?: string
  ): Promise<Cita[]> {
    const params = new URLSearchParams({
      skip: skip.toString(),
      limit: limit.toString(),
      ...(estado && { estado }),
      ...(fechaInicio && { fecha_inicio: fechaInicio }),
      ...(fechaFin && { fecha_fin: fechaFin }),
    });

    const response = await axios.get(`${API_URL}/citas/?${params}`);
    return response.data;
  },

  async actualizarEstadoCita(id: number, estado: string): Promise<Cita> {
    const response = await axios.put(`${API_URL}/citas/${id}/estado?estado=${estado}`);
    return response.data;
  },

  async obtenerCitasProximas(dias: number = 7): Promise<Cita[]> {
    const response = await axios.get(`${API_URL}/citas/proximas/${dias}`);
    return response.data;
  },
}; 