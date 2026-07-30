<?php
/**
 * bienvenido.php — Procesa el registro enviado desde formulario.php
 */
session_start();
require "usuario.php";

// Si se entra directamente sin enviar el formulario, se regresa a él.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulario.php");
    exit;
}

$cedula = trim($_POST['cedula'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$clave  = $_POST['clave'] ?? '';

// Validación en el servidor (el navegador se puede saltar).
if ($cedula === '' || $nombre === '' || strlen($clave) < 6) {
    $msg = "Complete todos los campos. La clave debe tener al menos 6 caracteres.";
    header("Location: formulario.php?error=" . urlencode($msg));
    exit;
}

// Si la cédula ya existe, no se registra de nuevo: va al ingreso.
if (validar($cedula)) {
    header("Location: ingreso.php?error=" . urlencode("Esa cédula ya está registrada. Inicie sesión."));
    exit;
}

$datos = [
    'cedula'       => $cedula,
    'nombre'       => $nombre,
    'estado_civil' => $_POST['estado_civil'] ?? 'soltero',
    'correo'       => $_POST['correo'] ?? '',
    'clave_hash'   => password_hash($clave, PASSWORD_DEFAULT)
];

guardar($datos);

$_SESSION['usuario'] = $nombre;
$_SESSION['cedula']  = $cedula;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuario Registrado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>USUARIO REGISTRADO</h1>
    <p class="exito">Bienvenido, <?= htmlspecialchars($nombre) ?>.</p>
    <p><a href="tareas.php">Ir a mis tareas</a></p>
    <p><a href="index.php">Volver al menú</a></p>
</div>
</body>
</html>
