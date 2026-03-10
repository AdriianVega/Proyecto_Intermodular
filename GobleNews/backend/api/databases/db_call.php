<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    class InvalidUpdateException extends Exception {}

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $user_id = $data['user_id'] ?? null;

    if (!$user_id) {
        http_response_code(401);
        echo json_encode(["error" => "No autorizado"]);
        exit();
    }

    require_once "../../config/db_pdo.inc";

    $entidad = $data['entidad'] ?? '';
    $id = $data['id'] ?? null;
    $valores = $data['valores'] ?? [];

    $tablas = [
        'categoria'     => ['nombre'],
        'pais'          => ['nombre', 'continente', 'bandera'],
        'medio'         => ['nombre', 'url'],
        'noticia'       => ['titulo', 'url', 'texto_original', 'texto_traducido', 'pais_id', 'medio_id', 'path'],
        'usuario'       => ['nombre', 'email', 'password', 'icono'],
        'administrador' => ['nombre', 'email', 'password', 'rol', 'icono']
    ];

    if (!array_key_exists($entidad, $tablas)) {
        echo json_encode(["success" => false, "message" => "Petición inválida"]);
        exit();
    }
?>
