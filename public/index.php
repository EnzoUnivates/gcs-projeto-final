<?php
require_once '../src/funcoes.php';
$tarefas = listarTarefas();
?>

<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Lista de Tarefas</title></head>
<body>
  <h1>Minhas Tarefas</h1>
  <ul>
    <?php foreach ($tarefas as $tarefa): ?>
      <li><?= $tarefa['descricao'] ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
