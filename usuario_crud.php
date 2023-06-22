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
        $registro = new stdClass();
        $registro = json_decode($_POST['registro']);

        $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
        
        // Verificar se já existe um usuário com o mesmo login
        $sql = "SELECT id FROM usuario WHERE login = ? AND id != ?";
        $pre = $conexao->prepare($sql);
        $pre->execute(array($registro->login_usuario, $registro->id_usuario));
        $resultado = $pre->fetch();
        if ($resultado) {
            throw new Exception("Login já cadastrado.");
        }

        // Obter os dados do usuário atual
        $sql = "SELECT senha FROM usuario WHERE id = ?";
        $pre = $conexao->prepare($sql);
        $pre->execute(array($registro->id_usuario));
        $usuario = $pre->fetch(PDO::FETCH_OBJ);

        if (!$usuario) {
            throw new Exception("Usuário não encontrado.");
        }

        $atual_senha = $registro->atual_senha;
        $nova_senha = $registro->nova_senha;

        if (!password_verify($atual_senha, $usuario->senha)) {
            throw new Exception("Senha atual incorreta.");
        }
         if (!empty($nova_senha)) {
            if (strlen($nova_senha) < 6) {
                throw new Exception("Nova senha deve ter pelo menos 6 caracteres.");
            }
        }

        if (!empty($nova_senha)) {
            $nova_senha_criptografada = password_hash($nova_senha, PASSWORD_BCRYPT, ['cost' => 12]);
            $sql = "UPDATE usuario SET nome = ?, email = ?, login = ?, senha = ? WHERE id = ?";
            $pre = $conexao->prepare($sql);
            $pre->execute(array(
                $registro->nome_usuario,
                $registro->email_usuario,
                $registro->login_usuario,
                $nova_senha_criptografada,
                $registro->id_usuario
            ));
        } else {
            $sql = "UPDATE usuario SET nome = ?, email = ?, login = ? WHERE id = ?";
            $pre = $conexao->prepare($sql);
            $pre->execute(array(
                $registro->nome_usuario,
                $registro->email_usuario,
                $registro->login_usuario,
                $registro->id_usuario
            ));
        }

        echo json_encode(1);
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
