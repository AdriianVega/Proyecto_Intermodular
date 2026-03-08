'use client'

import React, { useState, useEffect } from "react";
import Header from "../components/home/Header"
import Aside from "../components/home/Aside"
import TarjetaPrincipal from "../components/home/TarjetaPrincipal";
import BentoGrid from "../components/home/BentoGrid"
import Footer from "../components/home/Footer";
import styles from '../app/assets/scss/web/home/Estilo.module.scss';

export default function Home() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  useEffect(() => {
    const observerOptions = {
      threshold: 0.2,
      rootMargin: '0px 0px -100px 0px'
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add(styles.visible)
        } else {
          entry.target.classList.remove(styles.visible)
        }
      });
    }, observerOptions);

    const fadeElements = document.querySelectorAll(`.${styles.fadeInElement}`)
    fadeElements.forEach(element => observer.observe(element));

    return () => {
      observer.disconnect();
    };
  
  }, []);

  const handleMenuOpen = () => {
    setIsMenuOpen(true);
  };

  const handleMenuClose = () => {
    setIsMenuOpen(false);
  };

  return (
    <>
      <Aside isOpen={isMenuOpen} onMenuClose={handleMenuClose} />
      <button 
          className={styles.menuBtn} 
          onClick={handleMenuOpen}
          aria-label="Abrir menú"
      >
          <svg>
              <use href="/img/web/sprites.svg#icon-menu"></use>
          </svg>
      </button>

      <div className={`${styles.page} ${styles.pageScroll}`}>
        <Header />

        <main className={styles.mainSnap}>
            <TarjetaPrincipal />
            <BentoGrid />
        </main>

        <Footer />
      </div>
    </>
  );
}