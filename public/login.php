<?php
session_start();
require_once __DIR__ . '/../src/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; 
    $senha = $_POST['senha'];

    if (login($email, $senha)) {
        header('Location: index.php');
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
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
        <h2>Login</h2>
        <input type="email" name="email" required placeholder="E-mail">
        <input type="password" name="senha" required placeholder="Senha">
        <button type="submit">Entrar</button>
        <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    </form>
</div>

</body>
</html>

