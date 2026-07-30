<?php
/**
 * usuario.php — Funciones de usuarios (Fase 1)
 *
 * Los usuarios se guardan en usuarios.csv con el formato:
 * cedula,nombre,estado_civil,correo,clave_cifrada
 */

/**
 * Lee usuarios.csv y devuelve solo las filas válidas.
 */
function leerUsuarios() {
    if (!file_exists("usuarios.csv")) return [];

    $usuarios = [];
    $lineas = file("usuarios.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $campos = str_getcsv($linea);
        // Se ignoran líneas incompletas o sin cédula.
        if (count($campos) < 5 || trim($campos[0]) === "") continue;
        $usuarios[] = $campos;
    }
    return $usuarios;
}

/**
 * Almacena un usuario nuevo como una línea en usuarios.csv.
 * Se usa fputcsv para que un nombre con comas no dañe el archivo.
 */
function guardar($datos) {
    $f = fopen("usuarios.csv", "a");
    fputcsv($f, [
        $datos['cedula'],
        $datos['nombre'],
        $datos['estado_civil'],
        $datos['correo'],
        $datos['clave_hash']
    ]);
    fclose($f);
}

/**
 * Retorna true si la cédula ya está registrada.
 */
function validar($cedula) {
    foreach (leerUsuarios() as $campos) {
        if (trim($campos[0]) === trim($cedula)) return true;
    }
    return false;
}

/**
 * Valida usuario y contraseña con password_verify().
 */
function autenticar($cedula, $contrasena) {
    foreach (leerUsuarios() as $campos) {
        if (trim($campos[0]) === trim($cedula) &&
            password_verify($contrasena, trim($campos[4]))) {
            return true;
        }
    }
    return false;
}

/**
 * Devuelve el nombre registrado para una cédula (o la cédula si no existe).
 */
function obtenerNombre($cedula) {
    foreach (leerUsuarios() as $campos) {
        if (trim($campos[0]) === trim($cedula)) return $campos[1];
    }
    return $cedula;
}
?>
