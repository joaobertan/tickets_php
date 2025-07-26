<?php
require __DIR__ . '/vendor/autoload.php';
use App\repository\PDO\PDOconn;

$pdo = PDOconn::conectar();

if ($pdo) {
    echo "✅ Conectado ao PostgreSQL com sucesso!";
} else {
    echo "❌ Falha na conexão.";
}