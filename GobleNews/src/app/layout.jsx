import { Inter, Suez_One } from 'next/font/google';
import "./globals.css";

const inter = Inter({
  weight: ["600", "700", "900"],
  variable: "--font-inter",
  subsets: ["latin"],
});

const suezOne = Suez_One({
  weight: "400",
  variable: "--font-suez-one",
  subsets: ["latin"],
});

export const metadata = {
    title: {
        default: 'GobleNews — Noticias Internacionales',
        template: '%s | GobleNews'
    },
    description: 'Descubre las noticias más importantes de Europa y el mundo, traducidas automáticamente al español. Filtra por país, categoría o palabra clave.',
    keywords: ['noticias internacionales', 'noticias Europa', 'noticias traducidas', 'República Checa', 'Estonia', 'Alemania', 'Polonia'],
    authors: [{ name: 'Adrián Nataniel Vega Pérez' }],
    creator: 'Adrián Nataniel Vega Pérez',
    openGraph: {
        title: 'GobleNews — Noticias Internacionales',
        description: 'Las noticias más importantes de Europa traducidas al español.',
        url: 'https://goblenews.com',
        siteName: 'GobleNews',
        locale: 'es_ES',
        type: 'website',
        images: [
            {
                url: '/img/web/logo_tierra.png',
                width: 800,
                height: 600,
                alt: 'GobleNews'
            }
        ]
    },
    robots: {
        index: true,
        follow: true
    }
};

export default function RootLayout({ children }) {
  return (
    <html lang="es">
      <body className={`${inter.variable} ${suezOne.variable}`} suppressHydrationWarning>
        {children}
      </body>
    </html>
  );
}
