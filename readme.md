### Desafio CRUD Bolo

### Pré instalação:

Configure o banco de dados MySQL através do link: 
*[ Link ](https://github.com/raimoreirarodrigues/mysql)

### Instalação:

    01-)Execute o comando para criação do container:
        docker-compose build --no-cache
    
    02-)Entre no ambiente docker através do comando:
        sudo docker exec -it desafio bash
    
    03-)Faça uma cópia do arquivo .env.example com o nome de .env e verifique se os dados de acesso ao banco estão corretos:
        chmod 777 .env.example
        cp .env.example .env
    
    04-)Execute os comandos em sequência para dar permissão aos diretórios:
        chmod 777 -R storage/framework
        chmod 777 -R storage/logs
        chmod 777 -R storage/app
        chmod 777 -R bootstrap/cache
    
    05-)Gere uma key com o seguinte comando:
        php artisan key:generate

    06-)Execute os seguintes comandos em sequência para geração das tabelas:
        php artisan migrate:install
        php artisan migrate

    07-)Abra seu navegador de preferência e digite localhost:8000 e verifique se o projeto está em funcionamento.

### Endpoints

    O projeto em questão possui os seguintes endpoints:

    Endpoints - Bolo

        [GET]  api/v1/bolo
        (Retorna uma lista de bolos cadastrados juntamente com a lista de interessados em cada bolo)

        [POST] api/v1/bolo
        (Salva um novo bolo com a lista de interessados)
        Ex: 
            {
                "nome":"Bolo de Milho",
                "peso":"500",
                "valor":"10",
                "interessados":["interessado01@dominio.com","interessado02@dominio.com","interessado02@dominio.com"]
            }

        [GET] api/v1/bolo/:id/edit
        (Retorna os dados de um bolo específico para que seja possível atualizá-los)

        [PUT] api/v1/bolo/:id
        (Atualiza os dados de um bolo específico)

        [DELETE] api/v1/bolo/:id
        (Deleta os dados de um bolo específico)

