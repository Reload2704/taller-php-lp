<?php
/**
 * tareas.php — Gestor de tareas del usuario autenticado (Fase 2)
 */
session_start();
require "tarea.php";

// --- Protección por sesión ---------------------------------------------
// Si no hay sesión activa, no se muestra nada: se regresa al ingreso.
if (!isset($_SESSION['cedula'])) {
    header("Location: ingreso.php");
    exit;
}

// El usuario SIEMPRE se toma de la sesión, nunca de la URL ni del
// formulario. Así nadie puede ver las tareas de otra persona.
$usuario = $_SESSION['cedula'];
$nombre  = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : $usuario;
$mensaje = "";

// --- Agregar tarea ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['texto'])) {
    if (guardarTarea($usuario, $_POST['texto'])) {
        $mensaje = "Tarea agregada.";
    } else {
        $mensaje = "Escriba el texto de la tarea.";
    }
    // Patrón POST-Redirect-GET: evita que al refrescar se duplique la tarea.
    header("Location: tareas.php?msg=" . urlencode($mensaje));
    exit;
}

// --- Completar / Eliminar ----------------------------------------------
if (isset($_GET['completar'])) {
    completarTarea($usuario, $_GET['completar']);
    header("Location: tareas.php?msg=" . urlencode("Tarea completada."));
    exit;
}

if (isset($_GET['eliminar'])) {
    eliminarTarea($usuario, $_GET['eliminar']);
    header("Location: tareas.php?msg=" . urlencode("Tarea eliminada."));
    exit;
}

if (isset($_GET['msg'])) {
    $mensaje = $_GET['msg'];
}

// --- Datos para mostrar -------------------------------------------------
$listas      = listarTareas($usuario);
$pendientes  = $listas['pendientes'];
$completadas = $listas['completadas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">

    <h1>Mis Tareas</h1>
    <p>
        Sesión de <strong><?= htmlspecialchars($nombre) ?></strong>
        &nbsp;|&nbsp; <a href="logout.php">Cerrar sesión</a>
    </p>

    <?php if ($mensaje !== ""): ?>
        <p class="exito"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="tareas.php">
        <label for="texto">Nueva tarea:</label>
        <input type="text" id="texto" name="texto" maxlength="100" required>
        <input type="submit" value="Agregar">
    </form>

    <h2>Pendientes (<?= count($pendientes) ?>)</h2>
    <?php if (count($pendientes) === 0): ?>
        <p>No tiene tareas pendientes.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($pendientes as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['texto']) ?></td>
                    <td>
                        <a href="tareas.php?completar=<?= urlencode($t['id']) ?>">Completar</a>
                        &nbsp;|&nbsp;
                        <a href="tareas.php?eliminar=<?= urlencode($t['id']) ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h2>Completadas (<?= count($completadas) ?>)</h2>
    <?php if (count($completadas) === 0): ?>
        <p>Aún no ha completado tareas.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($completadas as $t): ?>
                <tr>
                    <td><s><?= htmlspecialchars($t['texto']) ?></s></td>
                    <td>
                        <a href="tareas.php?eliminar=<?= urlencode($t['id']) ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p class="ayuda">
        Sus tareas se guardan en <code>tareas_<?= htmlspecialchars($usuario) ?>.csv</code>
        y solo son visibles para usted.
    </p>

</div>
</body>
</html>
