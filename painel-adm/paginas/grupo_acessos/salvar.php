<?php
session_start();
$tabela = 'grupo_acessos';
require_once("../../../conexao.php");

if (!isset($_SESSION['id_user'])) { echo "Acesso negado!"; exit; }

// Recebe os dados (com fallback para vazio)
$id          = @$_POST['id'] ?? '';
$nome_grupo  = $_POST['nome'] ?? '';

if (empty($nome_grupo)) {
    echo "Preencha o nome do grupo!";
    exit;
}

// Validação: nome único (exceto o próprio registro)
if (!empty($id) && $id != 0) {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE nome_grupo = :nome AND id != :id");
    $nome_buscado->bindValue(":nome", $nome_grupo);
    $nome_buscado->bindValue(":id", $id, PDO::PARAM_INT);
} else {
    $nome_buscado = $pdo->prepare("SELECT id FROM $tabela WHERE nome_grupo = :nome");
    $nome_buscado->bindValue(":nome", $nome_grupo);
}
$nome_buscado->execute();
if ($nome_buscado->rowCount() > 0) {
    echo "Este nome já está cadastrado!";
    exit;
}

try {
    if (!empty($id) && $id != 0) {
        // UPDATE
        $query = $pdo->prepare("UPDATE $tabela SET nome_grupo = :nome WHERE id = :id");
        $query->bindValue(":nome", $nome_grupo);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
    } else {
        // INSERT
        $query = $pdo->prepare("INSERT INTO $tabela (nome_grupo) VALUES (:nome)");
        $query->bindValue(":nome", $nome_grupo);
    }
    
    $query->execute();  // ← Apenas UM execute
    echo "Salvo com Sucesso";
    
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
?>