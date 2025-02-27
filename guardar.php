<?php
include('bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $dedication = $_POST["dedication"];
    $validity = $_POST["validity"];
    $tratamiento = $_POST["tratamiento"];
    $duracion = $_POST["duracion"];
    $diseño = $_POST["diseño"];

    // Obtener la fecha actual
    $fecha_actual = date('Y-m-d');
    // Obtener la fecha con 3 meses adicionales
    $fecha_mas_3_meses = date('Y-m-d', strtotime('+3 months'));


    // 🔍 1. Verificar si el diseño ya existe en la tabla "diseños_tarjetas"
    $stmt = $conexion->prepare("SELECT ID FROM diseños_tarjetas WHERE Clase = ?");
    $stmt->bind_param("s", $diseño);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Diseño encontrado, obtener el ID
        $stmt->bind_result($id_diseño);
        $stmt->fetch();
    } else {
        // 🎨 2. Si el diseño no existe, insertarlo y obtener su ID
        $stmt = $conexion->prepare("INSERT INTO diseños_tarjetas (Clase) VALUES (?)");
        $stmt->bind_param("s", $diseño);
        $stmt->execute();
        $id_diseño = $stmt->insert_id; // Obtener el ID recién insertado
    }
    $stmt->close();

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("INSERT INTO `tarjetas`( `Destinatario`, `Texto`, `Validez`, `ID_Diseño`, `Tratamiento`, `Duracion`, `Fecha_Emision`, `Fecha_Vencimiento`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $dedication, $validity, $id_diseño, $tratamiento, $duracion, $fecha_actual, $fecha_mas_3_meses);

    if ($stmt->execute()) {

        $id_tarjeta = $stmt->insert_id; // Obtener el ID recién insertado
        header("Location: pantalla_final.php?id=$id_tarjeta"); // 🔥 Redirige a la pantalla 4
        exit();
    } else {
        echo "Error al guardar los datos.";
    }

    $stmt->close();
    $conexion->close();
}
