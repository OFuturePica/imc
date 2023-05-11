<?php
require_once("conexao.php");

function validaDados($registro)
{
    $erros = [];

    if (!filter_var($registro->nome_usuario, FILTER_SANITIZE_STRING)) {
        $erros["nome_usuario"] =  "Nome: Campo vazio e ou informação inválida!";
    }

    if (!filter_var($registro->email_usuario, FILTER_VALIDATE_EMAIL)) {
        $erros["email_usuario"] =  "E-mail: Campo vazio e ou informação inválida!";
    }

    if (!filter_var($registro->login_usuario, FILTER_SANITIZE_STRING)) {
        $erros["login_usuario"] =  "Login: Campo vazio e ou informação inválida!";
    }

    if (!filter_var($registro->senha_usuario, FILTER_SANITIZE_STRING)) {
        $erros["senha_usuario"] =  "Senha: Campo vazio e ou informação inválida!";
    }

    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
    //validando o login
    $sql = "select * from usuario where login = ?";
    $pre = $conexao->prepare($sql);
    $pre->execute(array(
        $registro->login_usuario
    ));
    $resultado = $pre->fetch();
    if ($resultado) {
        if (!$registro->id_usuario == $resultado['id']) {
            throw new Exception("Login: Login já cadastrado!");
        }
    }

    if (count($erros) > 0) {
        $_SESSION["erros"] = $erros;
        throw new Exception("Erro nas informações!");
    }
}
