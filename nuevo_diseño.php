<?php
include('bd.php'); // Asegúrate de tener la conexión a la BD

// Manejar el formulario de subida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_disenio = $_POST['nombre_disenio'];
    $clase = $_POST['clase'];
    $archivo = $_POST['archivo'];

    // Procesar imagen
    $target_dir = "Diseños/"; // Carpeta donde se guardarán las imágenes
    // Obtener la extensión del archivo
    $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);

    // Crear un nuevo nombre basado en el nombre del diseño + un ID único
    $nuevo_nombre = strtolower(str_replace(" ", "_", $_POST['clase'])) . "." . $extension;

    $target_file = $target_dir . $nuevo_nombre;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);

    // Guardar en la BD
    $stmt = $conexion->prepare("INSERT INTO disenos_tarjetas (Nombre, Clase, Link) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre_disenio, $clase, $archivo);
    $stmt->execute();
    $stmt->close();

    // Mensaje de éxito
    $mensaje = "Diseño agregado correctamente";
    $tipo_mensaje = "success";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Diseños</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Contenedor de notificaciones -->
    <div class="toast-container">
        <?php if (isset($mensaje)): ?>
            <div class="toast show align-items-center text-white bg-<?php echo $tipo_mensaje; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $mensaje; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Encabezado de página -->
    <div class="page-header mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="display-5 fw-bold">
                    <i class="fas fa-palette me-2"></i> Gestión de Diseños
                </h1>
                <a href="index.php" class="btn btn-light">
                    <i class="fas fa-home me-2"></i> Inicio
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Columna para formulario -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Nuevo Diseño</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data" id="designForm">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i> Nombre del Diseño:
                                </label>
                                <input type="text" class="form-control" name="nombre_disenio" required placeholder="Ej: Diseño Navideño">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-layer-group me-1"></i> Clase:
                                </label>
                                <input type="text" class="form-control" name="clase" required placeholder="Ej: Clase.css">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-file-alt me-2"></i> Archivo:
                                </label>
                                <input type="text" class="form-control" name="archivo" required placeholder="Ej: diseño_normal.php">
                            </div>


                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-image me-1"></i> Imagen del Diseño:
                                </label>
                                <input type="file" class="form-control" name="imagen" accept="image/*" required id="designImage">
                                <div class="preview-container mt-2">
                                    <img id="imagePreview" class="img-fluid rounded">
                                    <p class="text-muted small mb-0 mt-1">La imagen debe tener buena resolución</p>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Guardar Diseño
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna para tabla -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-th-list me-2"></i> Diseños Disponibles</h5>
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Buscar diseño..." id="searchDesign">
                            <button class="btn btn-outline-light" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php
                        $result = $conexion->query("SELECT * FROM disenos_tarjetas ORDER BY ID DESC");
                        if ($result->num_rows > 0):
                        ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="designsTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th>Nombre</th>
                                            <th>Clase</th>
                                            <th class="text-center">Vista Previa</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()):
                                            // Determinar clase para el badge
                                            $badgeClass = getBadgeClass($row['Clase']);

                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $row['ID']; ?></td>
                                                <td>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($row['Nombre']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-design <?php echo $badgeClass; ?>">
                                                        <?php echo htmlspecialchars($row['Clase']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <img src="./Diseños/<?php echo strtolower($row['Clase']); ?>.png"
                                                        class="design-thumbnail"
                                                        alt="<?php echo htmlspecialchars($row['Nombre']); ?>">
                                                </td>
                                                <td class="text-center action-buttons">
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verModal<?php echo $row['ID']; ?>"
                                                        title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editarModal<?php echo $row['ID']; ?>"
                                                        title="Editar diseño">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#eliminarModal_<?php echo $row['ID']; ?>"
                                                        title="Eliminar diseño">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="editarModal<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $row['ID']; ?>" aria-hidden="true">

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editarModalLabel<?php echo $row['ID']; ?>">
                                                                <i class="fas fa-edit me-2"></i>Editar Diseño #<?php echo $row['ID']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                        </div>
                                                        <form action="editar_diseno.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">

                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre del Diseño</label>
                                                                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($row['Nombre']); ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Clase del Diseño</label>
                                                                    <input type="text" class="form-control" name="clase" value="<?php echo htmlspecialchars($row['Clase']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre Archivo</label>
                                                                    <input type="text" class="form-control" name="archivo" value="<?php echo htmlspecialchars($row['Link']); ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Imagen Actual</label>
                                                                    <div class="text-center p-2 bg-light rounded">
                                                                        <img src="./Diseños/<?php echo strtolower($row['Clase']); ?>.png" class="img-fluid rounded" style="max-height: 150px;">
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Cambiar Imagen (opcional)</label>
                                                                    <input type="file" class="form-control" name="imagen" accept="image/*">
                                                                    <small class="text-muted">Deja en blanco para mantener la imagen actual</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Ver -->
                                            <div class="modal fade" id="verModal<?php echo $row['ID']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-info-circle me-2"></i>Detalles del Diseño
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center mb-4">
                                                                <img src="./Diseños/<?php echo strtolower($row['Clase']); ?>.png" class="img-fluid rounded" style="max-height: 200px;">
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">ID:</div>
                                                                <div class="col-md-8"><?php echo $row['ID']; ?></div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">Nombre:</div>
                                                                <div class="col-md-8"><?php echo htmlspecialchars($row['Nombre']); ?></div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">Clase:</div>
                                                                <div class="col-md-8">
                                                                    <span class="badge badge-design <?php echo $badgeClass; ?>">
                                                                        <?php echo htmlspecialchars($row['Clase']); ?>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-4 fw-bold">Imagen:</div>
                                                                <div class="col-md-8"><?php echo strtolower($row['Clase']); ?>.png</div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Eliminar -->
                                            <div class="modal fade" id="eliminarModal_<?php echo $row['ID']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center mb-4">
                                                                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                                                                <h4>¿Estás seguro?</h4>
                                                                <p>Estás a punto de eliminar el diseño <strong><?php echo htmlspecialchars($row['Nombre']); ?></strong>.</p>
                                                                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="eliminar_diseno.php" method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt me-1"></i> Sí, Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-paint-brush mb-3"></i>
                                <h5>No hay diseños disponibles</h5>
                                <p class="text-muted">Añade tu primer diseño utilizando el formulario.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón flotante para volver arriba -->
    <button class="btn btn-primary floating-btn" id="scrollTopBtn" style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <?php include('footer.php'); ?>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Previsualización de imágenes
        document.getElementById('designImage').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Búsqueda en tabla
        document.getElementById('searchDesign').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const table = document.getElementById('designsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');

                for (let j = 0; j < cells.length - 1; j++) { // Excluimos la última columna (acciones)
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        });

        // Botón para volver arriba
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById('scrollTopBtn').style.display = 'flex';
            } else {
                document.getElementById('scrollTopBtn').style.display = 'none';
            }
        };

        document.getElementById('scrollTopBtn').addEventListener('click', function() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        });

        // Auto ocultar el toast después de 5 segundos
        setTimeout(function() {
            const toastElement = document.querySelector('.toast');
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement);
                toast.hide();
            }
        }, 5000);
    </script>
</body>

</html>