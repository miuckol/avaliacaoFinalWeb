# Relatório de Uso de Inteligência Artificial Generativa

Este documento registra todas as interações significativas com ferramentas de IA generativa (como ChatGPT, Gemini, Copilot, etc.) durante o desenvolvimento deste projeto. O objetivo é promover o uso ético e transparente da IA como ferramenta de apoio, e não como substituta para a compreensão dos conceitos fundamentais.

## Política de Uso

O uso de IA foi utilizado para as seguintes finalidades:

* Explicação de conceitos de PHP, JavaScript, HTML, CSS e SQL.
* Planejamento da arquitetura do projeto.
* Sugestões de organização do código.
* Auxílio na identificação e correção de erros.
* Explicação sobre segurança da aplicação.
* Sugestões de melhorias na interface.

Todo código implementado foi analisado, compreendido e adaptado antes de ser incorporado ao projeto.

---

## Registro de Interações

### Interação 1

* **Data:** 11/06/2026

* **Etapa do Projeto:** Planejamento da aplicação

* **Ferramenta de IA Utilizada:** ChatGPT

* **Objetivo da Consulta:** Planejar a reconstrução completa do jogo de digitação e definir uma estratégia de desenvolvimento.

* **Prompt(s) Utilizado(s):**

  * "Quero reconstruir meu jogo de digitação do zero."
  * "Quero que você me guie e explique, não quero que faça o código para mim."

* **Resumo da Resposta da IA:**
  A IA sugeriu desenvolver o projeto em etapas, explicando a função de cada tecnologia (HTML, CSS, JavaScript, PHP e MySQL) e orientando uma implementação gradual para facilitar o aprendizado.

* **Análise e Aplicação:**
  A resposta serviu como guia para reorganizar o desenvolvimento do projeto. A implementação foi realizada manualmente, utilizando apenas as explicações como referência.

* **Referência no Código:**
  Estrutura geral do projeto.

---

### Interação 2

* **Data:** 24/06/2026

* **Etapa do Projeto:** Sistema de Autenticação

* **Ferramenta de IA Utilizada:** ChatGPT

* **Objetivo da Consulta:** Entender o funcionamento das sessões em PHP utilizadas para controlar o login dos usuários.

* **Prompt(s) Utilizado(s):**

  * Envio de trechos contendo `session_start()` e `$_SESSION`.

* **Resumo da Resposta da IA:**
  A IA explicou como funcionam as sessões em PHP, o armazenamento de informações do usuário autenticado e o redirecionamento para páginas protegidas.

* **Análise e Aplicação:**
  As explicações foram utilizadas para compreender o funcionamento do sistema de autenticação existente e realizar adaptações necessárias.

* **Referência no Código:**
  Arquivos responsáveis pelo login e pelas páginas protegidas.

---

### Interação 3

* **Data:** 24/06/2026

* **Etapa do Projeto:** Interface do Sistema

* **Ferramenta de IA Utilizada:** ChatGPT

* **Objetivo da Consulta:** Melhorar o layout da tela de equipes e ranking.

* **Prompt(s) Utilizado(s):**

  * "Como você deixaria melhor? O CSS não está alcançando o bloco ranking."

* **Resumo da Resposta da IA:**
  A IA analisou a estrutura HTML e CSS, identificando possíveis problemas relacionados à aplicação dos estilos e sugerindo melhorias na organização do layout.

* **Análise e Aplicação:**
  As sugestões foram utilizadas como referência para reorganizar a estrutura da página e corrigir a estilização.

* **Referência no Código:**
  Arquivos HTML e CSS da página de ranking.

---

### Interação 4

* **Data:** 25/06/2026

* **Etapa do Projeto:** Lógica JavaScript

* **Ferramenta de IA Utilizada:** ChatGPT

* **Objetivo da Consulta:** Compreender detalhadamente o funcionamento de funções JavaScript utilizadas no jogo.

* **Prompt(s) Utilizado(s):**

  * Envio da função `carregarFrase()` solicitando uma explicação linha por linha.

* **Resumo da Resposta da IA:**
  A IA explicou o funcionamento da função responsável por criar dinamicamente os caracteres da frase e destacar a letra atual durante a digitação.

* **Análise e Aplicação:**
  A explicação auxiliou na compreensão da lógica implementada e facilitou futuras alterações no código.

* **Referência no Código:**
  Arquivo JavaScript responsável pela lógica do jogo.

---

### Interação 5

* **Data:** 24/06/2026

* **Etapa do Projeto:** Histórico de partidas e sistema de pontuação

* **Ferramenta de IA Utilizada:** Claude (Anthropic)

* **Objetivo da Consulta:** Após reportar print de tela com erro ao criar/entrar em equipe, pedi para a IA confirmar a causa e em seguida ajudar a implementar a tela de ranking exigida pela especificação do trabalho (pontuação geral e por liga, total desde a criação e semanal).

