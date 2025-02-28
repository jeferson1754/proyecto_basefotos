<?php
include 'bd.php'; // Asegúrate de que este archivo conecta a la BD

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conexion->prepare("DELETE FROM diseños_tarjetas WHERE ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
        header("Location: nuevo_diseño.php");
        exit();
    } else {
        echo "error";
    }

    $stmt->close();
    $conexion->close();

}

