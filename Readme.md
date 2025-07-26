# 🎟️ Sistema de Venda de Ingressos

Este é um sistema web desenvolvido em **PHP puro com PDO** e **PostgreSQL**, seguindo o padrão orientado a objetos, utilizando **Composer** e **autoloader com namespaces**. O sistema permite o cadastro de usuários, criação de ingressos, reserva temporária ao acessar o ingresso e compra final de ingressos com controle de estoque e autenticação.

---

## 📦 Instruções para rodar o projeto

Versão Utilizada: PHP 8.4.8

1. Clonar o repositório:

```
git clone https://github.com/joaobertan/tickets_php.git
```

2. Na pasta do projeto, instale as dependências:

```
composer install
```

3. Suba o container docker com o banco de dados Postgres:

```
docker-compose up -d
```

4. Crie as tabelas do banco de dados:

```
php src/repository/PDO/create_db.php
```

5. Inicie o servidor php local:

```
php -S localhost:8000 -t public
```

6. Acesse: ```localhost:8000```
