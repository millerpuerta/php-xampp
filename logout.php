<?php
session_start();
$usuario = $_SESSION["usuario"] ?? "Usuario";
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3;url=login.php">
    <title>Cerrando sesión...</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0a0f;
            color: #f0f0f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(232,255,74,0.04) 0%, transparent 70%);
            pointer-events: none;
        }

        .box {
            text-align: center;
            animation: fadeUp 0.4s ease both;
            padding: 24px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .icon {
            font-size: 48px;
            margin-bottom: 20px;
            animation: wave 1.5s ease infinite;
        }

        @keyframes wave {
            0%,100% { transform: rotate(0deg); }
            20%     { transform: rotate(-15deg); }
            40%     { transform: rotate(15deg); }
            60%     { transform: rotate(-10deg); }
            80%     { transform: rotate(10deg); }
        }

        h2 {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        p {
            color: #6b6b80;
            font-size: 15px;
            margin-bottom: 28px;
        }

        .progress {
            width: 200px;
            height: 3px;
            background: #1e1e2e;
            border-radius: 100px;
            margin: 0 auto 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #e8ff4a;
            border-radius: 100px;
            animation: progress 3s linear forwards;
        }

        @keyframes progress {
            from { width: 0%; }
            to   { width: 100%; }
        }

        a {
            font-size: 13px;
            color: #e8ff4a;
            text-decoration: none;
            border-bottom: 1px solid rgba(232,255,74,0.3);
            padding-bottom: 1px;
            transition: border-color 0.2s;
        }

        a:hover { border-color: #e8ff4a; }
    </style>
</head>
<body>
<div class="box">
    <div class="icon">👋</div>
    <h2>¡Hasta pronto, <?= htmlspecialchars($usuario) ?>!</h2>
    <p>Tu sesión fue cerrada correctamente.<br>Redirigiendo en 3 segundos…</p>
    <div class="progress"><div class="progress-bar"></div></div>
    <a href="login.php">Volver al inicio ahora →</a>
</div>
</body>
</html>
