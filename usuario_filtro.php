<?php
require_once("conexao.php");
function validaDados($registro)
{
    $erros = [];

    if (!filter_var($registro->nome_usuario, FILTER_SANITIZE_STRING)) {
        $erros["nome_usuario"] =  "Nome: Campo vazio e/ou informação inválida!";
    }

    if (!filter_var($registro->email_usuario, FILTER_VALIDATE_EMAIL)) {
        $erros["email_usuario"] =  "E-mail: Campo vazio e/ou informação inválida!";
    }

    if (!filter_var($registro->login_usuario, FILTER_SANITIZE_STRING)) {
        $erros["login_usuario"] =  "Login: Campo vazio e/ou informação inválida!";
    }

    if (!filter_var($registro->nova_senha, FILTER_SANITIZE_STRING)) {
        $erros["nova_senha"] =  "Nova Senha: Campo vazio e/ou informação inválida!";
    }
    
    // Verifica se a propriedade atual_senha está presente no objeto $registro
    if (!isset($registro->atual_senha) || !filter_var($registro->atual_senha, FILTER_SANITIZE_STRING)) {
        $erros["atual_senha"] =  "Senha Atual: Campo vazio e/ou informação inválida!";
    }

    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
    // Validando o login
    $sql = "SELECT * FROM usuario WHERE login = ?";
    $pre = $conexao->prepare($sql);
    $pre->execute(array($registro->login_usuario));
    $resultado = $pre->fetch();
    if ($resultado) {
        if ($registro->id_usuario != $resultado['id']) {
            throw new Exception("Login: Login já cadastrado!");
        }
    }

    if (count($erros) > 0) {
        $_SESSION["erros"] = $erros;
        throw new Exception("Erro nas informações!");
    }
}



