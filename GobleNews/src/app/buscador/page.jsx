'use client';

import { useState } from 'react';
import styles from '@/app/assets/scss/web/pages/Secundaria.module.scss'
import Header from '@/components/pages/Header';
import Aside from '@/components/home/Aside';
import ContenidoBuscador from '@/components/pages/ContenidoBuscador';
import Footer from '@/components/home/Footer';

export default function Buscador() {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    
    const handleMenuOpen = () => {
        setIsMenuOpen(true);
    };

    const handleMenuClose = () => {
        setIsMenuOpen(false);
    };

    return (
        <>
            <div className={styles.page}>

                <div className={styles['page-scroll']}>
                    <Aside isOpen={isMenuOpen} onMenuClose={handleMenuClose} />
                
                    <button className={styles.navMenu} onClick={handleMenuOpen} aria-label="Abrir menú">
                        <svg>
                            <use href="/img/web/sprites.svg#icon-menu"></use>
                        </svg>
                    </button>
                    
                    <Header />

                    <ContenidoBuscador />

                    <Footer />
                </div>
            </div>
        </>
    );
}