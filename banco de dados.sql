CREATE DATABASE IF NOT EXISTS loja_db;
USE loja_db;

-- Tabela de avaliações
CREATE TABLE avaliacoes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    classificacao INT(11) NOT NULL,
    comentario TEXT NOT NULL,
    pedido_id INT(11) NULL,
    data_avaliacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- Tabela de pedidos
CREATE TABLE pedidos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    cliente_nome VARCHAR(100) NOT NULL,
    itens TEXT NULL,
    total DECIMAL(10,2) NULL,
    status ENUM('Pendente', 'Enviado', 'Entregue') NOT NULL DEFAULT 'Pendente',
    data_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- Tabela de produtos
CREATE TABLE produtos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NULL,
    preco DECIMAL(10,2) NOT NULL,
    imagem_url VARCHAR(255) NULL,
    PRIMARY KEY (id)
);

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('admin', 'comum') NOT NULL DEFAULT 'comum',
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- Relacionamento entre avaliações e pedidos (chave estrangeira)
ALTER TABLE avaliacoes
ADD CONSTRAINT fk_avaliacoes_pedido
FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
ON DELETE SET NULL ON UPDATE CASCADE;
