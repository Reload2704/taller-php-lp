<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Formulario de Registro</h1>

    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form method="POST" action="bienvenido.php">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" maxlength="10" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="30" required>

        <label for="estado_civil">Estado Civil:</label>
        <select id="estado_civil" name="estado_civil" required>
            <option value="soltero">Soltero</option>
            <option value="casado">Casado</option>
            <option value="union_libre">Unión libre</option>
            <option value="viudo">Viudo</option>
        </select>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="clave">Clave (mínimo 6 caracteres):</label>
        <input type="password" id="clave" name="clave" minlength="6" required>

        <input type="submit" value="Registrar">
        <input type="reset" value="Resetear">
    </form>

    <p class="ayuda"><a href="index.php">Volver al menú</a></p>
</div>
</body>
</html>
