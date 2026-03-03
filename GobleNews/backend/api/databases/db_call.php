<?php
    header("Content-Type: application/json");
    session_start();

    class InvalidUpdateException extends Exception {}

    if (!isset($_SESSION["email"])) {
        http_response_code(401);
        echo json_encode(["error" => "No autorizado"]);
        exit();
    }

    require_once "../../config/db_pdo.inc";

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $entidad = $data['entidad'] ?? '';
    $id = $data['id'] ?? null;
    $valores = $data['valores'] ?? [];

    $tablas = [
        'categoria' => ['nombre'],
        'pais' => ['nombre', 'continente'],
        'noticia' => ['nombre', 'url', 'texto_original', 'texto_traducido', 'pais_id', 'medio_id'],
        'usuario' => ['nombre', 'email', 'rol']
    ];

    if (!array_key_exists($entidad, $tablas)) {
        echo json_encode(["success" => false, "message" => "Petición inválida"]);
        exit();
    }
?>
