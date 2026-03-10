<?php
    $tmp = sys_get_temp_dir();
    session_save_path($tmp);

    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => 'localhost',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);
    
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    require_once "../../config/db_pdo.inc";

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Campos incompletos"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, nombre, email, rol, icono, password FROM administrador WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, trim($user['password']))) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["email"] = $user["email"];
            
            echo json_encode([
                "success" => true,
                "user_session" => [
                    "id" => $user["id"],
                    "nombre" => $user["nombre"],
                    "rol" => $user["rol"],
                    "icono" => $user["icono"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error de base de datos"]);
}
