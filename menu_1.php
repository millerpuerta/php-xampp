<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = htmlspecialchars($_SESSION["usuario"]);
$esAdmin = ($usuario === "admin");

// Menú de comidas de ejemplo
$categorias = [
    [
        "nombre" => "Entradas",
        "icono"  => "🥗",
        "items"  => [
            ["nombre" => "Ensalada César",        "precio" => "$12.000", "desc" => "Lechuga romana, pollo, parmesano"],
            ["nombre" => "Sopa del día",           "precio" => "$9.500",  "desc" => "Cremosa y reconfortante"],
            ["nombre" => "Tabla de quesos",        "precio" => "$18.000", "desc" => "Selección de quesos artesanales"],
        ]
    ],
    [
        "nombre" => "Platos fuertes",
        "icono"  => "🍖",
        "items"  => [
            ["nombre" => "Lomo al trapo",          "precio" => "$38.000", "desc" => "Corte premium, término medio"],
            ["nombre" => "Pechuga gratinada",      "precio" => "$28.000", "desc" => "Con espinaca y champiñones"],
            ["nombre" => "Pasta al pesto",         "precio" => "$22.000", "desc" => "Fettuccine, albahaca fresca"],
        ]
    ],
    [
        "nombre" => "Bebidas",
        "icono"  => "🥤",
        "items"  => [
            ["nombre" => "Limonada de coco",       "precio" => "$8.000",  "desc" => "Refrescante y tropical"],
            ["nombre" => "Jugo natural",            "precio" => "$6.500",  "desc" => "Naranja, mango o mora"],
            ["nombre" => "Agua con gas",            "precio" => "$4.000",  "desc" => "500 ml"],
        ]
    ],
    [
        "nombre" => "Postres",
        "icono"  => "🍮",
        "items"  => [
            ["nombre" => "Tiramisú",               "precio" => "$14.000", "desc" => "Receta tradicional italiana"],
            ["nombre" => "Brownie con helado",     "precio" => "$11.000", "desc" => "Chocolate intenso y vainilla"],
            ["nombre" => "Mousse de maracuyá",     "precio" => "$10.000", "desc" => "Suave y frutal"],
        ]
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal — FoodPanel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --card: #15151f;
            --border: #1e1e2e;
            --accent: #e8ff4a;
            --accent-dim: rgba(232,255,74,0.10);
            --text: #f0f0f5;
            --muted: #6b6b80;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 50% 40% at 10% 10%, rgba(232,255,74,0.05) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 90% 90%, rgba(100,80,255,0.07) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Navbar ── */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10,10,15,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand span {
            background: var(--accent);
            color: #0a0a0f;
            font-size: 18px;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-4deg);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--muted);
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--accent-dim);
            border: 1px solid rgba(232,255,74,0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--accent);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            padding: 7px 14px;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .btn-logout:hover {
            border-color: #ff5a5a;
            color: #ff5a5a;
            background: rgba(255,90,90,0.06);
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            z-index: 1;
            padding: 56px 32px 40px;
            max-width: 1100px;
            margin: 0 auto;
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-dim);
            border: 1px solid rgba(232,255,74,0.2);
            border-radius: 100px;
            padding: 4px 12px;
            font-size: 12px;
            color: var(--accent);
            font-weight: 500;
            margin-bottom: 16px;
        }

        .hero h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 10px;
        }

        .hero h2 em {
            font-style: normal;
            color: var(--accent);
        }

        .hero p {
            color: var(--muted);
            font-size: 15px;
            max-width: 420px;
        }

        <?php if ($esAdmin): ?>
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,180,0,0.1);
            border: 1px solid rgba(255,180,0,0.3);
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            color: #ffb400;
            font-weight: 500;
            margin-top: 16px;
        }
        <?php endif; ?>

        /* ── Grid de categorías ── */
        main {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 32px 64px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .categoria {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.2s, border-color 0.2s;
            animation: fadeUp 0.5s ease both;
        }

        .categoria:nth-child(1) { animation-delay: 0.05s; }
        .categoria:nth-child(2) { animation-delay: 0.10s; }
        .categoria:nth-child(3) { animation-delay: 0.15s; }
        .categoria:nth-child(4) { animation-delay: 0.20s; }

        .categoria:hover {
            transform: translateY(-4px);
            border-color: rgba(232,255,74,0.2);
        }

        .cat-header {
            padding: 20px 22px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cat-icon {
            font-size: 22px;
            width: 40px;
            height: 40px;
            background: var(--accent-dim);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cat-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 16px;
        }

        .cat-count {
            margin-left: auto;
            background: var(--border);
            border-radius: 100px;
            padding: 2px 10px;
            font-size: 11px;
            color: var(--muted);
        }

        .cat-items { padding: 8px 0; }

        .menu-item {
            padding: 12px 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            transition: background 0.15s;
            cursor: default;
        }

        .menu-item:last-child { border-bottom: none; }
        .menu-item:hover { background: rgba(255,255,255,0.02); }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .item-desc {
            font-size: 12px;
            color: var(--muted);
        }
        .item-price {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--accent);
            white-space: nowrap;
            margin-top: 2px;
        }

        @media (max-width: 600px) {
            nav { padding: 0 16px; }
            .hero, main { padding-left: 16px; padding-right: 16px; }
            main { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">
        <span>🍽️</span> FoodPanel
    </div>
    <div class="nav-right">
        <div class="nav-user">
            <div class="avatar"><?= strtoupper(substr($usuario, 0, 1)) ?></div>
            <?= $usuario ?>
        </div>
        <a href="logout.php" class="btn-logout">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Salir
        </a>
    </div>
</nav>

<div class="hero">
    <div class="hero-tag">
        <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
        Menú activo hoy
    </div>
    <h2>Bienvenido, <em><?= $usuario ?></em>.</h2>
    <p>Explora nuestras categorías y elige lo que más te apetezca.</p>
    <?php if ($esAdmin): ?>
    <div class="admin-badge">
        ⚡ Modo administrador activo
    </div>
    <?php endif; ?>
</div>

<main>
    <?php foreach ($categorias as $cat): ?>
    <div class="categoria">
        <div class="cat-header">
            <div class="cat-icon"><?= $cat['icono'] ?></div>
            <div class="cat-title"><?= htmlspecialchars($cat['nombre']) ?></div>
            <div class="cat-count"><?= count($cat['items']) ?></div>
        </div>
        <div class="cat-items">
            <?php foreach ($cat['items'] as $item): ?>
            <div class="menu-item">
                <div class="item-info">
                    <div class="item-name"><?= htmlspecialchars($item['nombre']) ?></div>
                    <div class="item-desc"><?= htmlspecialchars($item['desc']) ?></div>
                </div>
                <div class="item-price"><?= htmlspecialchars($item['precio']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</main>

</body>
</html>
