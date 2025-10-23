<<?php
// Pega dados do formulário
$nome = $_POST['nome'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação simples
if ($nome && $usuario && $email && $senha) {
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha
    $linha = "$nome|$usuario|$email|$senhaCriptografada\n";

    // Caminho do arquivo
    $arquivo = 'usuarios.txt';

    // Salva no arquivo
    if (file_put_contents($arquivo, $linha, FILE_APPEND)) {
        echo "<h3>Usuário cadastrado com sucesso!</h3>";
        echo "<a href='cadastro.php'>Cadastrar outro</a> | <a href='index.html'>Voltar ao início</a>";
    } else {
        echo "Erro ao salvar o cadastro!";
    }
} else {
    echo "Preencha todos os campos!";
}
?>
