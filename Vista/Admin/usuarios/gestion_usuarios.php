<?php
    // Iniciamos la sesión
    session_start();

    // Comprobamos el rol para que solo entren administradores
    if (!isset($_SESSION["rol"]) || (int)$_SESSION["rol"] !== 1) {
        
        // Redirigimos al inicio si no es admin
        header("location:../index.php");
        die();
    }

    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Definimos el directorio donde están las fotos
    $directorio = "../img/usuarios/";

    // Si recibimos la orden de crear un test
    if (isset($_GET["crear_test"])) {
        
        // Generamos un sufijo aleatorio para el nombre y email
        $random_suffix = substr(md5(uniqid(mt_rand(), true)), 0, 6);
        
        $nombre = "Usuario Test " . $random_suffix;
        $email = "test_" . $random_suffix . "@gmail.com";
        $password = password_hash("1234", PASSWORD_DEFAULT);
        $rol_empleado = 0;
        $icono = "admin.jpg";

        // Preparamos la consulta para meter el usuario de prueba
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, icono)
                VALUES ('$nombre', '$email', '$password', '$rol_empleado', '$icono')";
        
        // Ejecutamos y redirigimos según el resultado
        if(mysqli_query($conn, $sql)){
            header("location:gestion_usuarios.php?msg=test_ok");
        } else {
            header("location:gestion_usuarios.php?msg=error");
        }
        exit;
    }

    // Configuramos la paginación: registros por página y página actual
    $registros_por_pagina = 15;

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

    // Sacamos el total de usuarios para saber cuántas páginas hay
    $sql_total = "SELECT COUNT(*) as total FROM usuarios";
    $result_total = mysqli_query($conn, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);

    // Calculamos el total de páginas
    $total_registros = $row_total['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    // Si recibimos la orden de eliminar un usuario
    if (isset($_GET["eliminar"])) {
        
        // Recogemos el id a borrar
        $id_usu = intval($_GET["eliminar"]);

        // No dejamos que se borre al admin principal con id 1
        if($id_usu == 1) {
            header("location:gestion_usuarios.php?msg=error_admin");
            die();
        }

        // Lanzamos la consulta para borrar
        $sql = "DELETE FROM usuarios WHERE id = $id_usu";
        
        if(mysqli_query($conn, $sql)){
            header("location:gestion_usuarios.php?msg=deleted");
        } else {
            header("location:gestion_usuarios.php?msg=error");
        }
        exit;
    }

    // Sacamos los usuarios que tocan en esta página
    // Usamos offset para marcar el inicio correcto y el límite de registros
    $sql = "SELECT * FROM usuarios ORDER BY id ASC LIMIT $offset, $registros_por_pagina";
    $res = mysqli_query($conn, $sql);
    
    // Sacamos los datos de la sesión para el panel
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "usuarios";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tablas.css">
</head>
<body class="bg-light">
    <?php include "../php/panel_control.php"; ?>
    
    <div class="container-fluid mt-4">
        <div class="card shadow mt-5">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Equipo de Administración</span>

                <div>
                    <a href="?crear_test=1" class="btn btn-light btn-sm text-primary fw-bold me-2">
                        🎲 Generar Test
                    </a>
                    <a href="ins_usuario.php" class="btn btn-success btn-sm">
                        ➕ Nuevo Usuario
                    </a>
                </div>
            </div>
            <div class="card-body">
                
                <?php
                    // Mostramos los avisos según el mensaje que llegue por la URL
                    if(isset($_GET['msg'])) {
                        if($_GET['msg'] == 'test_ok') { echo '<div class="alert alert-info">🤖 Usuario de prueba generado (Pass: 1234).</div>'; }
                        if($_GET['msg'] == '0') { echo '<div class="alert alert-success">✅ Usuario guardado correctamente.</div>'; }
                        if($_GET['msg'] == 'deleted') { echo '<div class="alert alert-success">🗑️ Usuario eliminado.</div>'; }
                        if($_GET['msg'] == 'error') { echo '<div class="alert alert-danger">❌ Error en la base de datos.</div>'; }
                        if($_GET['msg'] == 'error_admin') { echo '<div class="alert alert-warning">⚠️ No puedes eliminar al Super Admin.</div>'; }
                        if($_GET['msg'] == 'error_mail') { echo '<div class="alert alert-warning">⚠️ Ya existe un usuario con ese email.</div>'; }
                    }
                ?>
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Recorremos los resultados para mostrar la tabla
                            while($row = mysqli_fetch_assoc($res)) {
                        ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><img src="<?=  $directorio.$row["icono"] ?>" alt="Icono de usuario" style="width: 100px;"></td>
                                <td><strong><?= htmlspecialchars($row['nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <?php
                                        // Mostramos el badge según el rol
                                        if($row['rol'] == 1) {
                                    ?>
                                        <span class="badge bg-danger">Administrador</span>
                                    <?php } else {
                                    ?>
                                        <span class="badge bg-info text-dark">Empleado</span>
                                    <?php }
                                    ?>
                                </td>
                                <td><small><?= date("d/m/Y", strtotime($row['create_time'])) ?></small></td>
                                <td class="text-end">
                                    <a href="edit_usuario.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <button onclick="eliminar(<?= $row['id'] ?>)" class="btn btn-sm btn-danger">🗑️</button>
                                </td>
                            </tr>
                        <?php }
                        ?>
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
