<?php
include 'bd.php'; // Conexión a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $clase = trim($_POST['clase']);
    $archivo = trim($_POST['archivo']);


    // Si el usuario sube una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        // Procesar imagen
        $target_dir = "Diseños/"; // Carpeta donde se guardarán las imágenes
        // Obtener la extensión del archivo
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);

        // Crear un nuevo nombre basado en el nombre del diseño + un ID único
        $nuevo_nombre = strtolower(str_replace(" ", "_", $_POST['clase'])) . "." . $extension;

        $target_file = $target_dir . $nuevo_nombre;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
    }

    $stmt = $conexion->prepare("UPDATE disenos_tarjetas SET Nombre = ?,Clase = ?, Link = ?  WHERE ID = ?");
    $stmt->bind_param("sssi", $nombre, $clase, $archivo, $id);

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
