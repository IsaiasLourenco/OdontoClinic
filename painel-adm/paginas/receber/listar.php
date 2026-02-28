<?php
$tabela = 'receber';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {

    echo <<<HTML

	<table class="table table-hover tabela-pequena" id="tabela">

	    <thead> 

	        <tr> 

	            <th>Descrição</th>	
	            <th class="esc">Paciente</th>	    
	            <th class="esc">Data Lançamento</th>	
	            <th class="esc">Data Pagmento</th>	
	            <th class="esc">Valor</th>	
	            <th class="esc">Arquivo</th>	
	            <th>Ações</th>

	        </tr> 

	    </thead> 

	    <tbody>	

HTML;

    for ($i = 0; $i < $linhas; $i++) {
        $id                 = $res[$i]['id'];
        $descricao          = $res[$i]['descricao'];
        $paciente           = $res[$i]['paciente'];
        $valor              = $res[$i]['valor'];
        $valorF             = number_format('R$' . $valor, 2, ',', '.');
        $data_vencimento    = $res[$i]['data_vencimento'];
        $data_vencimentoF   = date('d/m/Y', strtotime($data_vencimento));
        $data_lancamento    = $res[$i]['data_lancamento'];
        $data_lancamentoF = date('d/m/Y', strtotime($data_lancamento));
        $data_pagamento     = $res[$i]['data_pagamento'];
        $data_pagamentoF    = date('d/m/Y', strtotime($data_pagamento));
        $forma_pagamento    = $res[$i]['forma_pagamento'];
        $frequencia         = $res[$i]['frequencia'];
        $obs                = $res[$i]['obs'];
        $arquivo            = $res[$i]['arquivo'];
        $referencia         = $res[$i]['referencia'];
        $id_referencia      = $res[$i]['id_referencia'];
        $multa              = $res[$i]['multa'];
        $juros              = $res[$i]['juros'];
        $desconto           = $res[$i]['desconto'];
        $subtotal           = $res[$i]['subtotal'];

        $paciente = $pdo->prepare("SELECT * FROM pacientes WHERE id = :paciente");
        $paciente->bindValue(":id", $paciente);
        $paciente->execute();
        $res_paciente = $paciente->fetchAll(PDO::FETCH_ASSOC);
        $paciente_nome = $res_paciente[0]['nome'] ?? 'Paciente Desconhecido';
        
        $consultar_nome_frequencia = $pdo->prepare("SELECT * FROM frequencias WHERE id = :frequencia");
        $consultar_nome_frequencia->bindValue(":id", $frequencia);
        $consultar_nome_frequencia->execute();
        $res_frequencia = $consultar_nome_frequencia->fetchAll(PDO::FETCH_ASSOC);
        $frequencia_nome = $res_frequencia[0]['nome'] ?? 'Frequência Desconhecida';
        
        $consultar_nome_forma = $pdo->prepare("SELECT * FROM forma_pagamento WHERE id = :forma_pagamento");
        $consultar_nome_forma->bindValue(":id", $forma_pagamento);
        $consultar_nome_forma->execute();
        $res_forma = $consultar_nome_forma->fetchAll(PDO::FETCH_ASSOC);
        $forma_pagamento_nome = $res_forma[0]['nome'] ?? 'Forma de Pagamento Desconhecida';


        echo <<<HTML
            <tr style="color:{$classe_ativo}">
                <td>
                    <input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
                        {$descricao}
                </td>
                <td class="esc">{$paciente_nome}</td>
                <td class="esc">{$data_lancamentoF}</td>
                <td class="esc">{$data_pagamentoF}</td>
                <td class="esc">{$valorF}</td>
                <td class="esc"><img src="images/receber/{$arquivo}" width="25px"></td>
                <td>
	                <a href="#" onclick="editar('{$id}',
                                                '{$descricao}',
                                                '{$paciente_nome}',
                                                '{$valorF}',
                                                '{$data_vencimentoF}',
                                                '{$data_lancamentoF}',
                                                '{$data_pagamentoF}',
                                                '{$forma_pagamento_nome}',
                                                '{$frequencia_nome}',
                                                '{$obs}',
                                                '{$arquivo}',
                                                '{$referencia}',
                                                '{$id_referencia}',
                                                '{$multa}',
                                                '{$juros}',
                                                '{$desconto}',
                                                '{$subtotal}')" title="Editar Dados">
                                                    <i class="fa fa-edit text-primary ico-grande"></i>
                    </a>

	                <li class="dropdown head-dpdn2" style="display: inline-block;">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Excluir Registro">
                            <i class="fa-solid fa-trash-can text-danger ico-grande"></i>
                        </a>

		                <ul class="dropdown-menu" style="margin-left:-230px;">

		                    <li>
		                        <div class="notification_desc2">
		                            <p>Confirmar Exclusão? 
                                        <a href="#" onclick="excluir('{$id}')">
                                            <span class="text-danger">Sim</span>
                                        </a>
                                    </p>
		                        </div>
		                    </li>										
		                </ul>
                    </li>

                    <a href="#" onclick="mostrar('{$descricao}',
                                                 '{$paciente_nome}',
                                                 '{$valorF}',
                                                 '{$data_vencimentoF}',
                                                 '{$data_lancamentoF}',
                                                 '{$data_pagamentoF}',
                                                 '{$forma_pagamento_nome}',
                                                 '{$frequencia_nome}',
                                                 '{$obs}',
                                                 '{$arquivo}',
                                                 '{$referencia}',
                                                 '{$id_referencia}',
                                                 '{$multa}',
                                                 '{$juros}',
                                                 '{$desconto}',
                                                 '{$subtotal}')" title="Mostrar Dados">
                                                    <i class="fa fa-info-circle text-dark ico-grande"></i>
                    </a>

                    <a href="#" onclick="ativar('{$id}', 
                                                '{$acao}')" title="{$titulo_link}">
                                                    <i class="fa {$icone} text-success ico-grande"></i>
                    </a>

                    <a class="{$mostrar_adm}" href="#" onclick="permissoes('{$id}', 
                                                                           '{$descricao}')" title="Dar Permissões">
                                                                                    <i class="fa fa-lock text-success ico-grande"></i>
                    </a>

                </td>
            </tr>
