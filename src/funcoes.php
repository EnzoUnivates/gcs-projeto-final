<?php
function conectar() {
    $host = 'localhost';
    $dbname = 'tarefas_db';
    $usuario = 'root';
    $senha = 'senha123';
    
    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}

function login($email, $senha) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $senha === '1234') {
        $_SESSION['usuario'] = $usuario;
        return true;
    }
    return false;
}


function usuarioLogado() {
    return isset($_SESSION['usuario']);
}

function usuarioAtual() {
    return $_SESSION['usuario'] ?? null;
}

function adicionarTarefa($titulo, $descricao, $id_usuario) {
    if (trim($titulo) === '' || trim($descricao) === '') {
        throw new InvalidArgumentException("Título e descrição não podem ser vazios.");
    }

    $pdo = conectar();
    $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, id_usuario) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $descricao, $id_usuario]);

    return true;
}

function listarTarefas($id_usuario = null, $busca = null, $data = null, $usuarioNome = null) {
    $pdo = conectar();
    $sql = "SELECT tarefas.*, usuarios.nome AS nome_usuario FROM tarefas JOIN usuarios ON tarefas.id_usuario = usuarios.id WHERE 1=1";
    $params = [];

    if ($id_usuario && usuarioAtual()['nome'] !== 'admin') {
        $sql .= " AND tarefas.id_usuario = ?";
        $params[] = $id_usuario;
    }

    if ($busca) {
        $sql .= " AND (titulo LIKE ? OR descricao LIKE ?)";
        $params[] = "%$busca%";
        $params[] = "%$busca%";
    }

    if ($data) {
        $sql .= " AND DATE(tarefas.data_criacao) = ?";
        $params[] = $data;
    }

    if ($usuarioNome) {
        $sql .= " AND usuarios.nome LIKE ?";
        $params[] = "%$usuarioNome%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

function logoutSemRedirect() {
    $_SESSION = [];
    session_destroy();
}

