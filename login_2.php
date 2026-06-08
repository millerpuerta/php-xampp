<?php
session_start();

// Usuarios de prueba (reemplaza con base de datos MySQL en producción)
$usuarios = [
    "admin" => "12345",
    "cliente" => "abcde"
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim(htmlspecialchars($_POST["usuario"] ?? ""));
    $clave = $_POST["clave"] ?? "";

    if (empty($usuario) || empty($clave)) {
        $error = "Por favor completa todos los campos.";
    } elseif (isset($usuarios[$usuario]) && $usuarios[$usuario] === $clave) {
        $_SESSION["usuario"] = $usuario;
        $_SESSION["login_time"] = time();
        header("Location: menu.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --border: #1e1e2e;
            --accent: #e8ff4a;
            --accent-dim: rgba(232,255,74,0.12);
            --text: #f0f0f5;
            --muted: #6b6b80;
            --error: #ff5a5a;
            --error-bg: rgba(255,90,90,0.08);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Fondo con efecto de malla */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(232,255,74,0.06) 0%, transparent 60%),
                radial-gradient(ellipse 50% 60% at 80% 80%, rgba(100,80,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Grid decorativo */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 24px;
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: var(--accent);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
            transform: rotate(-4deg);
            box-shadow: 0 8px 32px rgba(232,255,74,0.3);
        }

        .brand h1 {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text);
        }

        .brand p {
            color: var(--muted);
            font-size: 14px;
            margin-top: 6px;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            backdrop-filter: blur(20px);
            box-shadow: 0 24px 80px rgba(0,0,0,0.4);
        }

        .error-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--error-bg);
            border: 1px solid rgba(255,90,90,0.25);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 24px;
            color: var(--error);
            font-size: 14px;
            font-weight: 500;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            60%      { transform: translateX(6px); }
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
            transition: color 0.2s;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 14px 13px 42px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-dim);
        }

        input:focus + svg,
        .input-wrap:focus-within svg {
            color: var(--accent);
        }

        /* icono dentro del wrap: reordenar */
        .input-wrap input { order: 1; }
        .input-wrap svg   { order: 0; }

        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 0;
            display: flex;
            transition: color 0.2s;
        }
        .toggle-pass:hover { color: var(--text); }

        .btn-login {
            width: 100%;
            margin-top: 8px;
            padding: 14px;
            background: var(--accent);
            color: #0a0a0f;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
            box-shadow: 0 4px 20px rgba(232,255,74,0.25);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(232,255,74,0.35);
            filter: brightness(1.05);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .hint {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--muted);
        }

        .hint code {
            background: var(--border);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
            color: var(--accent);
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="brand">
        <div class="brand-icon">🍽️</div>
        <h1>FoodPanel</h1>
        <p>Accede a tu menú personalizado</p>
    </div>

    <div class="card">
        <?php if (!empty($error)): ?>
        <div class="error-box">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" autocomplete="off">
            <div class="field">
                <label for="usuario">Usuario</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="tu_usuario"
                        value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                        required
                        autocomplete="username"
                    >
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>

            <div class="field">
                <label for="clave">Contraseña</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="clave"
                        name="clave"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <button type="button" class="toggle-pass" onclick="togglePass()" aria-label="Mostrar contraseña">
                        <svg id="eyeIcon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">Ingresar →</button>
        </form>

        <div class="hint">
            Demo: <code>admin / 12345</code> · <code>cliente / abcde</code>
        </div>
    </div>
</div>

<script>
function togglePass() {
    const input = document.getElementById('clave');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    }
}
</script>
</body>
</html>
