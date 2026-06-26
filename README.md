# Jogo de Digitação

## Descrição

O **Jogo de Digitação** é uma aplicação web desenvolvida em PHP, JavaScript, HTML, CSS e MySQL. O sistema permite que usuários se cadastrem, façam login e participem de desafios de digitação, registrando suas pontuações e disponibilizando um histórico de partidas e um ranking de desempenho.

O projeto foi desenvolvido com foco na integração entre front-end e back-end, utilizando banco de dados para armazenamento das informações dos usuários e das partidas.

---

# Funcionalidades

* Cadastro de usuários.
* Login e autenticação utilizando sessões em PHP.
* Jogo de digitação com frases.
* Registro automático da pontuação.
* Histórico das partidas realizadas.
* Ranking de jogadores baseado nas pontuações.
* Logout do usuário.

---

# Tecnologias Utilizadas

* HTML5
* CSS3
* JavaScript
* PHP
* MySQL
* XAMPP
* Git e GitHub

---

# Estrutura do Projeto

```text
Projeto/
│
├── README.md
├── AI_USAGE_LOG.md
├── index.php            # Página principal do jogo
├── forms.php            # Cadastro e login
├── conexao.php          # Conexão com o banco de dados
├── config.php           # Configurações da aplicação
├── historico.php        # Histórico das partidas
├── pontuacao.php        # Registro das pontuações
├── ranking.php          # Ranking dos jogadores
├── sair.php             # Encerramento da sessão
├── init_db.sql          # Script de criação do banco
│
├── style.css            # Estilos da aplicação
├── script.js            # Lógica principal do jogo
└── modal.js             # Controle dos modais da interface
```

---

# Funcionamento do Sistema

1. O usuário acessa a aplicação.
2. Caso ainda não possua cadastro, cria uma conta através da página de formulários.
3. Após realizar o login, uma sessão é iniciada.
4. O usuário inicia uma partida de digitação.
5. Durante a partida, o JavaScript controla o tempo, a frase e a validação da digitação.
6. Ao final, a pontuação é enviada ao servidor e armazenada no banco de dados.
7. O usuário pode consultar seu histórico de partidas e visualizar o ranking dos jogadores.

---

# Como Executar

1. Instale o XAMPP.
2. Inicie os serviços Apache e MySQL.
3. Importe `init_db.sql` no MySQL/MariaDB. 
4. Coloque todos os arquivos na raiz do seu servidor PHP (ex: `xampp/htdocs/jogo/`).
5. Ajuste `config.php` com suas credenciais.
6. Acesse `http://localhost/avaliacaoFinalWeb/`.

---

# Arquivos Principais

| Arquivo         | Função                      |
| --------------- | --------------------------- |
| `index.php`     | Página principal do jogo    |
| `forms.php`     | Cadastro e login            |
| `SCRIPT.js`     | Lógica da partida           |
| `STYLE.css`     | Estilos da interface        |
| `modal.js`      | Controle das janelas modais |
| `pontuacao.php` | Registro das pontuações     |
| `historico.php` | Consulta ao histórico       |
| `ranking.php`   | Exibição do ranking         |
| `conexao.php`   | Conexão com o banco         |
| `config.php`    | Configurações da aplicação  |
| `sair.php`      | Logout do usuário           |
| `init_db.sql`   | Script de criação do banco  |

---

# Autor

Projeto desenvolvido por **Gabriel Silva** e **Caroline L P Moraes** como trabalho acadêmico.
