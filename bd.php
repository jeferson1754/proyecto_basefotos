<?php

include('funciones.php');

$usuario  = "root";
$password = "";
$servidor = "localhost";
$basededatos = "epiz_32740026_r_user";
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
//mysqli_query($conexion,"SET SESSION collation_connection ='utf8'");
$dbo = mysqli_select_db($conexion, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

// Establecer la zona horaria de Santiago de Chile
date_default_timezone_set('America/Santiago');

// AGREGANDO CHARSET UTF8
if (!mysqli_set_charset($conexion, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conexion));
    exit();
}


try {
    $db = new PDO("mysql:host={$servidor};dbname={$basededatos}", $usuario, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Connection error: " . $exception->getMessage());
}
