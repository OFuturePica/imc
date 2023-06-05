<?php
require_once("valida_acesso.php");
?>
<?php
if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
    try {
        $erros = [];
        $id = filter_input(INPUT_POST, "id_medida", FILTER_VALIDATE_INT);
        $usuario_id = isset($_SESSION["usuario_id"]) ?  $_SESSION["usuario_id"] : 0;
        $pagina = filter_input(INPUT_POST, "pagina_medidas", FILTER_VALIDATE_INT);
        $texto_busca = filter_input(INPUT_POST, "texto_busca_medidas", FILTER_SANITIZE_STRING);

        if (!isset($pagina)) {
            $pagina = 1;
        }
    } catch (Exception $e) {
        $erros[] = $e->getMessage();
        $_SESSION["erros"] = $erros;
    }
}
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-start">
                    <h4>Adicionar Medidas</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" title="Home" id="home_index_medidas"><i class="fas fa-home"></i>
                                    <span>Home</span></a></li>
                            <li class="breadcrumb-item"><a href="#" title="Categoria" id="medidas_index"><i class="fas fa-plus fa-beat "></i><span>Medidas</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Adicionar</li>
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
            <div class="alert alert-info alert-dismissible fade show" style="display: none;" id="div_mensagem_registro_medidas">
                <button type="button" class="btn-close btn-sm" aria-label="Close" id="div_mensagem_registro_botao_medidas"></button>
                <p id="div_mensagem_registro_texto_medidas"></p>
            </div>
            <hr>
            <div class="col-md-12">
                <form enctype="multipart/form-data" method="post" accept-charset="utf-8" id="medidas_dados" role="form" action="">
                    <ul class="nav nav-tabs" id="tab_medidas" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="dadostab_medidas" data-bs-toggle="tab" data-bs-target="#dados_medidas" type="button" role="tab" aria-controls="dados_medidas" aria-selected="true">Dados</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="tabdados_medidas">
                        <div class="tab-pane fade show active" id="dados_medidas" role="tabpanel" aria-labelledby="dados_medidas">
                            <div class="col-md-6">
                                <label for="peso" class="form-label">peso</label>
                                <input type="text" class="form-control" id="peso_medidas" name="peso_medidas" maxlength="50"  >
                            </div>
                            <div class="col-md-6">
                                <label for="altura" class="form-label">altura</label>
                                <input type="text" class="form-control" id="altura_medidas" name="altura_medidas" maxlength="50" >
                            </div>
                            <div class="col-md-6">
                                <label for="data_C" class="form-label">Data de medida</label>
                                <input type="date" class="form-control" id="data_medidas" name="data_medidas" maxlength="50" >
                            </div>
                   
                            <input type="hidden" id="id_medida" value="<?php echo isset($id) ? $id : '' ?>" />
                            <input type="hidden" id="usuario_id_medida" name="usuario_id_medida" value="<?php echo isset($usuario_id) ? $usuario_id : '' ?>" />
                        </div>
                    </div>
                    <br>
                    <div>
                        <button type="button" class="btn btn-primary" id="botao_salvar_medidas">Salvar</button>
                        <button type="reset" class="btn btn-secondary" id="botao_limpar_medidas">Limpar</button>
                    </div>
                </form>
            </div>
            <div>
                <input type="hidden" id="pagina_medidas" name="pagina_medidas" value="<?php echo isset($pagina) ? $pagina : '' ?>" />
                <input type="hidden" id="texto_busca_medidas" name="texto_busca_medidas" value="<?php echo isset($texto_busca) ? $texto_busca : '' ?>" />
            </div>
        </div>
    </div>
</div>

<!--modal de salvar-->
<div class="modal fade" id="modal_salvar_medidas" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutlabel_medidas">Pergunta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja salvar o registro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal_salvar_sim_medidas">Sim</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
     //devido ao load precisa carregar o arquivo js dessa forma
    var url = "./js/sistema/medidas.js";
    $.getScript(url);
</script>