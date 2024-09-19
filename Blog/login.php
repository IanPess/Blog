<?php
session_start();
include('includes/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe
    $sql = "SELECT id, nome FROM usuario WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['nome']; // Armazena o nome do usuário na sessão
        $_SESSION['user_id'] = $user['id']; // Armazena o ID do usuário na sessão
        $_SESSION['logged_in'] = true; // Define o status de login
        header("Location: index.php"); // Redireciona para a página principal
        exit;
    } else {
        $errorMessage = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Blog</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<div class="container">
    <h1>Login</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <p style="color:red;"><?= $errorMessage ?></p>
    <?php endif; ?>
</div>

</body>
</html>