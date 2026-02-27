<?php
require_once("../../../conexao.php");
$tabela = 'grupo_acessos';

$id = $_POST['id'];

$acessos = $pdo->query("SELECT * FROM acessos WHERE grupo = '$id'");
$result = $acessos->fetchAll(PDO::FETCH_ASSOC);
$total_acessos = count($result);
if ($total_acessos > 0) {
    echo 'Existem acessos vinculados a este grupo. Exclua os acessos primeiro.';
    exit;
}

$pdo->query("DELETE FROM $tabela WHERE id = '$id'");
echo 'Excluído com Sucesso';
