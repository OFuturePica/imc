<?php
require_once("valida_acesso.php");
?>
<?php
require_once("conexao.php");
require_once("medidas_filtro.php");

//operações via ajax
if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
    if (!isset($_POST["acao"])) {
        return;
    }

    switch ($_POST["acao"]) {
        case "adicionar":
            try {
                $errosAux = "";


                $registro = new stdClass();
                $registro = json_decode($_POST['registro']);
                validaDados($registro);


                $sql = "insert into medidas(peso, altura, data, usuario_id) VALUES (?, ?, ?, ?) ";
                $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
                $pre = $conexao->prepare($sql);
                $pre->execute(array(
                    $registro->peso_medidas,
                    $registro->altura_medidas,
                    $registro->data_medidas,
                    $registro->usuario_id_medida
                ));
                print json_encode($conexao->lastInsertId());
            } catch (Exception $e) {
                if (isset($_SESSION["erros"])) {
                    foreach ($_SESSION["erros"] as $chave => $valor) {
                        $errosAux .= $valor . "<br>";
                    }
                }
                $errosAux .= $e->getMessage();
                unset($_SESSION["erros"]);
                echo "Erro: " . $errosAux . "<br>";
            } finally {
                $conexao = null;
            }
           
            break;

            
        case "editar":
            try {
                $errosAux = "";


                $registro = new stdClass();
                $registro = json_decode($_POST['registro']);
                validaDados($registro);
                //var_dump($registro);
//update medidas set peso = 11, altura = 11, data = 02 WHERE id = 11 and usuario_id =2 ;
            $sql = "update medidas set peso = ?, altura = ?, data = ? WHERE id = ? and usuario_id = ? ";
                $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
                $pre = $conexao->prepare($sql);
                $pre->execute(array(
                    $registro->peso_medidas,
                    $registro->altura_medidas,
                    $registro->data_medidas,
                    $registro->id_medida,
                    $registro->usuario_id_medida
                  
                ));
               
                print json_encode(1);
            } catch (Exception $e) {
                foreach ($_SESSION["erros"] as $chave => $valor) {
                    $errosAux .= $valor . "<br>";
                }
                $errosAux .= $e->getMessage();
                unset($_SESSION["erros"]);
                echo "Erro: " . $errosAux . "<br>";
            } finally {
                $conexao = null;
            }
            break;

        case "excluir":
            try {
                $registro = new stdClass();
                $registro = json_decode($_POST["registro"]);

                $sql = "delete from medidas where id = ? ";
                $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
                $pre = $conexao->prepare($sql);
                $pre->execute(array(
                    $registro->id
                ));

                print json_encode(1);
            } catch (Exception $e) {
            
                
                echo "Erro: " . $e->getMessage() . "<br>";
            } finally {
                $conexao = null;
            }
            break;
        case 'buscar':
            try {
                $registro = new stdClass();
                $registro = json_decode($_POST["registro"]);

                $sql = "select * from medidas where (id like)";
                $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
                $pre = $conexao->prepare($sql);
                $pre->execute(array(
                    $registro->id
                ));

                print json_encode($pre->fetchAll(PDO::FETCH_ASSOC));
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage() . "<br>";
            } finally {
                $conexao = null;
            }
            break;
       case 'grafico':
 try {
    $ano = filter_var($_POST["ano"], FILTER_VALIDATE_INT);
    $usuario_id = filter_var($_POST["usuario"], FILTER_VALIDATE_INT);
    $receber = null;
    $receber_aux = [];
    $linhas = [];

    $retorno = [];

    $meses = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];

    $sql = "SELECT extract(month FROM data) as mes, peso, altura " .
        "FROM medidas WHERE usuario_id = ? " .
        "AND extract(year FROM data) = ? " .
        "ORDER BY peso";

    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
    $pre = $conexao->prepare($sql);
    $pre->execute(array(
        $usuario_id,
        $ano
    ));

    $receber = $pre->fetchAll(PDO::FETCH_ASSOC);

    // aqui extraindo os dados de recebimentos da consulta
    for ($i = 0; $i < count($receber); $i++) {
        $linha = $receber[$i];
        $peso = $linha["peso"];
        $altura = $linha["altura"];
        $imc = round($peso / ($altura * $altura), 2);

        if (array_key_exists($linha["mes"], $meses)) {
            $linhas[$meses[$linha["mes"]]][] = $imc;
        }
    }

    // só preenchendo o vetor com os dados restantes se não vier 12 meses na consulta
    if (count($linhas) < 12) {
        for ($i = 1; $i < 13; $i++) {
            if (array_key_exists($meses[$i], $linhas)) {
                $receber_aux[$meses[$i]] = $linhas[$meses[$i]];
            } else {
                $receber_aux[$meses[$i]] = 0;
            }
        }
    }

    $retorno[] = $receber_aux;
    print json_encode($retorno);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "<br>";
} finally {
    $conexao = null;
}

break;
 

            
        default:
            print json_encode(0);
            return;
            
    }
}

function listarCategoria()
{
    try {
        $usuario_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : 0;

           $sql = "select * from medidas where usuario_id = ? order by peso";
        $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
        $pre = $conexao->prepare($sql);
        $pre->execute(array(
            $usuario_id
        ));
        $pre->execute();

        return $pre->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage() . "<br>";
    } finally {
        $conexao = null;
    }
}
function buscarCategoria(int $id)
{
    try {
        $sql = "select * from medidas where id = ?";
        $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
        $pre = $conexao->prepare($sql);
        $pre->execute(array(
            $id
        ));

        return $pre->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage() . "<br>";
    } finally {
        $conexao = null;
    }
}

