<?php
include('bd.php');

// Consulta para obtener las tarjetas históricas
$query = "SELECT tarjetas.*, disenos_tarjetas.Clase, disenos_tarjetas.Nombre as DisenoNombre FROM `tarjetas` INNER JOIN disenos_tarjetas ON tarjetas.ID_Diseno = disenos_tarjetas.ID ORDER BY `tarjetas`.`ID` DESC LIMIT 30";
$resultado = $conexion->query($query);

$total_palabras = 30;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tarjetas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="styles.css">
    <!-- Custom CSS -->
</head>

<body>
    <div class="container-fluid">
        <!-- Header section -->
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
            </button>
            <h2 class="text-center flex-grow-1">
                <i class="fas fa-id-card me-2"></i>Historial de Tarjetas
            </h2>
        </div>

        <!-- Card section with table -->
        <div class="card-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"></h4>
                <div class="input-group w-25">
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="tarjetasTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Validez</th>
                            <th>Tratamiento</th>
                            <th>Duración</th>
                            <th>Dedicatoria</th>
                            <th>Diseño</th>
                            <th>Fecha Emisión</th>
                            <th>Fecha Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado->num_rows > 0) {
                            while ($row = $resultado->fetch_assoc()) {
                                // Determinar clase de diseño para el color del badge
                                $badgeClass = getBadgeClass($row['Clase']);

                        ?>
                                <tr>
                                    <td><?php echo $row['ID']; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['Destinatario']); ?></td>

                                    <td><?php echo formatearValidez($row['Validez']); ?></td>


                                    <td>
                                        <span class="truncate tooltipped" title="<?php echo htmlspecialchars($row['Tratamiento']); ?>">
                                            <?php
                                            $tratamiento = $row['Tratamiento'];
                                            echo (strlen($tratamiento) > $total_palabras) ? substr(htmlspecialchars($tratamiento), 0, $total_palabras) . "..." : htmlspecialchars($tratamiento);
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['Duracion']); ?></td>
                                    <td>
                                        <span class="truncate tooltipped" title="<?php echo htmlspecialchars($row['Texto']); ?>">
                                            <?php
                                            $texto = $row['Texto'];
                                            echo (strlen($texto) > $total_palabras) ? substr(htmlspecialchars($texto), 0, $total_palabras) . "..." : htmlspecialchars($texto);
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge  <?php echo $badgeClass; ?> badge-design">
                                            <?php echo htmlspecialchars($row['DisenoNombre']); ?>
                                        </span>
                                    </td>

                                    <td><?php echo htmlspecialchars($row['Fecha_Emision']); ?></td>
                                    <td><?php
                                        // Calcular días restantes
                                        $fecha_fin = new DateTime($row['Fecha_Vencimiento']);
                                        $hoy = new DateTime(date("Y-m-d"));
                                        $intervalo = $hoy->diff($fecha_fin);
                                        $dias_restantes = $intervalo->days * ($intervalo->invert ? -1 : 1);

                                        // Definir clase para la fecha de finalización
                                        $clase_fecha = 'bg-success';
                                        $icon_class = 'fa-calendar-check';
                                        $texto_dias = 'Faltan ' . $dias_restantes . ' días';

                                        if ($dias_restantes < 0) {
                                            $clase_fecha = 'bg-danger';
                                            $icon_class = 'fa-calendar-times';
                                            $texto_dias = 'Vencido hace ' . abs($dias_restantes) . ' días';
                                        } elseif ($dias_restantes == 1) {
                                            $clase_fecha = 'bg-warning text-dark';
                                            $icon_class = 'fa-calendar-xmark';
                                            $texto_dias = '¡Próximo a vencer! ' . $dias_restantes . ' día';
                                        } elseif ($dias_restantes <= 7) {
                                            $clase_fecha = 'bg-warning text-dark';
                                            $icon_class = 'fa-calendar-xmark';
                                            $texto_dias = '¡Próximo a vencer! ' . $dias_restantes . ' días';
                                        }
                                        ?>
                                        <span class="badge <?php echo $clase_fecha; ?>">
                                            <i class="far <?php echo $icon_class; ?> me-1"></i>
                                            <?php echo date('d-m-Y', strtotime($row['Fecha_Vencimiento'])); ?>
                                        </span><br>
                                        <span class="small fw-medium status-text <?php echo $dias_restantes < 0 ? 'text-danger' : ($dias_restantes <= 7 ? 'text-warning' : 'text-success'); ?>">
                                            <i class="fas fa-clock"></i>
                                            <?php echo $texto_dias; ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $row['ID']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a class="btn btn-secondary btn-sm" href="./descarga.php?id=<?php echo $row['ID']; ?>">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal_<?php echo $row['ID']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal de Bootstrap 5 -->
                                <div class="modal fade" id="editModal_<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $row['ID']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel_<?php echo $row['ID']; ?>">
                                                    <i class="fas fa-calendar-alt me-2"></i>Editar Fechas - Tarjeta #<?php echo $row['ID']; ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="editForm_<?php echo $row['ID']; ?>" method="POST" action="actualizar.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="tarjeta_id" value="<?php echo $row['ID']; ?>">

                                                    <div class="mb-3">
                                                        <label for="destinatario_<?php echo $row['ID']; ?>" class="form-label">Destinatario</label>
                                                        <input type="text" class="form-control" id="destinatario_<?php echo $row['ID']; ?>" value="<?php echo htmlspecialchars($row['Destinatario']); ?>" disabled>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="fecha_emision_<?php echo $row['ID']; ?>" class="form-label">
                                                            <i class="fas fa-calendar-plus me-2"></i>Fecha de Emisión
                                                        </label>
                                                        <input type="date" class="form-control" id="fecha_emision_<?php echo $row['ID']; ?>" name="fecha_emision" value="<?php echo $row['Fecha_Emision']; ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="fecha_vencimiento_<?php echo $row['ID']; ?>" class="form-label">
                                                            <i class="fas fa-calendar-times me-2"></i>Fecha de Vencimiento
                                                        </label>
                                                        <input type="date" class="form-control" id="fecha_vencimiento_<?php echo $row['ID']; ?>" name="fecha_vencimiento" value="<?php echo $row['Fecha_Vencimiento']; ?>">
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>La actualización de fechas afectará la validez de la tarjeta.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-1"></i>Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
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
                                                    <p>Estás a punto de eliminar la tarjeta para <strong><?php echo htmlspecialchars($row['Destinatario']); ?></strong>.</p>
                                                    <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="eliminar_carta.php" method="POST">
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
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="10" class="text-center">No se encontraron tarjetas</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Búsqueda en tabla
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const table = document.getElementById('tarjetasTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        });

        // Tooltip para texto truncado
        const tooltippedElements = document.querySelectorAll('.tooltipped');
        tooltippedElements.forEach(function(element) {
            element.addEventListener('mouseenter', function() {
                const title = this.getAttribute('title');
                if (!title) return;

                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-custom';
                tooltip.textContent = title;
                tooltip.style.position = 'absolute';
                tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                tooltip.style.color = 'white';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '4px';
                tooltip.style.fontSize = '14px';
                tooltip.style.zIndex = '9999';
                tooltip.style.maxWidth = '300px';

                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.bottom + 10) + 'px';

                this.addEventListener('mouseleave', function() {
                    document.body.removeChild(tooltip);
                }, {
                    once: true
                });
            });
        });
    </script>

    <?php $conexion->close(); ?>
</body>

</html>