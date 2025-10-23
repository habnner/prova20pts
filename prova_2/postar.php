<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = trim($_POST['mensagem']);
    if (!empty($mensagem)) {
        $arquivo_posts = "posts.json";

        $posts = json_decode(file_get_contents($arquivo_posts), true);
        if (!is_array($posts)) {
            $posts = [];
        }

        $novo_post = [
            "username" => $_SESSION['username'],
            "nome" => $_SESSION['nome'] ?? $_SESSION['username'],
            "usuario" => "@" . $_SESSION['username'], // CHAVE 'usuario' ADICIONADA AQUI
            "foto_perfil" => "https://tse2.mm.bing.net/th/id/OIP.jEeLtL3Oj9bLke8RmUnlJQHaEM?rs=1&pid=ImgDetMain&o=7&rm=3",
            "mensagem" => $mensagem,
            "curtidas" => 0,
            "comentarios" => 0
        ];

        // Usa array_unshift para colocar o novo post no topo do feed
        array_unshift($posts, $novo_post); 

        // Salva com flags para evitar problemas de codificação
        file_put_contents($arquivo_posts, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    header("Location: feed.php");
    exit();
}