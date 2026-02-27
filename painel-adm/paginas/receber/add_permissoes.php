<?php
require_once("../../../conexao.php");

$id_usuario = @$_POST['id'] ?? '';
$permissao = @$_POST['acesso'] ?? '';

if (empty($id_usuario) || empty($permissao)) {
    echo "Erro: dados inválidos";
    exit;
}

// Verifica se já existe
$verifica = $pdo->query("SELECT id FROM permissoes WHERE usuario = '$id_usuario' AND permissao = '$permissao'");

if ($verifica->rowCount() > 0) {
    // Remove (desmarca)
    $pdo->query("DELETE FROM permissoes WHERE usuario = '$id_usuario' AND permissao = '$permissao'");
    echo "removido";
} else {
    // Insere (marca)
    $pdo->query("INSERT INTO permissoes (usuario, permissao) VALUES ('$id_usuario', '$permissao')");
    echo "inserido";
}
?>