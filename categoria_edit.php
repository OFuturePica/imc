<?php
require_once("valida_acesso.php");
?>
<?php
require_once("categoria_crud.php");


if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
    try {
        $erros = [];
        $id = filter_input(INPUT_POST, "id_medidas", FILTER_VALIDATE_INT);
        $usuario_id = isset($_SESSION["usuario_id"]) ?  $_SESSION["usuario_id"] : 0;
        $pagina = filter_input(INPUT_POST, "pagina_categoria", FILTER_VALIDATE_INT);
        $texto_busca = filter_input(INPUT_POST, "texto_busca_categoria", FILTER_SANITIZE_STRING);

        $sql = "select * from medidas where id = ?";

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
                    <h4>Editar Categoria</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" title="Home" id="home_index_categoria"><i class="fas fa-home"></i>
                                    <span>Home</span></a></li>
                            <li class="breadcrumb-item"><a href="#" title="Categoria" id="categoria_index"><i class="fas fa-plus fa-beat "></i> <span>Medidas</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
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
            <div class="alert alert-info alert-dismissible fade show" style="display: none;" id="div_mensagem_registro_categoria">
                <button type="button" class="btn-close btn-sm" aria-label="Close" id="div_mensagem_registro_botao_categoria"></button>
                <p id="div_mensagem_registro_texto_categoria"></p>
            </div>
            <hr>
            <div class="col-md-12">
                <form enctype="multipart/form-data" method="post" accept-charset="utf-8" id="categoria_dados" role="form" action="">
                    <ul class="nav nav-tabs" id="tab_categoria" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="dadostab_categoria" data-bs-toggle="tab" data-bs-target="#dados_categoria" type="button" role="tab" aria-controls="dados_categoria" aria-selected="true">Dados</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="tabdados_categoria">
                        <div class="tab-pane fade show active" id="dados_categoria" role="tabpanel" aria-labelledby="dados_categoria">
                            <div class="col-md-6">
                                    <label for="peso_categoria" class="form-label">peso</label>
                                <input type="text" class="form-control" id="peso_categoria" name="peso_categoria" maxlength="50" value="<?php echo isset($resultado['peso']) ? $resultado['peso'] : ''; ?>" autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="altura_categoria" class="form-label">altura</label>
                                <input type="text" class="form-control" id="altura_categoria" name="altura_categoria" maxlength="50" value="<?php echo isset($resultado['altura']) ? $resultado['altura'] : ''; ?>" autofocus>
                            </div>
                            <div class="col-md-6">
                                <label for="datac_categoria" class="form-label">data</label>
                                <input type="date" class="form-control" id="data_categoria" name="data_categoria" maxlength="50" value="<?php echo isset($resultado['data']) ? $resultado['data'] : ''; ?>" autofocus>
                            </div>


                         
                            <input type="hidden" id="id_medidas" name="id_medidas" value="<?php echo isset($id) ? $id : '' ?>" />
                             <input type="hidden" id="usuario_id_medida" name="usuario_id_medida" value="<?php echo isset($usuario_id) ? $usuario_id : '' ?>" />
                        </div>
                    </div>
                    <br>
                    <div>
                        <button type="button" class="btn btn-primary" id="botao_salvar_categoria">Salvar</button>
                        <button type="reset" class="btn btn-secondary" id="botao_limpar_categoria">Limpar</button>
                    </div>
                </form>
            </div>
            <div>
                <input type="hidden" id="pagina_categoria" name="pagina_categoria" value="<?php echo isset($pagina) ? $pagina : '' ?>" />
                <input type="hidden" id="texto_busca_categoria" name="texto_busca_categoria" value="<?php echo isset($texto_busca) ? $texto_busca : '' ?>" />
            </div>
        </div>
    </div>
</div>
<!--modal de salvar-->
<div class="modal fade" id="modal_salvar_categoria" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutlabel_categoria">Pergunta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja salvar o registro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal_salvar_sim_categoria">Sim</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
     //devido ao load precisa carregar o arquivo js dessa forma
    var url = "./js/sistema/categoria.js";
    $.getScript(url);
</script>