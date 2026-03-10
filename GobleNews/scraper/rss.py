import feedparser
import requests
import re
from bs4 import BeautifulSoup
from config import NOTICIAS_POR_MEDIO

FRASES_NAVEGACION = [
    "ir al contenido", "ir al menú", "ir a la búsqueda",
    "přejít k obsahu", "přejít k hlavnímu", "přejít k vyhledávání",
    "skip to content", "skip to main", "jump to navigation",
    "presentación comercial", "komerční prezentace"
]


def limpiar_texto(texto):
    if not texto:
        return ""
    soup = BeautifulSoup(texto, "html.parser")
    texto_limpio = soup.get_text(separator=" ")
    texto_limpio = re.sub(r'\s+', ' ', texto_limpio).strip()
    return texto_limpio


def filtrar_navegacion(texto):
    lineas = texto.split(".")
    lineas_limpias = []
    for linea in lineas:
        linea_lower = linea.lower().strip()
        if not any(frase in linea_lower for frase in FRASES_NAVEGACION):
            lineas_limpias.append(linea)
    return ".".join(lineas_limpias).strip()


def obtener_imagen(soup, url_base):
    og_image = soup.find("meta", property="og:image")
    if og_image and og_image.get("content"):
        return og_image["content"]

    twitter_image = soup.find("meta", attrs={"name": "twitter:image"})
    if twitter_image and twitter_image.get("content"):
        return twitter_image["content"]

    for selector in ["article img", ".article-body img", ".post-content img", "main img"]:
        img = soup.select_one(selector)
        if img and img.get("src"):
            src = img["src"]
            if src.startswith("http"):
                return src
            if src.startswith("//"):
                return "https:" + src

    return None


def descargar_imagen(url_imagen):
    try:
        headers = {"User-Agent": "Mozilla/5.0"}
        res = requests.get(url_imagen, headers=headers, timeout=10)
        res.raise_for_status()
        content_type = res.headers.get("Content-Type", "image/jpeg")
        extension = content_type.split("/")[-1].split(";")[0].strip()
        if extension not in ["jpeg", "jpg", "png", "webp", "gif"]:
            extension = "jpg"
        return res.content, extension
    except Exception as e:
        print(f"    No se pudo descargar la imagen: {e}")
        return None, None


def obtener_articulo_completo(url):
    try:
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
        }
        res = requests.get(url, headers=headers, timeout=10)
        res.raise_for_status()
        soup = BeautifulSoup(res.content, "html.parser")

        imagen_url = obtener_imagen(soup, url)

        for tag in soup(["nav", "header", "footer", "aside", "script", "style"]):
            tag.decompose()

        texto = ""
        for selector in ["article", ".article-body", ".article-content", ".post-content", ".entry-content", "main"]:
            elemento = soup.select_one(selector)
            if elemento:
                texto = limpiar_texto(elemento.get_text(separator=" "))
                if len(texto) > 200:
                    break

        if not texto:
            parrafos = soup.find_all("p")
            texto = " ".join(p.get_text() for p in parrafos if len(p.get_text()) > 50)
            texto = limpiar_texto(texto)

        texto = filtrar_navegacion(texto)

        return texto, imagen_url

    except Exception as e:
        print(f"    No se pudo obtener el articulo: {e}")
        return "", None


def leer_rss(url):
    try:
        feed = feedparser.parse(url)
        return feed.entries[:NOTICIAS_POR_MEDIO]
    except Exception as e:
        print(f"  Error al leer RSS: {e}")
        return []


def extraer_noticia(entrada):
    titulo = limpiar_texto(entrada.get("title", ""))
    url = entrada.get("link", "")
    resumen_rss = limpiar_texto(entrada.get("summary", ""))

    texto_completo, imagen_url = obtener_articulo_completo(url)
    texto = texto_completo if len(texto_completo) > len(resumen_rss) else resumen_rss

    return titulo, url, texto, imagen_url