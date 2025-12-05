<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$arquivo_posts = "posts.json";
$posts = [];

// Posts antigos hardcoded, apenas se o JSON ainda não existir (MANTIDO)
$posts_antigos = [
    [
        "foto_perfil" => "https://www.dci.com.br/wp-content/uploads/2020/09/20490-1130x580.jpg.webp",
        "nome" => "João Silva",
        "usuario" => "@jcaosilva",
        "mensagem" => "Muito feliz hoje, amassamos os caras ontem!",
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

// Cria JSON com posts antigos se não existir
if (!file_exists($arquivo_posts)) {
    file_put_contents($arquivo_posts, json_encode($posts_antigos, JSON_PRETTY_PRINT));
}

// LÊ TODOS OS POSTS (usando o operador de coalescência nula para evitar erros se o arquivo estiver vazio)
$posts = json_decode(file_get_contents($arquivo_posts), true) ?? [];

// LÓGICA DE CURTIR: processa o clique no link que passa o ID do post
if (isset($_GET['curtir_id']) && is_numeric($_GET['curtir_id'])) {
    $curtir_id = (int)$_GET['curtir_id'];

    if (isset($posts[$curtir_id])) {
        // Incrementa o número de curtidas
        $posts[$curtir_id]['curtidas']++;

        // Salva de volta no JSON
        $novo_json_content = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($arquivo_posts, $novo_json_content);

        // Redireciona para o feed para evitar reenvio (limpa o parâmetro da URL)
        header("Location: feed.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Feed</title>
    <link rel="stylesheet" href="assets/css/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* CSS para deixar o coração vermelho se tiver curtidas */
        .btn-like i.curtido {
            color: #dc3545 !important; 
        }
    </style>
</head>
<body>
<div class="home">
    <div class="menu">
        <a href="#"><i class="bi bi-house-door-fill icon-menu"></i></a>
        <a href="#"><i class="bi bi-search icon-menu"></i></a>
        <a href="#" class="d-flex align-items-center justify-content-center icon-plus">
            <i class="bi bi-plus-lg icon-menu"></i>
        </a>
        <a href="cadastro.php"><i class="bi bi-person icon-menu" title="Cadastrar novo usuário"></i></a>  
        <a href="logout.php" title="Sair"><i class="bi bi-box-arrow-right icon-menu"></i></a>
    </div>

    <section class="container mt-4 mb-4 feed">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <img src="https://tse2.mm.bing.net/th/id/OIP.jEeLtL3Oj9bLke8RmUnlJQHaEM?rs=1&pid=ImgDetMain&o=7&rm=3" 
                         class="rounded-circle me-3" alt="Foto de perfil" width="50" height="50">
                    <div>
                        <h6 class="mb-0 fw-bold">
                            <?= htmlspecialchars($_SESSION["nome"] ?? $_SESSION["username"]) ?>
                        </h6>
                        <small class="text-muted">@<?= htmlspecialchars($_SESSION["username"]) ?></small>
                    </div>
                </div>
               <a href="editar_perfil.php" class="btn btn-dark btn-sm">Editar Perfil</a>

            </div>

            <form method="post" action="postar.php">
                <textarea class="form-control mb-2" rows="2" name="mensagem" placeholder="Quais são as novidades?" required></textarea>
                <button type="submit" class="btn btn-dark btn-sm">Postar</button>
            </form>
        </div>

        <?php foreach ($posts as $i => $post): ?>
        <div class="mt-3 p-3 border rounded bg-white shadow-sm">
            <div class="d-flex align-items-center">
                <img src="<?= htmlspecialchars($post['foto_perfil'] ?? '') ?>" 
                     class="rounded-circle me-3" alt="Foto <?= htmlspecialchars($post['nome'] ?? 'Usuário') ?>" width="50" height="50">
                <div>
                    <strong><?= htmlspecialchars($post['nome'] ?? 'Usuário') ?></strong> 
                    <span class="text-muted">
                        <?= htmlspecialchars($post['usuario'] ?? "@" . ($post['username'] ?? '')) ?>
                    </span>
                    <p class="mb-0 mt-1"><?= htmlspecialchars($post['mensagem'] ?? '') ?></p>
                </div>
            </div>
            <div class="d-flex align-items-center mt-2 icons-interacao">
                
                <a href="?curtir_id=<?= $i ?>" class="btn-like btn btn-link p-0 text-decoration-none">
                    <i class="bi bi-heart-fill <?= ($post["curtidas"] ?? 0) > 0 ? 'curtido' : 'text-secondary' ?>" id="icone-<?= $i ?>"></i> 
                    <span id="likes-<?= $i ?>"><?= $post["curtidas"] ?? 0 ?></span> curtidas
                </a>
                
                <a href="comentar.php?post_id=<?= $i ?>" class="ms-3 text-muted text-decoration-none">
                    <i class="bi bi-chat-left-text"></i> <?= $post["comentarios"] ?? 0 ?> comentários
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>