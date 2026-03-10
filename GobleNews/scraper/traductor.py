import requests
import json
import re
from config import OPENROUTER_API_KEY


def llamar_ia(prompt):
    response = requests.post(
        "https://openrouter.ai/api/v1/chat/completions",
        headers={
            "Authorization": f"Bearer {OPENROUTER_API_KEY}",
            "Content-Type": "application/json"
        },
        json={
            "model": "openrouter/auto",
            "messages": [
                {"role": "user", "content": prompt}
            ]
        },
        timeout=60
    )
    data = response.json()
    return data["choices"][0]["message"]["content"]


def parsear_json_robusto(contenido):
    contenido = re.sub(r'```json|```', '', contenido).strip()

    try:
        return json.loads(contenido)
    except json.JSONDecodeError:
        pass

    titulo_match = re.search(r'"titulo"\s*:\s*"(.*?)"(?=\s*,|\s*})', contenido, re.DOTALL)
    texto_match = re.search(r'"texto"\s*:\s*"(.*?)"(?=\s*})', contenido, re.DOTALL)

    if titulo_match and texto_match:
        return {
            "titulo": titulo_match.group(1),
            "texto": texto_match.group(1)
        }

    return None


def traducir(titulo, texto, idioma_origen):
    try:
        prompt = f"""Traduce al español este articulo de noticias escrito en {idioma_origen}.
Responde UNICAMENTE con este JSON, sin explicaciones ni backticks:
{{"titulo": "titulo en español aqui", "texto": "traduccion completa del articulo al español aqui"}}

Titulo: {titulo}
Texto: {texto}"""

        contenido = llamar_ia(prompt)
        resultado = parsear_json_robusto(contenido)

        print(resultado)

        if resultado:
            return resultado.get("titulo", titulo), resultado.get("texto", texto)

        print("    JSON invalido, reintentando...")
        prompt_simple = f"""Traduce al español:
Titulo: {titulo}
Texto: {texto[:2000]}

Responde SOLO con JSON: {{"titulo": "...", "texto": "..."}}"""

        contenido = llamar_ia(prompt_simple)
        resultado = parsear_json_robusto(contenido)

        print(resultado)

        if resultado:
            return resultado.get("titulo", titulo), resultado.get("texto", texto)

        print("    No se pudo parsear JSON, usando original")
        return titulo, texto

    except Exception as e:
        print(f"    Error en traduccion: {e}")
        return titulo, texto