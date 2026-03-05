<?php
    require_once "db_call.php";

    try {
        $columnasSQL = [];
        $parametros = [];

        foreach ($valores as $columna => $valor) {

            if (in_array($columna, $tablas[$entidad])) {
                $columnasSQL[] = "$columna = ?";
                $parametros[] = $valor;
            }
        }

        if (empty($columnasSQL)) {
            throw new InvalidUpdateException("No hay campos válidos para insertar");
        }

        $setClause = implode(", ", $columnasSQL);
        $sql = "INSERT INTO $entidad SET $setClause";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute($parametros);

        echo json_encode([
            "success" => $resultado,
            "message" => "Registro en " . ucfirst($entidad) . " insertado."
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
