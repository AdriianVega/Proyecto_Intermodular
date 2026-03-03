
import Link from 'next/link';
import styles from './Header.module.scss';

export default function Header({ onMenuOpen }) {
    return (
        <header className={styles.header}>
            <video autoPlay muted loop id="video-header" className={styles.videoBackground}>
                <source src="/video/tierra-video.mp4" type="video/mp4" />
            </video>

            <Link id="logo-tierra" href="/" className={styles.logo}>      
                <img src="/img/logo_tierra.png" alt="Logo GobleNews" />
                <h3>GobleNews</h3>
            </Link>
            
            <div id="texto-header" className={styles.textoHeader}>
                <h1>La nueva inteligencia</h1>
                <h3>Periodismo técnico en la era post-humana</h3>
            </div>

            <button id="nav-menu" className={styles.navBtn} onClick={onMenuOpen}>
                <svg>
                <use href="/img/sprites.svg#icon-menu"></use>
                </svg>
            </button>
        </header>
    );
}