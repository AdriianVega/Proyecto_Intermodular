<?php
    require_once "../databases/db_call.php";

    try {
        if (!$id) {
            throw new Exception("ID no proporcionado");
        }

        $sql = "DELETE FROM $entidad WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([$id]);

        echo json_encode([
            "success" => $resultado,
            "message" => "Registro eliminado correctamente"
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
