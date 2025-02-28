<?php
include('bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tarjeta = $_POST["tarjeta_id"];
    $fecha_emision = $_POST["fecha_emision"];
    $fecha_vencimiento = $_POST["fecha_vencimiento"];

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Calcular la diferencia en meses entre ambas fechas
    $fechaInicio = new DateTime($fecha_emision);
    $fechaFin = new DateTime($fecha_vencimiento);
    $diferencia = $fechaInicio->diff($fechaFin);

    $periodo_validez = $diferencia->m + ($diferencia->y * 12); // Total de meses

    // Guardar en la base de datos
    $stmt = $conexion->prepare("UPDATE `tarjetas` SET `Fecha_Emision` = ?, `Fecha_Vencimiento` = ?, `Validez` = ? WHERE ID = ?;");
    $stmt->bind_param("sssi", $fecha_emision, $fecha_vencimiento, $periodo_validez, $id_tarjeta);

    if ($stmt->execute()) {
        header("Location: gestion.php");
        exit();
    } else {
        echo "Error al guardar los datos.";
    }

    $stmt->close();
    $conexion->close();
}
