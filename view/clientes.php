<?php
include_once '../includes/header.php';
include_once '../includes/sidebar.php';
?>

<div class="main-content p-4">
    <div class="container-fluid"> <br><br><br>
        <h2 class="fw-bold text-dark mb-4">Gestión de Clientes</h2>

        <!-- Mensajes -->
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                Cliente agregado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al agregar cliente: <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- Buscador y Añadir Cliente -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <form method="GET" class="input-group w-auto mb-2">
                <input type="text" name="buscar" id="busqueda" class="form-control" placeholder="Buscar por Razón Social o NIT" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button class="btn btn-outline-primary" type="submit" id="btnBuscar"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-primary d-flex align-items-center mb-2" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="bi bi-plus-lg me-2"></i> Añadir Cliente
            </button>
        </div>

        <!-- Tabla simplificada -->
        <div class="shadow-sm rounded bg-white">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Razón Social</th>
                        <th>NIT</th>
                        <th>Representante</th>
                        <th>Email Representante</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include("../config/conexion.php");
                    $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
                    $sql = "SELECT * FROM tb_clientes WHERE razon_social LIKE :buscar OR nit LIKE :buscar ORDER BY id DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['buscar' => "%$busqueda%"]);
                    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($clientes) > 0) {
                        foreach ($clientes as $c) {
                            $estadoClass = ($c['estado'] === 'Activo') ? 'bg-primary' : 'bg-secondary';
                            echo '<tr data-id="' . $c['id'] . '">';
                            echo '<td>' . htmlspecialchars($c['razon_social']) . '</td>';
                            echo '<td>' . htmlspecialchars($c['nit']) . '</td>';
                            echo '<td>' . htmlspecialchars($c['representante']) . '</td>';
                            echo '<td>' . htmlspecialchars($c['email_representante']) . '</td>';
                            echo '<td>' . htmlspecialchars($c['telefono']) . '</td>';
                            echo '<td><span class="badge ' . $estadoClass . ' estado-badge">' . $c['estado'] . '</span></td>';
                            echo '<td>
                                    <button class="btn btn-sm btn-outline-primary editar-estado me-1" data-id="' . $c['id'] . '" data-estado="' . $c['estado'] . '" data-bs-toggle="modal" data-bs-target="#modalEditarEstado">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary ver-cliente" 
                                        data-bs-toggle="modal" data-bs-target="#modalVerCliente"
                                        data-razon="' . htmlspecialchars($c['razon_social']) . '"
                                        data-nit="' . htmlspecialchars($c['nit']) . '"
                                        data-tipo="' . $c['tipo_persona'] . '"
                                        data-rep="' . htmlspecialchars($c['representante']) . '"
                                        data-email="' . htmlspecialchars($c['email_representante']) . '"
                                        data-direccion="' . htmlspecialchars($c['direccion']) . '"
                                        data-telefono="' . htmlspecialchars($c['telefono']) . '"
                                        data-ciudad="' . htmlspecialchars($c['ciudad']) . '"
                                        data-fecha="' . $c['fecha_incorporacion'] . '"
                                        data-obs="' . htmlspecialchars($c['observaciones']) . '"
                                        data-estado="' . $c['estado'] . '"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No hay clientes para mostrar</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AGREGAR CLIENTE -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarLabel">Añadir Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="../controllers/guardar_cliente.php" method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Razón Social:</label>
              <input type="text" class="form-control" name="razon_social" required>
            </div>
            <div class="col-md-6">
              <label>NIT:</label>
              <input type="text" class="form-control" name="nit" required>
            </div>
            <div class="col-md-6">
              <label>Tipo Persona:</label>
              <select class="form-select" name="tipo_persona" required>
                <option value="Normal">Normal</option>
                <option value="Juridica">Juridica</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Representante:</label>
              <input type="text" class="form-control" name="representante">
            </div>
            <div class="col-md-6">
              <label>Email Representante:</label>
              <input type="email" class="form-control" name="email_representante">
            </div>
            <div class="col-md-6">
              <label>Dirección:</label>
              <input type="text" class="form-control" name="direccion">
            </div>
            <div class="col-md-6">
              <label>Teléfono:</label>
              <input type="text" class="form-control" name="telefono">
            </div>
            <div class="col-md-6">
              <label>Ciudad:</label>
              <input type="text" class="form-control" name="ciudad">
            </div>
            <div class="col-md-6">
              <label>Fecha Incorporación:</label>
              <input type="date" class="form-control" name="fecha_incorporacion" value="<?= date('Y-m-d'); ?>" readonly>
            </div>
            <div class="col-md-6">
              <label>Estado:</label>
              <select class="form-select" name="estado" required>
                  <option value="Activo" selected>Activo</option>
                  <option value="Inactivo">Inactivo</option>
              </select>
            </div>
            <div class="col-12">
              <label>Observaciones:</label>
              <textarea class="form-control" name="observaciones" rows="2"></textarea>
            </div>
          </div>
          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDITAR ESTADO -->
<div class="modal fade" id="modalEditarEstado" tabindex="-1" aria-labelledby="modalEditarEstadoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEditarEstadoLabel">Editar Estado del Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarEstadoCliente">
          <input type="hidden" id="editar_id_cliente">
          <div class="mb-3">
            <label for="editar_estado_cliente" class="form-label">Nuevo Estado:</label>
            <select class="form-select" id="editar_estado_cliente" required>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-primary" id="guardarEstadoCliente">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL VER CLIENTE -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalVerClienteLabel">Detalles del Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
            <tr><th>Razón Social:</th><td id="ver_razon"></td></tr>
            <tr><th>NIT:</th><td id="ver_nit"></td></tr>
            <tr><th>Tipo Persona:</th><td id="ver_tipo"></td></tr>
            <tr><th>Representante:</th><td id="ver_rep"></td></tr>
            <tr><th>Email Representante:</th><td id="ver_email"></td></tr>
            <tr><th>Dirección:</th><td id="ver_direccion"></td></tr>
            <tr><th>Teléfono:</th><td id="ver_telefono"></td></tr>
            <tr><th>Ciudad:</th><td id="ver_ciudad"></td></tr>
            <tr><th>Fecha Incorporación:</th><td id="ver_fecha"></td></tr>
            <tr><th>Observaciones:</th><td id="ver_obs"></td></tr>
            <tr><th>Estado:</th><td id="ver_estado"></td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Editar estado
    document.querySelectorAll(".editar-estado").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("editar_id_cliente").value = btn.dataset.id;
            document.getElementById("editar_estado_cliente").value = btn.dataset.estado;
        });
    });

    // Guardar estado
    document.getElementById("guardarEstadoCliente").addEventListener("click", () => {
        const id = document.getElementById("editar_id_cliente").value;
        const estado = document.getElementById("editar_estado_cliente").value;

        fetch("../controllers/actualizar_estado_cliente.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id + "&estado=" + estado
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert("Error: " + data.message);
        })
        .catch(err => { console.error(err); alert("Error al actualizar el estado"); });
    });

    // Ver cliente
    document.querySelectorAll(".ver-cliente").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("ver_razon").textContent = btn.dataset.razon;
            document.getElementById("ver_nit").textContent = btn.dataset.nit;
            document.getElementById("ver_tipo").textContent = btn.dataset.tipo;
            document.getElementById("ver_rep").textContent = btn.dataset.rep;
            document.getElementById("ver_email").textContent = btn.dataset.email;
            document.getElementById("ver_direccion").textContent = btn.dataset.direccion;
            document.getElementById("ver_telefono").textContent = btn.dataset.telefono;
            document.getElementById("ver_ciudad").textContent = btn.dataset.ciudad;
            document.getElementById("ver_fecha").textContent = btn.dataset.fecha;
            document.getElementById("ver_obs").textContent = btn.dataset.obs;
            document.getElementById("ver_estado").textContent = btn.dataset.estado;
        });
    });
});
</script>

<?php include("../includes/footer.php"); ?>
