<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

// CORRECCIÓN AQUÍ: 3 niveles hacia atrás
include "../../../Modelo/db/db.inc";

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    // Nota: Si usas la tabla en singular o plural, asegúrate aquí.
    // He puesto 'noticia_categoria' y 'categoria' (singular) basado en tus últimos mensajes.
    $conn->query("DELETE FROM noticia_categoria WHERE categoria_id = $id");
    
    if($conn->query("DELETE FROM categoria WHERE id = $id")){
        header("location: gestion_categorias.php?msg=deleted");
    } else {
        header("location: gestion_categorias.php?msg=error");
    }
    exit();
}

$sql = "SELECT * FROM categoria ORDER BY nombre ASC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - GobleNews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Categorías</h2>
            <div class="d-flex gap-2">
                <a href="../menu/menu_inicio.php" class="btn btn-outline-secondary">Volver</a>
                <a href="ins_categoria.php" class="btn btn-primary gradient-button">Nueva Categoría</a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'created'): ?>
                <div class="alert alert-success">Categoría creada correctamente.</div>
            <?php elseif($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success">Categoría actualizada.</div>
            <?php elseif($_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-warning">Categoría eliminada.</div>
            <?php elseif($_GET['msg'] == 'error'): ?>
                <div class="alert alert-danger">Error en la base de datos.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Nombre</th>
                            <th class="p-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="p-3">#<?= $row['id'] ?></td>
                                <td class="p-3 fw-bold"><?= $row['nombre'] ?></td>
                                <td class="p-3 text-end">
                                    <a href="edit_categoria.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                    <a href="gestion_categorias.php?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?');">Borrar</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center p-4">No hay datos o error en la consulta.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>