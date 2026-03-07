import { useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import styles from '@/app/assets/scss/web/Estilo.module.scss'; 

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
                            <li className={styles.listItem}><Link href="/buscador?search=america">América</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=europa">Europa</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=asia">Asia</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=africa">África</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=oceania">Oceanía</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <p>Categoría</p>
                        <ul className={styles.linkColumn}>
                            <li className={styles.listItem}><Link href="/buscador?search=politica">Política</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=economia">Economía</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=tecnologia">Tecnología</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=deportes">Deportes</Link></li>
                            <li className={styles.listItem}><Link href="/buscador?search=cultura">Cultura</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <p>Sobre Nosotros</p>
                        <ul className={styles.linkColumn}>
                            <li className={styles.listItem}><Link href="#">Gmail</Link></li>
                            <li className={styles.listItem}><Link href="#">Teléfono</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <p>Últimas Noticias</p>
                    </div>
                    
                    <div>
                        <p>Cookies</p>
                    </div>
                </div>
            </section>

            <hr />
            
            <section className={styles.bottomSection}>
                <p>© 2026 GobleNews. Todos los derechos reservados.</p>
            
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