HTML;
    }
    echo <<<HTML
        </tbody>
        <div class="centro-pequeno" id="mensagem-excluir"></div>
    </table>
HTML;
} else {
    echo 'Nenhum Registro Encontrado!';
}
?>

<script>
    $(document).ready(function() {
        $('#btn-deletar').hide();
        var table = $('#tabela').DataTable({
            "ordering": false,
            "stateSave": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            },
            "columnDefs": [{
                "className": "dt-center",
                "targets": "_all"
            }]
        });

        // ✅ Aplica a classe .tabela-pequena no wrapper do DataTables
        $('#tabela_wrapper').addClass('tabela-pequena');
    });
</script>
'{$descricao}',
                                                 '{$paciente_nome}',
                                                 '{$valorF}',
                                                 '{$data_vencimentoF}',
                                                 '{$data_lancamentoF}',
                                                 '{$data_pagamentoF}',
                                                 '{$forma_pagamento_nome}',
                                                 '{$frequencia_nome}',
                                                 '{$obs}',
                                                 '{$arquivo}',
                                                 '{$referencia}',
                                                 '{$id_referencia}',
                                                 '{$multa}',
                                                 '{$juros}',
                                                 '{$desconto}',
                                                 '{$subtotal}'
<script type="text/javascript">
    function editar(id, descricao, paciente, valor, data_vencimento, data_lancamento, data_pagamento, forma_pagamento, frequencia, obs, arquivo, referencia, 
                    id_referencia, multa, juros, desconto, subtotal) {
        $('#mensagem').text('');
        $('#titulo_inserir').text('Editar Registro');

        $('#id').val(id);
        $('#descricao-perfil').val(descricao);
        $('#paciente-perfil').val(paciente);
        $('#valor-conta').val(valor);
        $('#vencimento-conta').val(data_vencimento);
        $('#lancamento-conta').val(data_lancamento);
        $('#pagamento-conta').val(data_pagamento);
        $('#forma_pagamento').val(forma_pagamento);
        $('#frequencia').val(frequencia);
        $('#obs-perfil').val(obs);
        $('#arquivo-conta').val(arquivo);
        $('#referencia-perfil').val(referencia);
        $('#id-referencia-perfil').val(id_referencia);
        $('#multa').val(multa);
        $('#juros').val(juros);
        $('#desconto').val(desconto);
        $('#subtotal').val(subtotal);

        $('#target-arquivo').attr("src", "images/receber/" + arquivo);

        // Abre o modal
        $('#modalForm').modal('show'); // Ou $('#modalPerfil').modal('show') se for o mesmo modal
    }

    function mostrar(descricao, paciente, valor, data_vencimento, data_lancamento, data_pagamento, forma_pagamento, frequencia, obs, arquivo, referencia, 
                    id_referencia, multa, juros, desconto, subtotal) {

        // Dados básicos
        $('#descricao_dados-cli').text(descricao);
        $('#paciente_dados-cli').text(paciente);
        $('#valor_dados-cli').text(valor);
        $('#vencimento_dados-cli').text(data_vencimento);
        $('#lancamento_dados-cli').text(data_lancamento);
        $('#pagamento_dados-cli').text(data_pagamento);
        $('#forma_pagamento_dados-cli').text(forma_pagamento);
        $('#frequencia_dados-cli').text(frequencia);
        $('#obs_dados-cli').text(obs);
        $('#referencia_dados-cli').text(referencia);
        $('#multa_dados-cli').text(multa);
        $('#juros_dados-cli').text(juros);
        $('#desconto_dados-cli').text(desconto);
        $('#subtotal_dados-cli').text(subtotal);

        // Arquivo
        if (arquivo && arquivo !== 'sem-arquivo.jpg') {
            $('#target-arquivo-dados').attr('src', 'images/receber/' + arquivo);
            $('#link-arquivo-dados').attr('href', 'images/receber/' + arquivo).show();
        } else {
            $('#target_arquivo').attr('src', 'images/receber/sem-arquivo.jpg');
            $('#link-arquivo-dados').hide();
        }

        // Foto (opcional, se quiser exibir)
        // ✅ Atualiza a foto
        if (arquivo && arquivo !== 'sem-foto.png') {
            $('#target_arquivo').attr('src', 'images/receber/' + arquivo);
        } else {
            $('#target_arquivo').attr('src', 'images/receber/sem-foto.jpg');
        }

        // Abre o modal CORRETO
        $('#modalDados').modal('show');
    }

    function limparCampos() {
        $('#id').val('');
        $('#descricao-perfil').val('');
        $('#paciente-perfil').val('');
        $('#valor-conta').val('');
        $('#vencimento-conta').val('');
        $('#lancamento-conta').val('');
        $('#pagamento-conta').val('');
        $('#forma_pagamento').val('');
        $('#frequencia').val('');
        $('#obs-perfil').val('');
        $('#arquivo-conta').val('');
        $('#referencia-perfil').val('');
        $('#id-referencia-perfil').val('');
        $('#multa').val('');
        $('#juros').val('');
        $('#desconto').val('');
        $('#subtotal').val('');

        // Foto (reseta input file e preview)
        $('#arquivo-conta').val('');
        $('#target-arquivo').attr('src', 'images/receber/sem-foto.jpg');

        // Mensagem de erro/sucesso
        $('#mensagem').text('').removeClass('text-danger');
    }

    function selecionar(id) {

        var ids = $('#ids').val();

        if ($('#seletor-' + id).is(":checked") == true) {
            var novo_id = ids + id + '-';
            $('#ids').val(novo_id);
        } else {
            var retirar = ids.replace(id + '-', '');
            $('#ids').val(retirar);
        }

        var ids_final = $('#ids').val();
        if (ids_final == "") {
            $('#btn-deletar').hide();
        } else {
            $('#btn-deletar').show();
        }
    }

    function deletarSel() {
        var ids = $('#ids').val();
        var id = ids.split("-");

        for (i = 0; i < id.length - 1; i++) {
            excluir(id[i]);
        }

        limparCampos();
    }

    function permissoes(id, nome) {

        $('#id_permissoes').val(id);
        $('#nome_permissoes').text(nome);

        $('#modalPermissoes').modal('show');
        listarPermissoes(id);
    }

    function listarPermissoes(id) {
        $.ajax({
            url: 'paginas/' + pag + "/listar_permissoes.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "html",

            success: function(result) {
                $('#listar_permissoes').html(result);
                $('#mensagem_permissao').text('');
            }
        });
    }

    function adicionarPermissoes(id, acesso) {
        $.ajax({
            url: 'paginas/' + pag + "/add_permissoes.php",
            method: 'POST',
            data: {
                id: id,
                acesso: acesso
            },
            dataType: "text",
            success: function(result) {
                // Só recarrega a lista se a gravação confirmou
                if (result.trim() === 'inserido' || result.trim() === 'removido') {
                    listarPermissoes(id);
                } else {
                    console.log('Erro ao salvar permissão: ' + result);
                    $('#mensagem_permissao').addClass('text-danger').text('Erro ao salvar: ' + result);
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro AJAX: ' + error);
                $('#mensagem_permissao').addClass('text-danger').text('Erro de conexão');
            }
        });
    }''

    function marcarTodos() {
        var id_usuario = $('#id_permissoes').val();
        var marcado = $('#input_todos').is(':checked'); // ← Verifica se está marcado

        $.ajax({
            url: 'paginas/' + pag + "/add_all_permissoes.php",
            method: 'POST',
            data: {
                id: id_usuario,
                acao: marcado ? 'marcar_todos' : 'desmarcar_todos' // ← Envia a ação correta
            },
            dataType: "html",
            success: function(result) {
                listarPermissoes(id_usuario);
            }
        });
    }

    function excluir(id) {
        $.ajax({
            url: 'paginas/' + pag + "/excluir.php",
            method: 'POST',
            data: {
                id
            },
            dataType: "html",

            success: function(mensagem) {
                if (mensagem.trim() == "Excluído com Sucesso") {
                    listar();
                } else {
                    $('#mensagem-excluir').addClass('text-danger')
                    $('#mensagem-excluir').text(mensagem)
                }
            }
        });
    }
</script>