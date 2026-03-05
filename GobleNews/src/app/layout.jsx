import { Inter, Suez_One } from 'next/font/google';
import "./globals.css";

const inter = Inter({
  weight: "600",
  variable: "--font-inter",
  subsets: ["latin"],
});

const suezOne = Suez_One({
  weight: "400",
  variable: "--font-suez-one",
  subsets: ["latin"],
});

export const metadata = {
  title: "GobleNews",
  description: "GobleNews es un proyecto de periodismo técnico que utiliza inteligencia artificial para generar noticias sobre tecnología, ciencia, salud, deportes y entretenimiento. Nuestro objetivo es ofrecer contenido de calidad y actualizado para nuestros lectores, utilizando las últimas herramientas de IA para analizar datos y generar artículos informativos y atractivos.",
};

export default function RootLayout({ children }) {
  return (
    <html lang="es">
      <body className={`${inter.variable} ${suezOne.variable}`}>
        {children}
      </body>
    </html>
  );
}
