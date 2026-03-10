'use client';

import { useState, useEffect } from 'react';
import { useSearchParams, useRouter } from 'next/navigation';
import DashboardLayout from '@/components/admin/DashboardLayout';
import styles from '@/app/assets/scss/admin/Editar.module.scss';

const tablas = [
    {
        titulo: "Categoría",
        entidad: "categoria",
        campos: [
            { key: 'nombre', label: 'Nombre', type: 'text', required: true }
        ]
    },
    {
        titulo: "País",
        entidad: "pais",
        campos: [
            { key: 'nombre', label: 'Nombre', type: 'text', required: true },
            { key: 'continente', label: 'Continente', type: 'text', required: false },
            { key: 'bandera', label: 'Bandera (emoji)', type: 'text', required: false }
        ]
    },
    {
        titulo: "Medio",
        entidad: "medio",
        campos: [
            { key: 'nombre', label: 'Nombre', type: 'text', required: true },
            { key: 'url', label: 'URL', type: 'url', required: false }
        ]
    },
    {
        titulo: "Noticia",
        entidad: "noticia",
        campos: [
            { key: 'titulo', label: 'Título', type: 'text', required: true },
            { key: 'url', label: 'URL', type: 'url', required: false },
            { key: 'texto_original', label: 'Texto Original', type: 'textarea', required: false },
            { key: 'texto_traducido', label: 'Texto Traducido', type: 'textarea', required: false },
            { key: 'pais_id', label: 'País', type: 'select', opciones: 'paises' },
            { key: 'medio_id', label: 'Medio', type: 'select', opciones: 'medios' },
            { key: 'path', label: 'Portada', type: 'file', required: false }
        ]
    },
    {
        titulo: "Usuario",
        entidad: "usuario",
        campos: [
            { key: 'nombre', label: 'Nombre', type: 'text', required: true },
            { key: 'email', label: 'Email', type: 'email', required: true },
            { key: 'password', label: 'Contraseña', type: 'password', required: true },
            { key: 'icono', label: 'Icono', type: 'file', required: false }
        ]
    },
    {
        titulo: "Operador",
        entidad: "administrador",
        campos: [
            { key: 'nombre', label: 'Nombre', type: 'text', required: true },
            { key: 'email', label: 'Email', type: 'email', required: true },
            { key: 'password', label: 'Contraseña', type: 'password', required: true },
            { key: 'rol', label: 'Rol', type: 'text', required: false },
            { key: 'icono', label: 'Icono', type: 'file', required: false }
        ]
    }
];

