<?php
include_once('session_manager.php');
startUserSession();

if (!isset($_SESSION["usuario"])) {
    echo "Você precisa estar logado para fazer o upload da imagem.";
    header("Location: ../login.html");
    exit();
}

function salvarImagemNoBancoDeDados($nomeCampo, $dbPath, $tabela, $coluna) {
    if (!isset($_FILES[$nomeCampo]) || $_FILES[$nomeCampo]['error'] !== UPLOAD_ERR_OK) {
        return "Nenhum arquivo foi enviado.";
    }
    
    $temporaryPath = $_FILES[$nomeCampo]['tmp_name'];
    $imageType = $_FILES[$nomeCampo]['type'];

    $fileContents = file_get_contents($temporaryPath);
    if ($fileContents === false) {
        return "Falha ao ler o arquivo.";
    }

    $imageBase64 = base64_encode($fileContents);

    try {
        $db = new PDO('sqlite:' . $dbPath);
        $query = $db->prepare("UPDATE $tabela SET $coluna = ?, tipo_imagem = ? WHERE email = ?");
        $query->execute([$imageBase64, $imageType, $_SESSION["usuario"]["email"]]);
        
        return "Imagem enviada com sucesso.";
        
    } catch (PDOException $e) {
        return "Erro de conexão com o banco de dados: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = salvarImagemNoBancoDeDados('imagem', '/tmp/clientes.db', 'usuarios', 'imagem');
    echo $resultado;
}
?>
