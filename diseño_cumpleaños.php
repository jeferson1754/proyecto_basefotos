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
        .tarjeta {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to bottom, #f9f9f9, #f5f5f5);
        }

        .diseno-cumpleanos {
            background: linear-gradient(135deg, #fff8e1, #fffde7);
            border: 2px solid #ffb347;
        }

        .encabezado {
            background: linear-gradient(to right, #ffb347, #ff9a9e);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .encabezado h2 {
            margin: 0;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .encabezado h1 {
            margin: 10px 0 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .contenido {
            padding: 25px;
            position: relative;
            z-index: 1;
        }

        .detalles {
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 10px;
        }

        .servicio {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ff6b6b;
            margin-top: 0;
            text-align: center;
        }

        .detalles p {
            margin: 8px 0;
            font-size: 1rem;
        }

        .mensaje-cumple {
            background-color: rgba(255, 183, 77, 0.15);
            border-left: 4px solid #ffb347;
            font-size: 1.1rem;
        }

        .mensaje-cumple p {
            font-size: 1.1rem;
            line-height: 1.5;
            color: #484848;
        }

        .onda {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            z-index: 0;
        }

        .contacto {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            text-align: center;
            position: relative;
            z-index: 1;
            font-size: 0.9rem;
            border-top: 1px dashed #ffb347;
        }

        .contacto p {
            margin: 0;
        }

        .decoracion-cumple {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
            height: 50px;
        }

        .globo {
            width: 35px;
            height: 40px;
            background: radial-gradient(#ff9a9e, #ff6b6b);
            border-radius: 50%;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        .globo:nth-child(2) {
            background: radial-gradient(#ffb347, #ff9347);
            animation-delay: 0.5s;
        }

        .globo:nth-child(3) {
            background: radial-gradient(#a18cd1, #6a5acd);
            animation-delay: 1s;
        }

        .globo:after {
            content: '';
            position: absolute;
            width: 1px;
            height: 50px;
            background: #ccc;
            bottom: -40px;
            left: 50%;
        }

        .pastel {
            width: 40px;
            height: 40px;
            background: linear-gradient(to bottom, #ffd1dc 70%, #8e44ad 70%);
            border-radius: 5px 5px 0 0;
            position: relative;
        }

        .pastel:before {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 12px;
            background: #ff6b6b;
        }

        .pastel:after {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 4px;
            background: #ffcc00;
            border-radius: 50% 50% 0 0;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 576px) {
            .encabezado h1 {
                font-size: 1.5rem;
            }

            .encabezado h2 {
                font-size: 1.2rem;
            }

            .servicio {
                font-size: 1.2rem;
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

                    <div class="tarjeta diseno-cumpleanos" id="preview-design">
                        <div class="encabezado">
                            <h2>Tarjeta de Regalo de Cumpleaños</h2>
                            <h1 class="titulo">Masajes "Manos Mágicas"</h1>
                        </div>

                        <div class="contenido">
                            <div class="decoracion-cumple">
                                <div class="globo"></div>
                                <div class="globo"></div>
                                <div class="globo"></div>
                                <div class="pastel"></div>
                            </div>

                            <div class="detalles">
                                <p class="servicio"><?php echo htmlspecialchars($tarjeta['Tratamiento']); ?></p>
                                <p><strong>Validez:</strong> <?php echo formatearValidez($tarjeta['Validez']); ?></p>
                                <p><strong>Fecha de emisión:</strong> <?php echo $fechaEmision; ?></p>
                                <p><strong>Duración:</strong> <?php echo htmlspecialchars($tarjeta['Duracion']); ?></p>
                                <p><strong>Para:</strong> <?php echo htmlspecialchars($tarjeta['Destinatario']); ?></p>
                            </div>

                            <div class="detalles mensaje-cumple">
                                <p class="text-center fst-italic">"Un regalo especial en tu día especial: ¡Feliz Cumpleaños! <?php echo htmlspecialchars($tarjeta['Texto']); ?>"</p>
                            </div>
                            <br>
                        </div>

                        <svg class="onda" preserveAspectRatio="none" viewBox="0 0 1200 120">
                            <path fill="#ff6b6b" d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                            <path fill="#ffb347" d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                            <path fill="#ff9a9e" d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" opacity=".75"></path>
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