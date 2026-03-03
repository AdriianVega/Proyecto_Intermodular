<?php
    $host = "localhost";       // Dirección del servidor
    $nombreBD = "noticias"; // Nombre de la base de datos
    $usuario = "root";          // Usuario con acceso a la BD
    $password = "";             // Contraseña del usuario

    $dsn = "mysql:host=$host;dbname=$nombreBD";

    try 
    {
        // Crear conexión
        $pdo = new PDO($dsn, $usuario, $password);
        
        // La conexión fue exitosa
        //echo "Conexión exitosa con PDO.";
    } 
    catch (\PDOException $e) 
    {
        // La conexión falló
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
?>
