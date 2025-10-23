<?php
session_start();

$mensagem = "";
$arquivo = "usuarios.json";

// Verifica se o arquivo JSON existe
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];

    if (empty($username) || empty($senha)) {
        $mensagem = "Preencha todos os campos.";
    } else {
        // Lê usuários do JSON
        $usuarios = json_decode(file_get_contents($arquivo), true);
        $usuario_encontrado = false;

        foreach ($usuarios as $u) {
            // Permitir login por username ou email
            if ($u['username'] === $username || $u['email'] === $username) {
                $usuario_encontrado = true;

                if (password_verify($senha, $u['senha'])) {
                    $_SESSION['username'] = $u['username'];
                    $_SESSION['nome'] = $u['nome'];
                    $_SESSION['email'] = $u['email'];
                    header("Location: feed.php");
                    exit;
                } else {
                    $mensagem = "Senha incorreta.";
                }
                break;
            }
        }

        if (!$usuario_encontrado) {
            $mensagem = "Usuário não encontrado.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f1f1; padding: 20px; }
        form { background: white; padding: 20px; border-radius: 10px; width: 300px; margin: auto; box-shadow: 0 0 10px #ccc; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1e7e34; }
        .msg { text-align: center; margin-bottom: 10px; color: #d00; }
        .sucesso { color: green; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Login</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Usuário ou email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>

<?php if (!empty($mensagem)): ?>
    <p class="msg"><?= $mensagem ?></p>
<?php endif; ?>

<p style="text-align:center;"><a href="cadastro.php">Não tem conta? Cadastre-se</a></p>

</body>
</html>
