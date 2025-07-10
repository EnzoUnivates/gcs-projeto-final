<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/funcoes.php';

session_start();

class FuncoesTest extends TestCase {
    public function testConexao() {
        $conexao = conectar();
        $this->assertInstanceOf(PDO::class, $conexao);
    }

    public function testHashDeSenhaEhValido() {
        $hash = password_hash('teste', PASSWORD_DEFAULT);
        $this->assertTrue(password_verify('teste', $hash));
    }

    public function testNaoPermiteAcessoSemLogin() {
        $_SESSION = [];
        $this->assertFalse(usuarioLogado());
    }

    public function testConectarRetornaInstanciaPDO() {
        $this->assertInstanceOf(PDO::class, conectar());
    }
}

class TarefaTest extends TestCase {

    protected function setUp(): void {
        $_SESSION = [];
        login('enzo@example.com', '1234');
    }

    public function testAdicionarTarefaComTituloVazio() {
        $this->expectException(PDOException::class);
        adicionarTarefa('', 'Descrição', usuarioAtual()['id']);
    }

    public function testAdicionarTarefaComDescricaoVazia() {
        $this->expectException(PDOException::class);
        adicionarTarefa('Título', '', usuarioAtual()['id']);
    }

    public function testListarTarefasRetornaArray() {
        $tarefas = listarTarefasUser(usuarioAtual()['id']);
        $this->assertIsArray($tarefas);
    }

    public function testListarTarefasDoOutroUsuarioNaoRetorna() {
        $tarefas = listarTarefasUser(9999);
        $this->assertEmpty($tarefas);
    }

    public function testLogoutLimpaSessao() {
        logout();
        $this->assertFalse(usuarioLogado());
    }

    public function testRegistrarLogCriacaoTarefa() {
        $pdo = conectar();
        adicionarTarefa('Log Test', 'Descrição', usuarioAtual()['id']);
        $stmt = $pdo->query("SELECT * FROM log_alteracoes WHERE acao LIKE '%Criou%' ORDER BY id DESC LIMIT 1");
        $log = $stmt->fetch();
        $this->assertNotEmpty($log);
    }

    public function testListarTarefasComBuscaPorPalavra() {
        adicionarTarefa('PalavraChave', 'Algum conteúdo', usuarioAtual()['id']);
        $tarefas = listarTarefasUser(usuarioAtual()['id'], 'PalavraChave');
        $this->assertNotEmpty($tarefas);
    }

    public function testListarTarefasComFiltroPorUsuario() {
        $tarefas = listarTarefasUser(null, null, null, 'Enzo');
        $this->assertIsArray($tarefas);
    }

    public function testUsuarioAtualRetornaArrayValido() {
        $usuario = usuarioAtual();
        $this->assertArrayHasKey('email', $usuario);
    }

    public function testCamposDaTarefaSaoValidos() {
        $tarefas = listarTarefasUser(usuarioAtual()['id']);
        if (!empty($tarefas)) {
            $this->assertArrayHasKey('titulo', $tarefas[0]);
            $this->assertArrayHasKey('descricao', $tarefas[0]);
        } else {
            $this->markTestSkipped("Sem tarefas para validar.");
        }
    }

    public function testEmailUnicoNoCadastro() {
        $this->expectException(PDOException::class);
        $pdo = conectar();
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute(['Outro', 'enzo@example.com', password_hash('0000', PASSWORD_DEFAULT)]);
    }

    public function testExibirNomeDoUsuarioNaTarefa() {
        $tarefas = listarTarefasUser();
        if (!empty($tarefas)) {
            $this->assertArrayHasKey('nome_usuario', $tarefas[0]);
        } else {
            $this->markTestSkipped("Sem tarefas cadastradas.");
        }
    }
}
