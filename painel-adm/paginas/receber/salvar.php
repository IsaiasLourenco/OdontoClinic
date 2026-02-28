<?php
session_start();
$tabela = 'receber';
require_once("../../../conexao.php");

// Verifica se está logado
if (!isset($_SESSION['id_user'])) {
    echo "Acesso negado!";
    exit;
}

// Recebe os dados do formulário
$id                 = $_POST['id'] ?? 0;
$descricao          = $_POST['descricao'] ?? '';
$paciente           = $_POST['paciente'] ?? '';
$valor              = $_POST['valor'] ?? 0;
$vencimento         = $_POST['vencimento'] ?? '';
$data_pagamento     = $_POST['pagamento'] ?? '';
$forma_pagamento    = $_POST['forma_pagamento'] ?? 0;
$frequancia         = $_POST['frequencia'] ?? 0;
$obs                = $_POST['obs'] ?? '';

if ($data_pagamento === '') {
    $pagamento = '';
} else {
    $pagamento = " , data_pagamento = '$data_pagamento'";
}

// Validações básicas
if (empty($descricao) || empty($paciente) || empty($valor) || empty($vencimento)) {
    echo "Preencha os campos obrigatórios!";
    exit;
}

// Upload da foto (se enviado)
$arquivo_nome = '';
if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK && !empty($_FILES['arquivo']['name'])) {
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $nome_arquivo = $_FILES['arquivo']['name'];
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    if (in_array($extensao, $extensoes_permitidas)) {
        // ✅ Usa o ID do usuário como nome da foto (sempre o mesmo!)
        $arquivo_nome = 'usuario_' . $id . '.' . $extensao;

        // Caminho absoluto usando __DIR__
        $pasta_destino = realpath(__DIR__ . '/../../images/receber/');

        if ($pasta_destino && is_dir($pasta_destino)) {
            $caminho_destino = $pasta_destino . DIRECTORY_SEPARATOR . $arquivo_nome;

            // ✅ Move e sobrescreve automaticamente se já existir
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_destino)) {
                // Foto salva com sucesso (antiga foi sobrescrita)
            } else {
                error_log("Erro ao mover arquivo para: " . $caminho_destino);
                echo "Erro ao salvar o arquivo!";
                exit;
            }
        } else {
            echo "Pasta de upload não encontrada!";
            exit;
        }
    } else {
        echo "Formato de imagem não permitido!";
        exit;
    }
}

try {
    if (!empty($id) && $id != 0) {
        // UPDATE - Atualizar usuário existente

        
        // Atualiza com nova senha
            if (!empty($arquivo_nome)) {
                // Com foto nova
                $query = $pdo->prepare("UPDATE $tabela SET 
                    descricao = :descricao, paciente = :paciente, valor = :valor, data_vencimento = 'vencimento' $data_pagamento, 
                    data_lancamento = curDate(), forma_pagemento = :forma_pagamento, frequencia = :frequencia, obs = :obs, arquivo = :arquivo 
                    WHERE id = :id");
                $query->bindValue(":arquivo", "$arquivo_nome");
            } else {
                // Sem foto nova
                $query = $pdo->prepare("UPDATE $tabela SET 
                    nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
                    cep = :cep, rua = :rua, numero = :numero, bairro = :bairro,
                    cidade = :cidade, estado = :estado, senha = :senha,
                    cargo = :cargo, ativo = :ativo 
                    WHERE id = :id");
            }
            $query->bindValue(":senha", "$senha_hash");
        
            // Atualiza sem alterar senha
            if (!empty($foto_nome)) {
                $query = $pdo->prepare("UPDATE $tabela SET 
                    nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
                    cep = :cep, rua = :rua, numero = :numero, bairro = :bairro,
                    cidade = :cidade, estado = :estado,
                    cargo = :cargo, ativo = :ativo, foto = :foto 
                    WHERE id = :id");
                $query->bindValue(":foto", "$foto_nome");
            } else {
                $query = $pdo->prepare("UPDATE $tabela SET 
                    nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
                    cep = :cep, rua = :rua, numero = :numero, bairro = :bairro,
                    cidade = :cidade, estado = :estado,
                    cargo = :cargo, ativo = :ativo 
                    WHERE id = :id");
            }
        

        $query->bindValue(":id", "$id", PDO::PARAM_INT);
    } else {
        // INSERT - Cadastrar novo usuário

        // Define foto padrão se não foi enviada
        if (empty($foto_nome)) {
            $foto_nome = "sem-foto.jpg";
        }

        $query = $pdo->prepare("INSERT INTO $tabela 
            (nome, email, senha, cpf, telefone, cep, rua, numero, bairro, cidade, estado, cargo, ativo, foto, data_criacao) 
            VALUES 
            (:nome, :email, :senha, :cpf, :telefone, :cep, :rua, :numero, :bairro, :cidade, :estado, :cargo, :ativo, :foto, NOW())");

        $query->bindValue(":senha", "$senha_hash");
        $query->bindValue(":foto", "$foto_nome");
    }

    // Bind dos campos comuns
    $query->bindValue(":nome", "$nome");
    $query->bindValue(":email", "$email");
    $query->bindValue(":cpf", "$cpf");
    $query->bindValue(":telefone", "$telefone");
    $query->bindValue(":cep", "$cep");
    $query->bindValue(":rua", "$rua");
    $query->bindValue(":numero", "$numero");
    $query->bindValue(":bairro", "$bairro");
    $query->bindValue(":cidade", "$cidade");
    $query->bindValue(":estado", "$estado");
    $query->bindValue(":cargo", "$nivel", PDO::PARAM_INT);
    $query->bindValue(":ativo", "$ativo");

    $query->execute();

    echo "Salvo com Sucesso";
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
