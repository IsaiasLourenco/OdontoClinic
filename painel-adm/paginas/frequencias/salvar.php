<?php
session_start();
$tabela = 'frequencias';
require_once("../../../conexao.php");

// Verifica se está logado
if (!isset($_SESSION['id_user'])) {
    echo "Acesso negado!";
    exit;
}

// Recebe os dados do formulário
$id          = $_POST['id'] ?? 0;
$frequencia  = $_POST['frequencia'] ?? '';
$dias        = $_POST['dias'] ?? 0;

// Validações básicas
if (empty($frequencia)) {
    echo "Preencha os campos obrigatórios!";
    exit;
}

// Validação: nome único (exceto o próprio registro)
if (!empty($id) && $id != 0) {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE frequencia = :frequencia AND id != :id");
    $nome_buscado->bindValue(":frequencia", $frequencia);
    $nome_buscado->bindValue(":id", $id, PDO::PARAM_INT);
} else {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE frequencia = :frequencia");
    $nome_buscado->bindValue(":frequencia", $frequencia);
}
$nome_buscado->execute();
if ($nome_buscado->rowCount() > 0) {
    echo "Esta Frequência já está cadastrada!";
    exit;
}

try {
    if (!empty($id) && $id != 0) {
        // UPDATE - Atualizar registro existente
        $query = $pdo->prepare("UPDATE $tabela SET frequencia = :frequencia, dias = :dias WHERE id = :id");
        $query->bindValue(":frequencia", "$frequencia");
        $query->bindValue(":dias", "$dias");
        $query->bindValue(":id", "$id", PDO::PARAM_INT);
    } else {
        // INSERT - Cadastrar novo registro
        $query = $pdo->prepare("INSERT INTO $tabela (frequencia, dias) VALUES (:frequencia, :dias)");
        $query->bindValue(":frequencia", "$frequencia");
        $query->bindValue(":dias", "$dias");
    }
    
    $query->execute();
    
    echo "Salvo com Sucesso";
    
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
?>