<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $texto_comentario = trim($_POST['comentario'] ?? '');

    if ($post_id === null || empty($texto_comentario)) {
        header("Location: feed.php");
        exit();
    }

    $arquivo_posts = "posts.json";
    $posts = [];
    
    // 1. Carregar posts
    if (file_exists($arquivo_posts)) {
        $posts = json_decode(file_get_contents($arquivo_posts), true) ?? [];
    }

    $post_id = (int)$post_id;

    if (isset($posts[$post_id])) {
        // 2. Criar o novo comentário
        $novo_comentario = [
            "username" => $_SESSION['username'],
            "nome" => $_SESSION['nome'] ?? $_SESSION['username'],
            "texto" => $texto_comentario,
            "data" => date("Y-m-d H:i:s") // Adiciona data e hora
        ];

        // Inicializa a lista de comentários se ela não existir
        if (!isset($posts[$post_id]['lista_comentarios']) || !is_array($posts[$post_id]['lista_comentarios'])) {
            $posts[$post_id]['lista_comentarios'] = [];
        }
        
        // 3. Adicionar o comentário à lista do post
        $posts[$post_id]['lista_comentarios'][] = $novo_comentario;

        // 4. Incrementar o contador de comentários
        $posts[$post_id]['comentarios'] = count($posts[$post_id]['lista_comentarios']);
        
        // 5. Salvar de volta no JSON
        $novo_json_content = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($arquivo_posts, $novo_json_content);
    }
    
    // Redireciona de volta para o feed após o comentário
    header("Location: feed.php"); 
    exit();
} else {
    header("Location: feed.php");
    exit();
}
?>