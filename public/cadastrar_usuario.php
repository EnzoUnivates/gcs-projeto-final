<?php
require_once __DIR__ . '/../src/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar a senha

    $pdo = conectar();
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$nome, $email, $senha])) {
        echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color:red;'>Erro ao cadastrar usuário.</p>";
    }
}
?>

<form method="post">
    <h2>Cadastrar Usuário</h2>
    <input type="text" name="nome" required placeholder="Nome"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="senha" required placeholder="Senha"><br>
    <button type="submit">Cadastrar</button>
</form>
