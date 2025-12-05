<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$usuarioAtual = $_SESSION["username"];
$usuarios = json_decode(file_get_contents("usuarios.json"), true);

foreach ($usuarios as &$u) {
    if ($u["usuario"] === $usuarioAtual) {

        // Atualiza nome
        $u["nome"] = $_POST["nome"];

        // Atualiza senha se usuário digitou
        if (!empty($_POST["senha"])) {
            $u["senha"] = password_hash($_POST["senha"], PASSWORD_DEFAULT);
        }

        break;
    }
}

file_put_contents("usuarios.json", json_encode($usuarios, JSON_PRETTY_PRINT));

// Atualizar sessão com nome novo
$_SESSION["nome"] = $_POST["nome"];

header("Location: feed.php?editado=1");
exit();
