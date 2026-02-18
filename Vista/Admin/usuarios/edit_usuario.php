<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if (!isset($_GET['id'])) {
    header("location: gestion_usuarios.php");
    die();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["nombre"]) && !empty($_POST["email"])) {
        
        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);
        
        // Si el usuario escribió una nueva contraseña, la actualizamos. Si no, la dejamos igual.
        if(!empty($_POST["password"])) {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nombre, $email, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre, $email, $id);
        }

        if ($stmt->execute()) {
            header("location: gestion_usuarios.php?msg=updated");
            exit();
        } else {
            $error = "Error al actualizar.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    header("location: gestion_usuarios.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4>Editar Usuario</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Contraseña (Dejar en blanco para no cambiar)</label>
                                <input type="password" class="form-control" name="password" placeholder="••••••">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning text-white">Actualizar</button>
                                <a href="gestion_usuarios.php" class="btn btn-light">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>