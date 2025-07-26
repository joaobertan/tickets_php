<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\services\ReservedService;
use App\services\OrderService;

$userId = $_POST['user_id'] ?? null;
$ticketId = $_POST['ticket_id'] ?? null;
$amount = (int) ($_POST['amount'] ?? 1);

if (!$userId || !$ticketId || $amount < 1) {
    echo "Dados inválidos.";
    exit;
}

$reservedService = new ReservedService();
$reservation = $reservedService->findValidReservation($ticketId, $userId);

if (!$reservation) {
    echo "Tempo de reserva expirado ou ingresso não reservado.";
    exit;
}

$orderService = new OrderService();

$isOrderOk = $orderService->create($userId, $ticketId, $amount);

if (!$isOrderOk) {
    echo "Erro ao criar pedido.";
} else {
    echo "Compra realizada com sucesso!";
}

echo "<a href='index.php'>Voltar</a>";
