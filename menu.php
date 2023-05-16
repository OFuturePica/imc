<?php
require_once("valida_acesso.php");
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <title>Menu</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="./css/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="./css/fontawesome/fontawesome.min.css" rel="stylesheet">
  <link href="./css/fontawesome/brands.min.css" rel="stylesheet">
  <link href="./css/fontawesome/solid.min.css" rel="stylesheet">
  <link href="./css/datatables/datatables.min.css" rel="stylesheet">
  <link href="./css/sistema/menu.css" rel="stylesheet">
</head>

<body id="body">
  <header class="header" id="header">
    <div class="header_toggle">
      <i class="fas fa-bars" id="header-toggle"></i>
    </div>
    <div class="header_user">
      <?php
      if (isset($_SESSION["usuario"])) {
        echo "<h6>" . $_SESSION["usuario"] . "</h6>";
      }
      ?>

    </div>
  </header>
  <div class="l-navbar" id="nav-bar">
    <nav class="navmenu">
      <div>
        <a class="nav_logo" title="Home" id="home_link"> 
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-activity" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z"/>
         </svg> 
         <span class="nav_logo-name">imc-master</span>
        </a>
        <div class="nav_list">
          <a href="#" class="nav_link" title="Categoria" id="categoria_link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
            <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9H1.475Z"/>
           <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.88Z"/>
           </svg> 
          <span class="nav_name">medidas</span>
          </a>
          <a class="nav_link" title="Usuário" id="usuario_link">
            <i class="fas fa-user-cog nav_icon"></i>
            <span class="nav_name">Usuário</span>
          </a>
          <a class="nav_link" title="Sobre" id="sobre_link">
            <i class="fas fa-question-circle nav_icon"></i>
            <span class="nav_name">Sobre</span>
          </a>
        </div>
      </div>
      <a href="#" class="nav_link" id="logout_link" title="Logout"> <i class="fas fa-sign-out-alt nav_icon"></i>
        <span class="nav_name">Sair</span>
      </a>
    </nav>
  </div>
  <!--div main-->
  <div class="height-10" id="conteudo">
    <br>
    <div class="container">
      <div class="row">
        <div id="carregando_menu" class="d-none text-center">
          <img src="./imagens/carregando.gif" />
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4 d-flex justify-content-start">
              <h4>Dashboard</h4>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
            </div>
            <div class="col-md-4 d-flex justify-content-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-home"></i>
                    <span>Home</span></a>
                  </li>
                </ol>
              </nav>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-4 d-flex justify-content-start">
            </div>
            <div class="col-md-4 d-flex justify-content-center">
              <select name="ano" id="ano">
                <?php
                for ($i = 0; $i < 10; $i++) {
                  if ($i == 0) {
                    echo "<option value='2021'>2021</option>";
                  } else {
                    $conta = 2021 + $i;
                    echo "<option value='$conta'>$conta</option>";
                  }
                }
                ?>
              </select>
              <a id="botao_pesquisar_grafico" class="btn btn-primary btn-sm" title="Pesquisar"><i class="fas fa-search"></i>&nbsp;Pesquisar</a>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
              <input type="hidden" id="usuario_id_menu" name="usuario_id_menu" value="<?php echo isset($_SESSION["usuario_id"]) ?  $_SESSION["usuario_id"] : 0; ?>" />
            </div>
          </div>
          <hr>
        </div>
        <div class="alert alert-info alert-dismissible fade show" style="display: none;" id="div_mensagem_menu">
          <button type="button" class="btn-close btn-sm" aria-label="Close" id="div_mensagem_botao_menu"></button>
          <p id="div_mensagem_texto_menu"></p>
        </div>
        <div id="div_grafico" class="col-md-12" style="height:400px;">
          <canvas id="grafico"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!--fim div main-->

  <!--modal de sobre-->
  <div class="modal fade" id="sobre_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutlabel">Informação</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>imc-master - Sistema de controle do imc para </p>
          <p>Desenvolvido por Kaue Marlon Pavanello e Nicollas Cauã Todt  - Desde 2023–<script>
              document.write(new Date().getFullYear())
            </script>
          </p>
      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>

  <!--modal de logout-->
  <div class="modal fade" id="logout_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutlabel">Pergunta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Deseja sair do sistema?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="logout_modal_sim">Sim</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <script src="./js/jquery/jquery.min.js"></script>
  <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="./js/datatables/datatables.min.js"></script>
  <script src="./js/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="./js/inputmask/jquery.inputmask.js"></script>
  <script src="./js/chartjs/chart.min.js"></script>
  <script src="./js/sistema/menu.js"></script>
</body>

</html>