import { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/Estilo.module.scss';

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
    const [isMuted, setIsMuted] = useState(true);
    const videoRef = useRef(null);

    useEffect(() => {
        fetch('http://localhost:8000/backend/api/handlers/noticia.php?destacada=true')
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

    if (loading) return <div className={styles.loading}>Cargando noticia...</div>;
    
    if (!noticia) {
        return <div className={styles.error}>No se pudo cargar la noticia destacada.</div>;
    }

    const toggleMute = (e) => {
        if (videoRef.current) {
            videoRef.current.muted = !videoRef.current.muted;
            setIsMuted(videoRef.current.muted);
        }
    }

    return (
        <section className={styles.seccionDestacada}>
            <article className={styles.noticiaDestacada}>
                
                <Link href={`/noticia/${noticia.id}`} className={styles.content}>
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
                    
                    {noticia.url_video && (
                        <>
                            <video 
                                key={noticia.id}
                                ref={videoRef}
                                loop 
                                muted={isMuted}
                                playsInline 
                                className={styles.videoLayer}
                            >
                                <source src={noticia.url_video} type="video/mp4" />
                            </video> 

                            <button type='button' className={styles.muteBtn} aria-pressed={!isMuted} onClick={toggleMute}>
                                {isMuted ? '🔇' : '🔊'}
                            </button> 
                        </>
                    )}
                </div>
            </article>
        </section>
    );
}