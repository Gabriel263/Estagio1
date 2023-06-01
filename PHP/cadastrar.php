<?php
// Dados de conexão com o banco de dados SQLite
$dbFile = "/tmp/clientes.db";

// Conexão com o banco de dados
try {
    $conexao = new PDO("sqlite:$dbFile");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
    exit();
}

// Verifica se a tabela "usuarios" existe, caso contrário, cria a tabela
try {
    $query = "SELECT name FROM sqlite_master WHERE type='table' AND name='usuarios'";
    $stmt = $conexao->query($query);
    $tabelaExiste = $stmt->fetchColumn();

    if (!$tabelaExiste) {
        $query = "CREATE TABLE usuarios (
            id INTEGER PRIMARY KEY,
            nome_completo VARCHAR(100),
            email VARCHAR(100),
            senha_hash VARCHAR(255),
            data_nascimento DATE,
            sexo VARCHAR(10) CHECK(sexo IN ('masculino', 'feminino')),
            imagem BLOB,
            tipo_imagem VARCHAR(10)
        )";

        $conexao->exec($query);
        echo "Tabela 'usuarios' criada com sucesso.<br>";
    }
} catch (PDOException $e) {
    echo "Erro ao verificar/criar a tabela: " . $e->getMessage();
    exit();
}

// Função para verificar se um email já existe no banco de dados
function emailExists($conexao, $email) {
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->fetchColumn();
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $confirmaSenha = $_POST["confirma-senha"];
    $dataNascimento = $_POST["data-nascimento"];
    $sexo = $_POST["sexo"];
    // Caso tenha imagem, a variável $imagem receberá o valor já codificado em base64, se não, permanecerá nula
    $imagem = isset($_POST["imagem"]) ? base64_encode($_POST["imagem"]) : null;
    // Obtém a extensão do arquivo de imagem
    $tipoImagem = isset($_POST["imagem"]) ? $_POST["tipo-imagem"] : null;

    // Verifica se o email é o email de exemplo
    if ($email == "exemplo@exemplo.com") {
        // Redireciona para a página de index com um parâmetro de erro
        header("Location: ../index.html?erro=email_invalido");
        exit();
    }
    // Verifica se o email já existe
    if (emailExists($conexao, $email)) {
        // Redireciona para a página de cadastro
        header("Location: ../index.html?erro=email_existente");
        exit();
    }

    // Verifica se as senhas coincidem
    if ($senha != $confirmaSenha) {
        die("As senhas não coincidem.");
    }

    // Criptografa a senha
    $senhaHash = crypt($senha, '$2a$07$usesomesillystringforsalt$');

    try {
        // Prepara a consulta SQL
        $sql = "INSERT INTO usuarios (nome_completo, email, senha_hash, data_nascimento, sexo, imagem, tipo_imagem) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$nome, $email, $senhaHash, $dataNascimento, $sexo, $imagem, $tipoImagem]);

        // Cadastro bem-sucedido, redireciona para a página de sucesso
        header("Location: ../login.html");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao cadastrar o usuário: " . $e->getMessage();
    }
}
?>
