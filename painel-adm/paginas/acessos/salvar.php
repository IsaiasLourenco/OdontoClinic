<?php
session_start();
$tabela = 'acessos';
require_once("../../../conexao.php");

// Verifica se está logado
if (!isset($_SESSION['id_user'])) {
    echo "Acesso negado!";
    exit;
}

// Recebe os dados do formulário
$id          = @$_POST['id'] ?? '';
$nome        = $_POST['nome'] ?? '';
$chave       = $_POST['chave'] ?? '';
$grupo       = $_POST['grupo'] ?? '';

// Validações básicas
if (empty($nome) || empty($chave)) {
    echo "Preencha os campos obrigatórios!";
    exit;
}

// Validação: nome único (exceto o próprio registro)
if (!empty($id) && $id != 0) {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE nome = :nome AND id != :id");
    $nome_buscado->bindValue(":nome", $nome);
    $nome_buscado->bindValue(":id", $id, PDO::PARAM_INT);
} else {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE nome = :nome");
    $nome_buscado->bindValue(":nome", $nome);
}
$nome_buscado->execute();
if ($nome_buscado->rowCount() > 0) {
    echo "Este Nome já está cadastrado!";
    exit;
}

try {
    if (!empty($id) && $id != 0) {
        // UPDATE - Atualizar registro existente
        $query = $pdo->prepare("UPDATE $tabela SET nome = :nome, chave = :chave, grupo = :grupo WHERE id = :id");
        $query->bindValue(":nome", "$nome");
        $query->bindValue(":chave", "$chave");
        $query->bindValue(":grupo", "$grupo");
        $query->bindValue(":id", "$id", PDO::PARAM_INT);
    } else {
        // INSERT - Cadastrar novo registro
        $query = $pdo->prepare("INSERT INTO $tabela (nome, chave, grupo) VALUES (:nome, :chave, :grupo)");
        $query->bindValue(":nome", "$nome");
        $query->bindValue(":chave", "$chave");
        $query->bindValue(":grupo", "$grupo");
    }
    
    $query->execute();
    
    echo "Salvo com Sucesso";
    
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
?>