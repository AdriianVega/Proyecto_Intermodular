import Image from "next/image";
import styles from "./page.module.css";
import Header from "../components/inicio/Header.jsx"

export default function Home() {
  return (
    <div className={styles.page}>
      <Header />
    </div>
  );
}
