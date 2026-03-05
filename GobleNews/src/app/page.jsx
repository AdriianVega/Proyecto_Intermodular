'use client'

import Image from "next/image";
import React, { useState } from "react";
import styles from "./page.module.css";
import Header from "../components/inicio/Header"
import Aside from "../components/Aside"

export default function Home() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const handleMenuClose = () => {
    setIsMenuOpen(false);
  };

  return (
    <div className={styles.page}>
      <Header />

      <Aside 
        isOpen={isMenuOpen} 
        onMenuClose={handleMenuClose} 
      />
    </div>
  );
}
