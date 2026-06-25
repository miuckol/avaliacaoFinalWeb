create database jogoDigitacao;
use jogoDigitacao;

create table Cadastrar (
	id int primary key,
    nome varchar (100),
    email char,
    senha varchar (20)

);

create table Equipe (
	idEq int primary key,
	nomeEq char,
    senhaEq varchar (20),
	pontuacao int
);