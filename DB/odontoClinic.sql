CREATE DATABASE odontoClinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  cargo INT(5) DEFAULT NULL,
  telefone VARCHAR(15) DEFAULT NULL,
  cep VARCHAR(9) DEFAULT NULL,
  rua VARCHAR(100) DEFAULT NULL,
  numero VARCHAR(10) DEFAULT NULL,
  bairro VARCHAR(100) DEFAULT NULL,
  cidade VARCHAR(100) DEFAULT NULL,
  estado VARCHAR(2) DEFAULT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  ativo TINYINT(1) DEFAULT 1,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cargos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE usuarios 
ADD CONSTRAINT fk_cargo 
FOREIGN KEY (cargo) REFERENCES cargos(id) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

CREATE TABLE configuracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_sistema VARCHAR(100),
    email_sistema VARCHAR(100),
    cnpj_sistema VARCHAR(20),
    telefone_sistema VARCHAR(20),
    telefone_fixo VARCHAR(20),
    cep_sistema VARCHAR(10),
    rua_sistema VARCHAR(100),
    numero_sistema VARCHAR(10),
    bairro_sistema VARCHAR(100),
    cidade_sistema VARCHAR(100),
    estado_sistema VARCHAR(2),
    instagram_sistema VARCHAR(200),
    tipo_relatorio VARCHAR(10),
    contatoZap VARCHAR(3),
    desenvolvedor VARCHAR(100),
    site_dev VARCHAR(200),
    url_sistema VARCHAR(200),
    chave_pix VARCHAR(100),
    tipo_chave VARCHAR(20),
    logotipo VARCHAR(100),
    icone VARCHAR(100),
    logo_rel VARCHAR(100)
);

CREATE TABLE grupo_acessos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_grupo VARCHAR(25)
);

CREATE TABLE acessos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    chave VARCHAR(50) NOT NULL,
    grupo INT NOT NULL
);

CREATE TABLE permissoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario INT NOT NULL,
    permissao INT NOT NULL
);
