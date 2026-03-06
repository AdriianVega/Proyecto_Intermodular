import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/Estilo.module.scss';


export default function BentoGrid() {
    const [noticias, setNoticias] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('http://localhost:8000/backend/api/handlers/noticia.php?bentogrid=true')
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

    return (
        <section className={`${styles.fadeInElement} ${styles.bentoGrid}`}>
                <article className={styles.noticia1}>
                    <h3>{noticias[0]?.titulo}</h3>
                    <p><span>{noticias[0]?.bandera}</span> {noticias[0]?.pais.toUpperCase()}</p>
                </article>

                <article className={styles.noticia2}>
                    <h3>{noticias[1]?.titulo}</h3>
                    <p><span>{noticias[1]?.bandera}</span> {noticias[1]?.pais.toUpperCase()}</p>
                </article>

                <article className={styles.noticia3}>
                    <h3>{noticias[2]?.titulo}</h3>
                    <p><span>{noticias[2]?.bandera}</span> {noticias[2]?.pais.toUpperCase()}</p>
                </article>

                <article className={styles.noticia4}>
                    <h3>{noticias[3]?.titulo}</h3>
                    <p><span>{noticias[3]?.bandera}</span> {noticias[3]?.pais.toUpperCase()}</p>
                </article>
        </section>
    )
}