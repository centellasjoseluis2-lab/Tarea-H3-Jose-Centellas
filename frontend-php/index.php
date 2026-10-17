<?php
// URL interna hacia el contenedor de tu API Quarkus en la red de Docker
$apiUrl = getenv('API_URL') ?: 'http://minimarket-api:8080/productos';
$mensaje = '';
$tipoMensaje = 'success';

// 1. Si el usuario envia el formulario para agregar o eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
        $nuevoProducto = [
            'nombre' => $_POST['nombre'],
            'proveedor' => $_POST['proveedor'],
            'precio' => (float)$_POST['precio'],
            'stock' => (int)$_POST['stock'],
            'fecha_vencimiento' => $_POST['fecha_vencimiento']
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nuevoProducto));
        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $mensaje = 'Producto registrado correctamente.';
        } else {
            $tipoMensaje = 'danger';
            $mensaje = 'Error al registrar: ' . htmlspecialchars($respuesta);
        }
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $id = (int)$_POST['id'];
        $ch = curl_init($apiUrl . '/' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_exec($ch);
        curl_close($ch);
        $mensaje = 'Producto eliminado correctamente.';
    }
}

// 2. Obtener la lista de productos desde la API Quarkus (GET /productos)
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$productos = json_decode($response, true);
if (!is_array($productos)) {
    $productos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Minimarket PRO413 - LUCHO.S y asociados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">🛒 Gestión de Minimarket LUCHO.S y asociados</h2>
        <span class="badge bg-success fs-6">Conectado a Quarkus API</span>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Formulario para crear nuevo producto -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white fw-bold">Agregar Nuevo Producto</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="crear">
                <div class="col-md-3">
                    <label class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Coca Cola 2L" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Proveedor</label>
                    <input type="text" name="proveedor" class="form-control" placeholder="Ej. Embol S.A." required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio (Bs)</label>
                    <input type="number" step="0.01" name="precio" class="form-control" placeholder="13.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" placeholder="45" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla con los productos -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">
            Inventario Activo (Total: <?= count($productos) ?> productos)
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Proveedor</th>
                        <th>Precio (Bs)</th>
                        <th>Stock</th>
                        <th>Fecha Vencimiento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">No hay productos registrados o no se pudo conectar con la API.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['id'] ?? '') ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($p['nombre'] ?? '') ?></td>
                                <td><?= htmlspecialchars($p['proveedor'] ?? '') ?></td>
                                <td>Bs <?= number_format((float)($p['precio'] ?? 0), 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= (($p['stock'] ?? 0) > 15) ? 'info text-dark' : 'warning text-dark' ?>">
                                        <?= htmlspecialchars($p['stock'] ?? 0) ?> uds
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($p['fecha_vencimiento'] ?? '') ?></td>
                                <td class="text-center">
                                    <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id'] ?? '') ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
