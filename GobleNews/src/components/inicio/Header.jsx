import Link from 'next/link';
// Usamos el alias @ para evitar los ../../ innecesarios
import styles from '@/app/assets/scss/web/Header.module.scss'; 

export default function Header({ onMenuOpen }) {
    return (
        <header className={styles.headerContainer}>
            <video 
                autoPlay 
                muted
                loop 
                className={styles.videoBackground}
            >
                <source src="/video/tierra-video.mp4" type="video/mp4" />
            </video>

            <Link href="/" className={styles.logoLink}>      
                <img 
                    src="/img/logo_tierra.png" 
                    alt="Logo GobleNews" 
                    className={styles.logoImg} 
                />
                <h3 className={styles.subtitle}>GobleNews</h3>
            </Link>
            
            <div className={styles.textWrapper}>
                <h1 className={styles.title}>La nueva inteligencia</h1>
                <h3 className={styles.subtitle}>Periodismo técnico en la era post-humana</h3>
            </div>

            <button 
                className={styles.menuBtn} 
                onClick={onMenuOpen}
                aria-label="Abrir menú"
            >
                <svg>
                    <use href="/img/sprites.svg#icon-menu"></use>
                </svg>
            </button>
        </header>
    );
}