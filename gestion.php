<?php
include('bd.php');

// Consulta para obtener las tarjetas históricas
$query = "SELECT tarjetas.*, diseños_tarjetas.Clase, diseños_tarjetas.Nombre as DiseñoNombre FROM `tarjetas` INNER JOIN diseños_tarjetas ON tarjetas.ID_Diseño = diseños_tarjetas.ID ORDER BY `tarjetas`.`ID` DESC";
$resultado = $conexion->query($query);

// Verificar si se ha enviado una solicitud para actualizar las fechas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Actualizar las fechas en la base de datos
    $updateQuery = "UPDATE tarjetas_historicas SET fecha_creacion = ?, fecha_vencimiento = ? WHERE id = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param('ssi', $fecha_emision, $fecha_vencimiento, $id);
    $stmt->execute();

    // Redirigir para evitar que el formulario se envíe nuevamente al actualizar
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$total_palabras = 30;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarjetas Históricas</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS y Popper.js (para funcionalidad del modal) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>

    <h2>Editar Tarjetas Históricas</h2>

    <!-- Tabla de tarjetas históricas -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Validez</th>
                <th>Tratamiento</th>
                <th>Duración</th>
                <th>Dedicatoria</th>
                <th>Diseño</th>
                <th>Fecha de Emisión</th>
                <th>Fecha de Vencimiento</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Destinatario']; ?></td>
                    <td><?php echo $row['Validez']; ?></td>
                    <td>
                        <?php
                        $tratamiento = $row['Tratamiento'];
                        echo (strlen($tratamiento) > $total_palabras) ? substr($tratamiento, 0, $total_palabras) . "..." : $tratamiento;
                        ?>
                    </td>
                    <td><?php echo $row['Duracion']; ?></td>
                    <td>
                        <?php
                        $texto = $row['Texto'];
                        echo (strlen($texto) >  $total_palabras) ? substr($texto, 0, $total_palabras) . "..." : $texto;
                        ?>
                    </td>
                    <td><?php echo $row['DiseñoNombre']; ?></td>
                    <td><?php echo $row['Fecha_Emision']; ?></td>
                    <td><?php echo $row['Fecha_Vencimiento']; ?></td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editModal_<?php echo $row['ID']; ?>">Editar</button>
                        <a class="btn btn-secondary" href="./descarga.php?id=<?php echo $row['ID']; ?>">Descargar</a>
                    </td>
                </tr>


                <!-- Modal de Bootstrap -->
                <div class="modal fade" id="editModal_<?php echo $row['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Fechas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editForm" method="POST" action="actualizar.php">
                                <div class="modal-body">
                                    <input type="hidden" name="tarjeta_id" value="<?php echo $row['ID']; ?>">
                                    <div class="form-group">
                                        <label for="fecha_emision">Fecha de Emisión</label>
                                        <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" value="<?php echo $row['Fecha_Emision']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $row['Fecha_Vencimiento']; ?>">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
    <?php $conexion->close(); ?>


</body>

</html>