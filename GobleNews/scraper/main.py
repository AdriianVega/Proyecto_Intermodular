import time
from datetime import datetime
from config import MEDIOS
from rss import leer_rss, extraer_noticia, descargar_imagen
from traductor import traducir
from api import subir_noticia, subir_imagen, url_existe


def obtener_entradas_por_medio():
    entradas_por_medio = []
    for medio in MEDIOS:
        print(f"\nLeyendo RSS: {medio['nombre']}")
        entradas = leer_rss(medio["rss"])
        if entradas:
            print(f"  {len(entradas)} noticias encontradas")
            entradas_por_medio.append((medio, entradas))
        else:
            print("  Sin entradas")
    return entradas_por_medio


def intercalar_entradas(entradas_por_medio):
    intercaladas = []
    max_entradas = max(len(entradas) for _, entradas in entradas_por_medio)
    for i in range(max_entradas):
        for medio, entradas in entradas_por_medio:
            if i < len(entradas):
                intercaladas.append((medio, entradas[i]))
    return intercaladas


def procesar_entrada(medio, entrada, indice, total):
    titulo_original, url, texto_original, imagen_url = extraer_noticia(entrada)
    print(f"\n[{indice}/{total}] {medio['nombre']} — {titulo_original[:60]}")

    if url_existe(url):
        print("    Ya existe, saltando")
        return False

    if not texto_original:
        print("    Sin texto, saltando")
        return False

    if medio["traducir"]:
        print(f"    Traduciendo desde {medio['idioma']}...")
        titulo_traducido, texto_traducido = traducir(
            titulo_original,
            texto_original,
            medio["idioma"]
        )
        time.sleep(1)
    else:
        titulo_traducido = titulo_original
        texto_traducido = texto_original

    path = None
    if imagen_url:
        print("    Descargando imagen...")
        imagen_bytes, extension = descargar_imagen(imagen_url)
        if imagen_bytes:
            path = subir_imagen(imagen_bytes, extension)
            if path:
                print(f"    Imagen subida: {path}")

    exito = subir_noticia(
        titulo=titulo_traducido,
        texto_original=texto_original,
        texto_traducido=texto_traducido,
        url=url,
        pais_id=medio["pais_id"],
        medio_id=medio["medio_id"],
        path=path
    )

    if exito:
        print(f"    Subida: {titulo_traducido[:50]}")
    else:
        print("    Error al subir")

    time.sleep(0.5)
    return exito


def main():
    print(f"GobleNews Scraper - {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print(f"{len(MEDIOS)} medios, procesando en orden intercalado...")

    entradas_por_medio = obtener_entradas_por_medio()

    if not entradas_por_medio:
        print("No se encontraron entradas")
        return

    cola = intercalar_entradas(entradas_por_medio)
    total = len(cola)
    subidas = 0

    print(f"\n{total} noticias en cola (orden intercalado por país)")
    print("=" * 50)

    for i, (medio, entrada) in enumerate(cola, 1):
        if procesar_entrada(medio, entrada, i, total):
            subidas += 1

    print(f"\nScraping completado: {subidas}/{total} subidas")


if __name__ == "__main__":
    main()
