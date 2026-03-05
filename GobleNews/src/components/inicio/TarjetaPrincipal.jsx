import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/Global.module.scss';

function TarjetaPrincipal() {
    const [noticia, setNoticia] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('http://localhost:8000/api/handlers/noticia.php?destacada=true')
            .then(response => response.json())
            .then(res => {
                if (res.success && res.data && res.data.length > 0) {
                    setNoticia(res.data[0]);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching noticia:', error);
                setLoading(false);
            });
    }, []);

    function tiempoTranscurrido(fecha) {
        const ahora = new Date();
        const fechaNoticia = new Date(fecha);

        const diffMs = ahora - fechaNoticia;
        const diffSegundos = Math.floor(diffMs / 1000);
        const diffMinutos = Math.floor(diffSegundos / 60);
        const diffHoras = Math.floor(diffMinutos / 60);
        const diffDias = Math.floor(diffHoras / 24);

        if (diffDias > 0) return `${diffDias} día${diffDias > 1 ? 's' : ''} atrás`;
        if (diffHoras > 0) return `${diffHoras} hora${diffHoras > 1 ? 's' : ''} atrás`;
        if (diffMinutos > 0) return `${diffMinutos} minuto${diffMinutos > 1 ? 's' : ''} atrás`;
        return `${diffSegundos} segundo${diffSegundos > 1 ? 's' : ''} atrás`;
    }

    if (loading) return <div className={styles.loading}>Cargando noticia...</div>;
    if (!noticia) return null;

    return (
        <section className={styles.noticiaDestacada}>
            <Link href={`/noticia/${noticia.id}`}>
                <div className={styles.content}>
                    <p className={styles.category}>
                        <span>{noticia.bandera}</span> - {noticia.nombre_pais}
                    </p>

                    <h1>{noticia.titulo}</h1>

                    <h5></h5>
                </div>
            </Link>

            <div className={styles.mediaContainer}>
                <Image
                    src={noticia.url_imagen || '/placeholder.jpg'}
                    alt={noticia.nombre}
                    width={600}
                    height={400}
                    className={styles.mainImage}
                    priority 
                />

                <video 
                    key={noticia.id}
                    loop 
                    muted 
                    playsInline 
                    className={styles.videoLayer}
                >
                    <source src={noticia.url_video || '/placeholder.mp4'} type="video/mp4" />
                </video>

                <button type='button' className={styles.muteBtn} aria-pressed='true'>
                    🔇
                </button>
            </div>
        </section>
    );
}

export default TarjetaPrincipal;