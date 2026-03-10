OPENROUTER_API_KEY = "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"
API_BASE = "http://localhost:3000/backend/api"
ADMIN_USER_ID = 1
NOTICIAS_POR_MEDIO = 5

MEDIOS = [
    {
        "nombre": "RTVE Noticias",
        "rss": "https://feeds.elpais.com/mrss-s/pages/ep/site/elpais.com/portada",
        "medio_id": 2,
        "pais_id": 1,
        "idioma": "español",
        "traducir": False
    },
    {
        "nombre": "Czech Radio",
        "rss": "https://www.ceskenoviny.cz/sluzby/rss/zpravy.php",
        "medio_id": 3,
        "pais_id": 3,
        "idioma": "checo",
        "traducir": True
    },
    {
        "nombre": "Tagesschau (ARD)",
        "rss": "https://www.tagesschau.de/infoservices/alle-meldungen-100~rss2.xml",
        "medio_id": 4,
        "pais_id": 4,
        "idioma": "alemán",
        "traducir": True
    },
    {
        "nombre": "Le Monde",
        "rss": "https://www.lemonde.fr/rss/une.xml",
        "medio_id": 5,
        "pais_id": 5,
        "idioma": "francés",
        "traducir": True
    },
    {
        "nombre": "ERR Uudised",
        "rss": "https://www.err.ee/rss",
        "medio_id": 6,
        "pais_id": 6,
        "idioma": "estonio",
        "traducir": True
    },
    {
        "nombre": "Gazeta Wyborcza",
        "rss": "https://wyborcza.pl/pub/rss/najnowsze.xml",
        "medio_id": 8,
        "pais_id": 7,
        "idioma": "polaco",
        "traducir": True
    },
    {
        "nombre": "ANSA",
        "rss": "https://www.ansa.it/sito/notizie/topnews/topnews_rss.xml",
        "medio_id": 7,
        "pais_id": 8,
        "idioma": "italiano",
        "traducir": True
    },
]