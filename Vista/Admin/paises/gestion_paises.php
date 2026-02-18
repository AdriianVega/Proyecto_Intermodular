<?php
    // Iniciamos la sesión
    session_start();

    // Comprobamos la sesión para entrar
    if(!isset($_SESSION["nombre"])) {
        header("location:../index.php");
        die();
    }
    
    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Si recibimos la orden de crear un pedido de prueba
    if (isset($_GET["crear_test"])) {

        // Asignamos IDs por defecto asumiendo que existen para testear
        $cliente_id = 1;
        $producto_id = 1;

        // Preparamos la consulta para meter el pedido de prueba
        $sql = "INSERT INTO pedidos (cliente_id, producto_id)
                VALUES ('$cliente_id', '$producto_id')";
        
        // Ejecutamos y redirigimos según el resultado
        if(mysqli_query($conn, $sql)){
            header("location:gestion_pedidos.php?msg=test_ok");
        } else {
            header("location:gestion_pedidos.php?msg=error");
        }
        exit;
    }

    // Configuramos la paginación: registros por página y página actual
    $registros_por_pagina = 25;

    // Obtenemos la página actual de la URL, si no está definida, por defecto será la 1
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    // Nos aseguramos que la página no sea menor que 1
    if ($pagina < 1) {
            $pagina = 1;
    }

    // Calculamos el offset para la consulta SQL
    // La lógica del calculo es le restamos 1 a la página actual y lo multiplicamos por los registros por página
    // Esto es porque la primera página es la 1, pero en SQL el offset empieza en 0
    $offset = ($pagina - 1) * $registros_por_pagina;

    // Sacamos el total de pedidos para saber cuántas páginas hay
    $sql_total = "SELECT COUNT(*) as total FROM pedidos";
    $result_total = mysqli_query($conn, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);

    // Calculamos el total de páginas
    $total_registros = $row_total['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    // Si recibimos la orden de eliminar un pedido
    if (isset($_GET["eliminar"])) {
        
        // Recogemos el id a borrar
        $id = intval($_GET["eliminar"]);

        // Lanzamos la consulta para borrar por id
        $sql = "DELETE FROM pedidos WHERE id = $id";
        
        if(mysqli_query($conn, $sql)){
            header("location:gestion_pedidos.php?msg=deleted");
        } else {
            header("location:gestion_pedidos.php?msg=error");
        }
        exit;
    }

    // Sacamos los pedidos con JOINS para obtener los nombres en lugar de los IDs
    // Usamos offset para marcar el inicio correcto y el límite de registros
    $sql = "SELECT p.*, c.nombre AS nombre_cliente, pr.nombre AS nombre_producto
            FROM pedidos p
            LEFT JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN productos pr ON p.producto_id = pr.id
            ORDER BY p.id ASC
            LIMIT $offset, $registros_por_pagina";

    $res = mysqli_query($conn, $sql);

    // Sacamos los datos de la sesión para el panel
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "pedidos";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tablas.css">
</head>
<body class="bg-light">

    <?php include "../php/panel_control.php"; ?>

    <div class="container-fluid mt-4">
        <div class="card shadow mt-5">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Gestión de Pedidos</span>

                <div>
                    <a href="?crear_test=1" class="btn btn-light btn-sm text-primary fw-bold me-2">
                        🎲 Generar Test
                    </a>
                    <a href="ins_pedido.php" class="btn btn-success btn-sm">
                        ➕ Nuevo Pedido
                    </a>
                </div>
            </div>
            <div class="card-body">
                
                <?php
                    // Mostramos los avisos según el mensaje que llegue por la URL
                    if(isset($_GET['msg'])) {
                        if($_GET['msg'] == 'test_ok') { echo '<div class="alert alert-info">🤖 Pedido de prueba generado.</div>'; }
                        if($_GET['msg'] == '0') { echo '<div class="alert alert-success">✅ Pedido guardado correctamente.</div>'; }
                        if($_GET['msg'] == 'deleted') { echo '<div class="alert alert-success">🗑️ Pedido eliminado.</div>'; }
                        if($_GET['msg'] == 'error') { echo '<div class="alert alert-danger">❌ Error en la base de datos. Verifique que los clientes/productos existan.</div>'; }
                    } ?>

                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Fecha Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Recorremos los resultados para mostrar la tabla
                            while($row = mysqli_fetch_assoc($res)) { ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><strong><?= htmlspecialchars($row['nombre_cliente'] ?? "Desconocido/Borrado") ?></strong></td>
                                <td><?= htmlspecialchars($row["nombre_producto"] ?? "Desconocido/Borrado") ?></td>
                                <td><small><?= date("d/m/Y H:i", strtotime($row['create_time'])) ?></small></td>
                                <td class="text-end">
                                    <a href="edit_pedido.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <?php
                                        // Solo el admin puede ver el botón de borrar
                                        if ($_SESSION["rol"] == "1") {
                                            echo '<button onclick="eliminar(' . $row['id'] . ')" class="btn btn-sm btn-danger">🗑️</button>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <nav aria-label="Navegación de página" class="mt-4">
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo; Anterior</span>
                            </a>
                        </li>

                        <li class="page-item disabled">
                            <span class="page-link">
                                Página <?= $pagina ?> de <?= $total_paginas ?>
                            </span>
                        </li>

                        <li class="page-item <?= ($pagina >= $total_paginas) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Siguiente">
                                <span aria-hidden="true">Siguiente &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <?php include "../php/custom_delete.php"; ?>
</body>
</html>
