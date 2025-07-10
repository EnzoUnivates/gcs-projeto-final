<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/funcoes.php';

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
        $tarefas = listarTarefas(usuarioAtual()['id']);
        $this->assertIsArray($tarefas);
    }

    public function testListarTarefasOutroUsuario() {
        $tarefas = listarTarefas(9999);
        $this->assertEmpty($tarefas);
    }

    public function testFiltroPorPalavraChave() {
        adicionarTarefa('Chave123', 'Busca lógica', usuarioAtual()['id']);
        $result = listarTarefas(usuarioAtual()['id'], 'Chave123');
        $this->assertNotEmpty($result);
    }

    public function testFiltroPorNomeUsuario() {
        $tarefas = listarTarefas(null, null, null, 'Enzo');
        $this->assertIsArray($tarefas);
    }

    public function testCamposTarefaValidos() {
        $tarefas = listarTarefas(usuarioAtual()['id']);
        if (!empty($tarefas)) {
            $this->assertArrayHasKey('titulo', $tarefas[0]);
            $this->assertArrayHasKey('descricao', $tarefas[0]);
            $this->assertArrayHasKey('nome_usuario', $tarefas[0]);
        } else {
            $this->markTestSkipped('Sem tarefas cadastradas.');
        }
    }
}
