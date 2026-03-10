<?php
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    $nombre = $_POST['nombre'] ?? null;
    $entidad = $_POST['entidad'] ?? null;
    $imagen = $_FILES['imagen'] ?? null;

    if (!$nombre || !$imagen || !$entidad) {
        echo json_encode(["success" => false, "error" => "Faltan datos"]);
        exit();
    }

    $directorios = [
        'noticia'       => '/../../../public/img/web/noticias/',
        'usuario'       => '/../../../public/img/admin/usuarios/',
        'administrador' => '/../../../public/img/admin/administradores/'
    ];

    if (!array_key_exists($entidad, $directorios)) {
        echo json_encode(["success" => false, "error" => "Entidad no válida"]);
        exit();
    }

    $nombreFinal = "img_" . $nombre;
    $destino = __DIR__ . $directorios[$entidad] . $nombreFinal;

    if (!is_dir(dirname($destino))) {
        mkdir(dirname($destino), 0775, true);
    }

    if (move_uploaded_file($imagen['tmp_name'], $destino)) {
        echo json_encode(["success" => true, "path" => $nombreFinal]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al subir imagen"]);
    }
?>
