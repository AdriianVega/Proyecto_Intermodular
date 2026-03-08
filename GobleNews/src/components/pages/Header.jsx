import styles from '@/app/assets/scss/web/pages/Header.module.scss';

export default function Header() {
    return (
        <header className={styles.headerContainer}>
            <a href="/">
                <img src="/img/web/logo_tierra.png" alt="Logo GobleNews" className={styles.logo} />
            </a>
        </header>
    );
}