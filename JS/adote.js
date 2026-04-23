document.querySelectorAll('.saiba').forEach(botao => {
  botao.addEventListener('click', () => {
    const card = botao.closest('.pet-card');
    card.classList.toggle('expandido');
  });
});

const genero = document.getElementById("genero");

genero.addEventListener("change", () => {
    genero.classList.add("select-icone");

    if (genero.value === "Macho") {
        genero.style.backgroundImage = "url('IMG/icones/genero-macho.png')";
    } else if (genero.value === "Fêmea") {
        genero.style.backgroundImage = "url('IMG/icones/genero-femea.png')";
    } else {
        genero.style.backgroundImage = "none";
    }
});

const especie = document.getElementById("especie");

especie.addEventListener("change", () => {
    especie.classList.add("select-icone");

    if (especie.value === "Cachorro") {
        especie.style.backgroundImage = "url('IMG/icones/dogicon.png')";
    } else if (especie.value === "Gato") {
        especie.style.backgroundImage = "url('IMG/icones/caticon.png')";
    } else {
        especie.style.backgroundImage = "none";
    }
});

