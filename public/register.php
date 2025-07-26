<?php
require __DIR__ . '/../vendor/autoload.php';

use App\controllers\UserController;

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new UserController();
        $controller->create($_POST);
        $mensagem = "Usuário cadastrado com sucesso!";
    } catch (Exception $e) {
        $mensagem = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="register-container"> <h2>Cadastro de Usuário</h2>

        <?php if ($mensagem): ?>
            <p class="<?= str_starts_with($mensagem, 'Erro') ? 'message-error' : 'message-success' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nome:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Senha:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Tipo de Usuário:</label><br>
            <select name="role" required>
                <option value="client">Cliente</option>
                <option value="admin">Administrador</option>
            </select><br><br>

            <button type="submit">Cadastrar</button>
        </form>

        <a href="/login.php">Voltar para login</a> </div> </body>
</html>
