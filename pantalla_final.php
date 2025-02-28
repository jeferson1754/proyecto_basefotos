<?php
include('bd.php');

$id_tarjeta = isset($_GET['id']) ? urldecode($_GET['id']) : null;
$tarjeta = null;
$error = null;

if ($id_tarjeta) {
    try {
        // Consulta SQL para obtener los datos de la tarjeta por ID
        $stmt = $db->prepare("SELECT tarjetas.*, diseños_tarjetas.Clase, diseños_tarjetas.Nombre as DiseñoNombre 
                              FROM `tarjetas`
                              INNER JOIN diseños_tarjetas ON tarjetas.ID_Diseño = diseños_tarjetas.ID
                              WHERE tarjetas.ID = :id");
        $stmt->bindParam(':id', $id_tarjeta);
        $stmt->execute();

        // Verifica si hay resultados
        if ($stmt->rowCount() > 0) {
            $tarjeta = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "No se encontró la tarjeta con el ID proporcionado.";
        }
    } catch (PDOException $e) {
        $error = "Error al conectar con la base de datos: " . $e->getMessage();
    }
} else {
    $error = "Se requiere un ID de tarjeta válido.";
}

// Fecha de emisión formateada
$fechaEmision = date("d/m/Y");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Regalo - Manos Mágicas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display&family=Poppins&family=Quicksand&display=swap" rel="stylesheet">

    <!-- Libraries for PDF/Image generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


</head>

<body>
    <div class="page-container">
        <div class="container card-container">
            <div class="text-center mb-4">
                <h1 class="mb-3">Tarjeta de Regalo</h1>
                <p class="text-muted">Masajes "Manos Mágicas"</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Volver al inicio
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($tarjeta):

                $query = "SELECT * FROM diseños_tarjetas";
                $resultado = $conexion->query($query);

                $mapa_diseños = [];
                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $mapa_diseños[$row['Clase']] = $row['Link'];
                    }
                } else {
                    echo "No se encontraron diseños en la base de datos.";
                }

                // Cerrar la conexión
                $conexion->close();

                // Obtener la clase de la tarjeta y asignar el diseño correspondiente
                $clase_tarjeta = $tarjeta['Clase'] ?? 'normal'; // Default a 'normal' si no hay clase definida
                $diseño = $mapa_diseños[$clase_tarjeta] ?? 'diseño_normal.php'; // Si no está en el mapa, usar 'diseño_normal.php'

                // Incluir el archivo correspondiente
                include($diseño);


            ?>
                <div id="tarjeta-container" class="mt-4">

                    <div class="d-flex justify-content-center gap-3 mb-5">
                        <button class="btn btn-primary btn-lg btn-download" onclick="descargarComoPNG()">
                            <i class="fas fa-image me-2"></i>Descargar como PNG
                        </button>
                        <button class="btn btn-success btn-lg btn-download" onclick="descargarComoPDF()">
                            <i class="fas fa-file-pdf me-2"></i>Descargar como PDF
                        </button>
                    </div>

                    <div class="text-center mb-4">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Volver al inicio
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php include('footer.php'); ?>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para descargar como PNG
        function descargarComoPNG() {
            const previewDesign = document.getElementById("preview-design");

            // Mostrar indicador de carga
            showLoading('Generando imagen...');

            setTimeout(() => {
                html2canvas(previewDesign, {
                    scale: 2
                }).then(canvas => {
                    // Ocultar indicador de carga
                    hideLoading();

                    const enlace = document.createElement('a');
                    enlace.href = canvas.toDataURL("image/png");
                    enlace.download = "tarjeta-regalo.png";
                    enlace.click();
                }).catch(error => {
                    hideLoading();
                    showError("Error al generar la imagen: " + error.message);
                });
            }, 500);
        }

        // Función para descargar como PDF
        function descargarComoPDF() {
            const previewDesign = document.getElementById("preview-design");

            // Mostrar indicador de carga
            showLoading('Generando PDF...');

            setTimeout(() => {
                html2canvas(previewDesign, {
                    scale: 2
                }).then(canvas => {
                    // Ocultar indicador de carga
                    hideLoading();

                    const imgData = canvas.toDataURL("image/png");
                    const {
                        jsPDF
                    } = window.jspdf;

                    const pdf = new jsPDF({
                        orientation: "portrait", // <-- Ahora en vertical
                        unit: "mm"
                    });

                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save("tarjeta-regalo.pdf");
                }).catch(error => {
                    hideLoading();
                    showError("Error al generar el PDF: " + error.message);
                });
            }, 500);
        }

        // Funciones auxiliares para mostrar/ocultar indicadores de carga
        function showLoading(message) {
            // Crear un div de carga si no existe
            if (!document.getElementById('loading-indicator')) {
                const loadingDiv = document.createElement('div');
                loadingDiv.id = 'loading-indicator';
                loadingDiv.style.position = 'fixed';
                loadingDiv.style.top = '0';
                loadingDiv.style.left = '0';
                loadingDiv.style.width = '100%';
                loadingDiv.style.height = '100%';
                loadingDiv.style.backgroundColor = 'rgba(0,0,0,0.5)';
                loadingDiv.style.display = 'flex';
                loadingDiv.style.justifyContent = 'center';
                loadingDiv.style.alignItems = 'center';
                loadingDiv.style.zIndex = '9999';

                const loadingContent = document.createElement('div');
                loadingContent.style.backgroundColor = 'white';
                loadingContent.style.padding = '20px 30px';
                loadingContent.style.borderRadius = '10px';
                loadingContent.style.textAlign = 'center';

                const spinner = document.createElement('div');
                spinner.className = 'spinner-border text-primary';
                spinner.setAttribute('role', 'status');

                const loadingText = document.createElement('p');
                loadingText.id = 'loading-text';
                loadingText.style.marginTop = '10px';
                loadingText.style.marginBottom = '0';
                loadingText.textContent = message || 'Cargando...';

                loadingContent.appendChild(spinner);
                loadingContent.appendChild(loadingText);
                loadingDiv.appendChild(loadingContent);
                document.body.appendChild(loadingDiv);
            } else {
                document.getElementById('loading-text').textContent = message || 'Cargando...';
                document.getElementById('loading-indicator').style.display = 'flex';
            }
        }

        function hideLoading() {
            const loadingDiv = document.getElementById('loading-indicator');
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
        }

        function showError(message) {
            // Crear alerta temporal
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.maxWidth = '500px';

            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            document.body.appendChild(alertDiv);

            // Eliminar automáticamente después de 5 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>

</html>