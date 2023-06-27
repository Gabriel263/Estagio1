<?php
    // Encerra a sessão ok
    session_destroy();

    // Redireciona para a página de login
    header("Location: ../login.html");
exit();
?>