<?php
require __DIR__ . '/../vendor/autoload.php';

use App\controllers\TicketController;

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new TicketController();
        $controller->create($_POST);
        $mensagem = "Ingresso cadastrado com sucesso!";
    } catch (Exception $e) {
        $mensagem = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Ticket</title>
    <link rel="stylesheet" href="css/create_ticket.css">
</head>
<body>
    <div class="create-ticket-container"> 
        <h1>Criar Novo Ticket</h1>
        <?php if ($mensagem): ?>
            <p class="<?= str_starts_with($mensagem, 'Erro') ? 'message-error' : 'message-success' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <form action="/create_ticket" method="POST">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" required><br><br>

            <label for="description">Descrição:</label><br>
            <textarea id="description" name="description" rows="4" required></textarea><br><br>

            <label for="amount">Quantidade disponível:</label><br>
            <input type="number" id="amount" name="amount" min="1" required><br><br>

            <label for="value">Preço (R$):</label><br>
            <input type="number" id="value" name="value" min="0" step="0.01" required><br><br>

            <label for="eventDate">Data do Evento:</label><br>
            <input type="date" id="eventDate" name="eventDate" required><br><br>

            <button type="submit">Criar Ticket</button>
        </form>

        <p><a href="/dashboard" class="back-link">← Voltar ao Dashboard</a></p> 
    </div>
</body>
</html>