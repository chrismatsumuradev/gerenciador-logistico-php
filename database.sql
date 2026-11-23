CREATE DATABASE IF NOT EXISTS gerenciador_logistico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gerenciador_logistico;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    descricao VARCHAR(255),
    peso_kg DECIMAL(10,2) NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apelido VARCHAR(80) NOT NULL,
    cep VARCHAR(15) NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf CHAR(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS transportadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_razao VARCHAR(150) NOT NULL,
    documento VARCHAR(25) NOT NULL,
    telefone VARCHAR(25) NOT NULL,
    veiculo VARCHAR(80) NOT NULL,
    placa VARCHAR(15) NOT NULL
);

CREATE TABLE IF NOT EXISTS fretes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transportador_id INT NOT NULL,
    endereco_origem_id INT NOT NULL,
    endereco_destino_id INT NOT NULL,
    data_frete DATE NOT NULL,
    valor_frete DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Agendado',
    observacoes TEXT,
    FOREIGN KEY (transportador_id) REFERENCES transportadores(id) ON DELETE RESTRICT,
    FOREIGN KEY (endereco_origem_id) REFERENCES enderecos(id) ON DELETE RESTRICT,
    FOREIGN KEY (endereco_destino_id) REFERENCES enderecos(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS frete_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frete_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
);
