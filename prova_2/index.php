<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Exemplo de dados simulados de posts (poderia vir de um banco de dados futuramente)
$posts = [
    [
        "foto_perfil" => "https://www.dci.com.br/wp-content/uploads/2020/09/20490-1130x580.jpg.webp",
        "nome" => "João Silva",
        "usuario" => "@jcaosilva",
        "mensagem" => "Muito feliz hoje, amassamos os caras ontem",
        "curtidas" => 1000,
        "comentarios" => 700,
    ],
    [
        "foto_perfil" => "https://alonsofotografia.com.br/wp-content/uploads/2020/07/segredos-foto-perfil-profissional-dermatologista.jpg",
        "nome" => "Ana Paula",
        "usuario" => "@anapaula",
        "mensagem" => "Allianz Parque virou banquete de festa, tudo nosso. VAI CURINTHIAAAAAA!",
        "curtidas" => 300,
        "comentarios" => 90,
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Feed</title>
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="home">
    <div class="menu">
        <a href="#"><i class="bi bi-house-door-fill icon-menu"></i></a>
        <a href="#"><i class="bi bi-search icon-menu"></i></a>
        <a href="#" class="d-flex align-items-center justify-content-center icon-plus"><i class="bi bi-plus-lg icon-menu"></i></a>
        <a href="cadastro.php"><i class="bi bi-person icon-menu" title="Cadastrar novo usuário"></i></a>  
        <a href="logout.php" title="Sair"><i class="bi bi-box-arrow-right icon-menu"></i></a>
    </div>

    <section class="container mt-4 mb-4 feed">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <img src="https://tse2.mm.bing.net/th/id/OIP.jEeLtL3Oj9bLke8RmUnlJQHaEM?rs=1&pid=ImgDetMain&o=7&rm=3" class="rounded-circle me-3" alt="Foto de perfil">
                    <div>
                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION["nome"] ?? $_SESSION["usuario"]) ?></h6>
                        <small class="text-muted">@<?= htmlspecialchars($_SESSION["usuario"]) ?></small>
                    </div>
                </div>
                <button class="btn btn-dark btn-sm">Editar Perfil</button>
            </div>
            <form method="post" action="postar.php">
                <textarea class="form-control mb-2" rows="2" name="mensagem" placeholder="Quais são as novidades?"></textarea>
                <button type="submit" class="btn btn-dark btn-sm">Postar</button>
            </form>
        </div>

        <?php foreach ($posts as $i => $post): ?>
        <div class="mt-3 p-3">
            <div class="d-flex align-items-center">
                <img src="<?= htmlspecialchars($post['foto_perfil']) ?>" class="rounded-circle me-3" alt="Foto <?= htmlspecialchars($post['nome']) ?>">
                <div>
                    <strong><?= htmlspecialchars($post['nome']) ?></strong> 
                    <span class="text-muted"><?= htmlspecialchars($post['usuario']) ?></span>
                    <p class="mb-0 mt-1"><?= htmlspecialchars($post['mensagem']) ?></p>
                </div>
            </div>
            <div class="d-flex align-items-center mt-2 icons-interacao">
                <button class="btn-like">
                    <i class="bi bi-heart-fill text-secondary" id="icone-<?= $i ?>"></i> 
                    <span id="likes-<?= $i ?>"><?= $post["curtidas"] ?></span> curtidas
                </button>
                <span class="ms-3 text-muted">
                    <i class="bi bi-chat-left-text"></i> <?= $post["comentarios"] ?> comentários
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
