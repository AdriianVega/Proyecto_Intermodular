import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import styles from '@/app/assets/scss/web/home/Estilo.module.scss';

export default function BentoGrid() {
    const [noticias, setNoticias] = useState([]);
    const [loading, setLoading] = useState(true);
    const sectionRef = useRef(null);

    useEffect(() => {
        fetch('/backend/api/handlers/pagina_principal.php?bento=true')
            .then(response => response.json())
            .then(res => {
                if (res.success && res.data && res.data.length > 0) {
                    setNoticias(res.data);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching noticia:', error);
                setLoading(false);
            });
    }, []);

    useEffect(() => {
        if (!loading && sectionRef.current) {
            const observer = new IntersectionObserver(([entry]) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add(styles.visible);
                } else {
                    entry.target.classList.remove(styles.visible);
                }
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            observer.observe(sectionRef.current);
            return () => observer.disconnect();
        }
    }, [loading]);

    if (loading) return <div className={styles.loading}>Cargando panel...</div>;
    if (noticias.length === 0) return <div className={styles.error}>No hay noticias disponibles.</div>;

    return (
        <section ref={sectionRef} className={`${styles.bentoGrid} ${styles.fadeInElement}`}>
                {noticias.slice(0, 4).map((noticia, index) => {
                    const imageUrl = noticia.path 
                    ? `/img/web/${noticia.path}/img_${noticia.path}.png` 
                    : '/img/web/logo_tierra.png';

                    return (
                        <article key={noticia.id} className={`${styles[`noticia${index + 1}`]} ${styles.tarjetaNoticia}`} style={{ '--bg-noticia': `url(${imageUrl})` }}>
                            <Link href={`noticia?id=${noticia.id}`}>
                                <h3>{noticia.titulo}</h3>
                                <p>
                                    <span>{noticia.bandera}</span> 
                                    {noticia.nombre_pais?.toUpperCase()}
                                </p>
                            </Link>
                        </article>
                    );
                })}
        </section>
    )
}