'use client';

import { useState } from 'react';
import styles from '@/app/assets/scss/web/pages/Secundaria.module.scss';
import Header from '@/components/pages/Header';
import Aside from '@/components/home/Aside';
import ContenidoSobreNosotros from '@/components/pages/ContenidoSobreNosotros'
import Footer from '@/components/home/Footer';

export default function SobreNosotros() {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    
    const handleMenuOpen = () => setIsMenuOpen(true);
    const handleMenuClose = () => setIsMenuOpen(false);

    return (
        <div className={styles.page}>
            <Aside isOpen={isMenuOpen} onMenuClose={handleMenuClose} />
            
            <button className={styles.navMenu} onClick={handleMenuOpen} aria-label="Abrir menú">
                <svg><use href="/img/web/sprites.svg#icon-menu"></use></svg>
            </button>
            
            <div className={styles['page-scroll']}>
                <Header />

                <ContenidoSobreNosotros />

                <Footer />
            </div>
        </div>
    );
}