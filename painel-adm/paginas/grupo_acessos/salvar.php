<?php
session_start();
$tabela = 'usuarios';
require_once("../../../conexao.php");

// Verifica se está logado
if (!isset($_SESSION['id_user'])) {
    echo "Acesso negado!";
    exit;
}

// Recebe os dados do formulário
$id          = @$_POST['id'];
$nome        = $_POST['nome'];
$email       = $_POST['email'];
$cpf         = $_POST['cpf'];
$telefone    = $_POST['telefone'];
$cep         = $_POST['cep'];
$rua         = $_POST['rua'];
$numero      = $_POST['numero'];
$bairro      = $_POST['bairro'];
$cidade      = $_POST['cidade'];
$estado      = $_POST['estado'];
$senha       = trim($_POST['senha'] ?? '');
$conf_senha  = trim($_POST['conf-senha'] ?? '');
$nivel       = $_POST['nivel'];
$ativo       = $_POST['ativo'];

// Validações básicas
if (empty($nome) || empty($email) || empty($cpf) || empty($nivel)) {
    echo "Preencha os campos obrigatórios!";
    exit;
}

// Validação de senha: obrigatória apenas para INSERT (novo usuário)
$senha_hash = null;
if (empty($id) || $id == 0) {
    // INSERT: senha é obrigatória
    if (empty($senha) || empty($conf_senha)) {
        echo "Preencha os campos de senha!";
        exit;
    }
    if ($senha !== $conf_senha) {
        echo "As senhas não conferem!";
        exit;
    }
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
} else {
    // UPDATE: senha é opcional, só valida se pelo menos um campo foi preenchido
    if (!empty($senha) || !empty($conf_senha)) {
        if (empty($senha) || empty($conf_senha)) {
            echo "Preencha ambos os campos de senha!";
            exit;
        }
        if ($senha !== $conf_senha) {
            echo "As senhas não conferem!";
            exit;
        }
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    }
    // Se nenhum campo de senha foi preenchido, $senha_hash permanece null
    // e no UPDATE a senha antiga será mantida
}

// Validação de email único
$email_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE email = :email");
$email_buscado->bindValue(":email", $email);
$email_buscado->execute();
$resultado_email = $email_buscado->fetch(PDO::FETCH_ASSOC);
if ($resultado_email && $resultado_email['id'] != $id) {
    echo "Este email já está cadastrado!";
    exit;
}

// Validação de telefone único
$telefone_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE telefone = :telefone");
$telefone_buscado->bindValue(":telefone", $telefone);
$telefone_buscado->execute();
$resultado_telefone = $telefone_buscado->fetch(PDO::FETCH_ASSOC);
if ($resultado_telefone && $resultado_telefone['id'] != $id) {
    echo "Este telefone já está cadastrado!";
    exit;
}

// Validação de CPF único
$cpf_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE cpf = :cpf");
$cpf_buscado->bindValue(":cpf", $cpf);
$cpf_buscado->execute();
$resultado_cpf = $cpf_buscado->fetch(PDO::FETCH_ASSOC);
if ($resultado_cpf && $resultado_cpf['id'] != $id) {
    echo "Este CPF já está cadastrado!";
    exit;
}

// Upload da foto (se enviado)
$foto_nome = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK && !empty($_FILES['foto']['name'])) {
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $nome_arquivo = $_FILES['foto']['name'];
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    if (in_array($extensao, $extensoes_permitidas)) {
        // ✅ Usa o ID do usuário como nome da foto (sempre o mesmo!)
        $foto_nome = 'usuario_' . $id . '.' . $extensao;

        // Caminho absoluto usando __DIR__
        $pasta_destino = realpath(__DIR__ . '/../../images/perfil/');

        if ($pasta_destino && is_dir($pasta_destino)) {
            $caminho_destino = $pasta_destino . DIRECTORY_SEPARATOR . $foto_nome;

            // ✅ Move e sobrescreve automaticamente se já existir
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_destino)) {
                // Foto salva com sucesso (antiga foi sobrescrita)
            } else {
                error_log("Erro ao mover arquivo para: " . $caminho_destino);
                echo "Erro ao salvar a foto!";
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

        if ($senha_hash !== null) {
            // Atualiza com nova senha
            if (!empty($foto_nome)) {
                // Com foto nova
                $query = $pdo->prepare("UPDATE $tabela SET 
                    nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
                    cep = :cep, rua = :rua, numero = :numero, bairro = :bairro,
                    cidade = :cidade, estado = :estado, senha = :senha,
                    cargo = :cargo, ativo = :ativo, foto = :foto 
                    WHERE id = :id");
                $query->bindValue(":foto", "$foto_nome");
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
        } else {
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
