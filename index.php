<?php
$file = 'notas.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$notas = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nota']) && trim($_POST['nota']) !== '') {
        $nota = trim($_POST['nota']);
        if (isset($_POST['index']) && $_POST['index'] !== '') {
            $index = intval($_POST['index']);
            if (isset($notas[$index])) {
                $notas[$index]['texto'] = $nota;
                $notas[$index]['fecha'] = date('Y-m-d H:i:s');
            }
        } else {
            $notas[] = [
                'texto' => $nota,
                'fecha' => date('Y-m-d H:i:s'),
            ];
        }
        file_put_contents($file, json_encode($notas, JSON_PRETTY_PRINT));
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['delete']) && isset($_GET['index'])) {
    $index = intval($_GET['index']);
    if (isset($notas[$index])) {
        unset($notas[$index]);
        $notas = array_values($notas);
        file_put_contents($file, json_encode($notas, JSON_PRETTY_PRINT));
    }
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    file_put_contents($file, json_encode([]));
    header('Location: index.php');
    exit;
}

$notaEditada = null;
$editIndex = null;

if (isset($_GET['edit']) && isset($_GET['index'])) {
    $index = intval($_GET['index']);
    if (isset($notas[$index])) {
        $notaEditada = $notas[$index]['texto'];
        $editIndex = $index;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Notas Mejorado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            padding: 2rem;
            max-width: 600px;
            background-color: #f9f9f9;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 { text-align: center; }
        ul { list-style-type: none; padding: 0; }
        li { background: #e3f2fd; margin: 5px 0; padding: 10px; border-radius: 5px; display: flex; justify-content: space-between; align-items: center; }
        form { margin-top: 1rem; }
        button { padding: 8px 12px; background: #2196f3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1976d2; }
        .fecha { font-size: 0.85rem; color: #757575; margin-left: 10px; }
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .edit-btn { background: #ff9800; }
        .edit-btn:hover { background: #f57c00; }
        .delete-btn { background: #f44336; }
        .delete-btn:hover { background: #d32f2f; }
    </style>
</head>
<body>
    <h1>Sistema de Notas Mejorado</h1>
    <ul>
        <?php if (!empty($notas)): ?>
            <?php foreach ($notas as $index => $nota): ?>
                <li>
                    <span><?= htmlspecialchars($nota['texto']) ?></span>
                    <span class="fecha"><?= htmlspecialchars($nota['fecha']) ?></span>
                    <a href="?edit=1&index=<?= $index ?>"><button class="action-btn edit-btn">Editar</button></a>
                    <a href="?delete=1&index=<?= $index ?>"><button class="action-btn delete-btn">Eliminar</button></a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No hay notas aún.</li>
        <?php endif; ?>
    </ul>

    <form method="POST" action="">
        <input type="hidden" name="index" value="<?= $editIndex !== null ? $editIndex : '' ?>">
        <input type="text" name="nota" placeholder="Escribe tu nota aquí" value="<?= htmlspecialchars($notaEditada ?? '') ?>" required>
        <button type="submit"><?= $notaEditada !== null ? 'Guardar Cambios' : 'Agregar Nota' ?></button>
    </form>

    <form method="GET" action="">
        <button type="submit" name="delete_all">Eliminar todas las notas</button>
    </form>
</body>
</html>
