Projeto Inovador — Sistema de Contas a Pagar
Protótipo funcional desenvolvido em Laravel 12.x com Filament 4.x, como parte do Projeto Inovador II (PI2) do curso de Ciência da Computação – UNESC. O sistema demonstra a aplicação de ferramentas do ecossistema Laravel para a criação de painéis administrativos modernos, com foco em produtividade, usabilidade e padronização visual.
Tecnologias utilizadas

- PHP 8.4.11
- Laravel 12.x
- Filament 4.x
- Tailwind CSS / Alpine.js / Livewire (TALL Stack)
- MySQL 8.4.6 (ambiente local)
- SQLite (deploy Render)
- Composer 2.8.x e Node.js 20.13.1 / NPM 10.8.2

Estrutura geral do projeto

A aplicação segue a estrutura padrão do Laravel e é organizada da seguinte forma:
```
app/
 ├─ Filament/
 │   ├─ Resources/     → CRUDs e telas administrativas
 │   └─ Widgets/       → Gráficos e componentes dinâmicos
 ├─ Helpers/
 │   └─ Financeiro.php → Cálculo de multas e juros
database/
 ├─ migrations/        → Estrutura do banco de dados
 └─ seeders/           → Popular dados de teste (DemoSeeder)
routes/
 └─ web.php            → Rotas principais (redirect para /admin)
```
Principais funcionalidades

- Autenticação nativa do Filament (painel /admin);
- Controle de usuários com papéis (roles);
- CRUDs automáticos com Eloquent ORM e Resources;
- Gráficos integrados (widgets) com Chart.js;
- Filtros, busca e ordenação em tabelas;
- Alternância de tema (claro/escuro/automático);
- Classe auxiliar Financeiro para cálculo de multas e juros.

Configuração e execução local

1. Pré-requisitos:
   - PHP 8.2+
   - Composer 2.6+
   - Node.js 18+
   - MySQL 8+ (ou SQLite)


2. Instalação:
   - Clonar o projeto e acessar a pasta
   - Criar o arquivo de ambiente (.env) a partir do modelo
   - Instalar dependências PHP e JS
   - Gerar chave da aplicação


3. Configuração do banco de dados (MySQL local):
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pi1
   DB_USERNAME=root
   DB_PASSWORD=


4. Executar migrations e seeders:
   php artisan migrate
   php artisan db:seed --class=DemoSeeder


5. Iniciar o servidor local:
   php artisan serve
   Acesse: http://localhost:8000/admin

Observação: o uso de Docker foi aplicado apenas no ambiente de deploy (Render), para simplificar a execução em nuvem. Para ambiente local, utilize PHP e Composer nativos.
Autor
Isak Gabriel Chedid Girardello
Curso de Ciência da Computação – UNESC
Orientador: Prof. Rogério Antônio Casagrande
Licença
Este projeto foi desenvolvido exclusivamente para fins acadêmicos, sem fins comerciais.
