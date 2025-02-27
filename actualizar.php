<?php
include('bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tarjeta = $_POST["tarjeta_id"];
    $fecha_emision = $_POST["fecha_emision"];
    $fecha_vencimiento = $_POST["fecha_vencimiento"];


    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    //echo $id_tarjeta . "<br>" . $fecha_emision . "<br>" . $fecha_vencimiento;

    
    $stmt = $conexion->prepare("UPDATE `tarjetas` SET `Fecha_Emision` = ?, `Fecha_Vencimiento` = ? WHERE ID = ?;");
    $stmt->bind_param("ssi",  $fecha_emision, $fecha_vencimiento, $id_tarjeta);

    if ($stmt->execute()) {

        header("Location: gestion.php"); 
        exit();
    } else {
        echo "Error al guardar los datos.";
    }

    $stmt->close();
    $conexion->close();
    
}
