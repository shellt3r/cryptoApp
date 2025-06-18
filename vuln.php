<?php
// Início da sessão com possibilidade de Session Fixation
if (isset($_GET['sess'])) {
    session_id($_GET['sess']); // ⚠️ Session Fixation
}
session_start();

$conn = new mysqli("localhost", "usuario", "senha", "banco");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Tratamento de login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];     // ⚠️ Sem validação
    $password = $_POST['password'];

    // ⚠️ SQL Injection
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['user'] = $email;
    } else {
        echo "Login inválido!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
</head>
<body>

<?php
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    // ⚠️ XSS (Cross-Site Scripting)
    echo "<h2>Bem-vindo, $user!</h2>";
} else {
?>
    <form method="POST">
        <label>Email: <input type="text" name="email" /></label><br>
        <label>Senha: <input type="password" name="password" /></label><br>
        <input type="submit" value="Entrar" />
    </form>
<?php
}
?>

</body>
</html>
