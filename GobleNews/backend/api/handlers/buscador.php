<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    require_once "../../config/db_pdo.inc";

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    $busqueda = isset($_GET['search']) ? trim($_GET['search']) : null;
    $pagina = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    
    $limite = 10;
    $offset = ($pagina - 1) * $limite;

    try {
        $countSql = "SELECT COUNT(*) as total FROM noticia n LEFT JOIN pais p ON n.pais_id = p.id";
        $params = [];

        if (!empty($busqueda)) {
            $countSql .= " WHERE n.titulo LIKE ? OR n.texto_traducido LIKE ? OR p.nombre LIKE ?";
            $searchTerm = "%" . $busqueda . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }

        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalResultados = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $totalPaginas = ceil($totalResultados / $limite);

        $sql = "SELECT n.id, n.titulo, n.pais_id, n.texto_traducido, n.medio_id, n.path, n.create_time, p.bandera, p.nombre AS nombre_pais
                FROM noticia n
                LEFT JOIN pais p ON n.pais_id = p.id";

        if (!empty($busqueda)) {
            $sql .= " WHERE n.titulo LIKE ? OR n.texto_traducido LIKE ? OR p.nombre LIKE ?";
        }

        $sql .= " ORDER BY n.create_time DESC LIMIT $limite OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $data ? $data : [],
            "pagination" => [
                "pagina_actual" => $pagina,
                "total_paginas" => $totalPaginas,
                "total_resultados" => $totalResultados
            ],
            "message" => $data ? "Datos recuperados correctamente" : "No se encontraron resultados"
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Error de BD: " . $e->getMessage()]);
    }
?>
