<?php
session_start();
include('includes/conexao.php');

// Verifique se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $user_id = $_SESSION['user_id'] ?? null; // Use o operador null coalescing

    if ($user_id === null) {
        die("Erro: ID do usuário não encontrado.");
    }

    // Insere o post no banco de dados
    $sql = "INSERT INTO post (titulo, texto, usuario_id, data_postagem) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $titulo, $texto, $user_id);
        
        if ($stmt->execute()) {
            header('Location: index.php'); // Redireciona após a inserção
            exit();
        } else {
            echo "Erro ao inserir: " . $stmt->error; // Exibe o erro se a inserção falhar
        }
    } else {
        echo "Erro na preparação da consulta: " . $conn->error; // Exibe erro na preparação
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Postagem</title>
</head>
<body>

<h1>Criar Postagem</h1>
<form method="POST" action="">
    <div>
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
    </div>
    <div>
        <label for="texto">Texto:</label>
        <textarea id="texto" name="texto" required></textarea>
    </div>
    <button type="submit">Publicar</button>
</form>

</body>
</html>