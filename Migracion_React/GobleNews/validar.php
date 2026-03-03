<?php
header("Content-Type: application/json");

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

$usuario = $datos['usuario'] ?? '';
$password = $datos['password'] ?? '';

$esValido = ($usuario === 'admin' && $password === '1234');

$response = [
    "success" => $esValido,
    "mensaje" => $esValido ? "¡Bienvenido, $usuario!" : "Usuario o clave incorrectos"
];

echo json_encode($response);