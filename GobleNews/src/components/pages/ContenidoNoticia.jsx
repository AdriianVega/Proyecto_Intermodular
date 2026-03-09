import { useEffect, useState, useRef } from "react"
import { useSearchParams } from 'next/navigation';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/pages/Secundaria.module.scss';

const parrafosSeparados = (text = "", size = 1000) => {
        if (!text) return [];
        const palabras = text.split(/\s+/);
        const parrafos = [];
        let current = "";

        for (const palabra of palabras) {
            const parrafo = current ? `${current} ${palabra}` : palabra;
            if (parrafo.length > size && current.endsWith('.')) {
                if (current) parrafos.push(current);
                current = palabra;
            } else {
                current = parrafo;
            }
        }

        if (current) parrafos.push(current);
        return parrafos;
    };

export default function ContenidoNoticia() {

    const [noticia, setNoticia] = useState(null);
    const [loading, setLoading] = useState(true);

    const videoSrc = noticia?.path
        ? `/video/web/${noticia.path}/video_${noticia.path}.mp4`
        : null;
    

    const searchParams = useSearchParams();

    const id = searchParams.get('id');

    const textoCompleto = noticia?.texto_traducido || "";
    const bloques = parrafosSeparados(textoCompleto, 1000);

    useEffect(() => {

        if (!id) {
            setLoading(false);
            return; 
        }

        fetch(`/backend/api/handlers/noticia.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    setNoticia(data.data);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('Error al obtener la noticia:', error);
                setLoading(false);
            });
    }, [id]);

    if (loading) {
        return <p>Cargando noticia...</p>;
    }

    if (!id || !noticia) {
        return <p>Noticia no encontrada.</p>;
    }
    
    return (
        <main id="scroll-container">
            <section>
                <article>
                    <h1>{noticia.titulo}</h1>
                
                    <Image 
                        src={noticia.path 
                        ? `/img/web/${noticia.path}/img_${noticia.path}.png` 
                        : '/img/web/logo_tierra.png'} 
                        alt={noticia.titulo} 
                        className={styles.fadeInElement} 
                        width={500}
                        height={500}/>
                </article>
                
                {bloques.map((bloque, i) => (
                    <article key={i}>
                        {(i === Math.floor(bloques.length / 2) 
                        ? (videoSrc && <video
                            key={noticia.id}
                            controls
                            preload="metadata"
                            className={styles.videoLayer}
                        >
                            <source src={videoSrc} type="video/mp4" />
                        </video>)
                        : null)}
                        <p>{bloque}</p>
                    </article>
                ))}
            </section>
        </main>
    )
}