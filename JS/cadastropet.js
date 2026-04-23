document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');

  form.addEventListener('submit', e => {
    e.preventDefault();

    const nome = form.nome.value.trim();
    const genero = form.genero.value;
    const peso = parseFloat(form.peso.value);
    const idade = parseInt(form.idade.value, 10);
    const especie = form.especie.value.trim();
    const porte = form.porte.value.trim();
    const raca = form.raca.value.trim();
    const local = form.local.value.trim();
    const imagem = form.imagem.files[0];

    if (!nome) {
      alert('Por favor, informe o nome do animal.');
      form.nome.focus();
      return;
    }

    if (!genero) {
      alert('Selecione o gênero do animal.');
      form.genero.focus();
      return;
    }

    if (isNaN(peso) || peso <= 0) {
      alert('Informe um peso válido e positivo.');
      form.peso.focus();
      return;
    }

    if (isNaN(idade) || idade < 0) {
      alert('Informe uma idade válida (0 ou mais).');
      form.idade.focus();
      return;
    }

    if (!especie) {
      alert('Informe a espécie do animal.');
      form.especie.focus();
      return;
    }

    if (!porte) {
      alert('Informe o porte do animal.');
      form.porte.focus();
      return;
    }

    if (!raca) {
      alert('Informe a raça do animal.');
      form.raca.focus();
      return;
    }

    if (!local) {
      alert('Informe o local.');
      form.local.focus();
      return;
    }

    if (!imagem) {
      alert('Por favor, insira uma foto do animal.');
      form.imagem.focus();
      return;
    }

    // Se quiser validar tipo e tamanho da imagem, dá pra fazer aqui (exemplo simples):
    const tiposAceitos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!tiposAceitos.includes(imagem.type)) {
      alert('Formato de imagem inválido. Use JPG, PNG ou GIF.');
      form.imagem.focus();
      return;
    }

    if (imagem.size > 5 * 1024 * 1024) { // limite 5MB
      alert('Imagem muito grande. Máximo permitido: 5MB.');
      form.imagem.focus();
      return;
    }

    // Tudo ok, pode enviar o formulário (aqui só uma simulação)
    alert('Cadastro do pet realizado com sucesso!');
    form.submit();
  });
});
