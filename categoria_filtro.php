<?php

function validaDados($registro)
{
$erros = [];


    $registro->peso_categoria = str_replace(".","",$registro->peso_categoria);
    $registro->peso_categoria = str_replace(",",".",$registro->peso_categoria);
    if (!filter_var($registro->peso_categoria, FILTER_VALIDATE_FLOAT)) {
        $erros["peso_categoria"] =  "Peso: Campo vazio e ou informação inválida!";
    }
    $registro->altura_categoria = str_replace(".","",$registro->altura_categoria);
    $registro->altura_categoria = str_replace(",",".",$registro->altura_categoria);
    if (!filter_var($registro->altura_categoria, FILTER_VALIDATE_FLOAT)) {
        $erros["altura_categoria"] =  "Altura: Campo vazio e ou informação inválida!";
}
 if (!filter_var($registro->data_categoria, FILTER_SANITIZE_STRING)) {
        $erros["data_categoria"] =  "Data medida: Campo vazio e ou informação inválida!";
    }
   
    if (count($erros) > 0) {
        $_SESSION["erros"] = $erros;
        throw new Exception("Erro nas informações!");
    }

}