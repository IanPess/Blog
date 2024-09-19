<?php
session_start();
include('includes/conexao.php');

// Busca as publicações no banco de dados
$sql = "SELECT post.titulo, post.texto, post.data_postagem, usuario.nome 
        FROM post 
        JOIN usuario ON post.usuario_id = usuario.id 
        ORDER BY post.data_postagem DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="container">
        <h1 class="logo">Bem-vindo ao Blog</h1>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <p>Olá, <?= htmlspecialchars($_SESSION['username']) ?> | <a href="logout.php">Logout</a> | <a href="escrever.php">Escrever Post</a></p>
            <?php else: ?>
                <a href="login.php">Login</a> | <a href="cadastro.php">Cadastre-se</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">
    <h2>Publicações Recentes</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="blog-posts">
            <?php while($row = $result->fetch_assoc()): ?>
                <article class="post">
                    <h3><?= htmlspecialchars($row['titulo']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['texto'])) ?></p>
                    <p><small>Publicado por <?= htmlspecialchars($row['nome']) ?> em <?= date('d/m/Y H:i', strtotime($row['data_postagem'])) ?></small></p>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Nenhuma publicação encontrada.</p>
    <?php endif; ?>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 - Blog. Todos os direitos reservados.</p>
    </div>
</footer>

</body>
</html>
