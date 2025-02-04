<?php
$file = 'notas.txt';

//funcion para guardas notas nuevas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nota'])){
    $nota = trim($_POST['nota']);
    if(!empty($nota)){
        file_put_contents($file, $nota . "\n", FILE_APPEND);
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['delete'])){
    file_put_contents($file,'');
    header('location: index.php');
    exit();
}

$notas = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Notas</title>
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
        li { background: #e3f2fd; margin: 5px 0; padding: 10px; border-radius: 5px; }
        form { margin-top: 1rem; }
        button { padding: 8px 12px; background: #2196f3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1976d2; }
    </style>
</head>
<body>
    <h1>Sistema de Notas</h1>
    <ul>
        <?php if (!empty($notas)): ?>
            <?php foreach ($notas as $nota): ?>
                <li><?= htmlspecialchars($nota) ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No hay notas aún.</li>
        <?php endif; ?>
    </ul>

    <form method="POST" action="">
        <input type="text" name="nota" placeholder="Escribe tu nota aquí" required>
        <button type="submit">Agregar Nota</button>
    </form>

    <form method="GET" action="">
        <button type="submit" name="eliminar">Eliminar todas las notas</button>
    </form>
</body>
</html>