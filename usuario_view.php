<?php
require_once("valida_acesso.php");
?>
<?php
require_once("conexao.php");

if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
    try {
        $erros = [];
        $id = filter_input(INPUT_POST, "id_usuario", FILTER_VALIDATE_INT);
        $pagina = filter_input(INPUT_POST, "pagina_usuario", FILTER_VALIDATE_INT);
        $texto_busca = filter_input(INPUT_POST, "texto_busca_usuario", FILTER_SANITIZE_STRING);

        $sql = "select * from usuario where id = ?";

        $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);

        $pre = $conexao->prepare($sql);
        $pre->execute(array(
            $id
        ));

        $resultado = $pre->fetch(PDO::FETCH_ASSOC);
        if (!$resultado) {
            throw new Exception("Não foi possível realizar a consulta!");
        }
    } catch (Exception $e) {
        $erros[] = $e->getMessage();
        $_SESSION["erros"] = $erros;
    } finally {
        $conexao = null;
    }
}
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-start">
                    <h4>Visualizar Usuário</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" title="Home" id="home_index_usuario"><i class="fas fa-home"></i>
                                    <span>Home</span></a></li>
                            <li class="breadcrumb-item"><a href="#" title="Usuário" id="usuario_index"><i class="fas fa-user-cog"></i> <span>Usuário</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <hr>
            <?php
            if (isset($_SESSION["erros"])) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                echo "<button type='button' class='btn-close btn-sm' data-bs-dismiss='alert'
                aria-label='Close'></button>";
                foreach ($_SESSION["erros"] as $chave => $valor) {
                    echo $valor . "<br>";
                }
                echo "</div>";
            }
            unset($_SESSION["erros"]);
            ?>
            <hr>
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="tab_usuario" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="dadostab_usuario" data-bs-toggle="tab" data-bs-target="#dados_usuario" type="button" role="tab" aria-controls="dados_usuario" aria-selected="true">Dados</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="segurancatab_usuario" data-bs-toggle="tab" data-bs-target="#seguranca_usuario" type="button" role="tab" aria-controls="seguranca_usuario" aria-selected="false">Segurança</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="tabdados_usuario">
                        <div class="tab-pane fade show active" id="dados_usuario" role="tabpanel" aria-labelledby="dados_usuario">
                            <h4>
                                <b><?= isset($resultado["id"]) ? $resultado["id"] : "" ?></b>
                                <b><?= " - "  ?></b>
                                <b><?= isset($resultado["nome"]) ? $resultado["nome"] : "" ?></b>
                            </h4>
                            <br>
                            <dl>
                                <dt>Nome</dt>
                                <dd>
                                    <?= isset($resultado["nome"]) ? $resultado["nome"] : ""; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>E-mail</dt>
                                <dd>
                                    <?= isset($resultado["email"]) ? $resultado["email"] : ""; ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="tab-pane fade" id="seguranca_usuario" role="tabpanel" aria-labelledby="seguranca_usuario">
                            <dl>
                                <dt>Login</dt>
                                <dd>Dado não listado</dd>
                            </dl>
                            <dl>
                                <dt>Senha</dt>
                                <dd>Dado não listado</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" id="pagina_usuario" name="pagina" value="<?php echo isset($pagina) ? $pagina : '' ?>" />
                    <input type="hidden" id="texto_busca_usuario" name="texto_busca_usuario" value="<?php echo isset($texto_busca) ? $texto_busca : '' ?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //devido ao load precisa carregar o arquivo js dessa forma
    var url = "./js/sistema/usuario.js";
    $.getScript(url);
</script>