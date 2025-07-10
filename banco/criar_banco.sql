CREATE DATABASE IF NOT EXISTS tarefas_db;

USE tarefas_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

INSERT INTO usuarios (nome, email, senha) VALUES
('Enzo', 'enzo@example.com', '1234'),
('Teste', 'teste@example.com', '1234');

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    id_usuario INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO tarefas (titulo, descricao, id_usuario) VALUES
('Corrigir erro de login', 'Erro 500 ao fazer login', 1),
('Atualizar documentação', 'Revisar docs do projeto X', 1),
('Testar novo layout', 'Aplicar responsividade na tela de tarefas', 2),
('Teste', 'Testando tabela de tarefas novamente', 1),
('\'123', 'abcsde', 1),
('Teste Unitário', 'Descrição', 1),
('Chave123', 'Busca lógica', 1);
