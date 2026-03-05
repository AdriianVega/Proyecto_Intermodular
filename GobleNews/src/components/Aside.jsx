'use client'

import React, { useEffect, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import styles from '@/app/assets/scss/web/Aside.module.scss'; 

const lista = [
    {
        id: 1,
        name: "Países"
    },
    {
        id: 2,
        name: "Categorías"
    },
    {
        id: 3,
        name: "Sobre Nosotros"
    },
    {
        id: 4,
        name: "Cookies"
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
    "Países": continentes,
    "Categorías": categorias
}

export default function Aside( { isOpen, onMenuClose } ) {
    const [nav, setNav] = useState('');

    const toggleNav = (name) => {
        setNav(prev => prev === name ? '' : name)
    };

    useEffect(() => {
        if (nav) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }, [nav]);

    return (
        <>
            <div
                id="overlay"
                className={isOpen ? "overlay-visible" : ""}
                onClick={onMenuClose}
            >
            </div>
            <aside className={styles.asideContainer}>
                <button 
                    onClick={onMenuClose} 
                    className={styles.closeBtn} 
                    aria-label="Cerrar menú">
                    ✕
                </button>

                <nav>
                    <ul>
                        <li className="buscador">
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
                                    className={isDropdownOpen ? "dropdown-open" : ""}
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
                    </ul>
                </nav>
            </aside>
        </>
        
    )
}