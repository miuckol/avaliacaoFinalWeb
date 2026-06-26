# Jogo de Digitação — ProjetoFinalWeb

## Como rodar

1. Importe `init_db.sql` no MySQL/MariaDB.
2. Ajuste `config.php` com suas credenciais.
3. Coloque todos os arquivos na raiz do seu servidor PHP (ex: `htdocs/jogo/`).
4. Acesse `http://localhost/jogo/`.

## Estrutura de arquivos

| Arquivo | Descrição |
|---|---|
| `init_db.sql` | Cria banco e tabelas (`equipe`, `cadastrar`, `partida`) |
| `config.php` | Credenciais do banco |
| `conexao.php` | Abre a conexão MySQLi |
| `forms.php` | Cadastro e login |
| `index.php` | Página principal + jogo + gestão de equipes |
| `script.js` | Lógica do jogo de digitação em JS |
| `pontuacao.php` | Endpoint JSON que salva partida e atualiza pontuação da equipe |
| `ranking.php` | Ranking geral (equipes + jogadores) e ranking de liga |
| `historico.php` | Histórico de partidas do usuário logado |
| `sair.php` | Destroi a sessão e redireciona |
| `style.css` | Estilos |
| `modal.js` | Abre/fecha modal de login |

## Bugs corrigidos

- **`index.php`** — `$dadosEquipe` nunca era preenchido (variável trocada com `$equipe`); formulário de equipe nunca aparecia.
- **`index.php`** — Uso de `$Equipe` (maiúsculo) no HTML quando a variável é `$equipe`.
- **`index.php`** — Visitantes não autenticados agora são redirecionados para `forms.php`.
- **`script.js`** — `fetch` apontava para `salvar_pontuacao.php` (arquivo inexistente); corrigido para `pontuacao.php` com `Content-Type: application/json`.
- **`script.js`** — Painel de resultado (`#resultado`, `#tempoFinal`, etc.) nunca era preenchido nem exibido; corrigido.
- **`script.js`** — Listener do `btnReiniciar` era adicionado dentro do `keydown` a cada tecla pressionada (vazamento de listeners); movido para fora.
- **`forms.php`** — Erros de login faziam `echo` antes do HTML, causando página em branco; agora são armazenados em variável e exibidos no template.
- **`init_db.sql`** — Ordem de criação corrigida (`equipe` antes de `cadastrar`) para satisfazer a FK.

## Funcionalidades adicionadas

- **`historico.php`** — Página com todas as partidas do usuário (WPM, precisão, erros, tempo, pontuação, data).
- **Ranking individual** — `ranking.php` agora exibe ranking geral de jogadores (total e semanal), além do ranking de equipes.
- **Linha destacada** — A linha do usuário logado aparece destacada nos rankings de jogadores.
- **Redirecionamento** — `sair.php` agora redireciona para `forms.php` (em vez de `index.php`).
