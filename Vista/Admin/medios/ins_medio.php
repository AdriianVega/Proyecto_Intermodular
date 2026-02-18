<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["nombre"])) {
        $nombre = trim($_POST["nombre"]);
        $url = trim($_POST["url"]);

        $stmt = $conn->prepare("INSERT INTO medio (nombre, url) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $url);

        if ($stmt->execute()) {
            header("location: gestion_medios.php?msg=created");
            exit();
        } else {
            $error = "Error al guardar.";
        }
    } else {
        $error = "El nombre es obligatorio.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Medio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4>Nuevo Medio</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre del Medio</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Ej: CNN, BBC..." required autofocus>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">URL / Sitio Web</label>
                                <input type="url" class="form-control" name="url" placeholder="https://..." required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary gradient-button">Guardar</button>
                                <a href="gestion_medios.php" class="btn btn-light">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>