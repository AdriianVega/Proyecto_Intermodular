'use client';

import { useEffect, useState } from "react";
import { useSearchParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import styles from '@/app/assets/scss/web/pages/ContenidoBuscador.module.scss'; 

const formatearBusqueda = (texto) => {
    if (!texto) return '';
    const textoLimpio = texto.trim();
    if (textoLimpio === '') return '';
    return textoLimpio.charAt(0).toUpperCase() + textoLimpio.slice(1);
};

function cortarTitulo(texto, max = 90) {
    return texto.length > max ? texto.slice(0, max) + "..." : texto;
}

export default function ContenidoBuscador() {
    const [resultados, setResultados] = useState([]);
    const [paginacion, setPaginacion] = useState({ pagina_actual: 1, total_paginas: 1, total_resultados: 0 });
    const [loading, setLoading] = useState(true);
    
    const searchParams = useSearchParams();
    const router = useRouter();
    
    const searchRaw = searchParams.get('search');
    const pageRaw = searchParams.get('page') || 1;
    const busquedaSegura = formatearBusqueda(searchRaw);

    useEffect(() => {
        if (!searchRaw) {
            setLoading(false);
            return; 
        }

        setLoading(true);

        fetch(`/backend/api/handlers/buscador.php?search=${encodeURIComponent(searchRaw)}&page=${pageRaw}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    setResultados(data.data);
                    if (data.pagination) {
                        setPaginacion(data.pagination);
                    }
                } else {
                    setResultados([]);
                    setPaginacion({ pagina_actual: 1, total_paginas: 1, total_resultados: 0 });
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('Error al obtener la búsqueda:', error);
                setResultados([]);
                setLoading(false);
            });
    }, [searchRaw, pageRaw]);

    const isPrevDisabled = Number(paginacion.pagina_actual) <= 1;
    const isNextDisabled = Number(paginacion.pagina_actual) >= Number(paginacion.total_paginas);

    const irPaginaAnterior = () => {
        if (!isPrevDisabled) {
            router.push(`?search=${encodeURIComponent(searchRaw)}&page=${paginacion.pagina_actual - 1}`, { scroll: true });
        }
    };

    const irPaginaSiguiente = () => {
        if (!isNextDisabled) {
            router.push(`?search=${encodeURIComponent(searchRaw)}&page=${paginacion.pagina_actual + 1}`, { scroll: true });
        }
    };

    if (loading) {
        return <p className={styles.mensajeEstado}>Cargando resultados...</p>;
    }

    if (!searchRaw) {
        return <p className={styles.mensajeEstado}>No has introducido ningún término de búsqueda.</p>;
    }
    
    return (
        <main id="scroll-container" className={styles.mainBuscador}>
            <section className={styles.seccionResultados}>
                <article className={styles.cabecera}>
                    <h1>Resultados para "{busquedaSegura}"</h1>
                    <h3>Resultados encontrados: {paginacion.total_resultados}</h3>
                </article>

                <div className={styles.listaTarjetas}>
                    {resultados.map((noticia) => {
                        const imageUrl = noticia.path 
                            ? `/img/web/noticias/${noticia.path}` 
                            : '/img/web/logo_tierra.png';

                        return (
                            <article 
                                key={noticia.id} 
                                className={styles.tarjetaNoticia}
                                style={{ '--bg-noticia': `url(${imageUrl})` }}
                            >
                                <Link href={`/noticia?id=${noticia.id}`}>
                                    <h3>{cortarTitulo(noticia.titulo)}</h3>
                                    <p>
                                        <span>{noticia.bandera}</span> 
                                        {noticia.nombre_pais?.toUpperCase()}
                                    </p>
                                </Link>
                            </article>
                        );
                    })}
                </div>

                {resultados.length > 0 && (
                    <article className={styles.navegador}>
                        <button className={styles.btnNav} type="button" disabled={isPrevDisabled} onClick={irPaginaAnterior}>
                            <svg aria-hidden="true">
                                <use href="/img/web/sprites.svg#icon-previous"></use>
                            </svg>
                        </button>

                        <span>Página {paginacion.pagina_actual} de {paginacion.total_paginas}</span>

                        <button className={styles.btnNav} type="button" disabled={isNextDisabled} onClick={irPaginaSiguiente}>
                            <svg aria-hidden="true">
                                <use href="/img/web/sprites.svg#icon-next"></use>
                            </svg>
                        </button>
                    </article>
                )}
            </section>
        </main>
    );
}