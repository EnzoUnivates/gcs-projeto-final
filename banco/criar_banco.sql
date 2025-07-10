CREATE DATABASE IF NOT EXISTS tarefas_db;

USE tarefas_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

INSERT INTO usuarios (nome, email, senha) VALUES
('Enzo', 'enzo@example.com', '$2y$10$3z2AvjXJPhAxFZBPq4zHveM1P1eI2.Vvx84MJf4gErLeBG5pjJtNe'), 
('Teste', 'teste@example.com', '$2y$10$96lD3gkQdt0uNAD9wIpgAOs0RMHEzFS7JbP2i4lFo7OfgPjU.QGWW'); 

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descricao TEXT,
    id_usuario INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO tarefas (titulo, descricao, id_usuario) VALUES
('Corrigir erro de login', 'Erro 500 ao fazer login', 1),
('Atualizar documentação', 'Revisar docs do projeto X', 1),
('Testar novo layout', 'Aplicar responsividade na tela de tarefas', 2);

CREATE TABLE log_alteracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    acao VARCHAR(255),
    tabela_afetada VARCHAR(50),
    id_registro INT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
