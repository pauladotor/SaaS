<?php
include_once '../includes/header.php';
include_once '../includes/sidebar.php';
?>

<div class="main-content p-4">
    <div class="container-fluid"> <br><br><br>
        <h2 class="fw-bold text-dark mb-4">Gestión de Proveedores</h2>

        <!-- Mensajes -->
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Proveedor agregado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- Buscador y Añadir Proveedor -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <form method="GET" class="input-group w-auto mb-2">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o documento" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success d-flex align-items-center mb-2" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="bi bi-plus-lg me-2"></i> Añadir Proveedor
            </button>
        </div>

        <!-- Tabla -->
        <div class="shadow-sm rounded bg-white">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-success">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo Documento</th>
                        <th>Número Documento</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("../config/conexion.php");
                    $busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
                    $sql = "SELECT * FROM tb_proveedores WHERE nombre LIKE :buscar OR numero_documento LIKE :buscar ORDER BY id DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['buscar' => "%$busqueda%"]);
                    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if(count($proveedores) > 0){
                        foreach($proveedores as $p){
                            $estadoClass = ($p['estado'] === 'Activo') ? 'bg-success' : 'bg-secondary';
                            echo '<tr data-id="'.$p['id'].'">';
                            echo '<td>'.htmlspecialchars($p['nombre']).'</td>';
                            echo '<td>'.$p['tipo_documento'].'</td>';
                            echo '<td>'.htmlspecialchars($p['numero_documento']).'</td>';
                            echo '<td>'.htmlspecialchars($p['telefono']).'</td>';
                            echo '<td>'.htmlspecialchars($p['email']).'</td>';
                            echo '<td><span class="badge '.$estadoClass.'">'.$p['estado'].'</span></td>';
                            echo '<td>
                                    <button class="btn btn-sm btn-outline-success editar-estado me-1" data-id="'.$p['id'].'" data-estado="'.$p['estado'].'" data-bs-toggle="modal" data-bs-target="#modalEditarEstado">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success ver-proveedor" 
                                        data-bs-toggle="modal" data-bs-target="#modalVerProveedor"
                                        data-nombre="'.htmlspecialchars($p['nombre']).'"
                                        data-tipo="'.$p['tipo_documento'].'"
                                        data-num="'.$p['numero_documento'].'"
                                        data-fecha="'.$p['fecha_incorporacion'].'"
                                        data-direccion="'.htmlspecialchars($p['direccion']).'"
                                        data-telefono="'.htmlspecialchars($p['telefono']).'"
                                        data-email="'.htmlspecialchars($p['email']).'"
                                        data-obs="'.htmlspecialchars($p['observaciones']).'"
                                        data-estado="'.$p['estado'].'"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No hay proveedores para mostrar</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AÑADIR PROVEEDOR -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalAgregarLabel">Añadir Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="../controllers/guardar_proveedor.php" method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label>Tipo Documento:</label>
              <select class="form-select" name="tipo_documento" required>
                <option value="Cedula de Ciudadania">Cédula Ciudadanía</option>
                <option value="Cedula Extranjera">Cédula Extranjera</option>
                <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Número Documento:</label>
              <input type="text" class="form-control" name="numero_documento" required>
            </div>
            <div class="col-md-6">
              <label>Fecha Incorporación:</label>
              <input type="date" class="form-control" name="fecha_incorporacion" value="<?= date('Y-m-d'); ?>" readonly>
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
              <label>Email:</label>
              <input type="email" class="form-control" name="email">
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
            <button type="submit" class="btn btn-success">Guardar</button>
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
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Editar Estado del Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editar_id_proveedor">
        <div class="mb-3">
            <label for="editar_estado_proveedor" class="form-label">Nuevo Estado:</label>
            <select class="form-select" id="editar_estado_proveedor">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-success" id="guardarEstadoProveedor">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL VER PROVEEDOR -->
<div class="modal fade" id="modalVerProveedor" tabindex="-1" aria-labelledby="modalVerProveedorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Detalles del Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
            <tr><th>Nombre:</th><td id="ver_nombre"></td></tr>
            <tr><th>Tipo Documento:</th><td id="ver_tipo"></td></tr>
            <tr><th>Número Documento:</th><td id="ver_num"></td></tr>
            <tr><th>Fecha Incorporación:</th><td id="ver_fecha"></td></tr>
            <tr><th>Dirección:</th><td id="ver_direccion"></td></tr>
            <tr><th>Teléfono:</th><td id="ver_telefono"></td></tr>
            <tr><th>Email:</th><td id="ver_email"></td></tr>
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
document.addEventListener("DOMContentLoaded", function(){
    // Editar estado
    document.querySelectorAll(".editar-estado").forEach(btn=>{
        btn.addEventListener("click", ()=>{
            document.getElementById("editar_id_proveedor").value = btn.dataset.id;
            document.getElementById("editar_estado_proveedor").value = btn.dataset.estado;
        });
    });

    document.getElementById("guardarEstadoProveedor").addEventListener("click", ()=>{
        const id = document.getElementById("editar_id_proveedor").value;
        const estado = document.getElementById("editar_estado_proveedor").value;

        fetch("../controllers/actualizar_estado_proveedor.php",{
            method:"POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"},
            body: "id="+id+"&estado="+estado
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success) location.reload();
            else alert("Error: "+data.message);
        })
        .catch(err=>{console.error(err); alert("Error al actualizar el estado");});
    });

    // Ver proveedor
    document.querySelectorAll(".ver-proveedor").forEach(btn=>{
        btn.addEventListener("click", ()=>{
            document.getElementById("ver_nombre").textContent = btn.dataset.nombre;
            document.getElementById("ver_tipo").textContent = btn.dataset.tipo;
            document.getElementById("ver_num").textContent = btn.dataset.num;
            document.getElementById("ver_fecha").textContent = btn.dataset.fecha;
            document.getElementById("ver_direccion").textContent = btn.dataset.direccion;
            document.getElementById("ver_telefono").textContent = btn.dataset.telefono;
            document.getElementById("ver_email").textContent = btn.dataset.email;
            document.getElementById("ver_obs").textContent = btn.dataset.obs;
            document.getElementById("ver_estado").textContent = btn.dataset.estado;
        });
    });
});
</script>

<?php include("../includes/footer.php"); ?>
