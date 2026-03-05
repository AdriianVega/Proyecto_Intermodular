<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Content-Type: application/json");

    require_once "../../config/db_pdo.inc";

    // Parámetros
    $id_noticia = $_GET['id'] ?? null;
    $categoria  = $_GET['categoria'] ?? null;
    $pais       = $_GET['pais'] ?? null;
    $medio      = $_GET['medio'] ?? null;
    $destacada  = $_GET['destacada'] ?? null;
    $bento      = $_GET['bento'] ?? null;

    try {
        $sql = "SELECT n.*, p.bandera, p.nombre AS nombre_pais 
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
            echo json_encode(["success" => false, "error" => "No se entendió la solicitud"]);
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