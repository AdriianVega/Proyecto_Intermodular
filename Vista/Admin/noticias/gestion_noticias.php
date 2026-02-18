<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("location: ../../control.php");
    die();
}

include "../../../Modelo/db/db.inc";

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    // Eliminamos relaciones si existieran en tablas pivote (opcional según tu diseño)
    $conn->query("DELETE FROM noticia_categoria WHERE noticia_id = $id");
    
    // Eliminamos la noticia
    if($conn->query("DELETE FROM noticia WHERE id = $id")){
        header("location: gestion_noticias.php?msg=deleted");
    } else {
        header("location: gestion_noticias.php?msg=error");
    }
    exit();
}

// Paginación
$registros_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $registros_por_pagina;

// Total registros
$sql_total = "SELECT COUNT(*) as total FROM noticia";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_paginas = ceil($row_total['total'] / $registros_por_pagina);

// Consulta Principal con JOINs para mostrar nombres de País y Medio
$sql = "SELECT n.*, p.nombre AS nombre_pais, m.nombre AS nombre_medio
        FROM noticia n
        LEFT JOIN pais p ON n.pais_id = p.id
        LEFT JOIN medio m ON n.medio_id = m.id
        ORDER BY n.fecha_publicacion DESC
        LIMIT $offset, $registros_por_pagina";

$resultado = $conn->query($sql);
$directorio_img = "../../img/noticias/"; // Ruta relativa desde Admin/noticias/ a img/noticias/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Noticias - GobleNews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin/estilo.css">
</head>
<body class="bg-light">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Noticias Publicadas</h2>
            <div class="d-flex gap-2">
                <a href="../menu/menu_inicio.php" class="btn btn-outline-secondary">Volver al Menú</a>
                <a href="ins_noticia.php" class="btn btn-primary gradient-button">➕ Redactar Noticia</a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'created'): ?>
                <div class="alert alert-success">Noticia publicada correctamente.</div>
            <?php elseif($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success">Noticia actualizada.</div>
            <?php elseif($_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-warning">Noticia eliminada.</div>
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
                            <th class="p-3">Imagen</th>
                            <th class="p-3">Titular (Nombre)</th>
                            <th class="p-3">País</th>
                            <th class="p-3">Medio</th>
                            <th class="p-3">Fecha</th>
                            <th class="p-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="p-3">#<?= $row['id'] ?></td>
                                <td class="p-3">
                                    <?php if(!empty($row['url'])): ?>
                                        <img src="<?= $directorio_img . $row['url'] ?>" alt="Img" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <span class="text-muted small">Sin img</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 fw-bold text-wrap" style="max-width: 300px;"><?= htmlspecialchars($row['nombre']) ?></td>
                                <td class="p-3"><span class="badge bg-info text-dark"><?= htmlspecialchars($row['nombre_pais'] ?? 'Global') ?></span></td>
                                <td class="p-3"><span class="badge bg-secondary"><?= htmlspecialchars($row['nombre_medio'] ?? 'Propio') ?></span></td>
                                <td class="p-3"><small><?= date("d/m/Y", strtotime($row['fecha_publicacion'])) ?></small></td>
                                <td class="p-3 text-end">
                                    <a href="edit_noticia.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">✏️</a>
                                    <a href="gestion_noticias.php?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Borrar esta noticia permanentemente?');">🗑️</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center p-5">No hay noticias registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if($total_paginas > 1): ?>
                <nav class="mt-4 mb-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
                        </li>
                        <li class="page-item disabled"><span class="page-link">Página <?= $pagina ?> de <?= $total_paginas ?></span></li>
                        <li class="page-item <?= ($pagina >= $total_paginas) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>