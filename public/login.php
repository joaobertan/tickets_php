<?php

require __DIR__ . '/../vendor/autoload.php';

use App\controllers\AuthController;
use App\services\SessionService;

SessionService::init();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new AuthController();
        $controller->login($_POST);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container"> <h2>Login</h2>
        <?php if (isset($erro)) echo "<p class='error-message'>$erro</p>"; ?> <form method="POST">
            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Senha:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem uma conta? <a href="/register.php">Cadastre-se</a></p>
    </div> </body>
</html>
