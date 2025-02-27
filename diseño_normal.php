
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
    <!-- Custom CSS -->
    <style>
        /* Estilos para la tarjeta de regalo */
        .tarjeta {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        /* Encabezado de la tarjeta */
        .encabezado {
            background: linear-gradient(135deg, #8e44ad 0%, #e91e63 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .encabezado h2 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: 300;
            letter-spacing: 2px;
        }

        .encabezado h1.titulo {
            font-size: 2.2rem;
            margin: 5px 0 10px;
            font-weight: 600;
        }

        /* Contenido principal */
        .contenido {
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .detalles {
            flex-basis: 48%;
            margin-bottom: 20px;
        }

        .servicio {
            font-size: 1.8rem;
            color: #8e44ad;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 8px;
        }

        .detalles p {
            margin: 8px 0;
            line-height: 1.6;
        }

        .detalles p strong {
            color: #e91e63;
        }

        .text-center {
            text-align: center;
        }

        .fst-italic {
            font-style: italic;
            font-size: 1.2rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px !important;
        }

        /* Ondas decorativas */
        .onda {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            z-index: 0;
        }

        /* Información de contacto */
        .contacto {
            background-color: rgba(142, 68, 173, 0.1);
            padding: 15px;
            text-align: center;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(142, 68, 173, 0.2);
        }

        .contacto p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }

        .fas {
            color: #e91e63;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detalles {
                flex-basis: 100%;
            }

            .encabezado h1.titulo {
                font-size: 1.8rem;
            }

            .servicio {
                font-size: 1.5rem;
            }
        }
    </style>

    <!-- Libraries for PDF/Image generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>

<body>
    <div class="page-container">
        <div class="container card-container">

            <?php if ($tarjeta): ?>
                <div id="tarjeta-container">

                    <div class="tarjeta diseno-<?php echo htmlspecialchars(strtolower($tarjeta['Clase'])); ?>" id="preview-design">
                        <div class="encabezado">
                            <!--
                            <img src="img/logo.png" alt="Logo Manos Mágicas" class="logo">
                            -->
                            <h2>Tarjeta de Regalo</h2>
                            <h1 class="titulo">Masajes "Manos Mágicas"</h1>
                        </div>

                        <div class="contenido">
                            <div class="detalles">
                                <p class="servicio"><?php echo htmlspecialchars($tarjeta['Tratamiento']); ?></p>
                                <p><strong>Validez:</strong> <?php echo htmlspecialchars($tarjeta['Validez']); ?></p>
                                <p><strong>Fecha de emisión:</strong> <?php echo $fechaEmision; ?></p>
                                <p><strong>Duración:</strong> <?php echo htmlspecialchars($tarjeta['Duracion']); ?></p>
                                <p style="margin-bottom: 20px;"><strong>Para:</strong> <?php echo htmlspecialchars($tarjeta['Destinatario']); ?></p>
                            </div>

                            <div class="detalles">
                                <p class="text-center fst-italic">"<?php echo htmlspecialchars($tarjeta['Texto']); ?>"</p>
                            </div>
                            <br>
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
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>