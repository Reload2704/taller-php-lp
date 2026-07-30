<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Tareas - ESPOL</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Gestor de Tareas Personal</h1>

    <?php if (isset($_SESSION['cedula'])): ?>
        <p>Ya tiene una sesión activa.</p>
        <p><a href="tareas.php">Ir a mis tareas</a></p>
        <p><a href="logout.php">Cerrar sesión</a></p>
    <?php else: ?>
        <p><a href="formulario.php">Registrarse</a></p>
        <p><a href="ingreso.php">Iniciar sesión</a></p>
    <?php endif; ?>
</div>
</body>
</html>
