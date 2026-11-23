# LogiTrack - Gerenciador Logístico em PHP

Projeto prático desenvolvido para a disciplina de **Fundamentos de Programação para Internet** (UNIPAR EAD).

## Funcionalidades
- Cadastro e edição de **Produtos**
- Cadastro e edição de **Endereços** (Origem e Destino)
- Cadastro e edição de **Transportadores**
- Geração de **Fretes** com relacionamento **1:N** (1 transportador levando múltiplos produtos adicionados dinamicamente via JavaScript)
- Filtro por data e download de **Relatório diário de fretes** em CSV compatível com Excel

## Tecnologias Utilizadas
- PHP 8+ (PDO)
- MySQL / MariaDB (XAMPP)
- HTML5, CSS3 e JavaScript (ES6)
- Bootstrap 5

## Como Executar no XAMPP
1. Copie a pasta `gerenciador_logistico` para `C:\xampp\htdocs\`.
2. Inicie os módulos **Apache** e **MySQL** no painel do XAMPP.
3. Acesse `http://localhost/phpmyadmin` e importe o arquivo `database.sql`.
4. Acesse no navegador: `http://localhost/gerenciador_logistico/`.
