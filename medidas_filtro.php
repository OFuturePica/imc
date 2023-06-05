<?php

function validaDados($registro)
{
$erros = [];


    $registro->peso_medidas = str_replace(".",".",$registro->peso_medidas);
    $registro->peso_medidas = str_replace(",",".",$registro->peso_medidas);
    if (!filter_var($registro->peso_medidas, FILTER_VALIDATE_FLOAT)) {
        $erros["peso_medidas"] =  "Peso: Campo vazio e ou informação inválida!";
    }
    $registro->altura_medidas = str_replace(".",".",$registro->altura_medidas);
    $registro->altura_medidas = str_replace(",",".",$registro->altura_medidas);
    if (!filter_var($registro->altura_medidas, FILTER_VALIDATE_FLOAT)) {
        $erros["altura_medidas"] =  "Altura: Campo vazio e ou informação inválida!";
}
 if (!filter_var($registro->data_medidas, FILTER_SANITIZE_STRING)) {
        $erros["data_medidas"] =  "Data medida: Campo vazio e ou informação inválida!";
    }
   
    if (count($erros) > 0) {
        $_SESSION["erros"] = $erros;
        throw new Exception("Erro nas informações!");
    }

}