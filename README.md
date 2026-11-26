# Sistema ETS/UFPB

Sistema de gerenciamento e análise de cargas horárias de cursos, ofertas, docentes e registros de aulas.

## Implantação em servidor

Requisitos: PHP 8.2 ou superior compatível com composer.lock, Composer 2, MySQL/MariaDB, extensões PHP exigidas pelo Composer (incluindo PDO MySQL), servidor web com HTTPS.

1. Extraia o projeto fora da raiz pública do servidor. Configure o document root para a pasta `public/` do projeto, nunca para a raiz do projeto. Configure o servidor para encaminhar rotas ao `public/index.php`.
2. Execute na raiz do projeto:

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
```

3. Edite `.env`: APP_ENV=production, APP_DEBUG=false, APP_URL=https://seu-dominio, DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD e SESSION_SECURE_COOKIE=true. Crie previamente o banco e o usuário MySQL. Não publique credenciais.
4. Para uma instalação nova, execute `php artisan key:generate`. Se estiver migrando uma instalação que usa dados criptografados, preserve a chave original por canal seguro.
5. Para banco vazio, execute `php artisan migrate --force`. Para preservar dados existentes, importe primeiro o SQL exportado da instalação original (incluindo tabela migrations) em banco vazio e depois execute `php artisan migrate --force`. Nunca use migrate:fresh em banco com dados.
6. Execute `php artisan storage:link` e `php artisan optimize:clear`. Garanta permissão de escrita para o usuário do servidor em storage e bootstrap/cache. Restaure os arquivos PPC e fotos autorizados em storage/app/public, se necessário. Esses arquivos podem ficar acessíveis pelo link público; não use documentos privados sem revisar a proteção de acesso.
7. Crie a conta administrativa com `php artisan ets:criar-admin` (não há senha padrão). Se importar contas existentes, use credenciais autorizadas ou redefina a senha com segurança.
8. Teste login, acesso de visitante somente leitura, CRUDs, filtros, registros de aulas, extratos, uploads e impressão antes de disponibilizar a URL. Use dados fictícios para demonstração. Não exponha o servidor de desenvolvimento artisan serve na internet.

## Conteúdo do pacote

Código reconstruído a partir da versão de registro de aulas e das atualizações posteriores disponíveis, incluindo dashboard, perfis, manual, impressão e PPC. Não inclui banco de dados real, credenciais, arquivos pessoais ou dependências vendor/node_modules. A instalação e os testes funcionais devem ser feitos no servidor de destino.
