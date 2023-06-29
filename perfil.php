<?php
include('PHP/session_manager.php');
startUserSession();

// Include the file with the function saveImageInDatabase
include('PHP/upload_img.php');
include('PHP/show_img.php');

// Check if the user's session is started
if (!isset($_SESSION["usuario"])) {
    // The session is not started, redirect the user to the login page
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="./CSS/style_perfil.css">
</head>
<body>
    <!-- Central container -->
    <div class="container">
        <div class="profile-picture" onclick="openFileExplorer()">
            <?php
                if (isset($_SESSION["usuario"])) {
                    exibirImagemDoBancoDeDados($_SESSION["usuario"]["email"], '/tmp/clientes.db');
                }
            ?>
        </div>
        <form method="POST" enctype="multipart/form-data" id="imageForm">
            <input type="hidden" id="hasImage" value="<?php echo (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["imagem"]) ? 'true' : 'false'; ?>">
            <input type="file" accept=".jpeg, .png" class="file-input" id="fileInput" name="imagem" onchange="handleFileChange()">
            <button type="submit" id="submitButton" class="button-style" style="display:none">Enviar</button>
        </form>
        <form method="POST" action="PHP/clear_img.php" id="clearForm">
            <button type="submit" id="clearButton" class="button-style" style="display:<?php echo (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["imagem"]) ? 'block' : 'none'; ?>">Limpar Imagem</button>
        </form>
        <h2><?php echo $_SESSION["usuario"]["nome_completo"]; ?></h2>
        <p><?php echo $_SESSION["usuario"]["email"]; ?></p>
        <p><strong>Nascimento:</strong> <?php echo $_SESSION["usuario"]["data_nascimento"]; ?></p>
        <p><strong>Sexo:</strong> <?php echo $_SESSION["usuario"]["sexo"]; ?></p>
        <button onclick="window.location.href='/PHP/session_manager.php?logout=true'" class="logout-button"><img src="IMAGENS/botao-de-logout-delineado.png" alt="Logout"></button>
    </div>
    <script src="JS/perfil.js"></script>
</body>
</html>
