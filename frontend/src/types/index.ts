export interface Cita {
  id: number;
  nombre_cliente: string;
  fecha_hora: string;
  duracion_minutos: number;
  telefono: string;
  email: string;
  servicio: string;
  estado: string;
  notas: string;
}

export interface CitaCreate {
  nombre_cliente: string;
  fecha_hora: string;
  duracion_minutos: number;
  telefono: string;
  email: string;
  servicio: string;
  notas?: string;
}

export interface CitaUpdate {
  nombre_cliente?: string;
  fecha_hora?: string;
  duracion_minutos?: number;
  telefono?: string;
  email?: string;
  servicio?: string;
  estado?: string;
  notas?: string;
}

export interface User {
  id: number;
  email: string;
  nombre: string;
}

export interface AuthResponse {
  access_token: string;
  token_type: string;
  user: User;
}

export interface LoginFormValues {
  email: string;
  password: string;
}

// Esta es la respuesta que devuelve el backend según router.py
export interface LoginResponse {
  access_token: string;
  token_type: string;
  user: User;
}

// Cambiamos de type a interface para mejor compatibilidad
export interface RegisterResponse {
  id: number;
  email: string;
  nombre: string;
} 