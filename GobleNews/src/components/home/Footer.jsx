import { useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/home/Estilo.module.scss'; 

const enlaces = [
    [
        {
            id: 1,
            nombre: "América",
            url: "america"
        },
        {
            id: 2,
            nombre: "Europa",
            url: "europa"
        },
        {
            id: 3,
            nombre: "Asia",
            url: "asia"
        },
        {
            id: 4,
            nombre: "África",
            url: "africa"
        },
        {
            id: 5,
            nombre: "Oceanía",
            url: "oceania"
        }
    ],
    [
        {
            id: 1,
            nombre: "Política",
            url: "politica"
        },
        {
            id: 2,
            nombre: "Economía",
            url: "economia"
        },
        {
            id: 3,
            nombre: "Tecnología",
            url: "tecnologia"
        },
        {
            id: 4,
            nombre: "Deportes",
            url: "deportes"
        },
        {
            id: 5,
            nombre: "Cultura",
            url: "cultura"
        }
    ],
    [
        {
            id: 1,
            nombre: "Gmail",
            url: "gmail"
        },
        {
            id: 2,
            nombre: "Teléfono",
            url: "telefono"
        }
    ]
    
]

export default function Footer() {

    const footerRef = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                entry.target.classList.add(styles.visible);
            } else {
                entry.target.classList.remove(styles.visible);
            }
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });

        if (footerRef.current) {
            observer.observe(footerRef.current);
        }

        return () => observer.disconnect();
    }, []);

    const handleScrollToTop = (e) => {
        e.preventDefault();
        
        const header = document.getElementById('header');
        if (header) {
            header.scrollIntoView({ behavior: 'smooth' });
        }
    };

    return (
        <footer ref={footerRef} className={`${styles.footer} ${styles.fadeInElement}`}>
            <section className={styles.topSection}>
                <Link href="/" onClick={handleScrollToTop}>
                    <Image 
                        src="/img/web/logo_tierra.png" 
                        alt="Logo GobleNews" 
                        width={80} 
                        height={80} 
                        className={styles.logo} 
                    />
                </Link>

                <div className={styles.linksWrapper}>
                    <div>
                        <p>Países</p>
                        <ul className={styles.linkColumn}>
                            {enlaces[0].map((enlace) => (
                                <li key={enlace.id} className={styles.listItem}>
                                    <Link href={`/buscador?search=${enlace.url}`}>{enlace.nombre}</Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                    
                    <div>
                        <p>Categoría</p>
                        <ul className={styles.linkColumn}>
                            {enlaces[1].map((enlace) => (
                                <li key={enlace.id} className={styles.listItem}>
                                    <Link href={`/buscador?search=${enlace.url}`}>{enlace.nombre}</Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                    
                    <div>
                        <p>Sobre Nosotros</p>
                        <ul className={styles.linkColumn}>
                            {enlaces[2].map((enlace) => (
                                <li key={enlace.id} className={styles.listItem}>
                                    <Link href={`/buscador?search=${enlace.url}`}>{enlace.nombre}</Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                    
                    <div>
                        <Link href={`/`}>Últimas Noticias</Link>
                    </div>
                    
                    <div>
                        <Link href={`/cookies`}>Cookies</Link>
                    </div>
                </div>
            </section>

            <hr />
            
            <section className={styles.bottomSection}>
                <p className={styles.copyright}>© 2026 GobleNews. Todos los derechos reservados.</p>
            
                <div className={styles.socialLinks}>
                    <a href="#">
                        <svg><use href="/img/web/sprites.svg#icon-instagram"></use></svg>
                    </a>
                    
                    <a href="#">
                        <svg><use href="/img/web/sprites.svg#icon-facebook"></use></svg>
                    </a>
                    
                    <a href="#">
                        <svg><use href="/img/web/sprites.svg#icon-linkedin"></use></svg>
                    </a>
                    
                    <a href="#">
                        <Image 
                            src="/img/web/icons8-x-50.png" 
                            alt="Twitter logo" 
                            width={20} 
                            height={20}
                        />
                    </a>
                </div>
            </section>
        </footer>
    );
}