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

    // Parámetros
    $id_noticia = $_GET['id'] ?? null;
    $categoria  = $_GET['categoria'] ?? null;
    $pais       = $_GET['pais'] ?? null;
    $medio      = $_GET['medio'] ?? null;
    $destacada  = $_GET['destacada'] ?? null;
    $bento      = $_GET['bento'] ?? null;

    try {
        $sql = "SELECT n.id, n.titulo, n.pais_id, n.medio_id, n.path, n.create_time, p.bandera, p.nombre AS nombre_pais
                    FROM noticia n
                    LEFT JOIN pais p ON n.pais_id = p.id";
        $params = [];
        $isSingleRecord = false;

        if ($id_noticia) {
            $sql .= " WHERE n.id = ?";
            $params[] = $id_noticia;
            $isSingleRecord = true;
        }
        elseif ($categoria || $pais || $medio) {
            $sql .= " WHERE 1=1";
            if ($categoria) { $sql .= " AND n.categoria_id = ?"; $params[] = $categoria; }
            if ($pais)      { $sql .= " AND n.pais_id = ?";      $params[] = $pais; }
            if ($medio)     { $sql .= " AND n.medio_id = ?";     $params[] = $medio; }
            $sql .= " ORDER BY n.id DESC";
        }
        elseif ($destacada) {
            $sql .= " ORDER BY n.id DESC LIMIT 1";
            $isSingleRecord = true;
        }
        elseif ($bento) {
            $sql .= " ORDER BY n.id DESC LIMIT 1, 4";
        }
        else {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "Solicitud no reconocida. GET recibido:", "debug" => $_GET]);
            exit();
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $isSingleRecord ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            http_response_code(404);
            echo json_encode(["success" => false, "error" => "No se encontraron resultados"]);
            exit();
        }

        echo json_encode([
            "success" => true,
            "data" => $data,
            "message" => "Datos recuperados correctamente"
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Error de BD: " . $e->getMessage()]);
    }
?>
