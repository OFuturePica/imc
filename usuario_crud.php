<?php
require_once("valida_acesso.php");

?>
<?php
require_once("conexao.php");
require_once("usuario_filtro.php");

//operações via ajax
if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
    if (!isset($_POST["acao"])) {
        return;
    }

  switch ($_POST["acao"]) {
    case "editar":
        try {
            $errosAux = "";

            $registro = new stdClass();
            $registro = json_decode($_POST['registro']);
            validaDados($registro);

            
        
            $nova_senha = $registro->nova_senha;

            // Obtém a senha criptografada armazenada no banco de dados
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $sql = "SELECT senha FROM usuario WHERE id = ?";
            $pre = $conexao->prepare($sql);
            $pre->execute(array($registro->id_usuario));
            $usuario = $pre->fetch(PDO::FETCH_OBJ);

        
                $nova_senha_criptografada = password_hash($nova_senha, PASSWORD_BCRYPT, ['cost' => 12]);

                // Atualiza a senha criptografada no banco de dados
                $sql = "update usuario set nome = ?, email = ?, login = ?, senha = ? WHERE id = ?";
                $pre = $conexao->prepare($sql);
                $pre->execute(array(
                    $registro->nome_usuario,
                    $registro->email_usuario,
                    $registro->login_usuario,
                    $nova_senha_criptografada,
                    $registro->id_usuario
                ));
                print json_encode(1);
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
         case "excluir":
            try {
                $registro = new stdClass();
                $registro = json_decode($_POST["registro"]);

                $sql = "delete from usuario where id = ? ";
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

                $sql = "select * from usuario where id = ?";
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
        default:
            print json_encode(0);
            return;
    }
}