* **Prompt(s) Utilizado(s):**

  * [Capturas de tela] do erro esta dando problema na hr de criar e entrar na equipe... e a parte de ranking como ficaria para fazer"

* **Resumo da Resposta da IA:**
  A IA forneceu explicações sobre como organizar a lógica para registrar as partidas realizadas, armazenar as pontuações no banco de dados e exibir essas informações aos usuários.

  A IA confirmou que o erro nas imagens era causado pelo bug dos formulários de equipe compartilhando o mesmo acao e que eu ainda estava testando a versão antiga do arquivo. A IA propôs e implementou: separação da lógica de salvar pontuação em um novo arquivo pontuacao.php; reescrita de ranking.php como página HTML com abas "Total"/"Semanal" para o ranking geral (todas as equipes) e para a liga do usuário (ranking dos membros); e correção do script.js, que terminava o jogo mas nunca enviava os dados da partida ao servidor nem preenchia a div de resultado na tela.


* **Análise e Aplicação:**
  A resposta serviu como reescrita para reorganizar o desenvolvimento do historico e ranking. A implementação foi realizada manualmente, utilizando apenas as explicações como referência.

* **Referência no Código:**
  Novo arquivo pontuacao.php (API de salvamento de pontuação); ranking.php reescrito por completo; script.js (envio da pontuação via fetch e atualização do bloco #resultado); index.php (link dinâmico para ranking.php?liga=); style.css (estilos das abas e tabelas de ranking).
---

### Interação 6

* **Data:** 24/06/2026

* **Etapa do Projeto:** Correção de erros e bugs

* **Ferramenta de IA Utilizada:** Claude (Anthropic)

* **Objetivo da Consulta:** Consultar sintaxes 

* **Prompt(s) Utilizado(s):**

  * [Capturas de tela] do erro esta dando problema na hr de criar e entrar na equipe... e a parte de ranking como ficaria para fazer"

* **Resumo da Resposta da IA:**
  A IA forneceu explicações sobre como organizar a lógica para registrar as partidas realizadas, armazenar as pontuações no banco de dados e exibir essas informações aos usuários.

  A IA confirmou que o erro nas imagens era causado pelo bug dos formulários de equipe compartilhando o mesmo acao e que eu ainda estava testando a versão antiga do arquivo. A IA propôs e implementou: separação da lógica de salvar pontuação em um novo arquivo pontuacao.php; reescrita de ranking.php como página HTML com abas "Total"/"Semanal" para o ranking geral (todas as equipes) e para a liga do usuário (ranking dos membros); e correção do script.js, que terminava o jogo mas nunca enviava os dados da partida ao servidor nem preenchia a div de resultado na tela.


* **Análise e Aplicação:**
  A resposta serviu como reescrita para reorganizar o desenvolvimento do historico e ranking. A implementação foi realizada manualmente, utilizando apenas as explicações como referência.

* **Referência no Código:**
  Novo arquivo pontuacao.php (API de salvamento de pontuação); ranking.php reescrito por completo; script.js (envio da pontuação via fetch e atualização do bloco #resultado); index.php (link dinâmico para ranking.php?liga=); style.css (estilos das abas e tabelas de ranking).
---

### Interação 

* **Data:** 22/06/2026

* **Etapa do Projeto:** Forms e index.php

* **Ferramenta de IA Utilizada:** Claude (Anthropic)

* **Objetivo da Consulta:** Aperfeiçoar e complementar o forms e index

* **Prompt(s) Utilizado(s):**

  * Consegue me ajudar a completar oq esta faltando"

* **Resumo da Resposta da IA:**
  A IA identificou falhas críticas de estrutura e segurança definição manual de IDs que deveriam ser AUTO_INCREMENT, falta de validações de senha e de criptografia segura (sugerindo o uso de password_hash() e password_verify()). No index.php, alertou que a query de cadastro estava sendo sobrescrita antes da execução. Sobre o tratamento do POST, explicou que a segunda opção (ou o uso do operador de coalescência nula ??) é a ideal para evitar warnings de índices indefinidos no PHP.

* **Análise e Aplicação:**
 resposta permitiu reestruturar completamente o fluxo de dados do projeto. Os formulários e o sistema de login foram corrigidos para operar de forma segura com criptografia e controle de sessões. Além disso, a lógica de manipulação das requisições foi refinada utilizando a verificação preventiva dos índices do POST (com isset ou ??), garantindo um código limpo, livre de avisos de erro e preparado para lidar com múltiplas ações (cadastro, login e equipes) no mesmo arquivo.

* **Referência no Código:**
  As implementações e correções foram aplicadas nos arquivos forms.php, index.php e no arquivo de controle de sessão logout.php.
---
