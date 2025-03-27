import React from 'react';
import { TextField, Button, Grid, Box } from '@mui/material';
import { useFormik } from 'formik';
import * as Yup from 'yup';

interface CitaFormProps {
  initialValues?: {
    nombre_cliente: string;
    fecha_hora: string;
    duracion_minutos: number;
    telefono: string;
    email: string;
    servicio: string;
    notas: string;
  };
  onSubmit: (values: any) => void;
  submitButtonText?: string;
}

export const CitaForm: React.FC<CitaFormProps> = ({ 
  initialValues,
  onSubmit,
  submitButtonText = "Crear Cita"
}) => {
  const formik = useFormik({
    initialValues: initialValues || {
      nombre_cliente: '',
      fecha_hora: '',
      duracion_minutos: 30,
      telefono: '',
      email: '',
      servicio: '',
      notas: ''
    },
    validationSchema: Yup.object({
      nombre_cliente: Yup.string().required('El nombre es requerido'),
      fecha_hora: Yup.string().required('La fecha y hora son requeridas'),
      duracion_minutos: Yup.number().required('La duración es requerida'),
      telefono: Yup.string().required('El teléfono es requerido'),
      email: Yup.string().email('Email inválido').required('El email es requerido'),
      servicio: Yup.string().required('El servicio es requerido'),
      notas: Yup.string()
    }),
    onSubmit: onSubmit
  });

  return (
    <form onSubmit={formik.handleSubmit}>
      <Grid container spacing={2}>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="nombre_cliente"
            name="nombre_cliente"
            label="Nombre del Cliente"
            value={formik.values.nombre_cliente}
            onChange={formik.handleChange}
            error={formik.touched.nombre_cliente && Boolean(formik.errors.nombre_cliente)}
            helperText={formik.touched.nombre_cliente && formik.errors.nombre_cliente}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="fecha_hora"
            name="fecha_hora"
            label="Fecha y Hora"
            type="datetime-local"
            value={formik.values.fecha_hora}
            onChange={formik.handleChange}
            error={formik.touched.fecha_hora && Boolean(formik.errors.fecha_hora)}
            helperText={formik.touched.fecha_hora && formik.errors.fecha_hora}
            InputLabelProps={{ shrink: true }}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="duracion_minutos"
            name="duracion_minutos"
            label="Duración (minutos)"
            type="number"
            value={formik.values.duracion_minutos}
            onChange={formik.handleChange}
            error={formik.touched.duracion_minutos && Boolean(formik.errors.duracion_minutos)}
            helperText={formik.touched.duracion_minutos && formik.errors.duracion_minutos}
            InputProps={{ inputProps: { min: 15, max: 480 } }}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="telefono"
            name="telefono"
            label="Teléfono"
            value={formik.values.telefono}
            onChange={formik.handleChange}
            error={formik.touched.telefono && Boolean(formik.errors.telefono)}
            helperText={formik.touched.telefono && formik.errors.telefono}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="email"
            name="email"
            label="Email"
            value={formik.values.email}
            onChange={formik.handleChange}
            error={formik.touched.email && Boolean(formik.errors.email)}
            helperText={formik.touched.email && formik.errors.email}
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <TextField
            fullWidth
            id="servicio"
            name="servicio"
            label="Servicio"
            value={formik.values.servicio}
            onChange={formik.handleChange}
            error={formik.touched.servicio && Boolean(formik.errors.servicio)}
            helperText={formik.touched.servicio && formik.errors.servicio}
          />
        </Grid>
        <Grid item xs={12}>
          <TextField
            fullWidth
            id="notas"
            name="notas"
            label="Notas"
            multiline
            rows={4}
            value={formik.values.notas}
            onChange={formik.handleChange}
            error={formik.touched.notas && Boolean(formik.errors.notas)}
            helperText={formik.touched.notas && formik.errors.notas}
          />
        </Grid>
      </Grid>
      <Box sx={{ mt: 2 }}>
        <Button type="submit" variant="contained" color="primary">
          {submitButtonText}
        </Button>
      </Box>
    </form>
  );
}; 