export default function NuevoPage() {
    const searchParams = useSearchParams();
    const router = useRouter();

    const tableParam = searchParams.get('table');
    const tablaConfig = tablas.find(t => t.entidad === tableParam);

    const [valores, setValores] = useState({});
    const [paises, setPaises] = useState([]);
    const [medios, setMedios] = useState([]);
    const [guardando, setGuardando] = useState(false);
    const [error, setError] = useState('');
    const [exito, setExito] = useState('');

    useEffect(() => {
        const cargarOpciones = async () => {
            const userData = JSON.parse(localStorage.getItem('user_session') || 'null');
            const [resPaises, resMedios] = await Promise.all([
                fetch('/backend/api/databases/gestionar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ entidad: 'pais', user_id: userData?.id })
                }),
                fetch('/backend/api/databases/gestionar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ entidad: 'medio', user_id: userData?.id })
                })
            ]);
            const dataPaises = await resPaises.json();
            const dataMedios = await resMedios.json();
            if (dataPaises.success) setPaises(dataPaises.data);
            if (dataMedios.success) setMedios(dataMedios.data);
        };

        cargarOpciones();
    }, []);

    const handleChange = (key, value) => {
        setValores(prev => ({ ...prev, [key]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setGuardando(true);
        setError('');

        const userData = JSON.parse(localStorage.getItem('user_session') || 'null');

        if (valores._file) {
            const formData = new FormData();
            formData.append('imagen', valores._file);
            formData.append('nombre', valores[valores._fileKey]);
            formData.append('entidad', tableParam);

            await fetch('/backend/api/handlers/subir_imagen.php', {
                method: 'POST',
                credentials: 'include',
                body: formData
            });
        }

        try {
            const res = await fetch('/backend/api/databases/insertar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({
                    entidad: tableParam,
                    valores,
                    user_id: userData?.id
                })
            });
            const data = await res.json();

            if (data.success) {
                setExito('Registro creado correctamente');
                setTimeout(() => {
                    router.push(`/admin/tabla?table=${tableParam}`);
                }, 2000);
            } else {
                setError(data.error || 'Error al insertar');
            }
        } catch {
            setError('Error de conexión con el servidor');
        } finally {
            setGuardando(false);
        }
    };

    if (!tablaConfig) {
        return (
            <DashboardLayout>
                <div className={styles.contenedor}>
                    <p>Tabla no encontrada</p>
                </div>
            </DashboardLayout>
        );
    }

    return (
        <DashboardLayout>
            <div className={styles.contenedor}>
                <div className={styles.cabecera}>
                    <h2>Nuevo {tablaConfig.titulo}</h2>
                    <button
                        onClick={() => router.push(`/admin/tabla?table=${tableParam}`)}
                        className={styles.btnVolver}
                    >
                        ← Volver
                    </button>
                </div>

                {error && <div className={styles.alertError}>{error}</div>}
                {exito && <div className={styles.alertExito}>{exito}</div>}

                <div className={styles.card}>
                    <form onSubmit={handleSubmit} className={styles.formulario}>
                        {tablaConfig.campos.map((campo) => (
                            <div key={campo.key} className={styles.inputGroup}>
                                <label htmlFor={campo.key}>{campo.label}</label>
                            {campo.type === 'select' ? (
                                <select
                                    id={campo.key}
                                    value={valores[campo.key] || ''}
                                    onChange={(e) => handleChange(campo.key, e.target.value)}
                                    className={styles.select}
                                >
                                    <option value="">Seleccionar...</option>
                                    {(campo.opciones === 'paises' ? paises : medios).map((op) => (
                                        <option key={op.id} value={op.id}>
                                            {op.titulo || op.nombre}
                                        </option>
                                    ))}
                                </select>
                            ) : campo.type === 'textarea' ? (
                                <textarea
                                    id={campo.key}
                                    value={valores[campo.key] || ''}
                                    onChange={(e) => handleChange(campo.key, e.target.value)}
                                    required={campo.required}
                                    rows={5}
                                />
                            ) : campo.type === 'file' ? (
                                    <input
                                        type="file"
                                        id={campo.key}
                                        accept="image/*"
                                        onChange={(e) => {
                                            const file = e.target.files[0];
                                            if (!file) return;

                                            const now = new Date();
                                            const timestamp = now.getFullYear().toString() +
                                                String(now.getMonth() + 1).padStart(2, '0') +
                                                String(now.getDate()).padStart(2, '0') + '_' +
                                                String(now.getHours()).padStart(2, '0') +
                                                String(now.getMinutes()).padStart(2, '0') +
                                                String(now.getSeconds()).padStart(2, '0');

                                            const extension = file.name.split('.').pop();
                                            const nuevoNombre = `${timestamp}.${extension}`;

                                            handleChange(campo.key, nuevoNombre);
                                            handleChange('_file', file);
                                            handleChange('_fileKey', campo.key);
                                        }}
                                    />
                            ) : (
                                <input
                                    type={campo.type}
                                    id={campo.key}
                                    value={valores[campo.key] || ''}
                                    onChange={(e) => handleChange(campo.key, e.target.value)}
                                    required={campo.required}
                                />
                            )}
                        </div>
                        ))}

                        <div className={styles.botones}>
                            <button
                                type="submit"
                                className={styles.btnGuardar}
                                disabled={guardando}
                            >
                                {guardando ? 'Guardando...' : 'Actualizar'}
                            </button>
                            <button
                                type="button"
                                onClick={() => router.push(`/admin/tabla?table=${tableParam}`)}
                                className={styles.btnCancelar}
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}