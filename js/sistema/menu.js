$(document).ready(function() {
  // Clicar no botão da div de erros e escondendo as mensagens de erros
  $("#div_mensagem_botao_menu").click(function() {
    $("#div_mensagem_menu").hide();
  });

  $('#botao_pesquisar_grafico').click(function(e) {
    var ano = $("#ano").val();
    var id_usuario = $("#usuario_id_menu").val();

    // Regenerando o canvas para não ter erro no gráfico
    $("#div_grafico").html("");
    $("#div_grafico").append("<canvas id='grafico'></canvas>");

    $.ajax({
      type: "POST",
      cache: false,
      url: "categoria_crud.php",
      data: {
        acao: "grafico",
        ano: ano,
        usuario: id_usuario
      },
      dataType: "json",
      success: function(data) {
        var medidas = [];
        var datas = [];
        var pesos = [];
        var alturas = [];
        var imcs = [];

        $.each(data, function(i, item) {
          if (i == 0) {
            medidas = item;
          }
        });

        $.each(medidas, function(i, item) {
          datas.push(item.data);
          pesos.push(item.peso);
          alturas.push(item.altura);
          var imc = item.peso / (item.altura * item.altura);
          imcs.push(imc.toFixed(2));
        });

        var dados = {
          labels: datas,
          datasets: [

            {
              label: "IMC",
              backgroundColor: "#99ccff",
              borderColor: "#6699cc",
              hoverBackgroundColor: "#cce6ff",
              hoverBorderColor: "#99ccff",
              borderWidth: 1,
              data: imcs
            }
          ]
        };

        var grafico_canva = $("#grafico");
        var graficoBarra = new Chart(grafico_canva, {
          type: "bar",
          data: dados,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: "top"
              },
              title: {
                display: true,
                text: "Medidas de Peso, Altura e IMC - " + ano
              }
            },
            scales: {
              y: {
                display: true,
                title: {
                  display: true,
                  text: "imc",
                  color: "#000000",
                  font: {
                    weight: "bold"
                  }
                }
              },
              x: {
                display: true,
                title: {
                  display: true,
                  text: "Data de Medida",
                  color: "#000000",
                  font: {
                    weight: "bold"
                  }
                }
              }
            }
          }
        });
      },
      // Resto do código...

            error: function (e) {
                $("#div_mensagem_texto_menu").empty().append(e.responseText);
                $("#div_mensagem_menu").show();
            },
            beforeSend: function () {
                $("#carregando_menu").removeClass("d-none");
            },
            complete: function () {
                $("#carregando_menu").addClass("d-none");
            }
        });
    });

    $("#home_link").click(function () {
        $(location).prop("href", "menu.php");
    });

    $("#categoria_link").click(function (e) {
        $("#conteudo").load("categoria_index.php");
    });

    $("#contareceber_link").click(function (e) {
        $("#conteudo").load("conta_receber_index.php");
    });

    $("#usuario_link").click(function (e) {
        $("#conteudo").load("usuario_index.php");
    });

    $("#logout_modal_sim").click(function (e) {
        $(location).attr("href", "logout.php");
    });

    $("#sobre_link").click(function () {
        $("#sobre_modal").modal("show");
    });

    $("#logout_link").click(function () {
        $("#logout_modal").modal("show");
    });


    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // show navbar
                nav.classList.toggle('showtab')
                // change icon
                toggle.classList.toggle('fa-times')
                // add padding to body
                bodypd.classList.toggle('body')
                // add padding to header
                headerpd.classList.toggle('body')
            })
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body', 'header');

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('activemenu'));
            this.classList.add('activemenu');
        }
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink));

});