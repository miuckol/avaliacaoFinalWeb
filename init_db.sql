create database jogoDigitacao;
use jogoDigitacao;

create table if not exists Cadastrar (
	id int AUTO_INCREMENT primary key,
    nome varchar (100) not null,
    email varchar (50) not null unique,
    senha varchar (255) not null,
    equipe_id int default null
);

create table if not exists Equipe (
	idEq int AUTO_INCREMENT primary key,
	nomeEq varchar (100) not null,
    codigoEq varchar (6) not null unique,
	pontuacao int default 0
);

CREATE TABLE IF NOT EXISTS partida (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    wpm         INT NOT NULL,
    precisao    DECIMAL(5,2) NOT NULL,
    erros       INT NOT NULL,
    tempo       DECIMAL(8,2) NOT NULL,
    pontuacao   INT NOT NULL,
    jogado_em   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES cadastrar(id)
);