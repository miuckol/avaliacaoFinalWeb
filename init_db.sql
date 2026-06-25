create database jogoDigitacao;
use jogoDigitacao;

create table Cadastrar (
	id int AUTO_INCREMENT,
    nome varchar (100),
    email char,
    senha varchar (20),
    equipe_id int(11),
    primary key (id)

);

create table Equipe (
	idEq int AUTO_INCREMENT,
	nomeEq char,
    senhaEq varchar (20),
	pontuacao int,
    primary key (idEq)
);