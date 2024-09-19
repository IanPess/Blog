<?php
// Simulação de conexão com banco de dados
include('includes/conexao.php');

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos estão definidos e não estão vazios
    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha']; // Senha em texto simples
        $adm = false; // Definindo que o usuário não é administrador por padrão

        // Verifica se o e-mail já está cadastrado
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "Usuário já cadastrado!";
        } else {
            // Insere o novo usuário no banco de dados
            $sql = "INSERT INTO usuario (nome, email, senha, data_criacao, ativo, adm) VALUES (?, ?, ?, NOW(), TRUE, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $email, $senha, $adm);

            if ($stmt->execute()) {
                $successMessage = "Cadastro realizado com sucesso!";
            } else {
                $errorMessage = "Erro ao cadastrar o usuário: " . $stmt->error;
            }
        }
    } else {
        $errorMessage = "Preencha todos os campos obrigatórios!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Blog</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Cadastro</h1>
    
    <?php if ($errorMessage): ?>
        <div class="popup error"><?= $errorMessage ?></div>
    <?php elseif ($successMessage): ?>
        <div class="popup success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form method="POST" action="">
    <div class="form-group">
        <label for="nome">Nome</label>
        <div class="input-icon">
            <i class="fas fa-user"></i> <!-- Ícone de usuário -->
            <input type="text" id="nome" name="nome" required>
        </div>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <div class="input-icon">
            <i class="fas fa-envelope"></i> <!-- Ícone de envelope -->
            <input type="email" id="email" name="email" required>
        </div>
    </div>

    <div class="form-group">
        <label for="senha">Senha</label>
        <div class="input-icon">
            <i class="fas fa-lock"></i> <!-- Ícone de cadeado -->
            <input type="password" id="senha" name="senha" required>
        </div>
    </div>

    <button type="submit">Cadastrar</button>
</form>
</div>

</body>
</html>