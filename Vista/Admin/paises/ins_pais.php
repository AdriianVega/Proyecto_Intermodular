<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["nombre"]) && !empty($_POST["continente"])) {
        $nombre = trim($_POST["nombre"]);
        $continente = trim($_POST["continente"]);

        $stmt = $conn->prepare("INSERT INTO pais (nombre, continente) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $continente);

        if ($stmt->execute()) {
            header("location: gestion_paises.php?msg=created");
            exit();
        } else {
            $error = "Error al guardar.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo País</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4>Nuevo País</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre del País</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Ej: España, Japón..." required autofocus>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Continente</label>
                                <select class="form-select" name="continente" required>
                                    <option value="" selected disabled>Selecciona uno...</option>
                                    <option value="África">África</option>
                                    <option value="América">América</option>
                                    <option value="Asia">Asia</option>
                                    <option value="Europa">Europa</option>
                                    <option value="Oceanía">Oceanía</option>
                                    <option value="Antártida">Antártida</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary gradient-button">Guardar</button>
                                <a href="gestion_paises.php" class="btn btn-light">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>