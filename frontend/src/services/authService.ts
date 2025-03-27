import axios from 'axios';
import { LoginResponse } from '../types';
import { API_URL } from '../config/index';

export interface RegisterResponse {
  token: string;
  user: {
    id: number;
    email: string;
    nombre: string;
  };
}

export const authService = {
  async login(email: string, password: string): Promise<LoginResponse> {
    const params = new URLSearchParams();
    params.append('username', email);
    params.append('password', password);

    const response = await axios.post<LoginResponse>(`${API_URL}/auth/login`, params, {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      withCredentials: true
    });

    // Para debug
    console.log('Respuesta completa:', response);
    console.log('Datos de la respuesta:', response.data);

    // Guardar el token si existe
    if (response.data.access_token) {
      localStorage.setItem('token', response.data.access_token);
    }

    return response.data;
  },

  async register(
    email: string,
    password: string,
    nombre: string
  ): Promise<RegisterResponse> {
    const response = await axios.post(`${API_URL}/auth/register`, {
      email,
      password,
      nombre,
    });

    // Hacer login automático después del registro
    await this.login(email, password);
    
    return response.data;
  },

  async getCurrentUser() {
    const token = localStorage.getItem('token');
    if (!token) return null;

    try {
      const response = await axios.get(`${API_URL}/auth/me`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      return response.data;
    } catch (error) {
      this.logout();
      return null;
    }
  },

  logout() {
    localStorage.removeItem('token');
    delete axios.defaults.headers.common['Authorization'];
  },

  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  },
}; 