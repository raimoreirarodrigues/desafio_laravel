### Desafio CRUD Bolo

### Pré instalação:

Configure o banco de dados MySQL através do link: 
*[ Link ](https://github.com/raimoreirarodrigues/mysql)

### Instalação:

    01-)Entre no diretório desafio_laravel
        cd desafio_laravel
    
    02-)Execute o comando para criação do container:
        docker-compose build --no-cache
    
    03-)Rode o comando para instalar as dependências do projeto:
    docker-compose run desafio php -d memory_limit=-1 /usr/local/bin/composer install
    
    04-)Entre no ambiente docker através do comando:
        sudo docker exec -it desafio bash
    
    05-)Faça uma cópia do arquivo .env.example com o nome de .env e verifique se os dados de acesso ao banco estão corretos:
        chmod 777 .env.example
        cp .env.example .env
    
    06-)Execute os comandos em sequência para dar permissão aos diretórios:
        chmod 777 -R storage/framework
        chmod 777 -R storage/logs
        chmod 777 -R storage/app
        chmod 777 -R bootstrap/cache
    
    07-)Gere uma key com o seguinte comando:
        php artisan key:generate

    08-)Execute os seguintes comandos em sequência para geração das tabelas:
        php artisan migrate:install
        php artisan migrate

    09-)Abra seu navegador de preferência e digite localhost:8000 e verifique se o projeto está em funcionamento.

    Nota: para executar o JOB de envio de notificações aos interessados, executar o passo 02 e, em seguida, executar:

    php artisan queue:listen --queue=notificar_interessado_bolo --timeout=0 --tries=4

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

## Operação

    O sistema verifica a cada 5(cinco) minutos (através de um schedule) os bolos que estão disponíveis (quantidade >0) e suas respectivas listas de interessados que ainda não foram notificados. Gera-se, então, uma fila de notificações, sendo que cada interessado receberá por e-mail uma notificação que seu bolo está disponível.