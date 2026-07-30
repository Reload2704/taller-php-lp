<?php
/**
 * ingreso.php — Autenticación de usuarios (Fase 1)
 */
session_start();
require "usuario.php";
require "registros.php";

$error = isset($_GET['error']) ? $_GET['error'] : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $clave  = $_POST['clave'] ?? '';

    if (autenticar($cedula, $clave)) {
        // Sesión nueva y limpia para el usuario autenticado.
        session_regenerate_id(true);
        $_SESSION['cedula']  = $cedula;
        $_SESSION['usuario'] = obtenerNombre($cedula);
        header("Location: tareas.php");
        exit;
    } else {
        registrarFallo($cedula);
        $error = "Cédula o contraseña incorrecta.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Ingreso</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="ingreso.php">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" maxlength="10" required>

        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>

        <input type="submit" value="Ingresar">
    </form>

    <p class="ayuda">
        ¿No tiene cuenta? <a href="formulario.php">Regístrese aquí</a> &nbsp;|&nbsp;
        <a href="index.php">Volver al menú</a>
    </p>
</div>
</body>
</html>
