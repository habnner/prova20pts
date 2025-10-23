<?php
session_start();

$mensagem = "";


$arquivo = "usuarios.json";


if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $data_nascimento = $_POST['data_nascimento'];

    if (empty($nome) || empty($username) || empty($email) || empty($senha) || empty($confirma_senha) || empty($data_nascimento)) {
        $_SESSION['mensagem'] = "Preencha todos os campos.";
    } elseif ($senha !== $confirma_senha) {
        $_SESSION['mensagem'] = "As senhas não coincidem.";
    } else {
        
        $usuarios = json_decode(file_get_contents($arquivo), true);

        
        $usuario_existe = false;
        foreach ($usuarios as $u) {
            if ($u['username'] === $username || $u['email'] === $email) {
                $usuario_existe = true;
                break;
            }
        }

        if ($usuario_existe) {
            $_SESSION['mensagem'] = "Nome de usuário ou email já cadastrado.";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            
            $novo_usuario = [
                "nome" => $nome,
                "username" => $username,
                "email" => $email,
                "senha" => $senha_hash,
                "data_nascimento" => $data_nascimento
            ];

            
            $usuarios[] = $novo_usuario;
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

            $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça login.";
        }
    }

    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$mensagem = $_SESSION['mensagem'] ?? "";
unset($_SESSION['mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f1f1; padding: 20px; }
        form { background: white; padding: 20px; border-radius: 10px; width: 300px; margin: auto; box-shadow: 0 0 10px #ccc; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .msg { text-align: center; margin-bottom: 10px; color: #d00; }
        .sucesso { color: green; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Cadastro de Usuário</h2>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="text" name="username" placeholder="Nome de usuário" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="password" name="confirma_senha" placeholder="Confirme a senha" required>
    <label>Data de nascimento:</label>
    <input type="date" name="data_nascimento" required>
    <button type="submit">Cadastrar</button>
</form>

<?php if (!empty($mensagem)): ?>
    <p class="msg <?= str_contains($mensagem, 'sucesso') ? 'sucesso' : '' ?>"><?= $mensagem ?></p>
<?php endif; ?>

<p style="text-align:center;"><a href="login.php">Já tem conta? Faça login</a></p>

</body>
</html>
