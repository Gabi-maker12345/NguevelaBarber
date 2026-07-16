# Deploy Laravel no Vercel

Este projeto usa o runtime comunitario `vercel-php` para executar Laravel em uma Vercel Serverless Function.

## Arquivos preparados

- `vercel.json`: configura a funcao PHP, rotas para assets do `public/` e defaults serverless.
- `api/index.php`: entrada da funcao, apontando para `public/index.php` e usando `/tmp` para caches gravaveis.
- `.vercelignore`: evita enviar dependencias locais, logs e arquivos temporarios.
- `.env.vercel.example`: lista as variaveis para cadastrar no painel da Vercel.

## Variaveis obrigatorias no Vercel

Cadastre em Project Settings > Environment Variables:

```env
APP_NAME="Nguevela Barber"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://seu-dominio.vercel.app

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Gere o `APP_KEY` localmente com:

```bash
php artisan key:generate --show
```

## Variaveis recomendadas

```env
LOG_CHANNEL=stderr
LOG_LEVEL=error
CACHE_STORE=array
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=sync
BROADCAST_CONNECTION=log

APP_CONFIG_CACHE=/tmp/laravel/config.php
APP_EVENTS_CACHE=/tmp/laravel/events.php
APP_PACKAGES_CACHE=/tmp/laravel/packages.php
APP_ROUTES_CACHE=/tmp/laravel/routes.php
APP_SERVICES_CACHE=/tmp/laravel/services.php
VIEW_COMPILED_PATH=/tmp/laravel/views
```

## Banco de dados

Nao use SQLite local em producao na Vercel, porque o filesystem da funcao nao e persistente. Use um banco externo, como MySQL, MariaDB ou PostgreSQL.

Depois de configurar o banco, rode as migrations uma vez a partir da sua maquina apontando para o banco de producao:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Se preferir, rode apenas os seeders que realmente quer em producao.

## Build

O script Composer `vercel` roda:

```bash
npm ci
npm run build
```

Assim o Vite gera `public/build` durante o deploy.

## Observacoes

- Uploads em `storage/` nao ficam persistentes na Vercel. Se no futuro o app salvar imagens ou arquivos, use S3 ou outro storage externo.
- Logs devem ir para `stderr`, para aparecerem no painel da Vercel.
- O runtime esta fixado em `vercel-php@0.7.4`, que usa PHP 8.3, alinhado ao `composer.json`.
