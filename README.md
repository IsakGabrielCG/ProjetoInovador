# Sistema de Contas a Pagar

> Protótipo funcional desenvolvido em **Laravel 12.x** com **Filament 4.x**, como parte do **Projeto Inovador II (PI2)** do curso de **Ciência da Computação – UNESC**.

Sistema demonstra a aplicação de ferramentas do ecossistema Laravel para criação de painéis administrativos modernos, com foco em **produtividade, usabilidade e padronização visual**.

## 🚀 Tecnologias Utilizadas

- **PHP** 8.4.11
- **Laravel** 12.x
- **Filament** 4.x
- **TALL Stack** (Tailwind CSS, Alpine.js, Livewire)
- **MySQL** 8.4.6 (ambiente local)
- **Composer** 2.8.x
- **Node.js** 20.13.1 / **NPM** 10.8.2

## 📁 Estrutura do Projeto

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

## ⚡ Funcionalidades

- ✅ **Autenticação** nativa do Filament (painel `/admin`)
- ✅ **Controle de usuários** com papéis: administrador e usuário padrão
- ✅ **CRUDs automáticos** com Eloquent ORM e Resources
- ✅ **Gráficos integrados** (widgets) com Chart.js
- ✅ **Filtros, busca e ordenação** em tabelas
- ✅ **Alternância de tema** (claro, escuro e automático)
- ✅ **Cálculo de multas e juros** via classe `Financeiro`
- ✅ **Responsividade** e consistência visual

## 🛠️ Instalação e Configuração

### Pré-requisitos
- PHP 8.2+
- Composer 2.6+
- Node.js 18+
- MySQL 8+ (ou compatível)

### Passo a Passo

1. **Clonar o repositório**
   ```bash
   git clone https://github.com/IsakGabrielCG/ProjetoInovador.git
   cd sistema-contas-pagar
   ```

2. **Instalar dependências PHP**
   ```bash
   composer install
   ```

3. **Instalar dependências Node.js e compilar assets**
   ```bash
   npm install
   ```

4. **Configurar ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar banco de dados**
   ```bash
   # Crie um banco de dados MySQL chamado 'contas_pagar'
   # Edite o arquivo .env com suas credenciais do MySQL:
   # DB_DATABASE=contas_pagar
   # DB_USERNAME=seu_usuario
   # DB_PASSWORD=sua_senha
   ```

6. **Executar migrações e seeders**
   ```bash
   php artisan migrate
   php artisan db:seed --class=DemoSeeder
   ```

7. **Iniciar servidor local**
   ```bash
   php artisan serve
   ```
   Acesse: http://localhost:8000/admin

## 👥 Credenciais de Acesso

A seeder cria automaticamente dois usuários para teste:

**Administrador:**
- Email: `admin@gmail.com`
- Senha: `12345678`

**Usuário Padrão:**
- Email: `user@gmail.com`
- Senha: `12345678`

## 📝 Observações

> **Nota:** O uso de Docker foi aplicado apenas no ambiente de deploy (Render), para simplificar a execução em nuvem. Para ambiente local, utilize PHP e Composer nativos.

## 👨‍💻 Autor

**Isak Gabriel Chedid Girardello**  
Curso de Ciência da Computação – UNESC  
Orientador: Prof. Rogério Antônio Casagrande

## 📄 Licença

Este projeto foi desenvolvido exclusivamente para fins acadêmicos, sem fins comerciais.
