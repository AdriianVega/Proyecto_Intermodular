'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import styles from '@/app/assets/scss/admin/Tabla.module.scss';

export default function Tabla({ titulo, entidad, columnas, rutaInsertar, rutaEditar }) {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [paginaActual, setPaginaActual] = useState(1);
    const registrosPorPagina = 15;

    const userData = JSON.parse(localStorage.getItem('user_session') || 'null');
    const esAdmin = userData?.rol == 1;

    useEffect(() => {
        setPaginaActual(1);
        cargarDatos();
    }, [entidad]);

    const cargarDatos = async () => {
        setLoading(true);
        const userData = JSON.parse(localStorage.getItem('user_session') || 'null');
        try {
            const response = await fetch('/backend/api/databases/gestionar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ entidad, user_id: userData?.id })
            });
            const resData = await response.json();
            setData(resData.data);
        } catch (err) {
            setError('Error de conexión con el servidor');
        } finally {
            setLoading(false);
        }
    };

    const handleEliminar = async (id) => {
        if (!window.confirm(`¿Estás seguro de eliminar este registro de ${titulo}?`)) return;
        try {
            const response = await fetch('/backend/api/databases/eliminar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ entidad, id, user_id: JSON.parse(localStorage.getItem('user_session') || 'null')?.id })
            });
            const resData = await response.json();
            if (resData.success) {
                setSuccess('Registro eliminado correctamente');
                setTimeout(() => setSuccess(''), 10000);
                cargarDatos();
            } else {
                alert(resData.error || 'Error al eliminar');
            }
        } catch (err) {
            alert('Error de conexión al intentar eliminar');
        }
    };

    const totalPaginas = Math.ceil(data.length / registrosPorPagina);
    const inicio = (paginaActual - 1) * registrosPorPagina;
    const datosPagina = data.slice(inicio, inicio + registrosPorPagina);

    return (
        <div className={styles.tablaContenedor}>
            <div className={styles.cabecera}>
                <h2>{titulo}</h2>
                <Link href={rutaInsertar} className={styles.btnNuevo}>
                    + Nuevo Registro
                </Link>
            </div>

            {error && <div className={styles.alertError}>{error}</div>}
            {success && <div className={styles.alertSuccess}>{success}</div>}

            <div className={styles.cardTabla}>
                {loading ? (
                    <p className={styles.loading}>Cargando datos...</p>
                ) : (
                    <>
                        <table className={styles.tabla}>
                            <thead>
                                <tr>
                                    {columnas.map((col, index) => (
                                        <th key={index}>{col.label}</th>
                                    ))}
                                    <th className={styles.textEnd}>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.length > 0 ? (
                                    datosPagina.map((fila) => (
                                        <tr key={fila.id}>
                                            {columnas.map((col, index) => (
                                                <td key={index}>
                                                    {col.render ? col.render(fila) : fila[col.key]}
                                                </td>
                                            ))}
                                            <td className={styles.acciones}>
                                                <div className={styles.accionesInner}>
                                                    {esAdmin && (
                                                        <Link href={`${rutaEditar}&id=${fila.id}`} className={styles.btnEditar}>
                                                            Editar
                                                        </Link>
                                                    )}
                                                    {esAdmin && !(entidad === 'administrador' && fila.rol == 1) && (
                                                        <button
                                                            onClick={() => handleEliminar(fila.id)}
                                                            className={styles.btnEliminar}
                                                        >
                                                            Borrar
                                                        </button>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={columnas.length + 1} className={styles.sinDatos}>
                                            No hay registros disponibles.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>

                        {totalPaginas > 1 && (
                            <div className={styles.paginacion}>
                                <button
                                    onClick={() => setPaginaActual(p => Math.max(p - 1, 1))}
                                    disabled={paginaActual === 1}
                                    className={styles.btnPagina}
                                >
                                    ← Anterior
                                </button>
                                <span className={styles.paginaInfo}>
                                    Página {paginaActual} de {totalPaginas}
                                </span>
                                <button
                                    onClick={() => setPaginaActual(p => Math.min(p + 1, totalPaginas))}
                                    disabled={paginaActual === totalPaginas}
                                    className={styles.btnPagina}
                                >
                                    Siguiente →
                                </button>
                            </div>
                        )}
                    </>
                )}
            </div>
        </div>
    );
}