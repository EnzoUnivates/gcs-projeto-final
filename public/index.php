<?php
session_start();
require_once __DIR__ . '/../src/funcoes.php';

if (!usuarioLogado()) {
    header('Location: login.php');
    exit;
}

$usuario = usuarioAtual();
$busca = $_GET['busca'] ?? null;
$data = $_GET['data'] ?? null;
$usuarioFiltro = $_GET['usuario'] ?? null;

$tarefas = listarTarefas($usuario['id'], $busca, $data, $usuarioFiltro);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Minhas Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bem-vindo, <?= htmlspecialchars($usuario['nome']) ?>!</h1>
    
    <div class="container">

    <a href="cadastrar.php"><button>Nova Tarefa</button></a>
    <a href="logout.php"><button>Logout</button></a>

    <form method="get">
        <input type="text" name="busca" placeholder="Buscar palavra-chave" value="<?= $_GET['busca'] ?? '' ?>">
        <input type="date" name="data" value="<?= $_GET['data'] ?? '' ?>">
        <?php if ($usuario['nome'] === 'admin'): ?>
            <input type="text" name="usuario" placeholder="Filtrar por usuário" value="<?= $_GET['usuario'] ?? '' ?>">
        <?php endif; ?>
        <button type="submit">Filtrar</button>
    </form>
    
    </div>

    <div class="container">
    <h2>Minhas Tarefas Final</h2>
    <ul>
        <?php foreach ($tarefas as $tarefa): ?>
            <li>
                <strong><?= htmlspecialchars($tarefa['titulo']) ?></strong><br>
                <?= nl2br(htmlspecialchars($tarefa['descricao'])) ?><br>
                <small>Por: <?= htmlspecialchars($tarefa['nome_usuario']) ?> em <?= $tarefa['data_criacao'] ?></small>
            </li>
        <?php endforeach; ?>
    </ul>

    </div>
</body>
</html>
