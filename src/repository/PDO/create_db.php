<?php
namespace App;
use App\repository\PDO\PDOconn;

require __DIR__ . '/../../../vendor/autoload.php';

$pdo = PDOconn::conectar();

$pdo->exec("
    CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";

    CREATE TABLE IF NOT EXISTS users (
        id UUID PRIMARY KEY,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        hashed_password TEXT NOT NULL,
        role TEXT NOT NULL,
        created_at BIGINT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS tickets (
        id UUID PRIMARY KEY,
        amount INTEGER NOT NULL CHECK (amount >= 0),
        value NUMERIC(10,2) NOT NULL CHECK (value >= 0),
        user_id UUID NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        event_date BIGINT NOT NULL,
        created_at BIGINT NOT NULL,
        deleted_at BIGINT,

        CONSTRAINT fk_ticket_user FOREIGN KEY (user_id)
            REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE INDEX IF NOT EXISTS idx_tickets_user_id ON tickets(user_id);

    CREATE TABLE IF NOT EXISTS reserved (
        id UUID PRIMARY KEY,
        ticket_id UUID NOT NULL,
        user_id UUID NOT NULL,
        created_at BIGINT NOT NULL,

        CONSTRAINT fk_reserved_ticket FOREIGN KEY (ticket_id)
            REFERENCES tickets(id) ON DELETE CASCADE,

        CONSTRAINT fk_reserved_user FOREIGN KEY (user_id)
            REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE INDEX IF NOT EXISTS idx_reserved_ticket_id ON reserved(ticket_id);
    CREATE INDEX IF NOT EXISTS idx_reserved_created_at ON reserved(created_at);

    CREATE TABLE IF NOT EXISTS orders (
        id UUID PRIMARY KEY,
        user_id UUID NOT NULL,
        created_at BIGINT NOT NULL,

        CONSTRAINT fk_order_user FOREIGN KEY (user_id)
            REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id);

    CREATE TABLE IF NOT EXISTS order_itens (
        id UUID PRIMARY KEY,
        order_id UUID NOT NULL,
        ticket_id UUID NOT NULL,
        amount INTEGER NOT NULL CHECK (amount > 0),

        CONSTRAINT fk_order_item_order FOREIGN KEY (order_id)
            REFERENCES orders(id) ON DELETE CASCADE,

        CONSTRAINT fk_order_item_ticket FOREIGN KEY (ticket_id)
            REFERENCES tickets(id) ON DELETE CASCADE
    );

    CREATE INDEX IF NOT EXISTS idx_order_itens_order_id ON order_itens(order_id);
    CREATE INDEX IF NOT EXISTS idx_order_itens_ticket_id ON order_itens(ticket_id);
");
