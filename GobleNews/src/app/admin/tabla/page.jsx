'use client';

import { useEffect } from 'react';
import Tabla from '@/components/admin/Tabla';
import { useSearchParams, useRouter } from 'next/navigation';
import Image from 'next/image';
import DashboardLayout from '@/components/admin/DashboardLayout';

const tablas = [
    {
        titulo: "Categorías",
        entidad: "categoria",
        data: [
            { key: 'id', label: 'ID'},
            { key: 'nombre', label: 'Nombre' }
        ]
    },
    {
        titulo: "Países",
        entidad: "pais",
        data: [
            { key: 'id', label: 'ID'},
            { key: 'nombre', label: 'Nombre' },
            { key: 'continente', label: 'Continente' },
            { key: 'bandera', label: 'Bandera' }
        ]
    },
    {
        titulo: "Medios",
        entidad: "medio",
        data: [
            { key: 'id', label: 'ID'},
            { key: 'nombre', label: 'Nombre' },
            { key: 'url', label: 'URL' }
        ]
    },
    {
        titulo: "Noticias",
        entidad: "noticia",
        data: [
            { key: 'id', label: 'ID'},
            { key: 'titulo', label: 'Título' },
            { key: 'texto_original', label: 'Texto Original', render: (fila) => fila.texto_original?.slice(0, 100) + (fila.texto_original?.length > 100 ? '...' : '') },
            { key: 'texto_traducido', label: 'Texto Traducido', render: (fila) => fila.texto_traducido?.slice(0, 100) + (fila.texto_traducido?.length > 100 ? '...' : '') },
            { key: 'nombre_pais', label: 'País', },
            { key: 'nombre_medio', label: 'Medio' },
            { key: 'path', label: 'Portada', render: (fila) => fila.path ? <Image src={`/img/web/noticias/${fila.path}`} alt={fila.path || 'null'} width={140} height={140}/> : 'Sin imagen' }
        ]
    },
    {
        titulo: "Usuarios",
        entidad: "usuario",
        data: [
            { key: 'id', label: 'ID' },
            { key: 'nombre', label: 'Nombre' },
            { key: 'email', label: 'Email' },
            { key: 'icono', label: 'Icono', render: (fila) => fila.icono ? <Image src={`/img/admin/usuarios/img_${fila.icono}`} alt={`img_${fila.icono}`} width={50} height={50}/> : 'Sin icono' }
        ]
    },
    {
        titulo: "Operadores",
        entidad: "administrador",
        data: [
            { key: 'id', label: 'ID' },
            { key: 'nombre', label: 'Nombre' },
            { key: 'email', label: 'Email' },
            { key: 'rol', label: 'Rol', render: (fila) => fila.rol == 1 ? 'Administrador' : 'Operador' },
            { key: 'icono', label: 'Icono', render: (fila) => fila.icono ? <Image src={`/img/admin/administradores/img_${fila.icono}`} alt={`img_${fila.icono}`} width={50} height={50}/> : 'Sin icono' }
        ]
    }
];

export default function TablaPage() {
    const searchParams = useSearchParams();
    const tableParam = searchParams.get('table');
    const router = useRouter();

    const tablaConfig = tablas.find(t => t.entidad === tableParam);

    useEffect(() => {
        const userData = JSON.parse(localStorage.getItem('user_session') || 'null');
        if (tableParam === 'administrador' && userData?.rol != 1) {
            router.push('/admin/dashboard');
        }
    }, [tableParam]);

    if (!tablaConfig) return <DashboardLayout><p>Tabla no encontrada</p></DashboardLayout>;

    return (
        <DashboardLayout>
            <Tabla
                titulo={`Gestión de ${tablaConfig.titulo}`}
                entidad={tablaConfig.entidad}
                columnas={tablaConfig.data}
                rutaInsertar={`/admin/tabla/insertar?table=${tablaConfig.entidad}`}
                rutaEditar={`/admin/tabla/editar?table=${tablaConfig.entidad}`}
            />
        </DashboardLayout>
    );
}