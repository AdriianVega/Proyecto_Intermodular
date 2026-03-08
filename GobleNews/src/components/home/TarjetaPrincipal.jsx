import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { FastAverageColor } from 'fast-average-color';
import styles from '@/app/assets/scss/web/home/Estilo.module.scss';

function tiempoTranscurrido(fecha) {
    if (!fecha) return 'Error: fecha es undefined'; 

    const fechaISO = fecha.replace(' ', 'T');
    const ahora = new Date();
    const fechaNoticia = new Date(fechaISO);

    if (fechaNoticia.getDate() === ahora.getDate() && 
        fechaNoticia.getMonth() === ahora.getMonth() && 
        fechaNoticia.getFullYear() === ahora.getFullYear()) {
        
        const horas = Math.floor((ahora - fechaNoticia) / (1000 * 60 * 60));
        if (horas < 1) return 'Hace minutos';
        return `Hace ${horas} hora${horas > 1 ? 's' : ''}`;
    
    } else if (fechaNoticia.getMonth() === ahora.getMonth() && fechaNoticia.getFullYear() === ahora.getFullYear()) {
        const dias = Math.floor((ahora - fechaNoticia) / (1000 * 60 * 60 * 24));
        if (dias < 1) return 'Ayer';
        return `Hace ${dias} día${dias > 1 ? 's' : ''}`;

    } else if (fechaNoticia.getFullYear() === ahora.getFullYear()) {
        const meses = Math.floor((ahora - fechaNoticia) / (1000 * 60 * 60 * 24 * 30));
        if (meses < 1) return 'Hace semanas';
        return `Hace ${meses} mes${meses > 1 ? 'es' : ''}`;
    }
    
    return 'Fallo en fecha, depuración: ' + fecha;
}

export default function TarjetaPrincipal() {
    const [noticia, setNoticia] = useState(null);
    const [loading, setLoading] = useState(true);
    const [bgColor, setBgColor] = useState('#D65108');
    
    const videoRef = useRef(null);
    const sectionRef = useRef(null);

    useEffect(() => {
        fetch('http://localhost:8000/backend/api/handlers/pagina_principal.php?destacada=true')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data && data.data.id > 0) {
                    setNoticia(data.data);
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
            }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });

            observer.observe(sectionRef.current);
            return () => observer.disconnect();
        }
    }, [loading]);

    useEffect(() => {
        if (noticia) {
            const fac = new FastAverageColor();
            const imageUrl = noticia.path 
                ? `/img/web/${noticia.path}/img_${noticia.path}.png` 
                : '/img/web/logo_tierra.png';

            const img = new window.Image();
            img.crossOrigin = 'anonymous';
            img.src = imageUrl;

            img.onload = () => {
                try {
                    const color = fac.getColor(img);
                    setBgColor(color.hex);
                    fac.destroy(); 
                } catch (e) {
                    console.error('Fallo al extraer color:', e);
                }
            };
        }
    }, [noticia]);

    const handleMouseEnter = () => {
        if (!videoRef.current) return;
        const p = videoRef.current.play();
        if (p && typeof p.catch === 'function') p.catch(() => {});
    };

    const handleMouseLeave = () => {
        if (!videoRef.current) return;
        videoRef.current.pause();
        videoRef.current.currentTime = 0;
    };

    if (loading) return <div className={styles.loading}>Cargando noticia...</div>;
    
    if (!noticia) {
        return <div className={styles.error}>No se pudo cargar la noticia destacada.</div>;
    }

    const videoSrc = noticia?.path
        ? `/video/web/${noticia.path}/video_${noticia.path}.mp4`
        : null;

    return (
        <section ref={sectionRef} className={`${styles.seccionDestacada} ${styles.fadeInElement} `} style={{ '--color-dominante': bgColor }}>
            <article
                className={styles.noticiaDestacada}
                onMouseEnter={handleMouseEnter}
                onMouseLeave={handleMouseLeave}
            >
                
                <Link href={`/noticia?id=${noticia.id}`} className={styles.content}>
                    <p className={styles.category}>
                        <span>{noticia.bandera}</span> {noticia.nombre_pais.toUpperCase()}
                    </p>

                    <h1>{noticia.titulo}</h1>

                    <h5>Novedad - {tiempoTranscurrido(noticia.create_time)}</h5>
                </Link>

                <div className={styles.mediaContainer}>
                    <Image
                        src={noticia.path ? `/img/web/${noticia.path}/img_${noticia.path}.png` : '/img/web/logo_tierra.png'}
                        alt={noticia.titulo || 'Noticia destacada'}
                        width={600}
                        height={400}
                        className={styles.mainImage}
                        priority 
                    />
                    
                    {videoSrc ? (
                        <>
                            <video
                                key={noticia.id}
                                ref={videoRef}
                                controls
                                loop
                                muted={isMuted}
                                playsInline
                                preload="metadata"
                                className={styles.videoLayer}
                            >
                                <source src={videoSrc} type="video/mp4" />
                            </video>
                        </>
                    ) : (
                        <div className={styles.noVideo}>
                            <h1>Error: 404</h1>
                            <h2>No hay video disponible</h2>
                        </div>)}
                </div>
            </article>
        </section>
    );
}