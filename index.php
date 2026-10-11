<?php
require_once __DIR__ . '/config/security.php';
send_security_headers();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Crédito CMR: Pide tu Tarjeta 100% en línea</title>
    <meta name="description"
        content="Pide tu Tarjeta de Crédito CMR de Banco Falabella en línea, disfruta de cientos de beneficios y 3 meses sin cuota de manejo.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="top-bar">
        <div class="container top-bar-container">
            <div class="top-bar-links">
                <a href="#">Falabella</a>
                <a href="#">Homecenter</a>
                <a href="#">Seguros Falabella</a>
                <span class="divider">|</span>
                <a href="#">Tranferencias</a>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container header-container">
            <a href="#" class="logo">
                <img src="img/logo-fab.svg" alt="">
                <span class="logo-text"><span>Banco</span><span>Falabella</span></span>
            </a>
            <div class="header-actions">
                <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú"
                    aria-expanded="false"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>

    <nav class="main-nav" id="mainNav">
        <div class="container">
            <ul class="nav-list">
                <li class="active"><a href="#">Tarjeta de Crédito</a></li>
                <li><a href="#">Cuenta de Ahorros</a></li>
                <li><a href="#">Productos de Inversión</a></li>
                <li><a href="#">Créditos</a></li>
                <li><a href="#">Beneficios y CMR Puntos</a></li>
                <li><a href="#">Empresas</a></li>
                <li><a href="#">Educación Financiera</a></li>
                <li><a href="#">Seguros</a></li>
                <li><a href="#">Ayuda y Contacto</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="container">
                <div class="hero-banner">
                    <picture>
                        <source media="(min-width: 768px)" srcset="img/hero-desktop.webp">
                        <img src="img/hero.webp" alt="Mujer en centro comercial con tarjeta CMR" class="hero-bg-img">
                    </picture>
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <h1>Tarjeta de Crédito CMR</h1>
                        <p>Pídela en línea, disfruta de cientos de beneficios y 3 meses sin cuota de manejo</p>
                        <a href="solicitud.php" class="btn-hero">¡Pídela aquí!</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="banner50">
                    <div class="banner50-image"><img src="img/b01.webp" alt="Tarjeta de crédito CMR"></div>
                    <div class="banner50-content">
                        <h2>¿Qué es una Tarjeta de Crédito?</h2>
                        <ul class="check-list">
                            <li>Una <b>Tarjeta de Crédito</b> es un medio de pago que te permite financiar compras, si
                                no cuentas con el dinero en el momento y pagar después a un número de cuotas elegido.
                            </li>
                            <li>Funciona como un cupo de crédito aprobado por el banco. Puedes usarla en
                                establecimientos físicos, compras en internet, avances y pagos en línea de manera
                                flexible.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-heading">
                    <h2>Tarjeta 100% Digital</h2>
                    <p>Una tarjeta de crédito 100% digital la puedes pedir, gestionar y usar sin tener una tarjeta
                        física. Acá te contamos algunas ventajas</p>
                </div>
                <div class="icon-cards">
                    <div class="icon-card"><img src="img/dig1.webp" alt=""><span>Billeteras Digitales</span></div>
                    <div class="icon-card"><img src="img/dig2.webp" alt=""><span>Pago sin contacto en datafonos</span>
                    </div>
                    <div class="icon-card"><img src="img/dig3.webp" alt=""><span>Uso en todo el mundo</span></div>
                    <div class="icon-card"><img src="img/dig4.webp" alt=""><span>App y Banca en línea</span></div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="captador">
                    <img src="img/captador.webp" alt="" class="captador-img">
                    <div class="captador-overlay"></div>
                    <div class="captador-content">
                        <h2>Disfrutar de cientos de beneficios, descuentos, preventas y mucho más.</h2>
                        <a href="solicitud.php" class="btn-hero btn-inline">Pide tu Tarjeta CMR</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="banner50 banner50--right">
                    <div class="banner50-image"><img src="img/b04.webp"
                            alt="Requisitos para pedir una Tarjeta de Crédito"></div>
                    <div class="banner50-content">
                        <h2>Requisitos para pedir una Tarjeta de Crédito</h2>
                        <p>Los requisitos para pedir una Tarjeta de Crédito en Banco Falabella son:</p>
                        <ul class="check-list">
                            <li>Tener mínimo 21 años. Pensionados mayores de 35 años.</li>
                            <li>Ingresos mínimos mensuales de 1SMMLV o de 5,2 SMMLV si eres extranjero residente.</li>
                            <li>Ser colombiano o extranjero residente.</li>
                            <li>No estar reportado de centrales de riesgo.</li>
                            <li>Documentos personales.</li>
                            <li>Contrato Unificado de Producto (CUP) firmado digitalmente.</li>
                        </ul>
                        <p>Recuerda que puede hacer este proceso 100% digital</p>
                        <a href="solicitud.php" class="btn-hero btn-inline">Pedir mi CMR</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="section-heading">
                    <h2>Pasos para pedir tu Tarjeta de Crédito</h2>
                    <p>Acá te contamos cuales son los pasos para pedir tu Tarjeta de Crédito 100% digital y disfrutar de
                        todos los beneficios.</p>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-icon"><img src="img/step1.svg" alt=""></div>
                        <h3>Ingresas aquí.</h3>
                        <p>Pide tu Tarjeta de Crédito</p>
                        <a href="solicitud.php" class="step-link">Pidela aquí</a>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#3b9326" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8" />
                            </svg>
                        </div>
                        <h3>Completa los datos del formulario</h3>
                        <p>Completa cada uno de los campos del formulario que son requeridos</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#3b9326" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                                <path d="M14 3v6h6" />
                                <path d="M8 13h8M8 17h5" />
                            </svg>
                        </div>
                        <h3>Lee atentamente las indicaciones</h3>
                        <p>Sigue los pasos del formulario, es facíl y no toma más de 5 minutos</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="banner50">
                    <div class="banner50-image"><img src="img/b05.webp" alt="Ventajas de tener una Tarjeta de Crédito">
                    </div>
                    <div class="banner50-content">
                        <h2>Ventajas de tener una Tarjeta de Crédito</h2>
                        <p>Una tarjeta de crédito no es solo un medio de pago es una herramienta que puedes usar para
                            disfrutar más la vida.</p>
                        <ul class="check-list">
                            <li>Uso en comercios nacionales e internacionales.</li>
                            <li>Financiar compras de alto valor.</li>
                            <li>Pago a cuotas.</li>
                            <li>Avances en efectivo.</li>
                            <li>Beneficios exclusivos.</li>
                            <li>Acumulación de puntos.</li>
                        </ul>
                        <a href="solicitud.php" class="btn-hero btn-inline">Pedir mi CMR</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="banner50 banner50--right">
                    <div class="banner50-image"><img src="img/b06.webp" alt="Cuota de manejo"></div>
                    <div class="banner50-content">
                        <h2>Cuota de manejo ¿Qué es y cómo funciona?</h2>
                        <p>La cuota de manejo es el costo o valor que pagas por tener disponible el cupo de tu tarjeta.
                            Algunos bancos cobran cada dos o tres meses, incluso si no has utilizado la tarjeta. Otras
                            solo la aplican si tienes un saldo pendiente o has hecho compras con ella.</p>
                        <p>En Banco Falabella no pagas cuota de manejo en los siguientes casos:</p>
                        <ul class="check-list">
                            <li>3 meses si eres cliente nuevo.</li>
                            <li>Si tienes una cuenta de ahorros Banco Falabella con beneficios plus.</li>
                            <li>Con compras superiores a $1.500.000</li>
                        </ul>
                        <a href="solicitud.php" class="btn-hero btn-inline">Conoce más</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="cenefa">
                    <div>
                        <h2>Pide tu Tarjeta de Crédito CMR</h2>
                        <h3>de Banco Falabella y empieza a disfrutar hoy</h3>
                    </div>
                    <a href="solicitud.php" class="btn-hero btn-inline btn-white">¡Quiero mi CMR!</a>
                </div>
                <div class="faq">
                    <details>
                        <summary>¿Cómo mejorar el score crediticio para obtener una tarjeta?</summary>
                    </details>
                    <details>
                        <summary>¿Qué se tiene en cuenta para aprobar una Tarjeta de Crédito?</summary>
                    </details>
                    <details>
                        <summary>¿Diferencia entre una Tarjeta de Crédito y una Tarjeta Débito?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo elegir la mejor Tarjeta de Crédito?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo se aplican los pagos realizados a mi Tarjeta de Crédito?</summary>
                    </details>
                    <details>
                        <summary>Cuando realizo el pago de la tarjeta de Credito CMR Banco Falabella a través de PSE ¿en
                            cuánto tiempo ingresa mi pago?</summary>
                    </details>
                    <details>
                        <summary>¿Después de realizar una compra a más de una cuota, ¿Cuándo me cobran los intereses
                            corrientes?</summary>
                    </details>
                    <details>
                        <summary>¿A qué tasa de interés se liquida una compra realizada en el exterior?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo puedo crear o desbloquear mi clave digital?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo hago para cambiar la fecha de corte de mi Tarjeta de Crédito?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo puedo solicitar el plástico de mi Tarjeta si se encuentra bloqueada?</summary>
                    </details>
                    <details>
                        <summary>¿Cuándo recibo mi extracto?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo funciona la acumulación de CMR Puntos por medio de tu Tarjeta de Crédito?
                        </summary>
                    </details>
                    <details>
                        <summary>¿Cómo puedo aumentar el cupo de mi Tarjeta?</summary>
                    </details>
                    <details>
                        <summary>¿Cómo puedo realizar el bloqueo de mi Tarjeta de crédito en caso de robo o pérdida?
                        </summary>
                    </details>
                    <details>
                        <summary>¿Qué cubre el seguro de vida deudor?</summary>
                    </details>
                </div>
            </div>
        </section>

        <section class="section section-last">
            <div class="container">
                <div class="section-heading">
                    <h2>Conoce nuestras Tarjetas de Crédito Banco Falabella</h2>
                    <p>En Banco Falabella contamos con dos Tarjetas de Crédito, elige la mejor opción para ti.</p>
                </div>
                <div class="product-cards">
                    <article class="product-card">
                        <img src="img/info1.webp" alt="Tarjeta CMR Mastercard">
                        <div class="product-body">
                            <h2>Tarjeta CMR Mastercard</h2>
                            <p>Disfruta de una tarjeta que puedes usar todos los días y en cualquier comercio.</p>
                            <ul class="check-list">
                                <li>3 meses sin cuota de manejo para clientes nuevos</li>
                                <li>40% de descuentos todos los días</li>
                                <li>Acumulación de CMR Puntos en todas tus compras</li>
                                <li>Preventas exclusivas</li>
                                <li>Tarjeta 100% digital desde la App Banco Falabella</li>
                                <li>Beneficios exclusivos en Homecenter, Falabella y Falabella.com.</li>
                            </ul>
                            <p class="fee">Cuota de manejo: $31.990, si eres cliente nuevo tienes 3 meses sin cuota de
                                manejo</p>
                        </div>
                    </article>
                    <article class="product-card">
                        <img src="img/info2.webp" alt="Tarjeta CMR Black Mastercard">
                        <div class="product-body">
                            <h2>Tarjeta CMR Black Mastercard</h2>
                            <p>Una Tarjeta Black donde disfruta de beneficios todos los días, aparte de los beneficios
                                de la CMR Clásica tienes:</p>
                            <ul class="check-list">
                                <li>Acceso a Salas VIP (3 entradas gratis al año)</li>
                                <li>3 meses sin cuota de manejo para clientes nuevos</li>
                                <li>40% de descuentos todos los días</li>
                                <li>Acumulación de CMR Puntos en todas tus compras</li>
                                <li>Preventas exclusivas</li>
                                <li>Tarjeta 100% digital desde la App Banco Falabella</li>
                                <li>Beneficios exclusivos en Homecenter, Falabella y Falabella.com.</li>
                            </ul>
                            <p class="fee">Cuota de manejo: $46.990, si eres cliente nuevo, tienes 3 meses sin cuota de
                                manejo.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Banco Falabella S.A. Vigilado por la Superintendencia Financiera de Colombia.</p>
        </div>
    </footer>

    <a href="#" class="floating-pse-link"><span class="pse-dot"></span>Pagos en línea PSE</a>

    <script>
        var t = document.getElementById('menuToggle'), n = document.getElementById('mainNav');
        t.addEventListener('click', function () {
            var o = n.classList.toggle('open');
            t.setAttribute('aria-expanded', o);
        });
    </script>

</body>

</html>