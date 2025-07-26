<?php
require __DIR__ . '/../vendor/autoload.php';

use App\services\SessionService;
use App\services\UserService;
use App\controllers\UserController;

SessionService::init();

if (!SessionService::loggedUser()) {
    header('Location: /login');
    exit;
}

$userService = new UserService();
$user = $userService->findById(SessionService::getId());

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new UserController();
        $controller->update($_POST);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-container">
        <h1>Editar Perfil</h1>

        <?php if (isset($erro)): ?>
            <p class="error-message">
                <?= htmlspecialchars($erro) ?>
            </p>
        <?php endif; ?>

        <form action="/profile" method="POST">
            <label>Nome:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user->name) ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required><br><br>

            <label>Nova senha (opcional):</label><br>
            <input type="password" name="password" placeholder="Deixe em branco para manter a mesma senha"><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <p><a href="/dashboard" class="back-link">Voltar</a></p>
    </div> 
</body>
</html>
