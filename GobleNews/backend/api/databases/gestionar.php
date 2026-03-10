<?php
    

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    header("Content-Type: application/json");

    require_once "db_call.php";

    try {
        if ($entidad === 'noticia') {
            $sql = "SELECT n.*, p.nombre AS nombre_pais, m.nombre AS nombre_medio
                    FROM noticia n
                    LEFT JOIN pais p ON n.pais_id = p.id
                    LEFT JOIN medio m ON n.medio_id = m.id";
        } else {
            $sql = "SELECT * FROM $entidad";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $resultado
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => "Error de base de datos: " . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
?>
