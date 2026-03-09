import styles from "@/app/assets/scss/web/pages/SobreNosotros.module.scss"

export default function SobreNosotros() {
    return (
        <main className={styles.mainSobreNosotros}>
            <section className={styles.heroSection}>
                <h1>Sobre GobleNews</h1>
                <p className={styles.tagline}>Información global, perspectiva local.</p>
            </section>

            <section className={styles.gridInfo}>
                <article className={styles.cardInfo}>
                    <h2>Nuestra Misión</h2>
                    <p>Conectar al mundo a través de noticias verificadas y análisis profundo, eliminando las barreras del lenguaje mediante tecnología de vanguardia.</p>
                </article>

                <article className={styles.cardInfo}>
                    <h2>El Proyecto</h2>
                    <p>GobleNews nace como una plataforma de noticias inteligente, diseñada para ofrecer una experiencia de usuario fluida y visualmente inmersiva.</p>
                </article>
            </section>

            <section className={styles.contactoDirecto}>
                <h2>Contacto</h2>
                <div className={styles.datosContacto}>
                    <p><strong>Email:</strong> contacto@goblenews.com</p>
                    <p><strong>Teléfono:</strong> +34 900 000 000</p>
                </div>
            </section>
        </main>
    )
}