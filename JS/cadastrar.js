function carregarCidades(cidadePreSelecionada = null) {
  console.log("Cidade recebida pelo carregarCidades:", cidadePreSelecionada);
    const estado = document.getElementById('estado').value;
    const selectCidade = document.getElementById('cidade');
    
    selectCidade.innerHTML = '<option value="">Carregando...</option>';
    selectCidade.disabled = true;

    if (!estado) {
        selectCidade.innerHTML = '<option value="">Selecione um estado primeiro</option>';
        return;
    }

    fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estado}/municipios?orderBy=nome`)
        .then(res => {
            if (!res.ok) throw new Error('Erro ao consultar a API do IBGE');
            return res.json();
        })
        .then(cidades => {
            selectCidade.innerHTML = '<option value="">Selecione uma Cidade</option>';
            
            cidades.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.nome;
                opt.textContent = c.nome;

                // AQUI ESTÁ O TRUQUE: 
                // Se a cidade atual do loop for igual a que veio do banco, seleciona ela
                if (cidadePreSelecionada && c.nome === cidadePreSelecionada) {
                    opt.selected = true;
                }

                selectCidade.appendChild(opt);
            });
            
            selectCidade.disabled = false;
        })
        .catch(err => {
            console.error(err);
            selectCidade.innerHTML = '<option value="">Erro ao carregar</option>';
        });
}

function mascaraCPF(input) {
  let value = input.value.replace(/\D/g, "");

  if (value.length > 11) value = value.slice(0, 11);

  if (value.length >= 3 && value.length < 6)
    input.value = value.replace(/(\d{3})(\d+)/, "$1.$2");
  else if (value.length >= 6 && value.length < 9)
    input.value = value.replace(/(\d{3})(\d{3})(\d+)/, "$1.$2.$3");
  else if (value.length >= 9)
    input.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, "$1.$2.$3-$4");
  else
    input.value = value;
}

function mascaraTel(input) {
  let value = input.value.replace(/\D/g, "");

  if (value.length > 11) value = value.slice(0, 11);

  if (value.length > 6)
    input.value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
  else if (value.length > 2)
    input.value = value.replace(/(\d{2})(\d{0,5})/, "($1) $2");
  else
    input.value = value;
}


function validar() {
  const senhaInput = document.getElementById("senha");
  const CsenhaInput = document.getElementById("Csenha");

  const senha = senhaInput.value;
  const Csenha = CsenhaInput.value;

  const regex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;

  // Reset (remove o vermelho se estiver ok)
  senhaInput.style.border = "";
  CsenhaInput.style.border = "";

  if (!regex.test(senha)) {
    senhaInput.style.border = "2px solid red";
    alert("A senha deve ter no mínimo 8 caracteres e conter letras e números.");
    return false;
  }

  if (senha !== Csenha) {
    CsenhaInput.style.border = "2px solid red";
    alert("As senhas não coincidem.");
    return false;
  }

  return true;
}

document.addEventListener('DOMContentLoaded', function() {
  const senhaAtualInput = document.getElementById('senha_atual_input');
  const novaSenhaInput = document.getElementById('nova_senha_input');

function toggleNovaSenha() {
  if (senhaAtualInput.value.trim() !== '') {
  novaSenhaInput.disabled = false;
  novaSenhaInput.placeholder = "Digite a nova senha";
} else {
  novaSenhaInput.disabled = true;
  novaSenhaInput.value = '';
  novaSenhaInput.placeholder = "Preencha a Senha Atual para mudar";
}
}

  senhaAtualInput.addEventListener('input', toggleNovaSenha);
  toggleNovaSenha(); 
});