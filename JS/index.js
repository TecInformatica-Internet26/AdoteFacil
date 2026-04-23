// SAIBA MAIS CARDS
document.addEventListener("DOMContentLoaded", function () {
  const botoesSaibaMais = document.querySelectorAll(".saiba");

  botoesSaibaMais.forEach((botao) => {
    botao.addEventListener("click", function () {
      const card = this.closest(".pet-card");
      const imagem = card.querySelector(".pet-imagem");
      const sobre = card.querySelector(".sobre");

      card.classList.toggle("expandido");

      if (card.classList.contains("expandido")) {
        imagem.style.opacity = "0";
        botao.textContent = "Ocultar";
      } else {
        imagem.style.opacity = "1";
        botao.textContent = "Saber mais";
      }
    });
  });


  // DROPDOWN MENU
  document.querySelectorAll('.dropdown-content a').forEach(link => {
    link.addEventListener('click', () => {
      document.getElementById('burger-menu').checked = false;
    });
  });
});


// SLIDES
let slideAtual = 0;
const slides = document.querySelectorAll('.slide');
const container = document.querySelector('.slides-container');

function mostrarSlide(index) {
  if (index >= slides.length) slideAtual = 0;
  if (index < 0) slideAtual = slides.length - 1;
  container.style.transform = `translateX(-${slideAtual * 100}%)`;
}

document.querySelector('.seta.direita').addEventListener('click', () => {
  slideAtual++;
  mostrarSlide(slideAtual);
});

document.querySelector('.seta.esquerda').addEventListener('click', () => {
  slideAtual--;
  mostrarSlide(slideAtual);
});

// Auto-play a cada 6 segundos
setInterval(() => {
  slideAtual++;
  mostrarSlide(slideAtual);
}, 6000);

const menuPerfil = document.querySelector('.perfil-menu');
const botaoEntrar = document.getElementById('btn-entrar');

// Se o perfil existir, oculta o botão "Entrar"
if (menuPerfil) {
    botaoEntrar?.style.setProperty("display", "none", "important");
}

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