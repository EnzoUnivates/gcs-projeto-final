<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/funcoes.php';
session_start();

class FuncoesTest extends TestCase {
    public function testConexao() {
        $this->assertInstanceOf(PDO::class, conectar());
    }

    public function testLoginValido() {
        $this->assertTrue(login('enzo@example.com', '1234'));
    }

    public function testLoginInvalido() {
        $this->assertFalse(login('fake@example.com', 'errado'));
    }

    public function testUsuarioAtual() {
        login('enzo@example.com', '1234');
        $usuario = usuarioAtual();
        $this->assertArrayHasKey('email', $usuario);
    }

    public function testUsuarioLogado() {
        login('enzo@example.com', '1234');
        $this->assertTrue(usuarioLogado());
    }

    public function testLogout() {
        logout();
        $this->assertFalse(usuarioLogado());
    }

    public function testHashSenhaValida() {
        $hash = password_hash('teste', PASSWORD_DEFAULT);
        $this->assertTrue(password_verify('teste', $hash));
    }

    public function testHashSenhaInvalida() {
        $hash = password_hash('teste', PASSWORD_DEFAULT);
        $this->assertFalse(password_verify('outra', $hash));
    }

    public function testSessaoVaziaNaoLogado() {
        $_SESSION = [];
        $this->assertFalse(usuarioLogado());
    }

    public function testEmailUnicoNoBanco() {
        $this->expectException(PDOException::class);
        $pdo = conectar();
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute(['Outro', 'enzo@example.com', password_hash('0000', PASSWORD_DEFAULT)]);
    }
}
