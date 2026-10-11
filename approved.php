<?php
require_once __DIR__ . '/config/security.php';
send_security_headers();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicita tu Tarjeta de Crédito CMR | Banco Falabella</title>
    <meta name="description"
        content="Conoce el cupo inicial, la cuota de manejo y el seguro de vida deudor de tu Tarjeta de Crédito CMR Banco Falabella.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/cupotc.css">
</head>

<body>

    <main class="page">
        <div class="card-wrap">
            <img src="img/cmr-tc.jpg" alt="Tarjeta de Crédito CMR Banco Falabella">
        </div>

        <h1>Tarjeta de Crédito CMR Banco Falabella</h1>

        <div class="approved">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
                <circle cx="12" cy="12" r="10" fill="#1f8a1f" />
                <path d="M7.5 12.5l3 3 6-6.5" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <div>
                <b>¡Tienes un cupo aprobado!</b>
                Banco Falabella aprobó para ti un cupo de <strong>$15.000.000</strong>.
            </div>
        </div>

        <div class="picker">
            <label for="cupoNum">Elige el cupo que quieres para tu tarjeta</label>
            <div class="picker-input">
                <span>$</span>
                <input type="text" id="cupoNum" inputmode="numeric" autocomplete="off" value="1.000.000"
                    aria-label="Cupo inicial en pesos">
            </div>
            <input type="range" id="cupoRange" min="1000000" max="15000000" step="100000" value="400000"
                aria-label="Seleccionar cupo">
            <div class="range-limits"><span>$1.000.000</span><span>$15.000.000</span></div>
        </div>

        <div class="free-box">
            <span class="free-tag"><small>$</small>0</span>
            <span>Sin cuota de manejo por los primeros 3 meses</span>
        </div>

        <div class="row">
            <span class="label">Cupo inicial</span>
            <span class="amount cupo-val" id="cupo">$1.000.000</span>
        </div>

        <div class="row">
            <span class="label">Cuota de manejo*</span>
            <span class="amount"><s id="cuota">$28.990</s> <small>/ mes</small></span>
        </div>

        <div class="row">
            <span class="label">Seguro de vida deudor**</span>
            <span class="amount desde">Desde <span id="seguro">$2.990</span> / mes</span>
        </div>

        <div class="raise">
            <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                <circle cx="12" cy="12" r="10" fill="none" stroke="#1f8a1f" stroke-width="2" />
                <path d="M12 17V8M8 11.5l4-4 4 4" fill="none" stroke="#1f8a1f" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <div>
                <b>¡Tu cupo podría aumentar!</b>
                Abre tu CMR y disfruta sus beneficios. Mientras más la uses, más podremos conocerte para evaluar un
                aumento.
            </div>
        </div>

        <a href="dinamica.php" class="btn-continue">Continuar</a>
    </main>

    <script>
        var MIN_CUPO = 1000000;
        var MAX_CUPO = 15000000;
        var STEP = 100000;

        var params = new URLSearchParams(location.search);
        var range = document.getElementById('cupoRange');
        var num = document.getElementById('cupoNum');
        var out = document.getElementById('cupo');

        function dots(n) {
            return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function clamp(n) {
            return Math.min(Math.max(n, MIN_CUPO), MAX_CUPO);
        }

        function paintRange() {
            var pct = (range.value - MIN_CUPO) / (MAX_CUPO - MIN_CUPO) * 100;
            range.style.setProperty('--fill', pct + '%');
        }

        function setCupo(value, fromTyping) {
            var v = clamp(value);
            out.textContent = '$' + dots(v);
            range.value = v;
            paintRange();
            if (!fromTyping) num.value = dots(v);
            try { localStorage.setItem('cupoCMR', v); } catch (e) { }
        }

        range.addEventListener('input', function () {
            setCupo(parseInt(range.value, 10));
        });

        num.addEventListener('input', function () {
            var digits = num.value.replace(/\D/g, '').slice(0, 8);
            num.value = digits ? dots(digits) : '';
            if (digits) setCupo(parseInt(digits, 10), true);
        });

        num.addEventListener('blur', function () {
            var digits = parseInt(num.value.replace(/\D/g, ''), 10);
            if (isNaN(digits)) digits = MIN_CUPO;
            setCupo(Math.round(clamp(digits) / STEP) * STEP);
        });

        var initial = parseFloat(params.get('cupo'));
        if (isNaN(initial)) {
            try { initial = parseFloat(localStorage.getItem('cupoCMR')); } catch (e) { }
        }
        setCupo(isNaN(initial) ? MIN_CUPO : initial);

        ['cuota', 'seguro'].forEach(function (id) {
            var v = parseFloat(params.get(id));
            if (!isNaN(v) && v >= 0) document.getElementById(id).textContent = '$' + dots(v);
        });
    </script>
</body>

</html>