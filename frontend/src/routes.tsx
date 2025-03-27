import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import { Navbar } from './components/Navbar';
import { Login } from './pages/Login';
import { Register } from './pages/Register';
import { Dashboard } from './pages/Dashboard';
import { ListaCitas } from './pages/ListaCitas';
import { DetalleCita } from './pages/DetalleCita';
import { NuevaCita } from './pages/NuevaCita';

const PrivateRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { isAuthenticated } = useAuth();
  return isAuthenticated ? <>{children}</> : <Navigate to="/login" />;
};

const AppRoutes: React.FC = () => {
  return (
    <AuthProvider>
      <Navbar />
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route
          path="/dashboard"
          element={
            <PrivateRoute>
              <Dashboard />
            </PrivateRoute>
          }
        />
        <Route
          path="/citas"
          element={
            <PrivateRoute>
              <ListaCitas />
            </PrivateRoute>
          }
        />
        <Route
          path="/citas/nueva"
          element={
            <PrivateRoute>
              <NuevaCita />
            </PrivateRoute>
          }
        />
        <Route
          path="/citas/:id"
          element={
            <PrivateRoute>
              <DetalleCita />
            </PrivateRoute>
          }
        />
        <Route path="/" element={<Navigate to="/dashboard" />} />
      </Routes>
    </AuthProvider>
  );
};

export default AppRoutes; 