'use client'

import React, { useState } from "react";
import Header from "../components/inicio/Header"
import Aside from "../components/Aside"
import TarjetaPrincipal from "../components/inicio/TarjetaPrincipal";
import BentoGrid from "../components/inicio/BentoGrid"
import styles from '../app/assets/scss/web/Estilo.module.scss';

export default function Home() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const handleMenuOpen = () => {
    setIsMenuOpen(true);
  };

  const handleMenuClose = () => {
    setIsMenuOpen(false);
  };

  return (
    <div className={styles.page}>
      <Header onMenuOpen={handleMenuOpen} />

      <Aside 
        isOpen={isMenuOpen} 
        onMenuClose={handleMenuClose} 
      />

      <main>
        <TarjetaPrincipal />

        <BentoGrid />
      </main>
    </div>
  );
}