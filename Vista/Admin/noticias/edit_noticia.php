<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if (!isset($_GET['id'])) {
    header("location: gestion_noticias.php");
    die();
}

$id = intval($_GET['id']);
$directorio = "../../img/noticias/";

// Función auxiliar
function limpiarNombreArchivo($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return trim($str, '-');
}

// Procesar Formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $texto_original = $conn->real_escape_string($_POST["texto_original"]);
    $texto_traducido = $conn->real_escape_string($_POST["texto_traducido"]);
    $pais_id = !empty($_POST["pais_id"]) ? intval($_POST["pais_id"]) : "NULL";
    $medio_id = !empty($_POST["medio_id"]) ? intval($_POST["medio_id"]) : "NULL";

    // Iniciar consulta base
    $sql = "UPDATE noticia SET 
            nombre = '$nombre', 
            texto_original = '$texto_original', 
            texto_traducido = '$texto_traducido',
            pais_id = $pais_id,
            medio_id = $medio_id ";

    // Verificar si se subió nueva imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nuevo_nombre_archivo = limpiarNombreArchivo($_POST["nombre"]) . "_" . time() . "." . $extension;

        if (getimagesize($archivo_temporal)) {
            move_uploaded_file($archivo_temporal, $directorio . $nuevo_nombre_archivo);
            // Añadir a la consulta SQL
            $sql .= ", url = '$nuevo_nombre_archivo' ";
        }
    }

    $sql .= " WHERE id = $id";

    if ($conn->query($sql)) {
        header("location: gestion_noticias.php?msg=updated");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}

// Obtener datos actuales
$noticia = $conn->query("SELECT * FROM noticia WHERE id = $id")->fetch_assoc();
if (!$noticia) die("Noticia no encontrada");

// Listas para selects
$paises = $conn->query("SELECT id, nombre FROM pais ORDER BY nombre ASC");
$medios = $conn->query("SELECT id, nombre FROM medio ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white border-0 pt-4 px-4">
                        <h4>✏️ Editar Noticia</h4>
                    </div>
                    <div class="card-body p-5">
                        <?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Titular (Nombre)</label>
                                        <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($noticia['nombre']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Texto Original</label>
                                        <textarea class="form-control" name="texto_original" rows="8" required><?= htmlspecialchars($noticia['texto_original']) ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Texto Traducido</label>
                                        <textarea class="form-control" name="texto_traducido" rows="4"><?= htmlspecialchars($noticia['texto_traducido']) ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="mb-3 text-center">
                                            <label class="form-label d-block text-start small text-muted">Imagen Actual</label>
                                            <?php if(!empty($noticia['url'])): ?>
                                                <img src="<?= $directorio . $noticia['url'] ?>" class="img-fluid rounded mb-2" style="max-height: 150px;">
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Cambiar Imagen</label>
                                            <input type="file" class="form-control" name="imagen" accept="image/*">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">País</label>
                                            <select name="pais_id" class="form-select" required>
                                                <?php while($p = $paises->fetch_assoc()): ?>
                                                    <option value="<?= $p['id'] ?>" <?= ($p['id'] == $noticia['pais_id']) ? 'selected' : '' ?>>
                                                        <?= $p['nombre'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Medio</label>
                                            <select name="medio_id" class="form-select" required>
                                                <?php while($m = $medios->fetch_assoc()): ?>
                                                    <option value="<?= $m['id'] ?>" <?= ($m['id'] == $noticia['medio_id']) ? 'selected' : '' ?>>
                                                        <?= $m['nombre'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <hr>
                                        <button type="submit" class="btn btn-warning w-100 py-2">Guardar Cambios</button>
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