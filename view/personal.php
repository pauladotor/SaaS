<?php
include("../config/conexion.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$fechaActual = date('Y-m-d');

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if (!empty($buscar)) {
    $query = "SELECT id_personal, nombre, cedula, direccion, telefono, tipo_nomina, estado, fecha_incorporacion, fecha_nacimiento, genero FROM tb_personal WHERE nombre LIKE :buscar OR cedula LIKE :buscar";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['buscar' => "%$buscar%"]);
} else {
    $query = "SELECT id_personal, nombre, cedula, direccion, telefono, tipo_nomina, estado, fecha_incorporacion, fecha_nacimiento, genero FROM tb_personal";
    $stmt = $pdo->query($query);
}

$personal = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content p-4">
    <div class="container-fluid"> <br><br><br>
        <!-- Título principal -->
        <h2 class="fw-bold text-dark mb-4">Gestión de Personal</h2>

    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-4" id="personalTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="tab-personal" data-bs-toggle="tab" data-bs-target="#contenidoPersonal" type="button" role="tab" aria-controls="contenidoPersonal" aria-selected="true">
          <i class="bi bi-person-fill me-1"></i> Personal
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="tab-grupal" data-bs-toggle="tab" data-bs-target="#contenidoGrupal" type="button" role="tab" aria-controls="contenidoGrupal" aria-selected="false">
          <i class="bi bi-people-fill me-1"></i> Personal Grupal
        </button>
      </li>
    </ul>

    <div class="tab-content" id="personalTabsContent">
      
      <!-- TAB PERSONAL INDIVIDUAL -->
      <div class="tab-pane fade show active" id="contenidoPersonal" role="tabpanel" aria-labelledby="tab-personal">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
          <div class="input-group w-auto">
            <input type="text" id="busqueda" class="form-control" placeholder="Buscar por cédula o nombre">
            <button id="btnBuscar" class="btn btn-outline-success"><i class="bi bi-search"></i></button>
          </div>
          <button class="btn btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="bi bi-person-plus me-2"></i> Añadir Personal
          </button>
        </div>

        <div class=" shadow-sm rounded bg-white">
          <table class="table table-hover align-middle mb-0 text-center">
            <thead class="table-success">
              <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Tipo Nómina</th>
                <th>Estado</th>
                <th>Fecha Incorporación</th>
                <th>Fecha Nacimiento</th>
                <th>Género</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaPersonal">
              <?php 
              if (count($personal) > 0) {
                  foreach ($personal as $p) {
                      $id = htmlspecialchars($p['id_personal']);
                      $nombre = isset($p['nombre']) ? htmlspecialchars($p['nombre']) : 'N/A';
                      $cedula = isset($p['cedula']) ? htmlspecialchars($p['cedula']) : 'N/A';
                      $direccion = isset($p['direccion']) ? htmlspecialchars($p['direccion']) : 'N/A';
                      $telefono = isset($p['telefono']) ? htmlspecialchars($p['telefono']) : 'N/A';
                      $tipo_nomina = isset($p['tipo_nomina']) ? htmlspecialchars(ucfirst($p['tipo_nomina'])) : 'N/A';
                      $estado = isset($p['estado']) ? $p['estado'] : 'activo';
                      $estadoTexto = ucfirst($estado);
                      $estadoClass = ($estado == 'activo') ? 'bg-success' : 'bg-secondary';
                      $fecha_inc = isset($p['fecha_incorporacion']) ? htmlspecialchars($p['fecha_incorporacion']) : 'N/A';
                      $fecha_nac = isset($p['fecha_nacimiento']) ? htmlspecialchars($p['fecha_nacimiento']) : 'N/A';
                      $genero = isset($p['genero']) ? htmlspecialchars($p['genero']) : 'N/A';
                      
                      echo '<tr data-id="' . $id . '">';
                      echo '<td>' . $nombre . '</td>';
                      echo '<td>' . $cedula . '</td>';
                      echo '<td>' . $direccion . '</td>';
                      echo '<td>' . $telefono . '</td>';
                      echo '<td>' . $tipo_nomina . '</td>';
                      echo '<td>';
                      echo '<span class="badge ' . $estadoClass . ' estado-badge">' . $estadoTexto . '</span>';
                      echo '</td>';
                      echo '<td>' . $fecha_inc . '</td>';
                      echo '<td>' . $fecha_nac . '</td>';
                      echo '<td>' . $genero . '</td>';
                      echo '<td>';
                      echo '<button class="btn btn-sm btn-outline-success editar-btn me-1" ';
                      echo 'data-id="' . $id . '" ';
                      echo 'data-estado="' . $estado . '" ';
                      echo 'data-bs-toggle="modal" ';
                      echo 'data-bs-target="#modalEditar">';
                      echo '<i class="bi bi-pencil"></i>';
                      echo '</button>';
                      echo '<button class="btn btn-sm btn-outline-danger cambiar-estado-btn">';
                      echo '<i class="bi bi-person-dash"></i>';
                      echo '</button>';
                      echo '</td>';
                      echo '</tr>';
                  }
              } else {
                  echo '<tr><td colspan="10" class="text-center">No hay registros para mostrar</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>

      <!-- TAB PERSONAL GRUPAL -->
      <div class="tab-pane fade" id="contenidoGrupal" role="tabpanel" aria-labelledby="tab-grupal">
        <div class="card shadow-sm p-4 bg-white">
          <h5 class="text-success fw-bold mb-3"><i class="bi bi-people-fill me-2"></i>Listado de Personal Grupal</h5>
          

        </div>
      </div>

    </div><!-- /.tab-content -->
  </div>
</div>


<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalAgregarLabel">Añadir Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="../controllers/guardar_personal.php" method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Nombre:</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label>Cédula:</label>
              <input type="text" class="form-control" name="cedula" required>
            </div>
            <div class="col-md-6">
              <label>Dirección:</label>
              <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="col-md-6">
              <label>Teléfono:</label>
              <input type="text" class="form-control" name="telefono" required>
            </div>
            <div class="col-md-6">
              <label>Tipo Nómina:</label>
              <select class="form-select" name="tipo_nomina" required>
                <option value="semanal">Semanal</option>
                <option value="quincenal">Quincenal</option>
                <option value="mensual">Mensual</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Estado:</label>
              <select class="form-select" name="estado" required>
                <option value="activo" selected>Activo</option>
                <option value="desactivo">Desactivo</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Fecha Incorporación:</label>
              <input type="date" class="form-control" name="fecha_incorporacion" value="<?php echo $fechaActual; ?>" readonly>
            </div>
            <div class="col-md-6">
              <label>Fecha Nacimiento:</label>
              <input type="date" class="form-control" name="fecha_nacimiento" required>
            </div>
            <div class="col-md-6">
              <label>Género:</label>
              <select class="form-select" name="genero" required>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
              </select>
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

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEditarLabel">Editar Estado del Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarEstado">
          <input type="hidden" id="editar_id">
          <div class="mb-3">
            <label for="editar_estado" class="form-label">Nuevo Estado:</label>
            <select class="form-select" id="editar_estado" required>
              <option value="activo">Activo</option>
              <option value="desactivo">Desactivo</option>
            </select>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-success" id="guardarEstado">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var tabla = document.getElementById("tablaPersonal");

  // Función para adjuntar event listeners
  function adjuntarEventListeners() {
    // Editar estado
    var botonesEditar = document.querySelectorAll(".editar-btn");
    botonesEditar.forEach(function(btn) {
      btn.addEventListener("click", function() {
        var id = btn.getAttribute("data-id");
        var estado = btn.getAttribute("data-estado");
        document.getElementById("editar_id").value = id;
        document.getElementById("editar_estado").value = estado;
      });
    });

    // Cambiar estado rápido
    var botonesCambiar = document.querySelectorAll(".cambiar-estado-btn");
    botonesCambiar.forEach(function(btn) {
      btn.addEventListener("click", function() {
        var fila = btn.closest("tr");
        var id = fila.getAttribute("data-id");
        var badge = fila.querySelector(".estado-badge");
        var estadoActual = badge.textContent.trim().toLowerCase();
        var nuevoEstado = (estadoActual === "activo") ? "desactivo" : "activo";

        fetch("../controllers/actualizar_estado_personal.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + id + "&estado=" + nuevoEstado
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
          if (data.success) {
            badge.textContent = nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1);
            if (nuevoEstado === "activo") {
              badge.classList.remove("bg-secondary");
              badge.classList.add("bg-success");
            } else {
              badge.classList.remove("bg-success");
              badge.classList.add("bg-secondary");
            }
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch(function(error) {
          console.error("Error:", error);
          alert("Error al actualizar el estado");
        });
      });
    });
  }

  // Adjuntar listeners inicialmente
  adjuntarEventListeners();

  // Buscar con botón
  document.getElementById("btnBuscar").addEventListener("click", function() {
    var valor = document.getElementById("busqueda").value.trim();
    window.location.href = window.location.pathname + "?buscar=" + encodeURIComponent(valor);
  });

  // Buscar con Enter
  document.getElementById("busqueda").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      document.getElementById("btnBuscar").click();
    }
  });

  // Guardar estado desde modal
  document.getElementById("guardarEstado").addEventListener("click", function() {
    var id = document.getElementById("editar_id").value;
    var estado = document.getElementById("editar_estado").value;
    
    fetch("../controllers/actualizar_estado_personal.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + id + "&estado=" + estado
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.success) {
        location.reload();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(function(error) {
      console.error("Error:", error);
      alert("Error al actualizar el estado");
    });
  });
});
</script>

<?php include("../includes/footer.php"); ?>