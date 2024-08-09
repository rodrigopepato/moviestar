# MovieStar 🎬

**MovieStar** é um site de avaliação de filmes desenvolvido em PHP, HTML, CSS e MySQL. Nele, os usuários podem adicionar títulos de filmes, duração, trailer e um resumo. Outros usuários podem avaliar os filmes com estrelas e deixar comentários.

## Funcionalidades
- Adicionar um filme com título, duração, trailer e resumo.
- Avaliar filmes com estrelas e comentários.
- Visualizar a lista de filmes e suas avaliações.

## Requisitos
Para rodar o projeto na sua máquina, você vai precisar de:
- PHP 7.4 ou superior
- Servidor Apache ou Nginx
- MySQL 5.7 ou superior
- Composer
- Um navegador moderno

## Configuração do Ambiente

1. **Banco de Dados**:
   - Crie um banco de dados no MySQL.
   - Importe o arquivo SQL disponível no projeto para configurar as tabelas e dados iniciais.

2. **Configuração do Projeto**:
   - Atualize as credenciais do banco de dados no arquivo de configuração (`.env` ou equivalente).

3. **Instalar Dependências**:
   - Utilize o Composer para instalar as dependências do projeto:
     ```bash
     composer install
     ```

4. **Configurar o Servidor Web**:
   - Configure o servidor Apache ou Nginx para apontar para o diretório do projeto.
   - Certifique-se de que o mod_rewrite está habilitado (se estiver usando Apache).

5. **Iniciar o Servidor**:
   - Se estiver usando o PHP built-in server, você pode iniciar o servidor localmente com o comando:
     ```bash
     php -S localhost:8000
     ```

6. **Acessar o Projeto**:
   - Acesse o MovieStar através do navegador em `http://localhost:8000`.

## Licença
Este projeto está licenciado sob a [MIT License](LICENSE).

