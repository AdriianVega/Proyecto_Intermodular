import Image from "next/image";
import styles from "./page.module.css";

export default function Home() {
  return (
    <div className={styles.page}>
      <main>
        <h1>GobleNews</h1>
        <p>El portal de noticias automatizado está en camino...</p>
      </main>
    </div>
  );
}
