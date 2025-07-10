<?php
require_once __DIR__ . '/../src/funcoes.php';

$pdo = conectar();

$sqlFile = __DIR__ . '/../banco/criar_banco.sql';
$fp = fopen($sqlFile, 'w');

// 1. Cabeçalho
fwrite($fp, "CREATE DATABASE IF NOT EXISTS tarefas_db;\n\nUSE tarefas_db;\n\n");

// 2. Tabela usuarios
fwrite($fp, "CREATE TABLE usuarios (\n"
    . "    id INT AUTO_INCREMENT PRIMARY KEY,\n"
    . "    nome VARCHAR(100),\n"
    . "    email VARCHAR(100) UNIQUE,\n"
    . "    senha VARCHAR(255)\n);\n\n");

// Exportar usuários
$stmt = $pdo->query("SELECT nome, email, senha FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

fwrite($fp, "INSERT INTO usuarios (nome, email, senha) VALUES\n");
foreach ($usuarios as $i => $u) {
    $linha = sprintf("('%s', '%s', '%s')", addslashes($u['nome']), $u['email'], $u['senha']);
    $linha .= ($i + 1 === count($usuarios)) ? ";\n\n" : ",\n";
    fwrite($fp, $linha);
}

// 3. Tabela tarefas
fwrite($fp, "CREATE TABLE tarefas (\n"
    . "    id INT AUTO_INCREMENT PRIMARY KEY,\n"
    . "    titulo VARCHAR(255) NOT NULL,\n"
    . "    descricao TEXT NOT NULL,\n"
    . "    id_usuario INT,\n"
    . "    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,\n"
    . "    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)\n);\n\n");

// Exportar tarefas
$stmt = $pdo->query("SELECT titulo, descricao, id_usuario FROM tarefas");
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

fwrite($fp, "INSERT INTO tarefas (titulo, descricao, id_usuario) VALUES\n");
foreach ($tarefas as $i => $t) {
    $linha = sprintf("('%s', '%s', %d)", addslashes($t['titulo']), addslashes($t['descricao']), $t['id_usuario']);
    $linha .= ($i + 1 === count($tarefas)) ? ";\n" : ",\n";
    fwrite($fp, $linha);
}

fclose($fp);

echo "Arquivo criar_banco.sql atualizado com sucesso.\n";
