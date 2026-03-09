'use client';

import Tabla from '@/components/admin/Tabla';
import { useSearchParams } from 'next/navigation';
import DashboardLayout from '@/components/admin/DashboardLayout';

const tablas = [
    {
        titulo: "Categorías",
        entidad: "categoria",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'nombre', label: 'Nombre' }
        ]
    },
    {
        titulo: "Países",
        entidad: "pais",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'nombre', label: 'Nombre' },
            { key: 'continente', label: 'Continente' },
            { key: 'bandera', label: 'Bandera' }
        ]
    },
    {
        titulo: "Medios",
        entidad: "medio",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'nombre', label: 'Nombre' },
            { key: 'url', label: 'URL' }
        ]
    },
    {
        titulo: "Noticias",
        entidad: "noticia",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'titulo', label: 'Título' },
            { key: 'texto_original', label: 'Texto Original' },
            { key: 'texto_traducido', label: 'Texto Traducido' },
            { key: 'pais_id', label: 'País' },
            { key: 'medio_id', label: 'Medio' },
            { key: 'path', label: 'Portada' },
            { key: 'publicado', label: 'Publicado' }
        ]
    },
    {
        titulo: "Usuarios",
        entidad: "usuario",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'nombre', label: 'Nombre' },
            { key: 'email', label: 'Email' }
        ]
    },
    {
        titulo: "Operadores",
        entidad: "administrador",
        data: [
            { key: 'id', label: 'ID', render: (fila) => `#${fila.id}` },
            { key: 'nombre', label: 'Nombre' },
            { key: 'email', label: 'Email' },
            { key: 'rol', label: 'Rol' },
            { key: 'icono', label: 'Icono' }
        ]
    }
];

export default function TablaPage() {
    const searchParams = useSearchParams();
    const tableParam = searchParams.get('table');

    const tablaConfig = tablas.find(t => t.entidad === tableParam);

    if (!tablaConfig) return <DashboardLayout><p>Tabla no encontrada</p></DashboardLayout>;

    return (
        <DashboardLayout>
            <Tabla
                titulo={`Gestión de ${tablaConfig.titulo}`}
                entidad={tablaConfig.entidad}
                columnas={tablaConfig.data}
                rutaNuevo={`/admin/tabla/nuevo?table=${tablaConfig.entidad}`}
                rutaEditar={`/admin/tabla/editar?table=${tablaConfig.entidad}`}
            />
        </DashboardLayout>
    );
}