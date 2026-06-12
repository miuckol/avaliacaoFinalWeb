const abrirLogin = document.getElementById("abrirLogin");
const overlay = document.getElementById("overlay");
const fecharModal = document.getElementById("fecharModal");

abrirLogin.addEventListener("click", function(event) {
    event.preventDefault();
    overlay.classList.remove("login-oculto");
});

fecharModal.addEventListener("click", function() {
    overlay.classList.add("login-oculto");
});