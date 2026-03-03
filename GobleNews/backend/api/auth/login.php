<?php
    session_start();
    header("Content-Type: application/json");
    
    include "../../config/db.inc";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $email = $data["email"] ?? '';
        $password = $data["password"] ?? '';

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
            
            
            $check = $conn->prepare("SELECT * FROM administrador WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $res = $check->get_result();
            $datos = $res->fetch_assoc();

            if ($datos && password_verify($password, trim($datos["password"]))) {
                
                $_SESSION["id"]     = $datos["id"];
                $_SESSION["nombre"] = $datos["nombre"];
                $_SESSION["email"]  = $datos["email"];
                $_SESSION["rol"]    = $datos["rol"];
                
                echo json_encode(["success" => true, "user" => $datos["nombre"]]);
            } else {
                echo json_encode(["success" => false, "message" => "Email o contraseña incorrectos"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Datos inválidos"]);
        }
        exit;
    }
?>
