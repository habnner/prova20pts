<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['post_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comentar Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Deixe seu Comentário</h1>
        <?php if ($post_id !== null): ?>
            <p class="text-muted">Você está comentando o Post #<?= htmlspecialchars($post_id) ?></p>
            
            <form method="post" action="processa_comentario.php">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id) ?>">
                <textarea class="form-control mb-3" rows="3" name="comentario" placeholder="Escreva seu comentário aqui..." required></textarea>
                <button type="submit" class="btn btn-primary">Enviar Comentário</button>
                <a href="feed.php" class="btn btn-secondary">Voltar ao Feed</a>
            </form>
            
        <?php else: ?>
            <p class="alert alert-danger">Nenhum post selecionado para comentar.</p>
            <a href="feed.php" class="btn btn-primary">Voltar ao Feed</a>
        <?php endif; ?>
    </div>
</body>
</html>