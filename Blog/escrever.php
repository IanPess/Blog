<?php
session_start();
include('includes/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("Erro: Você precisa estar logado para publicar.");
}

// Verifica se o ID do usuário está definido
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id === null) {
    die("Erro: ID do usuário não encontrado.");
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $texto = trim($_POST['texto']);

    // Verifica se os campos estão preenchidos
    if ($titulo === '' || $texto === '') {
        die('Por favor, preencha todos os campos.');
    }

    // Insere a publicação no banco de dados
    $sql = "INSERT INTO post (titulo, texto, usuario_id, data_postagem) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Usa o ID do usuário na consulta
    $stmt->bind_param("ssi", $titulo, $texto, $user_id);
    if ($stmt->execute()) {
        echo "<p>Publicação criada com sucesso!</p>";
    } else {
        echo "<p>Erro ao inserir: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Postagem</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="escrever.css"> <!-- Link para o CSS -->
</head>
<body>

<div class="container">
    <h1><i class="fas fa-pencil-alt"></i> Criar Postagem</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="titulo"><i class="fas fa-heading"></i> Título:</label>
            <input type="text" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="texto"><i class="fas fa-file-alt"></i> Texto:</label>
            <textarea id="texto" name="texto" required></textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Publicar</button>
    </form>
</div>

</body>
</html>
