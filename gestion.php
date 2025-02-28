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
    <title>Gestión de Tarjetas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: #343a40;
            color: white;
            font-weight: 500;
            padding: 12px;
            text-align: left;
            position: sticky;
            top: 0;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tr:hover {
            background-color: #f1f3f5;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .badge-design {
            font-size: 85%;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .pagination {
            margin-top: 20px;
            justify-content: center;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .tooltipped {
            position: relative;
            cursor: pointer;
        }

        .table-responsive {
            overflow-x: auto;
            max-height: 100%;
        }

        @media (max-width: 768px) {
            .card-container {
                padding: 15px;
            }

            .header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Header section -->
        <div class="header text-center">
            <h2><i class="fas fa-id-card me-2"></i>Gestión de Tarjetas</h2>
        </div>

        <!-- Card section with table -->
        <div class="card-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Tarjetas</h4>
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
                                $designClass = isset($row['Clase']) ? strtolower($row['Clase']) : 'secondary';
                                switch ($designClass) {
                                    case 'premium':
                                        $badgeClass = 'bg-warning text-dark';
                                        break;
                                    case 'especial':
                                        $badgeClass = 'bg-info';
                                        break;
                                    case 'basico':
                                        $badgeClass = 'bg-success';
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
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
                                    <td><span class="badge <?php echo $badgeClass; ?> badge-design"><?php echo htmlspecialchars($row['DiseñoNombre']); ?></span></td>
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
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo $texto_dias; ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $row['ID']; ?>">
                                            <i class="fas fa-edit me-1"></i>
                                        </button>
                                        <a class="btn btn-secondary btn-sm" href="./descarga.php?id=<?php echo $row['ID']; ?>">
                                            <i class="fas fa-download me-1"></i>
                                        </a>
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