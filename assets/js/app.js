function adicionarCampoProduto() {
    const container = document.getElementById('lista-produtos-frete');
    const modelo = document.querySelector('.item-produto-frete');
    if (!container || !modelo) return;

    const novoItem = modelo.cloneNode(true);
    const selects = novoItem.querySelectorAll('select');
    const inputs = novoItem.querySelectorAll('input');

    selects.forEach(s => s.selectedIndex = 0);
    inputs.forEach(i => i.value = 1);

    const btnRemover = novoItem.querySelector('.btn-remover-produto');
    if (btnRemover) {
        btnRemover.classList.remove('d-none');
    }

    container.appendChild(novoItem);
}

function removerCampoProduto(botao) {
    const item = botao.closest('.item-produto-frete');
    const total = document.querySelectorAll('.item-produto-frete').length;
    if (total > 1 && item) {
        item.remove();
    }
}
