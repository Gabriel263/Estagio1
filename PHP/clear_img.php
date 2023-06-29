<?php
include('session_manager.php');
startUserSession();

// Verifique se o usuário está logado
if (!isset($_SESSION["usuario"])) {
    // O usuário não está logado, redirecione-o para a página de login
    header("Location: ../login.html");
    exit();
}

try {
    // Conexão com o banco de dados SQLite
    $db = new PDO('sqlite:/tmp/clientes.db');

    // Preparar a consulta SQL para limpar a imagem e o tipo da imagem
    $consulta = $db->prepare("UPDATE usuarios SET imagem = NULL, tipo_imagem = NULL WHERE email = :email");
    $consulta->execute([':email' => $_SESSION["usuario"]["email"]]);

    // Redirecionar de volta para a página do perfil
    header("Location: ../perfil.php");
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>
