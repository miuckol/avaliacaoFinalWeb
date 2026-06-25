const frases = [
    "O rato roeu a roupa do rei de roma",
    "JavaScript e uma linguagem muito utilizada",
    "Programar exige pratica e paciencia",
    "A tecnologia transforma o mundo"
];

const fraseElemento = document.getElementById("frase");
const mensagem = document.getElementById("mensagem");
const botao = document.getElementById("btnComecar");
const areaCurso = document.getElementById("areaCurso");
const btnReiniciar = document.getElementById("btnReiniciar");

const fraseAtual =
    frases[Math.floor(Math.random() * frases.length)];

let indiceAtual = 0;
let inicio;
let fim;
let erros = 0;
let acertos = 0;

function carregarFrase() {

    fraseElemento.innerHTML = "";

    fraseAtual.split("").forEach(letra => {

        const span = document.createElement("span");

        span.textContent = letra;

        fraseElemento.appendChild(span);
    });

    const spans = fraseElemento.querySelectorAll("span");

    if (spans.length > 0) {
        spans[0].classList.add("atual");
    }
}

carregarFrase();

botao.addEventListener("click", () => {

    inicio = Date.now();

    areaCurso.classList.remove("oculto");

    botao.style.display = "none";
});

document.addEventListener("keydown", (evento) => {

    if (areaCurso.classList.contains("oculto")) {
        return;
    }

    const tecla = evento.key;

    if (tecla.length !== 1) {
        return;
    }

    const spans = fraseElemento.querySelectorAll("span");

    const letraCorreta = fraseAtual[indiceAtual];

    if (tecla === letraCorreta) {

        acertos++;

        spans[indiceAtual].classList.remove("atual");
        spans[indiceAtual].classList.remove("errado");
        spans[indiceAtual].classList.add("correto");

        indiceAtual++;

        if (indiceAtual < spans.length) {

            spans[indiceAtual].classList.add("atual");

        } else {

            fim = Date.now();

            const tempoSegundos =
                ((fim - inicio) / 1000).toFixed(2);

            const totalTentativas =
                acertos + erros;

            const precisao =
                ((acertos / totalTentativas) * 100).toFixed(1);

            const caracteres =
                fraseAtual.length;

            const tempoMinutos =
                parseFloat(tempoSegundos) / 60;

            const wpm =
                Math.round((caracteres / 5) / tempoMinutos);

            mensagem.textContent =
                `Parabéns! Você concluiu a frase em ${tempoSegundos} segundos, com ${erros} erro(s), ${precisao}% de precisão e ${wpm} WPM.`;

                btnReiniciar.classList.remove("oculto");

            const resultado = document.getElementById("resultado");
            document.getElementById("tempoFinal").textContent = `${tempoSegundos}s`;
            document.getElementById("errosFinal").textContent = erros;
            document.getElementById("precisaoFinal").textContent = `${precisao}%`;
            document.getElementById("wpmFinal").textContent = wpm;
            document.getElementById("pontuacaoFinal").textContent = "Salvando...";
            resultado.classList.remove("oculto");

            fetch("salvar_pontuacao.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ wpm, precisao, erros, tempo: tempoSegundos })
            })
                .then(resp => resp.json())
                .then(data => {
                    if (data.ok) {
                        document.getElementById("pontuacaoFinal").textContent = data.pontuacao;
                    } else {
                        document.getElementById("pontuacaoFinal").textContent = "Erro ao salvar";
                        console.error(data.msg);
                    }
                })
                .catch(err => {
                    document.getElementById("pontuacaoFinal").textContent = "Erro de conexão";
                    console.error(err);
                });

                btnReiniciar.classList.remove("oculto");

            }

        } else {

            erros++;

            spans[indiceAtual].classList.add("errado");

    }
});
    btnReiniciar.addEventListener("click", () => {

    location.reload(); 
});
    