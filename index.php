<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Tarjetas Personalizadas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Libraries for PDF/Image generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        .screen {
            display: none;
        }

        .active-screen {
            display: block;
        }

        .design-options {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .design-card {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
            min-width: 200px;
            width: 23%;
        }

        .design-card.selected {
            border-color: #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
        }

        .design-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .design-card .card-body {
            padding: 10px;
            text-align: center;
        }

        .progress-steps {
            position: relative;
            margin-bottom: 30px;
        }

        .progress-bar {
            height: 4px;
            background-color: #e9ecef;
            position: relative;
            margin: 20px 0;
        }

        .progress-bar-fill {
            position: absolute;
            height: 100%;
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .step-circles {
            display: flex;
            justify-content: space-between;
            position: absolute;
            width: 100%;
            top: -10px;
        }

        .step-circle {
            width: 24px;
            height: 24px;
            background-color: white;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: #6c757d;
            transition: all 0.3s;
        }

        .step-circle.active {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .step-circle.completed {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .step-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .step-label {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            width: 25%;
        }

        .step-label.active {
            color: #0d6efd;
            font-weight: bold;
        }

        /* Tarjeta Estilo */
        .tarjeta {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .encabezado {
            background: linear-gradient(45deg, #8e44ad, #e91e63);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .contenido {
            padding: 20px;
        }

        .detalles {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .servicio {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .contacto {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        .onda {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 50px;
            z-index: -1;
        }

        .ocultar-celular {
            display: inline;
        }

        @media (max-width: 768px) {
            /*Ocultar en vista celular*/

            .ocultar-celular {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-5">Generador de Tarjetas de Regalo</h1>

        <!-- Progress Steps -->
        <div class="progress-steps mb-5">
            <div class="progress-bar">
                <div class="progress-bar-fill" id="progress-fill"></div>
            </div>
            <div class="step-circles">
                <div class="step-circle active" id="step-circle-1">1</div>
                <div class="step-circle" id="step-circle-2">2</div>
                <div class="step-circle" id="step-circle-3">3</div>
                <div class="step-circle" id="step-circle-4">4</div>
            </div>
            <div class="step-labels">
                <div class="step-label active" id="step-label-1">Datos</div>
                <div class="step-label" id="step-label-2">Diseño</div>
                <div class="step-label" id="step-label-3">Vista Previa</div>
                <div class="step-label" id="step-label-4">Descargar</div>
            </div>
        </div>

        <!-- Pantallas -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <!-- Pantalla 1: Datos -->
                <div id="screen-1" class="screen active-screen">
                    <h3 class="card-title mb-4 text-center">Ingresa los datos de la tarjeta</h3>
                    <form id="form-data" class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre del destinatario:</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="validity" class="form-label">Validez:</label>
                            <input type="text" class="form-control" name="validity" id="validity" placeholder="Ej: 3 meses desde la fecha de emisión" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tratamiento" class="form-label">Tratamiento:</label>
                            <input type="text" class="form-control" name="tratamiento" id="tratamiento" placeholder="Ej: Masaje relajante" required>
                        </div>

                        <div class="col-md-6">
                            <label for="duracion" class="form-label">Duración:</label>
                            <input type="text" class="form-control" name="duracion" id="duracion" placeholder="Ej: 1h 30min" required>
                        </div>

                        <div class="col-12">
                            <label for="dedication" class="form-label">Dedicatoria:</label>
                            <textarea class="form-control" name="dedication" id="dedication" rows="3" required></textarea>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 d-flex justify-content-start">
                                <button type="button" class="btn btn-primary me-2 ocultar-celular" onclick="goToGestion()">
                                    Historial <i class="fas fa-clock ms-1"></i>
                                </button>
                                <button type="button" class="btn btn-success ocultar-celular" onclick="goToNuevoDiseno()">
                                    Nuevo Diseño <i class="fas fa-plus ms-1"></i>
                                </button>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" onclick="goToNextScreen(2)">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Pantalla 2: Diseño -->
                <div id="screen-2" class="screen">
                    <h3 class="card-title mb-4 text-center">Selecciona un diseño</h3>

                    <div class="design-options">

                        <?php
                        include('bd.php');
                        // Consulta para obtener los diseños
                        $query = "SELECT * FROM disenos_tarjetas";
                        $result = $conexion->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Generar el HTML dinámicamente para cada diseño
                                echo '
                                <div class="design-card" onclick="selectDesign(\'' . $row['Clase'] . '\', this)">
                                    <img src="./Diseños/' . htmlspecialchars($row['Clase']) . '.png" alt="Diseño ' . htmlspecialchars($row['Nombre']) . '">
                                    <div class="card-body">
                                        <h5 class="card-title">' . htmlspecialchars($row['Nombre']) . '</h5>
                                    </div>
                                    <input type="radio" name="design" value="' . htmlspecialchars($row['Clase']) . '" class="d-none">
                                </div>';
                            }
                        } else {
                            echo "No se encontraron diseños.";
                        }
                        ?>

                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="goToPreviousScreen(1)">
                            <i class="fas fa-arrow-left me-1"></i> Atrás
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToNextScreen(3)">
                            Siguiente <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Pantalla 3: Vista Previa -->
                <div id="screen-3" class="screen">
                    <h3 class="card-title mb-4 text-center">Vista previa de tu tarjeta</h3>

                    <div id="preview-design" class="mb-4">
                        <div class="tarjeta" id="tarjeta-preview">
                            <div class="encabezado">
                                <h3>Tarjeta de Regalo</h3>
                                <h1 class="titulo">Masajes "Manos Mágicas"</h1>
                            </div>

                            <div class="contenido">
                                <div class="detalles">
                                    <p class="servicio" id="preview-tratamiento"></p>
                                    <p id="preview-validity"><strong>Validez:</strong></p>
                                    <p><strong>Fecha:</strong> <span id="current-date"></span></p>
                                    <p id="preview-duracion"><strong>Duración:</strong></p>
                                    <p id="preview-name"><strong>Para:</strong></p>
                                    <p id="preview-diseño"><strong>Diseño:</strong></p>
                                </div>

                                <div class="detalles">
                                    <p class="servicio" id="preview-dedication"></p>
                                </div>
                            </div>

                            <svg class="onda" preserveAspectRatio="none" viewBox="0 0 1200 120">
                                <path fill="#8e44ad" d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                                <path fill="#e91e63" d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                                <path fill="#e91e63" d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" opacity=".75"></path>
                            </svg>

                            <div class="contacto">
                                <p><i class="fas fa-phone me-2"></i>+56920557548 <i class="fas fa-envelope mx-2"></i>rosamagica113@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <form id="saveForm" action="guardar.php" method="POST">
                        <input type="hidden" name="name" id="hidden-name">
                        <input type="hidden" name="dedication" id="hidden-dedication">
                        <input type="hidden" name="validity" id="hidden-validity">
                        <input type="hidden" name="tratamiento" id="hidden-tratamiento">
                        <input type="hidden" name="duracion" id="hidden-duracion">
                        <input type="hidden" name="diseño" id="hidden-diseño">

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="goToPreviousScreen(2)">
                                <i class="fas fa-arrow-left me-1"></i> Atrás
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Aceptar <i class="fas fa-check ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pantalla 4: Descarga -->
                <div id="screen-4" class="screen">
                    <div class="text-center">
                        <h3 class="card-title mb-4">¡Tu tarjeta está lista!</h3>
                        <p class="mb-4">Puedes descargarla en formato imagen o PDF</p>

                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-primary" onclick="descargarComoPNG()">
                                <i class="fas fa-image me-2"></i> Descargar como PNG
                            </button>
                            <button class="btn btn-success" onclick="descargarComoPDF()">
                                <i class="fas fa-file-pdf me-2"></i> Descargar como PDF
                            </button>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="reiniciarProceso()">
                                <i class="fas fa-redo me-1"></i> Crear otra tarjeta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Establecer la fecha actual en el formato dd/mm/yyyy
        const today = new Date();
        const formattedDate = `${today.getDate().toString().padStart(2, '0')}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${today.getFullYear()}`;
        document.getElementById('current-date').textContent = formattedDate;

        // Variables para controlar la navegación
        let currentScreen = 1;
        let selectedDesign = '';

        // Función para ir a la siguiente pantalla
        function goToNextScreen(screenNumber) {
            // Validaciones
            if (screenNumber === 3) {
                if (!selectedDesign) {
                    alert('Por favor, selecciona un diseño para continuar.');
                    return;
                }
                updatePreview();
            } else if (screenNumber === 2) {
                if (!validateForm('form-data')) {
                    return;
                }
            }

            document.getElementById(`screen-${currentScreen}`).classList.remove('active-screen');
            document.getElementById(`screen-${screenNumber}`).classList.add('active-screen');

            // Actualizar progreso
            updateProgress(screenNumber);

            currentScreen = screenNumber;
        }

        // Función para ir a la pantalla anterior
        function goToPreviousScreen(screenNumber) {
            document.getElementById(`screen-${currentScreen}`).classList.remove('active-screen');
            document.getElementById(`screen-${screenNumber}`).classList.add('active-screen');

            // Actualizar progreso
            updateProgress(screenNumber);

            currentScreen = screenNumber;
        }

        // Función para seleccionar diseño
        function selectDesign(designValue, element) {
            // Remover clase seleccionada de todos los diseños
            document.querySelectorAll('.design-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Añadir clase seleccionada al diseño clickeado
            element.classList.add('selected');

            // Seleccionar el radio button correspondiente
            element.querySelector('input[type="radio"]').checked = true;

            // Guardar el diseño seleccionado
            selectedDesign = designValue;
            document.getElementById('hidden-diseño').value = designValue;
        }

        // Función para validar formulario
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                alert('Por favor, completa todos los campos requeridos.');
            }

            return isValid;
        }

        // Función para actualizar la barra de progreso
        function updateProgress(step) {
            // Calcular porcentaje de progreso
            const progressPercentage = ((step - 1) / 3) * 100;
            document.getElementById('progress-fill').style.width = `${progressPercentage}%`;

            // Actualizar círculos de progreso
            for (let i = 1; i <= 4; i++) {
                const circle = document.getElementById(`step-circle-${i}`);
                const label = document.getElementById(`step-label-${i}`);

                if (i < step) {
                    circle.className = 'step-circle completed';
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    label.className = 'step-label';
                } else if (i === step) {
                    circle.className = 'step-circle active';
                    circle.innerHTML = i;
                    label.className = 'step-label active';
                } else {
                    circle.className = 'step-circle';
                    circle.innerHTML = i;
                    label.className = 'step-label';
                }
            }
        }

        // Función para actualizar la vista previa
        function updatePreview() {
            const name = document.getElementById('name').value;
            const dedication = document.getElementById('dedication').value;
            const validity = document.getElementById('validity').value;
            const tratamiento = document.getElementById('tratamiento').value;
            const duracion = document.getElementById('duracion').value;
            const design = document.querySelector('input[name="design"]:checked')?.value; // Obtener el valor del diseño seleccionado

            // Actualizar previsualizaciones
            document.getElementById('preview-name').innerHTML = `<strong>Para:</strong> ${name}`;
            document.getElementById('preview-dedication').textContent = dedication;
            document.getElementById('preview-validity').innerHTML = `<strong>Validez:</strong> ${validity}`;
            document.getElementById('preview-tratamiento').textContent = tratamiento;
            document.getElementById('preview-duracion').innerHTML = `<strong>Duración:</strong> ${duracion}`;
            document.getElementById('preview-diseño').innerHTML = `<strong>Diseño:</strong> ${design}`;

            // Actualizar valores ocultos para el formulario
            document.getElementById('hidden-name').value = name;
            document.getElementById('hidden-dedication').value = dedication;
            document.getElementById('hidden-validity').value = validity;
            document.getElementById('hidden-tratamiento').value = tratamiento;
            document.getElementById('hidden-duracion').value = duracion;
        }

        // Función para reiniciar el proceso
        function reiniciarProceso() {
            // Limpiar formulario
            document.getElementById('form-data').reset();

            // Reiniciar diseño seleccionado
            selectedDesign = '';
            document.querySelectorAll('.design-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Volver a la primera pantalla
            document.getElementById(`screen-${currentScreen}`).classList.remove('active-screen');
            document.getElementById('screen-1').classList.add('active-screen');

            // Reiniciar progreso
            updateProgress(1);

            currentScreen = 1;
        }

        function goToGestion() {
            window.location.href = "gestion.php";
        }

        function goToNuevoDiseno() {
            window.location.href = "nuevo_diseño.php";
        }
    </script>
</body>

</html>