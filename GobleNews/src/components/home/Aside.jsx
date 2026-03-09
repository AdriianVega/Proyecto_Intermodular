import React, { useEffect, useState } from "react";
import Link from "next/link";
import styles from '@/app/assets/scss/web/home/Aside.module.scss'; 

const lista = [
    {
        id: 1,
        name: "Continentes"
    },
    {
        id: 2,
        name: "Categorías"
    },
    {
        id: 3,
        name: "Sobre Nosotros"
    }
]

const continentes = [
    {
        id: 1,
        name: "América",
        search: "america"
    },
    {
        id: 2,
        name: "Europa",
        search: "europa"
    },
    {
        id: 3,
        name: "Asia",
        search: "asia"
    },
    {
        id: 4,
        name: "África",
        search: "africa"
    },
    {
        id: 5,
        name: "Oceanía",
        search: "oceania"
    }
]

const categorias = [
    {
        id: 1,
        name: "Tecnología",
        search: "tecnologia"
    },
    {
        id: 2,
        name: "Ciencia",
        search: "ciencia"
    },
    {
        id: 3,
        name: "Salud",
        search: "salud"
    },
    {
        id: 4,
        name: "Deportes",
        search: "deportes"
    },
    {
        id: 5,
        name: "Entretenimiento",
        search: "entretenimiento"
    }
]

const dropdown = {
    "Continentes": continentes,
    "Categorías": categorias
}

export default function Aside( { isOpen, onMenuClose } ) {
    const [nav, setNav] = useState('');

    const toggleNav = (name) => {
        setNav(prev => prev === name ? '' : name)
    };

    useEffect(() => {
        document.documentElement.style.overflow = isOpen ? 'hidden' : '';
        document.body.style.overflow = isOpen ? 'hidden' : '';

        return () => {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        };
    }, [isOpen]);

    return (
        <>
            <div
                className={`${styles.overlay} ${isOpen ? styles.overlayVisible : ""}`}
                onClick={onMenuClose}
            >
            </div>
            <aside 
                className={`${styles.asideContainer} ${isOpen ? styles.menuVisible : ''}`}
                onClick={(e) => e.stopPropagation()}
            >
                <button 
                    onClick={onMenuClose} 
                    className={styles.closeBtn} 
                    aria-label="Cerrar menú">
                    ✕
                </button>

                <nav>
                    <ul>
                        <li className={styles.buscador}>
                            <form action="/buscador" method="get">
                                <label htmlFor="search">Buscar</label>
                                <input 
                                    type="text" 
                                    id="search" 
                                    name="search" 
                                    placeholder="Buscar"/>

                                <button type="submit">
                                    <svg>
                                        <use href="img/web/sprites.svg#btn-buscador"></use>
                                    </svg>
                                </button>
                            </form>
                        </li>

                        {lista.map((item) => {
                            const hasDropdown = dropdown[item.name];
                            const isDropdownOpen = nav === item.name;

                            return (
                                <li
                                    key={item.id}
                                    className={isDropdownOpen ? styles.dropdownOpen : ""}
                                >
                                    {
                                        hasDropdown ? 

                                        <>
                                            <span onClick={() => toggleNav(item.name)}>
                                                {item.name}
                                            </span>
                                            
                                            <ul>
                                                {hasDropdown.map((sub) => (
                                                    <li key={sub.id}>
                                                        {
                                                            <Link
                                                                href={`/buscador?search=${sub.search}`}
                                                                onClick={(e) => {
                                                                    e.preventDefault();

                                                                    onMenuClose();
                                                                }}
                                                            >
                                                                {sub.name}
                                                            </Link>
                                                        }
                                                    </li>
                                                ))}
                                            </ul>
                                        </>
                                    : (
                                        <Link
                                            href={`/${item.name.toLowerCase().replace(/\s+/g, "-")}`}
                                            onClick={onMenuClose}
                                        >
                                            {item.name}
                                        </Link>
                                    )}    
                                </li>
                            )
                        })}
                        <li className={styles.controlPanel}>
                            <Link
                                href="/panel-control"
                                onClick={onMenuClose}
                            >
                                Panel de Control
                            </Link>
                        </li>
                    </ul>
                </nav>
            </aside>
        </>
        
    )
}