<?php
session_start();
require_once __DIR__ . '/../src/funcoes.php';

if (!usuarioLogado()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    adicionarTarefa($titulo, $descricao, usuarioAtual()['id']);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<form method="post">
    <h2>Nova Tarefa</h2>
    <input type="text" name="titulo" required placeholder="Título"><br>
    <textarea name="descricao" required placeholder="Descrição"></textarea><br>
    <button type="submit">Salvar</button>
</form>
</div> 

</body>
</html>
