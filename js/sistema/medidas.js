$(document).ready(function () {
	//configurando a tabela de dados listados
	$("#lista_medidas").DataTable({
		columnDefs: [{
			targets: [2],
			orderable: false
		}],
		destroy: true,
		info: false,
		language: {
			decimal: ",",
			thousands: "."
		},
		order: [
			[0, "asc"]
		],
		ordering: true,
		paging: false,
		searching: false
	});

	//configurando validação dos dados digitados no cadastro/edição
	$("#medidas_dados").validate({
		rules: {
			peso_medidas: {
				required: true
			},
			altura_medidas: {
				required: true
			},
			data_medidas: {
				required: true
			}
		},
		highlight: function (element) {
			$(element).addClass("is-invalid");
		},
		unhighlight: function (element) {
			$(element).removeClass("is-invalid");
		},
		errorElement: "div",
		errorClass: "invalid-feedback",
		errorPlacement: function (error, element) {
			if (element.parent(".input-group-prepend").length) {
				$(element).siblings(".invalid-feedback").append(error);
			} else {
				error.insertAfter(element);
			}
		},
		messages: {
			peso_medidas: {
				required: "Este campo não pode ser vazio!"
			},
			altura_medidas: {
				required: "Este campo não pode ser vazio!"
			},
			data_medidas: {
				required: "Este campo não pode ser vazio!",
			}
		}
	});
		/*$("#altura_medidas").inputmask("currency", {
		autoUnmask: true,
		radixPoint: ",",
		groupSeparator: ".",
		allowMinus: false,
		prefix: 'M ',
		digits: 2,
		digitsOptional: false,
		rightAlign: true,
		unmaskAsNumber: false
	});
		$("#peso_medidas").inputmask("currency", {
		autoUnmask: true,
		radixPoint: ",",
		groupSeparator: ".",
		allowMinus: false,
		prefix: 'Kg ',
		digits: 2,
		digitsOptional: false,
		rightAlign: true,
		unmaskAsNumber: false
	});*/
$("#peso_medidas").inputmask("numeric", {
	autoUnmask: true,
    radixPoint: ",",
    groupSeparator: ".",
    allowMinus: false,
    suffix: ' kg',
    digits: 2,
    digitsOptional: false,
    rightAlign: true,
    unmaskAsNumber: false
});
$("#altura_medidas").inputmask("numeric", {
	autoUnmask: true,
    radixPoint: ",",
    groupSeparator: "",
    allowMinus: false,
    suffix: ' m',
    digits: 2,
    digitsOptional: false,
    rightAlign: true,
    unmaskAsNumber: false
});


	//clicar no botão da div de erros e escondendo as mensagens de erros de validação da listagem
	$("#div_mensagem_botao_medidas").click(function () {
		$("#div_mensagem_medidas").hide();
	});

	//clicar no botão da div de erros e escondendo as mensagens de erros de validação do registro
	$("#div_mensagem_registro_botao_medidas").click(function () {
		$("#div_mensagem_registro_medidas").hide();
	});

	//voltando para a página inicial do menu do sistema
	$("#home_index_medidas").click(function () {
		$(location).prop("href", "menu.php");
	});

	//voltando para a página de listagem de medidas na mesma página onde ocorreu a chamada
	$("#medidas_index").click(function (e) {
		e.stopImmediatePropagation();

		$("#conteudo").load("medidas_index.php", {
			pagina_medidas: $("#pagina_medidas").val(),
			texto_busca_medidas: $("#texto_busca_medidas").val()
		});
	});

	//botão limpar do cadastro de informações
	$("#botao_limpar_medidas").click(function () {
		$("#nome").focus();
		$("#medidas_dados").each(function () {
			$(this).find(":input").removeClass("is-invalid");
			$(this).find(":input").removeAttr("value");
		});
	});

	//botão salvar do cadastro de informações
	$("#botao_salvar_medidas").click(function (e) {
		$("#modal_salvar_medidas").modal("show");
	});

	//botão sim da pergunta de salvar as informações de cadastro
	$("#modal_salvar_sim_medidas").click(function (e) {
		e.stopImmediatePropagation();

		if (!$("#medidas_dados").valid()) {
			$("#modal_salvar_medidas").modal("hide");
			return;
		}

		var dados = $("#medidas_dados").serializeArray().reduce(function (vetor, obj) {
			vetor[obj.name] = obj.value;
			return vetor;
		}, {});
		var operacao = null;

		$("#carregando_medidas").removeClass("d-none");

		if ($.trim($("#id_medida").val()) != "") {
			operacao = "editar";
		} else {
			operacao = "adicionar";
		}
		dados = JSON.stringify(dados);

		$.ajax({
			type: "POST",
			cache: false,
			url: "medidas_crud.php",
			data: {
				acao: operacao,
				registro: dados
			},
			dataType: "json",
			success: function (e) {
				$("#conteudo").load("medidas_index.php", {
					pagina_medidas: $("#pagina_medidas").val(),
					texto_busca_medidas: $("#texto_busca_medidas").val()
				}, function () {
					$("#div_mensagem_texto_medidas").empty().append("Categoria cadastrada!");
					$("#div_mensagem_medidas").show();
				});
			},
			error: function (e) {
				$("#div_mensagem_registro_texto_medidas").empty().append(e.responseText);
				$("#div_mensagem_registro_medidas").show();
			},
			complete: function () {
				$("#modal_salvar_medidas").modal("hide");
				$("#carregando_medidas").addClass("d-none");
			}
		});
	});

	//botão adicionar da tela de listagem de registros
	$("#botao_adicionar_medidas").click(function (e) {
		e.stopImmediatePropagation();

		//levando os elementos para tela de consulta para depois realizar as buscas/pesquisas
		var pagina = $("#pagina_medidas.btn.btn-primary.btn-sm").val();
		var texto_busca = $("#texto_busca_medidas").val();

		$("#conteudo").load("medidas_add.php", function () {
			$("#carregando_medidas").removeClass("d-none");

			$.ajax({
				type: "POST",
				cache: false,
				url: "medidas_add.php",
				data: {
					pagina_medidas: pagina,
					texto_busca_medidas: texto_busca
				},
				dataType: "html",
				success: function (e) {
					$("#conteudo").empty().append(e);
				},
				error: function (e) {
					$("#div_mensagem_texto_medidas").empty().append(e.responseText);
					$("#div_mensagem_medidas").show();
				},
				complete: function () {
					$("#carregando_medidas").addClass("d-none");
				}
			});
		});
	});

	//botão pesquisar da tela de listagem de registros
	$("#botao_pesquisar_medidas").click(function (e) {
		e.stopImmediatePropagation();

		$("#carregando_medidas").removeClass("d-none");

		$.ajax({
			type: "POST",
			cache: false,
			url: "medidas_index.php",
			data: {
				texto_busca_medidas: $("#texto_busca_medidas").val()
			},
			dataType: "html",
			success: function (e) {
				$("#conteudo").empty().append(e);
			},
			error: function (e) {
				$("#div_mensagem_texto_medidas").empty().append(e.responseText);
				$("#div_mensagem_medidas").show();
			},
			complete: function () {
				$("#carregando_medidas").addClass("d-none");
			}
		});
	});

	//botão editar da tela de listagem de registros
	$(document).on("click", "#botao_editar_medidas", function (e) {
		e.stopImmediatePropagation();
		//levando os elementos para tela de consulta para depois realizar as buscas/pesquisas
		var id = $(this).attr("chave");
		var pagina = $("#pagina_medidas.btn.btn-primary.btn-sm").val();
		var texto_busca = $("#texto_busca_medidas").val();

		$("#conteudo").load("medidas_edit.php", function () {
			$("#carregando_medidas").removeClass("d-none");

			$.ajax({
				type: "POST",
				cache: false,
				url: "medidas_edit.php",
				data: {
					id_medida: id,
					pagina_medidas: pagina,
					texto_busca_medidas: texto_busca
				},
				dataType: "html",
				success: function (e) {
					$("#conteudo").empty().append(e);
				},
				error: function (e) {
					$("#div_mensagem_texto_medidas").empty().append(e.responseText);
					$("#div_mensagem_medidas").show();
				},
				complete: function () {
					$("#carregando_medidas").addClass("d-none");
				}
			});
		});
	});

	//botão visualizar da tela de listagem de registros
	$(document).on("click", "#botao_view_medidas", function (e) {
		e.stopImmediatePropagation();
		//levando os elementos para tela de consulta para depois realizar as buscas/pesquisas
		var id = $(this).attr("chave");
		var pagina = $("#pagina_medidas.btn.btn-primary.btn-sm").val();
		var texto_busca = $("#texto_busca_medidas").val();

		$("#conteudo").load("medidas_view.php", function () {
			$("#carregando_medidas").removeClass("d-none");

			$.ajax({
				type: "POST",
				cache: false,
				url: "medidas_view.php",
				data: {
					id_medidas: id,
					pagina_medidas: pagina,
					texto_busca_medidas: texto_busca
				},
				dataType: "html",
				success: function (e) {
					$("#conteudo").empty().append(e);
				},
				error: function (e) {
					$("#div_mensagem_texto_medidas").empty().append(e.responseText);
					$("#div_mensagem_medidas").show();
				},
				complete: function () {
					$("#carregando_medidas").addClass("d-none");
				}
			});
		});
	});

	//botão paginação da tela de listagem de registros
	$(document).on("click", "#pagina_medidas", function (e) {
		//Aqui como links de botões têm o mesmo nome é necessário parar as chamadas
		e.stopImmediatePropagation();

		var texto_busca = $("#texto_busca_medidas").val();
		var pagina = $(this).val();
		$("#carregando_medidas").removeClass("d-none");

		$.ajax({
			type: "POST",
			cache: false,
			url: "medidas_index.php",
			data: {
				pagina_medidas: pagina,
				texto_busca_medidas: texto_busca
			},
			dataType: "html",
			success: function (e) {
				$("#conteudo").empty().append(e);
			},
			error: function (e) {
				$("#div_mensagem_texto_medidas").empty().append(e.responseText);
				$("#div_mensagem_medidas").show();
			},
			complete: function () {
				$("#carregando_medidas").addClass("d-none");
				$("#texto_busca_medidas").text(texto_busca);
			}
		});
	});

	//botão excluir da tela de listagem de registros
	$(document).on("click", "#botao_excluir_medidas", function (e) {
		e.stopImmediatePropagation();

		confirmaExclusao(this);
	});

	function confirmaExclusao(registro) {
		$("#modal_excluir_medidas").modal("show");
		$("#id_excluir_medidas").val($(registro).attr("chave"));
	}

	//botão sim da pergunta de excluir de listagem de registros
	$("#modal_excluir_sim_medidas").click(function () {
		excluirRegistro();
	});

	//operação de exclusão do registro
	function excluirRegistro() {
		var registro = new Object();
		var registroJson = null;

		registro.id = $("#id_excluir_medidas").val();
		registroJson = JSON.stringify(registro);

		$.ajax({
			type: "POST",
			cache: false,
			url: "medidas_crud.php",
			data: {
				acao: "excluir",
				registro: registroJson
			},
			dataType: "json",
			success: function () {
				$("#div_mensagem_texto_medidas").empty().append("Categoria excluída!");
				$("#div_mensagem_medidas").show();
				$("tr#" + registro.id + "_medidas").remove();
			},
			error: function (e) {
				$("#div_mensagem_texto_medidas").empty().append(e.responseText);
				$("#div_mensagem_medidas").show();
			},
			complete: function () {
				$("#modal_excluir_medidas").modal("hide");
			}
		});
	}
});