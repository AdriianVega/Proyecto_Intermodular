'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import styles from '@/app/assets/scss/admin/DashboardLayout.module.scss';

export default function DashboardLayout({ children }) {
    const [user, setUser] = useState(null);
    const router = useRouter();
    const pathImg = "/img/admin/menu/";

    const secciones = [
        { titulo: "Dashboard", imagen: `${pathImg}home.svg`, ruta: "dashboard", table: false},
        { titulo: "Noticias", imagen: `${pathImg}noticias.svg`, ruta: "noticia", table: true},
        { titulo: "Paises", imagen: `${pathImg}pais.svg`, ruta: "pais", table: true},
        { titulo: "Medios", imagen: `${pathImg}medio.svg`, ruta: "medio", table: true},
        { titulo: "Categorías", imagen: `${pathImg}lista.svg`, ruta: "categoria", table: true},
        { titulo: "Usuarios", imagen: `${pathImg}usuario.svg`, ruta: "usuario", table: true},
        { titulo: "Operadores", imagen: `${pathImg}admin.svg`, ruta: "administrador", table: true},
        { titulo: "Inicio", imagen: `${pathImg}web.svg`, ruta: "/", table: false},
        { titulo: "Cerrar Sesión", imagen: `${pathImg}logout.svg`, ruta: "logout", table: false}
    ];

    useEffect(() => {
        const userData = localStorage.getItem('user_session');
        if (!userData) {
            router.push('/admin/panel-control');
        } else {
            setUser(JSON.parse(userData));
        }
    }, [router]);

    const logout = (e, seccion) => {
        if (seccion.titulo === "Cerrar Sesión") {
            e.preventDefault();
            localStorage.removeItem('user_session');
            router.push('/admin/panel-control');
        }
    };

    const inicio = (e, seccion) => {
        if (seccion.titulo === "Inicio") {
            e.preventDefault();
            router.push('/');
        }
    };

    if (!user) return null;

    const rutaPerfil = user.icono 
    ? `/img/admin/administradores/img_${user.icono}` 
    : '/img/admin/administradores/default.png';

    return (
        <div className={styles.layoutContainer}>
            <aside className={styles.sidebar}>
                <div className={styles.profile}>
                    <Image
                        src={rutaPerfil}
                        alt="Perfil"
                        width={90}
                        height={90}
                        className={styles.profileImage}
                    />
                    <h3>{user.nombre}</h3>
                    <span className={user.rol === "1" ? styles.badgeAdmin : styles.badgeUser}>
                        {user.rol === "1" ? "Administrador" : "Usuario"}
                    </span>
                </div>

                <nav className={styles.menuNav}>
                    {secciones.filter(seccion => {
                        if (seccion.ruta === 'administrador' && user.rol != 1) return false;
                        return true;
                    }).map((seccion, index) => (
                        <Link 
                            key={index} 
                            href={seccion.table === false ? `${seccion.ruta}` : `tabla?table=${seccion.ruta}`} 
                            onClick={(e) => {
                                logout(e, seccion);
                                inicio(e, seccion);
                            }}
                            className={styles.navLink}
                        >
                            <Image
                                src={seccion.imagen} 
                                alt={seccion.titulo} 
                                width={20}
                                height={20}
                            />
                            <span>{seccion.titulo}</span>
                        </Link>
                    ))}
                </nav>
            </aside>

            <main className={styles.mainContent}>
                {children}
            </main>
        </div>
    );
}