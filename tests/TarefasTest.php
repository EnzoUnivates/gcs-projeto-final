<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/funcoes.php';
session_start();

class TarefasTest extends TestCase {

    protected function setUp(): void {
        login('enzo@example.com', '1234');
    }

    public function testAdicionarTarefaValida() {
        $result = adicionarTarefa('Teste Unitário', 'Descrição', usuarioAtual()['id']);
        $this->assertTrue($result);
    }

    public function testAdicionarTarefaSemTitulo() {
        $this->expectException(PDOException::class);
        adicionarTarefa('', 'Desc', usuarioAtual()['id']);
    }

    public function testAdicionarTarefaSemDescricao() {
        $this->expectException(PDOException::class);
        adicionarTarefa('Título', '', usuarioAtual()['id']);
    }

    public function testListarTarefasRetornaArray() {
        $tarefas = listarTarefasUser(usuarioAtual()['id']);
        $this->assertIsArray($tarefas);
    }

    public function testListarTarefasOutroUsuario() {
        $tarefas = listarTarefasUser(9999);
        $this->assertEmpty($tarefas);
    }

    public function testFiltroPorPalavraChave() {
        adicionarTarefa('Chave123', 'Busca lógica', usuarioAtual()['id']);
        $result = listarTarefasUser(usuarioAtual()['id'], 'Chave123');
        $this->assertNotEmpty($result);
    }

    public function testFiltroPorNomeUsuario() {
        $tarefas = listarTarefasUser(null, null, null, 'Enzo');
        $this->assertIsArray($tarefas);
    }

    public function testCamposTarefaValidos() {
        $tarefas = listarTarefasUser(usuarioAtual()['id']);
        if (!empty($tarefas)) {
            $this->assertArrayHasKey('titulo', $tarefas[0]);
            $this->assertArrayHasKey('descricao', $tarefas[0]);
            $this->assertArrayHasKey('nome_usuario', $tarefas[0]);
        } else {
            $this->markTestSkipped('Sem tarefas cadastradas.');
        }
    }

    public function testLogDeCriacaoRegistrado() {
        adicionarTarefa('Teste Log', 'Logando ação', usuarioAtual()['id']);
        $pdo = conectar();
        $stmt = $pdo->query("SELECT * FROM log_alteracoes WHERE acao LIKE '%Criou%' ORDER BY id DESC LIMIT 1");
        $log = $stmt->fetch();
        $this->assertNotEmpty($log);
    }
}
