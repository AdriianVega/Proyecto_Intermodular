'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import styles from '@/app/assets/scss/admin/Dashboard.module.scss';

export default function Dashboard() {
    const [user, setUser] = useState(null);
    const router = useRouter();

    const pathImg = "/img/admin/menu/";
    const path = "/admin/tabla?table=";

    const secciones = [
        { titulo: "Noticias", imagen: `${pathImg}noticias.svg`, ruta: "noticia", table: true},
        { titulo: "Paises", imagen: `${pathImg}pais.svg`, ruta: "pais", table: true},
        { titulo: "Medios", imagen: `${pathImg}medio.svg`, ruta: "medio", table: true},
        { titulo: "Categorías", imagen: `${pathImg}lista.svg`, ruta: "categoria", table: true},
        { titulo: "Usuarios", imagen: `${pathImg}usuario.svg`, ruta: "usuario", table: true},
        { titulo: "Operadores", imagen: `${pathImg}admin.svg`, ruta: "administrador", table: true},
        { titulo: "Inicio", imagen: `${pathImg}web.svg`, ruta: "/", table: false},
        { titulo: "Cerrar Sesión", imagen: `${pathImg}logout.svg`, ruta: "logout", table: false},
    ];

    const logout = (e, seccion) => {
    if (seccion.titulo === "Cerrar Sesión") {
            e.preventDefault();

            localStorage.removeItem('user_session');

            router.push('/admin/panel-control');
            
            return;
        }
    };

    const inicio = (e, seccion) => {
        if (seccion.titulo === "Inicio") {
            e.preventDefault();
            router.push('/');
        }
    };

    useEffect(() => {
        const userData = localStorage.getItem('user_session');
        if (!userData) {
            router.push('/admin/panel-control');
        } else {
            setUser(JSON.parse(userData));
        }
    }, [router]);

    if (!user) return null;

    const rutaPerfil = user.icono 
    ? `/img/admin/administradores/img_${user.icono}` 
    : '/img/admin/administradores/default.png';

    return (
        <div className={styles.dashboardContainer}>
            <header className={styles.header}>
                <Image src={rutaPerfil} alt="Perfil" width={200} height={200} className={styles.profileImage} />
                <h1>Bienvenido {user.nombre}</h1>
                <div className={styles.badgeWrapper}>
                    <span className={user.rol === "1" ? styles.badgeAdmin : styles.badgeUser}>
                        {user.rol === "1" ? "Administrador" : "Usuario"}
                    </span>
                </div>
            </header>

            <main className={styles.mainGrid}>
                {secciones.filter(seccion => {
                    if (seccion.ruta === 'administrador' && user.rol != 1) return false;
                    return true;
                }).map((seccion, index) => (
                    <Link 
                        key={index} 
                        href={seccion.table ? `${path}${seccion.ruta}` : `/admin/${seccion.ruta}`} 
                        className={styles.enlace} 
                        onClick={(e) => {
                            logout(e, seccion);
                            inicio(e, seccion);
                        }}
                    >
                        <article className={styles.tarjeta}>
                            <Image src={seccion.imagen} alt={seccion.titulo} width={200} height={60} />
                            <h3>{seccion.titulo}</h3>
                        </article>
                    </Link>
                ))}
            </main>
        </div>
    );
}