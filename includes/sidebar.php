<?php
$menu = [
    ['nombre' => 'Página principal', 'icono' => 'bi-house-door', 'link' => '../view/PaginaPrincipal.php'],
    ['nombre' => 'Articulos', 'icono' => 'bi-box', 'link' => '../view/articulos.php'],
    /* ['nombre' => 'Cartera', 'icono' => 'bi-wallet2', 'link' => '../view/cartera.php'], */
    ['nombre' => 'Clientes', 'icono' => 'bi-people', 'link' => '../view/clientes.php'],
    ['nombre' => 'Compras', 'icono' => 'bi-cart-check', 'link' => '../view/compras.php'],
    /* ['nombre' => 'Consumos', 'icono' => 'bi-graph-up', 'link' => '../view/consumos.php'], */
    ['nombre' => 'Nomina', 'icono' => 'bi-person-lines-fill', 'link' => '../view/nomina.php'],
    ['nombre' => 'Personal', 'icono' => 'bi-person-workspace', 'link' => '../view/personal.php'],
    ['nombre' => 'Produccion', 'icono' => 'bi-gear', 'link' => '../view/produccion.php'],
    ['nombre' => 'Proveedores', 'icono' => 'bi-truck', 'link' => '../view/proveedores.php'],
    ['nombre' => 'Ventas', 'icono' => 'bi-cash-stack', 'link' => '../view/ventas.php']
];

// Detectar la página actual
$current_page = basename($_SERVER['PHP_SELF']);

// Colores por sección (puedes personalizarlos)
$colorSeccion = [
    'PaginaPrincipal.php' => 'linear-gradient(90deg, #2e7d32, #66bb6a)',
    'articulos.php'        => 'linear-gradient(90deg, #1b9a2eff, #6abc47ff)',
    /* 'cartera.php'          => 'linear-gradient(90deg, #00ef34ff, #189849ff)', */
    'clientes.php'         => 'linear-gradient(90deg, #31c015ff, #42f596ff)',
    'compras.php'          => 'linear-gradient(90deg, #2e7d32, #81c784)',
    /* 'consumos.php'         => 'linear-gradient(90deg, #25f930ff, #73bd42ff)', */
    'nomina.php'           => 'linear-gradient(90deg, #77c628ff, #50ef60ff)',
    'personal.php'         => 'linear-gradient(90deg, #526d41ff, #5cc83eff)',
    'produccion.php'       => 'linear-gradient(90deg, #71a957ff, #5cc078ff)',
    'proveedores.php'      => 'linear-gradient(90deg, #006913ff, #26a64aff)',
    'ventas.php'           => 'linear-gradient(90deg, #2e7d32, #81c784)'
];
?>

<div class="sidebar">
    <ul class="nav flex-column">
        <?php foreach ($menu as $item): 
            $pageName = basename($item['link']);
            $isActive = ($pageName == $current_page);
            $bgColor = $isActive ? "background: {$colorSeccion[$pageName]}; color: white; font-weight:600; box-shadow: inset 3px 0 0 #1b5e20;" : "";
        ?>
            <li class="nav-item">
                <a href="<?= $item['link'] ?>" class="nav-link d-flex align-items-center <?= $isActive ? 'active' : '' ?>" style="<?= $bgColor ?>">
                    <i class="bi <?= $item['icono'] ?> me-2" style="<?= $isActive ? 'color:white;' : '' ?>"></i> 
                    <?= $item['nombre'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
