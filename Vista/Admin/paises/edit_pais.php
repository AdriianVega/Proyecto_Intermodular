<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if (!isset($_GET['id'])) {
    header("location: gestion_paises.php");
    die();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["nombre"]) && !empty($_POST["continente"])) {
        $nombre = trim($_POST["nombre"]);
        $continente = trim($_POST["continente"]);

        $stmt = $conn->prepare("UPDATE pais SET nombre = ?, continente = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $continente, $id);

        if ($stmt->execute()) {
            header("location: gestion_paises.php?msg=updated");
            exit();
        } else {
            $error = "Error al actualizar.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM pais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$pais = $res->fetch_assoc();

if (!$pais) {
    header("location: gestion_paises.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar País</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4>Editar País</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre del País</label>
                                <input type="text" class="form-control" name="nombre" value="<?= $pais['nombre'] ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Continente</label>
                                <select class="form-select" name="continente" required>
                                    <option value="África" <?= ($pais['continente'] == 'África') ? 'selected' : '' ?>>África</option>
                                    <option value="América" <?= ($pais['continente'] == 'América') ? 'selected' : '' ?>>América</option>
                                    <option value="Asia" <?= ($pais['continente'] == 'Asia') ? 'selected' : '' ?>>Asia</option>
                                    <option value="Europa" <?= ($pais['continente'] == 'Europa') ? 'selected' : '' ?>>Europa</option>
                                    <option value="Oceanía" <?= ($pais['continente'] == 'Oceanía') ? 'selected' : '' ?>>Oceanía</option>
                                    <option value="Antártida" <?= ($pais['continente'] == 'Antártida') ? 'selected' : '' ?>>Antártida</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning text-white">Actualizar</button>
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