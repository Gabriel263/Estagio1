//limpa o input email que está com um texto pré-fixo
function limparValor(){
    document.getElementById("email").value='';
}
//mostra a categoria da senha
function passwordStrength() {
    var password = document.getElementById("senha").value;
    var strengthIndicator = document.getElementById("password-strength");

    //define as condições das senhas
    var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    var mediumRegex = new RegExp("^(?=.*[a-z])(?=.*[0-9])(?=.{6,})");
            
    if(strongRegex.test(password)) {
        strengthIndicator.innerText = "Senha: Forte";
    } else if(mediumRegex.test(password)) {
        strengthIndicator.innerText = "Senha: Média";
    } else {
        strengthIndicator.innerText = "Senha: Fraca";
    }
}
//verifica se o email ja existe e se é válido
window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('erro') == 'email_existente') {
            document.getElementById('erro-email-existente').style.display = 'block';
        }
        if(urlParams.get('erro') == 'email_invalido') {
            document.getElementById('erro-email-invalido').style.display = 'block';
        }
}