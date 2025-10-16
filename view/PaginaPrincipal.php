<?php
include_once '../includes/header.php';
include_once '../includes/sidebar.php';
?>

<div class="main-content">
    <div class="container py-5">
        <h2 class="text-center mb-4 text-success fw-bold">Panel Principal</h2>
        <div class="row g-4 justify-content-center">
            <?php
            $secciones = [
                ['nombre' => 'Articulos', 'icono' => 'bi-box', 'link' => 'articulos.php'],
                /* ['nombre' => 'Cartera', 'icono' => 'bi-wallet2', 'link' => '../view/cartera.php'], */
                ['nombre' => 'Clientes', 'icono' => 'bi-people', 'link' => '../view/clientes.php'],
                ['nombre' => 'Compras', 'icono' => 'bi-cart-check', 'link' => '../view/compras.php'],
                /* ['nombre' => 'Consumos', 'icono' => 'bi-graph-up', 'link' => '../view/consumos.php'], */
                ['nombre' => 'Nomina', 'icono' => 'bi-person-lines-fill', 'link' => '../view/nomina.php'],
                ['nombre' => 'Personal', 'icono' => 'bi-person-workspace', 'link' => '../view/personal.php'],
                ['nombre' => 'Produccion', 'icono' => 'bi-gear', 'link' => '../view/produccion.php'],
                ['nombre' => 'Proveedores', 'icono' => 'bi-truck', 'link' => '../view/proveedores.php'],
                ['nombre' => 'Ventas', 'icono' => 'bi-cash-stack', 'link' => '../view/ventas.php'],
            ];

            foreach ($secciones as $sec) {
                echo '
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="'.$sec['link'].'" class="text-decoration-none">
                        <div class="card-btn text-center p-4">
                            <i class="bi '.$sec['icono'].' icono mb-2"></i>
                            <h6>'.$sec['nombre'].'</h6>
                        </div>
                    </a>
                </div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
