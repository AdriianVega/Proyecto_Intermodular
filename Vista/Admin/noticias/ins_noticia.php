<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

// Función auxiliar para nombres de archivo
function limpiarNombreArchivo($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return trim($str, '-');
}

// Cargar listas para los selects
$paises = $conn->query("SELECT id, nombre FROM pais ORDER BY nombre ASC");
$medios = $conn->query("SELECT id, nombre FROM medio ORDER BY nombre ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Directorio de destino
    $directorio = "../../img/noticias/"; 
    $nuevo_nombre_archivo = "";

    // 1. Procesar Imagen (Campo 'url' en la BD)
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nuevo_nombre_archivo = limpiarNombreArchivo($_POST["nombre"]) . "_" . time() . "." . $extension;

        if (getimagesize($archivo_temporal)) {
            move_uploaded_file($archivo_temporal, $directorio . $nuevo_nombre_archivo);
        }
    }

    // 2. Procesar Datos de Texto
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $texto_original = $conn->real_escape_string($_POST["texto_original"]);
    $texto_traducido = $conn->real_escape_string($_POST["texto_traducido"]);
    $pais_id = !empty($_POST["pais_id"]) ? intval($_POST["pais_id"]) : "NULL";
    $medio_id = !empty($_POST["medio_id"]) ? intval($_POST["medio_id"]) : "NULL";
    
    // Fecha actual para fecha_publicacion
    $fecha_publicacion = date('Y-m-d H:i:s');

    // 3. Insertar en BD
    // Nota: 'url' guarda el nombre del archivo de imagen
    $sql = "INSERT INTO noticia (nombre, url, texto_original, texto_traducido, fecha_publicacion, pais_id, medio_id)
            VALUES ('$nombre', '$nuevo_nombre_archivo', '$texto_original', '$texto_traducido', '$fecha_publicacion', $pais_id, $medio_id)";

    if ($conn->query($sql)) {
        header("location: gestion_noticias.php?msg=created");
    } else {
        $error = "Error al guardar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-success text-white border-0 pt-4 px-4">
                        <h4>📝 Redactar Nueva Noticia</h4>
                    </div>
                    <div class="card-body p-5">
                        <?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Titular (Nombre)</label>
                                        <input type="text" class="form-control form-control-lg" name="nombre" required placeholder="Titular impactante...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Texto Original (Contenido)</label>
                                        <textarea class="form-control" name="texto_original" rows="8" required placeholder="Cuerpo de la noticia..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Texto Traducido (Opcional)</label>
                                        <textarea class="form-control" name="texto_traducido" rows="4" placeholder="Versión traducida si aplica..."></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <h5 class="mb-3">Configuración</h5>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">País</label>
                                            <select name="pais_id" class="form-select" required>
                                                <option value="" selected disabled>Seleccionar...</option>
                                                <?php while($p = $paises->fetch_assoc()): ?>
                                                    <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Medio / Fuente</label>
                                            <select name="medio_id" class="form-select" required>
                                                <option value="" selected disabled>Seleccionar...</option>
                                                <?php while($m = $medios->fetch_assoc()): ?>
                                                    <option value="<?= $m['id'] ?>"><?= $m['nombre'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Imagen de Portada</label>
                                            <input type="file" class="form-control" name="imagen" accept="image/*" required>
                                            <div class="form-text small">Se guardará en el campo 'url'.</div>
                                        </div>

                                        <hr>
                                        <button type="submit" class="btn btn-success w-100 py-2">Publicar Noticia</button>
                                        <a href="gestion_noticias.php" class="btn btn-light w-100 mt-2">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>