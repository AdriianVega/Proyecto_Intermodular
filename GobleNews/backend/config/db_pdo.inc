<?php
    $host = "127.0.0.1";
    $nombreBD = "noticias";
    $usuario = "root";
    $password = "";

    $dsn = "mysql:host=$host;dbname=$nombreBD;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $usuario, $password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
    } catch (\PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "success" => false,
            "error" => "Fallo de conexión a la Base de Datos",
            "detalle" => $e->getMessage()
        ]);
        exit();
    }
?>
