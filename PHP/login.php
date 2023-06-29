<?php
include('session_manager.php');
// Dados de conexão com o banco de dados
$dbFile = '/tmp/clientes.db';

// Conexão com o banco de dados
try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão com o banco de dados: " . $e->getMessage());
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Verifica se o email e a senha foram fornecidos
    if (!empty($email) && !empty($senha)) {
        // Prepara a consulta SQL
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email);

        // Executa a consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se encontrou um usuário
        if ($result) {
            // Verifica se a senha fornecida corresponde ao hash armazenado no banco de dados
            if (password_verify($senha, $result["senha_hash"])) {
                // Inicia uma nova sessão e armazena as informações do usuário na sessão
                loginUser($result);

                // Redireciona para a nova página
                header("Location: ../perfil.php");

                exit(); // Certifica-se de que o código seja encerrado após o redirecionamento
            } else {
                echo "Senha inválida.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        header("Location: ../index.html");
        exit();
    }
}
?>
