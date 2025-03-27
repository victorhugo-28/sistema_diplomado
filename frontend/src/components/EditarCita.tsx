import React from 'react';
import { Dialog, DialogTitle, DialogContent } from '@mui/material';
import { CitaForm } from './CitaForm';
import { Cita } from '../types';
import { citaService } from '../services/citaService';

interface EditarCitaProps {
  cita: Cita | null;
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export const EditarCita: React.FC<EditarCitaProps> = ({ cita, open, onClose, onSuccess }) => {
  const handleSubmit = async (values: any) => {
    try {
      if (cita) {
        await citaService.actualizarCita(cita.id, {
          ...values,
          fecha_hora: new Date(values.fecha_hora).toISOString()
        });
        onSuccess();
        onClose();
      }
    } catch (error) {
      console.error('Error al actualizar la cita:', error);
    }
  };

  return (
    <Dialog open={open} onClose={onClose} maxWidth="sm" fullWidth>
      <DialogTitle>Editar Cita</DialogTitle>
      <DialogContent>
        {cita && (
          <CitaForm
            initialValues={{
              nombre_cliente: cita.nombre_cliente,
              fecha_hora: cita.fecha_hora,
              duracion_minutos: cita.duracion_minutos,
              telefono: cita.telefono,
              email: cita.email,
              servicio: cita.servicio,
              notas: cita.notas || ''
            }}
            onSubmit={handleSubmit}
            submitButtonText="Actualizar Cita"
          />
        )}
      </DialogContent>
    </Dialog>
  );
}; 