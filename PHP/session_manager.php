<?php
function startUserSession() {
    // Inicia a sessão se ainda não foi iniciada
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}

function loginUser($user) {
    // Inicia a sessão e armazena as informações do usuário
    startUserSession();
    $_SESSION["usuario"] = $user;
}

function logoutUser() {
    // Destrói a sessão e apaga todas as variáveis de sessão
    startUserSession();
    $_SESSION = array();
    session_destroy();
}
// Verifique se a variável de consulta de logout está definida, se estiver, efetue logout
if (isset($_GET['logout'])) {
    logoutUser();
    // Redireciona para a página de login após o logout
    header('Location: login.html');
    exit();
}

?>