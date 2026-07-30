<?php
/**
 * tarea.php — Funciones de gestión de tareas (Fase 2)
 *
 * Cada usuario tiene su propio archivo: tareas_<cedula>.csv
 * Formato de cada línea:  id,texto,estado
 * estado = "pendiente" | "completada"
 */

/**
 * Devuelve la ruta del archivo de tareas del usuario.
 * Se limpia el identificador para que nadie pueda salir de la carpeta
 * (por ejemplo con "../") ni leer el archivo de otro usuario.
 */
function archivoTareas($usuario) {
    $seguro = preg_replace('/[^A-Za-z0-9]/', '', $usuario);
    return "tareas_" . $seguro . ".csv";
}

/**
 * Lee todas las tareas del usuario como arreglo de filas.
 */
function leerTareas($usuario) {
    $archivo = archivoTareas($usuario);
    if (!file_exists($archivo)) return [];

    $tareas = [];
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $campos = str_getcsv($linea);
        if (count($campos) < 3) continue;
        // trim() elimina espacios y retornos de carro (\r) que quedan
        // cuando el archivo se escribe en Windows.
        $tareas[] = [
            'id'     => trim($campos[0]),
            'texto'  => $campos[1],
            'estado' => trim($campos[2])
        ];
    }
    return $tareas;
}

/**
 * Reescribe el archivo completo con las tareas dadas.
 */
function escribirTareas($usuario, $tareas) {
    $f = fopen(archivoTareas($usuario), "w");
    foreach ($tareas as $t) {
        fputcsv($f, [$t['id'], $t['texto'], $t['estado']]);
    }
    fclose($f);
}

/**
 * Agrega una tarea nueva (estado "pendiente") al archivo del usuario.
 */
function guardarTarea($usuario, $texto) {
    $texto = trim($texto);
    if ($texto === "") return false;

    // El id es el mayor id existente + 1
    $tareas = leerTareas($usuario);
    $maximo = 0;
    foreach ($tareas as $t) {
        if ((int)$t['id'] > $maximo) $maximo = (int)$t['id'];
    }
    $id = $maximo + 1;

    $f = fopen(archivoTareas($usuario), "a");
    fputcsv($f, [$id, $texto, "pendiente"]);
    fclose($f);
    return true;
}

/**
 * Retorna las tareas del usuario separadas en pendientes y completadas.
 */
function listarTareas($usuario) {
    $pendientes  = [];
    $completadas = [];

    foreach (leerTareas($usuario) as $t) {
        if ($t['estado'] === "completada") {
            $completadas[] = $t;
        } else {
            $pendientes[] = $t;
        }
    }

    return ['pendientes' => $pendientes, 'completadas' => $completadas];
}

/**
 * Cambia el estado de una tarea a "completada".
 */
function completarTarea($usuario, $id) {
    $tareas = leerTareas($usuario);
    $encontrada = false;

    foreach ($tareas as $i => $t) {
        if ($t['id'] == $id) {
            $tareas[$i]['estado'] = "completada";
            $encontrada = true;
        }
    }

    if ($encontrada) escribirTareas($usuario, $tareas);
    return $encontrada;
}

/**
 * Elimina del archivo la línea correspondiente a la tarea indicada.
 */
function eliminarTarea($usuario, $id) {
    $tareas = leerTareas($usuario);
    $restantes = [];

    foreach ($tareas as $t) {
        if ($t['id'] != $id) $restantes[] = $t;
    }

    if (count($restantes) === count($tareas)) return false;

    escribirTareas($usuario, $restantes);
    return true;
}
?>
