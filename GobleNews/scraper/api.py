import requests
from datetime import datetime
from config import API_BASE, ADMIN_USER_ID


def url_existe(url):
    try:
        res = requests.post(
            f"{API_BASE}/databases/gestionar.php",
            json={"entidad": "noticia", "user_id": ADMIN_USER_ID},
            timeout=10
        )
        data = res.json()
        if data.get("success"):
            for noticia in data.get("data", []):
                if noticia.get("url") == url:
                    return True
        return False
    except:
        return False


def subir_imagen(imagen_bytes, extension, entidad="noticia"):
    try:
        now = datetime.now()
        nombre = now.strftime("%Y%m%d_%H%M%S_%f") + f".{extension}"

        res = requests.post(
            f"{API_BASE}/handlers/subir_imagen.php",
            data={"nombre": nombre, "entidad": entidad},
            files={"imagen": (nombre, imagen_bytes, f"image/{extension}")},
            timeout=15
        )

        data = res.json()
        if data.get("success"):
            return data.get("path")
        return None

    except Exception as e:
        print(f"    Error al subir imagen: {e}")
        return None


def subir_noticia(titulo, texto_original, texto_traducido, url, pais_id, medio_id, path=None):
    try:
        valores = {
            "titulo": titulo,
            "url": url,
            "texto_original": texto_original,
            "texto_traducido": texto_traducido,
            "pais_id": pais_id,
            "medio_id": medio_id
        }

        if path:
            valores["path"] = path

        payload = {
            "entidad": "noticia",
            "user_id": ADMIN_USER_ID,
            "valores": valores
        }

        res = requests.post(
            f"{API_BASE}/databases/insertar.php",
            json=payload,
            timeout=10
        )

        data = res.json()
        if not data.get("success"):
            print(f"    Respuesta BD: {data}")
        return data.get("success", False)

    except Exception as e:
        print(f"    Error al subir noticia: {e}")
        return False