const frases = [
    "O rato roeu a roupa do rei de roma",
    "JavaScript e uma linguagem muito utilizada",
    "Programar exige pratica e paciencia",
    "A tecnologia transforma o mundo cada dia mais",
    "O desenvolvimento web une criatividade e logica",
    "Aprender a digitar rapido aumenta a produtividade"
];

const fraseElemento = document.getElementById("frase");
const mensagem      = document.getElementById("mensagem");
const botao         = document.getElementById("btnComecar");
const areaCurso     = document.getElementById("areaCurso");
const btnReiniciar  = document.getElementById("btnReiniciar");
const divResultado  = document.getElementById("resultado");

const fraseAtual = frases[Math.floor(Math.random() * frases.length)];

let indiceAtual = 0;
let inicio      = null;
let erros       = 0;
let acertos     = 0;
let jogoAtivo   = false;

function carregarFrase() {
    fraseElemento.innerHTML = "";
    fraseAtual.split("").forEach(function(letra) {
        var span = document.createElement("span");
        span.textContent = letra;
        fraseElemento.appendChild(span);
    });
    fraseElemento.querySelectorAll("span")[0].classList.add("atual");
}

carregarFrase();
botao.addEventListener("click", function() {
    inicio    = Date.now();
    jogoAtivo = true;
    areaCurso.classList.remove("oculto");
    botao.style.display = "none";
    fraseElemento.querySelectorAll("span")[0].classList.add("atual");
});

btnReiniciar.addEventListener("click", function() {
    location.reload();
});
document.addEventListener("keydown", function(evento) {
    if (!jogoAtivo) return;

    var tecla = evento.key;
    if (tecla.length !== 1) return;

    var spans        = fraseElemento.querySelectorAll("span");
    var letraCorreta = fraseAtual[indiceAtual];

    if (tecla === letraCorreta) {
        acertos++;
        spans[indiceAtual].classList.remove("atual", "errado");
        spans[indiceAtual].classList.add("correto");
        indiceAtual++;

        if (indiceAtual < spans.length) {
            spans[indiceAtual].classList.add("atual");
        } else {
            jogoAtivo = false;

            var fim            = Date.now();
            var tempoSegundos  = ((fim - inicio) / 1000).toFixed(2);
            var totalTentativas = acertos + erros;
            var precisao       = totalTentativas > 0 ? ((acertos / totalTentativas) * 100).toFixed(1) : "100.0";
            var tempoMinutos   = parseFloat(tempoSegundos) / 60;
            var wpm            = Math.round((fraseAtual.length / 5) / tempoMinutos);
            var pontuacao      = Math.round(wpm * (parseFloat(precisao) / 100) * 10);

            // Preenche e exibe painel de resultado
            document.getElementById("tempoFinal").textContent     = tempoSegundos;
            document.getElementById("errosFinal").textContent     = erros;
            document.getElementById("precisaoFinal").textContent  = precisao;
            document.getElementById("wpmFinal").textContent       = wpm;
            document.getElementById("pontuacaoFinal").textContent = pontuacao;

            divResultado.classList.remove("oculto");
            mensagem.textContent = "Parabens! Frase concluida!";
            btnReiniciar.classList.remove("oculto");

            // Salva pontuação no servidor
            if (typeof USUARIO_LOGADO !== "undefined" && USUARIO_LOGADO) {
                var payload = {
                    wpm:      wpm,
                    precisao: parseFloat(precisao),
                    erros:    erros,
                    tempo:    parseFloat(tempoSegundos)
                };

                console.log("[Digitacao] Enviando para pontuacao.php:", payload);

                fetch("pontuacao.php", {
                    method:      "POST",
                    credentials: "same-origin",   // garante envio do cookie de sessão
                    headers:     { "Content-Type": "application/json" },
                    body:        JSON.stringify(payload)
                })
                .then(function(r) {
                    // lê como texto primeiro para debugar caso não seja JSON válido
                    return r.text();
                })
                .then(function(texto) {
                    console.log("[Digitacao] Resposta de pontuacao.php:", texto);
                    try {
                        var data = JSON.parse(texto);
                        if (!data.ok) {
                            console.warn("[Digitacao] Pontuacao nao salva:", data.msg, data);
                        } else {
                            console.log("[Digitacao] Pontuacao salva! Pontos:", data.pontuacao);
                        }
                    } catch(e) {
                        console.error("[Digitacao] Resposta nao e JSON:", texto);
                    }
                })
                .catch(function(err) {
                    console.error("[Digitacao] Erro no fetch:", err);
                });
            }
        }
    } else {
        erros++;
        spans[indiceAtual].classList.add("errado");
    }
});