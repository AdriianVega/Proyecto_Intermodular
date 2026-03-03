<?php
    require_once "db_call.php";

    try {
        $sql = "SELECT * FROM $entidad";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $resultado
        ]);
    } catch (InvalidUpdateException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
?>
