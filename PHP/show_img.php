<?php
function exibirImagemDoBancoDeDados($email, $dbPath) {
    try {
        // Conexão com o banco de dados SQLite
        $db = new PDO('sqlite:' . $dbPath);

        // Preparar consulta SQL para obter a imagem e o tipo da imagem do banco de dados
        $consulta = $db->prepare("SELECT imagem, tipo_imagem FROM usuarios WHERE email = :email LIMIT 1");
        $consulta->execute([':email' => $email]);
        $resultado = $consulta->fetch();

        // Verificar se a consulta retornou um resultado
        if ($resultado) {
            $imagemBase64 = $resultado['imagem'];
            $tipoImagem = $resultado['tipo_imagem'];

            // Exibir a imagem no navegador
            echo '<img src="data:image/' . $tipoImagem . ';base64,' . $imagemBase64 . '" alt="Imagem do Perfil">';
        } else {
            echo "Nenhuma imagem encontrada no banco de dados.";
        }
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    }
}
?>