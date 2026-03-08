import Link from 'next/link';
import Image from "next/image";
import styles from '@/app/assets/scss/web/home/Estilo.module.scss';

export default function Header() {
    return (
        <header className={`${styles.headerContainer} ${styles.fadeInElement}`} id="header">
            <video 
                autoPlay
                playsInline
                muted
                loop 
                className={styles.videoBackground}
            >
                <source src="/video/tierra-video.mp4" type="video/mp4" />
            </video>

            <Link href="/" className={styles.logoLink}>      
                <Image 
                    src="/img/web/logo_tierra.png" 
                    alt="Logo GobleNews" 
                    className={styles.logoImg}
                    width={100}
                    height={100}
                    priority 
                />
                <h3 className={styles.subtitle}>GobleNews</h3>
            </Link>
            
            <div className={styles.textWrapper}>
                <h1 className={styles.title}>La nueva inteligencia</h1>
                <h3 className={styles.subtitle}>Periodismo técnico en la era post-humana</h3>
            </div>
        </header>
    );
}