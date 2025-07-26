<?php
require __DIR__ . '/../vendor/autoload.php';

use App\services\SessionService;
use App\services\TicketService;
use App\controllers\TicketController;

$userId = SessionService::getId();
$ticketService = new TicketService();

$id = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['id'] ?? '')
    : ($_GET['id'] ?? '');

$ticket = $ticketService->findById($id);

if (!$ticket || $ticket->userId !== $userId) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new TicketController();
        $controller->update($_POST);
        header('Location: /dashboard');
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

function formatDateInput(int $timestamp): string {
    return date('Y-m-d', $timestamp);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Ticket</title>
    <link rel="stylesheet" href="css/ticket_edit.css">
</head>
<body>
    <div class="edit-ticket-container"> <h1>Editar Ticket</h1>
        <?php if (isset($erro)): ?>
            <p class="error-message">
                <?= htmlspecialchars($erro) ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="/ticket_edit">
            <input type="hidden" name="id" value="<?= htmlspecialchars($ticket->id) ?>">

            <label>Título:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($ticket->title) ?>" required><br>

            <label>Descrição:</label>
            <textarea name="description" required><?= htmlspecialchars($ticket->description) ?></textarea><br>

            <label>Quantidade:</label>
            <input type="number" name="amount" value="<?= (int)$ticket->amount ?>" required><br>

            <label>Preço (R$):</label>
            <input type="number" step="0.01" name="value" value="<?= (float)$ticket->value ?>" required><br>

            <label>Data do Evento:</label>
            <input type="date" name="eventDate" value="<?= formatDateInput((int)$ticket->eventDate) ?>" required><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="/dashboard" class="back-link">Voltar</a> </div> </body>
</html>
