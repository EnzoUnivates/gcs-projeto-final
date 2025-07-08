<?php
function conectar() {
  return new PDO("mysql:host=localhost;dbname=tarefas_db", "root", "");
}

function listarTarefas() {
  $pdo = conectar();
  $stmt = $pdo->query("SELECT * FROM tarefas");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
