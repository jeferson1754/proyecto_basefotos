
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
        /* Estilos para la tarjeta de regalo - Día de las Madres */
        .tarjeta.diseno-dia-madres {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff9fb;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(231, 127, 179, 0.2);
            overflow: hidden;
            font-family: 'Cormorant Garamond', serif;
            color: #4a4a4a;
            border: 1px solid rgba(231, 127, 179, 0.3);
        }

        /* Encabezado especial para día de las madres */
        .encabezado-madres {
            background: linear-gradient(135deg, #e77fb3 0%, #f6c3d7 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contenido-encabezado {
            z-index: 2;
        }

        .flores-decoracion {
            position: absolute;
            width: 150px;
            height: 150px;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.8;
            z-index: 1;
        }

        .flores-decoracion.izquierda {
            background-image: url('img/flores-izquierda.png');
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .flores-decoracion.derecha {
            background-image: url('img/flores-derecha.png');
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .encabezado-madres h2 {
            font-size: 1.6rem;
            margin: 0;
            font-weight: 400;
            letter-spacing: 3px;
            color: #fff;
        }

        .encabezado-madres h1.titulo {
            font-size: 2.8rem;
            margin: 8px 0;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
        }

        .encabezado-madres .subtitulo {
            font-size: 1.4rem;
            margin: 5px 0 0;
            font-weight: 300;
        }

        /* Contenido principal */
        .contenido-madres {
            padding: 30px;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .mensaje-madre {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e77fb3;
        }

        .dedicatoria {
            font-size: 1.8rem;
            color: #d23c77;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .descripcion {
            font-size: 1.2rem;
            color: #666;
            font-style: italic;
        }

        .detalles-regalo {
            background-color: rgba(231, 127, 179, 0.08);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .servicio {
            font-size: 1.8rem !important;
            color: #d23c77;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #e77fb3;
            padding-bottom: 8px;
        }

        .detalles-regalo p {
            margin: 10px 0;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .detalles-regalo p strong {
            color: #d23c77;
        }

        .mensaje-personal {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            border: 1px solid rgba(231, 127, 179, 0.2);
        }

        .text-center {
            text-align: center;
        }

        .fst-italic {
            font-style: italic;
            font-size: 1.3rem;
            color: #777;
            line-height: 1.8;
        }

        /* Separador floral */
        .separador-floral {
            position: relative;
            height: 60px;
            margin-top: 20px;
        }

        .onda-madres {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Información de contacto */
        .contacto-madres {
            background-color: rgba(231, 127, 179, 0.1);
            padding: 15px;
            text-align: center;
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(231, 127, 179, 0.2);
        }

        .contacto-madres p {
            margin: 5px 0;
            font-size: 0.95rem;
            color: #777;
        }

        .nota-especial {
            font-style: italic;
            font-size: 0.9rem;
            color: #d23c77;
            margin-top: 8px;
        }

        .fas {
            color: #e77fb3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .encabezado-madres h1.titulo {
                font-size: 2.2rem;
            }

            .flores-decoracion {
                width: 100px;
                height: 100px;
            }

            .dedicatoria {
                font-size: 1.6rem;
            }

            .servicio {
                font-size: 1.5rem !important;
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

                    <div class="tarjeta diseno-dia-madres" id="preview-design">
                        <div class="encabezado-madres">
                            <div class="flores-decoracion izquierda"></div>
                            <div class="contenido-encabezado">
                                <h2>Regalo Especial</h2>
                                <h1 class="titulo">Día de las Madres</h1>
                                <p class="subtitulo">Masajes "Manos Mágicas"</p>
                            </div>
                            <div class="flores-decoracion derecha"></div>
                        </div>

                        <div class="contenido-madres">
                            <div class="mensaje-madre">
                                <p class="dedicatoria">Para la mejor madre del mundo</p>
                                <p class="descripcion">Te mereces un momento de relajación y cuidado</p>
                            </div>

                            <div class="detalles-regalo">
                                <p class="servicio"><?php echo htmlspecialchars($tarjeta['Tratamiento']); ?></p>
                                <p><strong>Validez:</strong> <?php echo htmlspecialchars($tarjeta['Validez']); ?></p>
                                <p><strong>Duración:</strong> <?php echo htmlspecialchars($tarjeta['Duracion']); ?></p>
                                <p><strong>Para:</strong> <?php echo htmlspecialchars($tarjeta['Destinatario']); ?></p>
                            </div>

                            <div class="mensaje-personal">
                                <p class="text-center fst-italic">"<?php echo htmlspecialchars($tarjeta['Texto']); ?>"</p>
                            </div>
                        </div>

                        <div class="separador-floral">
                            <svg class="onda-madres" preserveAspectRatio="none" viewBox="0 0 1200 120">
                                <path fill="#e77fb3" d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                                <path fill="#e77fb3" d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                            </svg>
                        </div>

                        <div class="contacto-madres">
                            <p><i class="fas fa-phone me-2"></i>+56920557548 <i class="fas fa-envelope mx-2"></i>rosamagica113@gmail.com</p>
                            <p class="nota-especial">Con todo nuestro cariño para celebrar a las madres</p>
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