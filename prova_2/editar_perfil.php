<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$usuarios = json_decode(file_get_contents("usuarios.json"), true);
$usuarioLogado = $_SESSION["username"];

$dados = null;
foreach ($usuarios as $u) {
    if ($u["username"] === $usuarioLogado) {
        $dados = $u;
        break;
    }
}

// Se não encontrou o usuário, evita erro fatal
if (!$dados) {
    die("Erro: usuário não encontrado no arquivo JSON.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h3>Editar Perfil</h3>

<form action="processa_editar_perfil.php" method="POST" class="mt-3">

    <label>Usuário (não pode alterar):</label>
    <input type="text" class="form-control mb-3" value="<?= htmlspecialchars($dados['username']) ?>" readonly>

    <label>Nome Completo:</label>
    <input type="text" name="nome" class="form-control mb-3" 
           value="<?= htmlspecialchars($dados['nome']) ?>">

    <label>Nova Senha:</label>
    <input type="password" name="senha" class="form-control mb-3" placeholder="Deixe em branco para não alterar">

    <button type="submit" class="btn btn-dark">Salvar alterações</button>
    <a href="feed.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
