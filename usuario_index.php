<?php
require_once("valida_acesso.php");
?>
<?php
require_once("conexao.php");

try {
    //verificando se é uma requisição post para efetuar a pesquisa específica e preparar paginação
    $texto_busca = "";
    $pagina = 1;
    $inicio = 0;
    $usuarios = [];
    $barra_paginacao = "";
    $usuario_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : 0;

    if (filter_input(INPUT_SERVER, "REQUEST_METHOD") === "POST") {
        if (isset($_POST["texto_busca_usuario"])) {
            $texto_busca = filter_input(INPUT_POST, "texto_busca_usuario", FILTER_SANITIZE_STRING);
        }
        if (isset($_POST["pagina_usuario"])) {
            $pagina = filter_input(INPUT_POST, "pagina_usuario", FILTER_VALIDATE_INT);
            $inicio = ($pagina - 1) * REGISTROS_POR_PAGINA;

            if ($inicio < 0) {
                $inicio = 0;
            }
        }
    }

    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);

    //Sql para ser consultada
    $sql = "select * from usuario where (id like :palavra or nome like :palavra or login like :palavra) and id = :id  order by id asc ";

    // Codificação da paginação
    $pre_pagina = $conexao->prepare($sql);
    $pre_pagina->bindValue(":palavra", "%" . $texto_busca . "%", PDO::PARAM_STR);
    $pre_pagina->bindValue(":id", $usuario_id, PDO::PARAM_INT);
    $pre_pagina->execute();
    $resultado_pagina = $pre_pagina->rowCount();

    if (!empty($resultado_pagina)) {
        $barra_paginacao .= "<div style='text-align:center;margin:20px 0px;'>";
        $total_paginas = ceil($resultado_pagina / REGISTROS_POR_PAGINA);
        if ($total_paginas > 1) {
            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina) {
                    $barra_paginacao .= "<input type='button' name='pagina_usuario' id='pagina_usuario' value='" . $i . "' class='btn btn-primary btn-sm' />";
                } else {
                    $barra_paginacao .= "<input type='button' name='pagina_usuario' id='pagina_usuario' value='" . $i . "' class='btn btn-secondary btn-sm' />";
                }
            }
        }
        $barra_paginacao .= "</div>";
    }

    $limite = "limit " . $inicio . ", " . REGISTROS_POR_PAGINA;

    $sql = $sql . $limite;
    $pre_registros = $conexao->prepare($sql);
    $pre_registros->bindValue(":palavra", "%" . $texto_busca . "%", PDO::PARAM_STR);
    $pre_registros->bindValue(":id", $usuario_id, PDO::PARAM_INT);
    $pre_registros->execute();
    $usuarios = $pre_registros->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $erros[] = $e->getMessage();
    $_SESSION["erros"] = $erros;
} finally {
    $conexao = null;
}
?>
<br>
<div class="container">
    <div class="row">
        <div id="carregando_usuario" class="d-none text-center">
            <img src="./imagens/carregando.gif" />
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-start">
                    <h4>Lista de Usuários</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" title="Home" id="home_index_usuario"><i class="fas fa-home"></i>
                                    <span>Home</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Usuário</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 d-flex justify-content-start">
                    <a href="#" class="btn btn-primary btn-sm" title="Adicionar" id="botao_adicionar_usuario"><i class="fas fa-plus-square"></i>&nbsp;Adicionar</a>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <input type="text" name="texto_busca" value="<?php echo $texto_busca; ?>" id="texto_busca_usuario" maxlength="25">
                    <a id="botao_pesquisar_usuario" class="btn btn-primary btn-sm" title="Pesquisar"><i class="fas fa-search"></i>&nbsp;Pesquisar</a>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-md-12">
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
            <div class="alert alert-info alert-dismissible fade show" style="display: none;" id="div_mensagem_usuario">
                <button type="button" class="btn-close btn-sm" aria-label="Close" id="div_mensagem_botao_usuario"></button>
                <p id="div_mensagem_texto_usuario"></p>
            </div>
            <?php
            if (!count($usuarios)) {
            ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    Nenhum usuário encontrado!
                </div>
            <?php
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="lista_usuario">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>A&ccedil;&otilde;es</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($usuarios as $usuario) {
                            ?>
                                <tr id="<?php echo $usuario['id']."_usuario"; ?>">
                                    <td><?php echo $usuario["id"]; ?></td>
                                    <td><?php echo $usuario["nome"]; ?></td>
                                    <td>
                                        <a id="botao_view_usuario" chave="<?php echo $usuario['id']; ?>" class="btn btn-info btn-sm" title="Visualizar"><i class="fas fa-eye"></i></a>
                                        <a id="botao_editar_usuario" chave="<?php echo $usuario['id']; ?>" class="btn btn-success btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a id="botao_excluir_usuario" chave="<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" title="Excluir"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php echo $barra_paginacao; ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<!--modal de excluir-->
<div class="modal fade" id="modal_excluir_usuario" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutlabel_usuario">Pergunta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja excluir o registro?
                <input type="hidden" id="id_excluir_usuario" value="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal_excluir_sim_usuario">Sim</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
    //devido ao load precisa carregar o arquivo js dessa forma
    var url = "./js/sistema/usuario.js";
    $.getScript(url);
</script>