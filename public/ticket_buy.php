<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\services\SessionService;
use App\services\TicketService;
use App\services\ReservedService;

$userId = $_SESSION['id_user'] ?? null;
$ticketId = $_POST['id'] ?? '';

if (!$ticketId) {
    echo "ID do ingresso não fornecido.";
    echo "<br><a href='/dashboard'>Voltar para dashboard</a>";
    exit;
}

$reservedService = new ReservedService();
$success = $reservedService->create($ticketId, $userId);

if (!$success) {
    echo "Este ingresso não está mais disponível.";
    echo "<br><a href='/dashboard'>Voltar para dashboard</a>";
    exit;
}

$ticketService = new TicketService();
$ticket = $ticketService->findById($ticketId);

if (!$ticket) {
    echo "Ingresso não encontrado.";
    echo "<br><a href='/dashboard'>Voltar para dashboard</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Compra de Ingresso</title>
</head>
<body>
    <h1>Comprar Ingresso</h1>
    <p><strong>Você tem 2 minutos para confirmar a compra antes que sua reserva expire!!</strong></p>
    <p><strong>Título:</strong> <?= htmlspecialchars($ticket->title) ?></p>
    <p><strong>Descrição:</strong> <?= htmlspecialchars($ticket->description) ?></p>
    <p><strong>Data do Evento:</strong> <?= date('d/m/Y', $ticket->eventDate) ?></p>
    <p><strong>Preço:</strong> R$ <?= number_format($ticket->value, 2, ',', '.') ?></p>

    <form action="/confirm_buy.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticketId) ?>">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
        
        <label for="amount">Quantidade:</label>
        <input type="number" id="amount" value="1" name="amount" min="1">

        <br><br>
        <button type="submit">Confirmar Compra</button>
    </form>

    <a href="/dashboard">Voltar para dashboard</a>
</body>
</html>
