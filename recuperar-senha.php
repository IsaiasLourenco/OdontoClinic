<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("conexao.php");

$response = [
    'status' => 'error',
    'message' => 'Erro inesperado'
];

$email = $_POST['email'] ?? '';

if (empty($email)) {
    $response['message'] = 'Email não informado!';
    echo json_encode($response);
    exit;
}

$query = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
$query->bindValue(":email", $email);
$query->execute();
$res = $query->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    $response['message'] = 'Esse email não está cadastrado!';
    echo json_encode($response);
    exit;
}

// Token
$token = bin2hex(random_bytes(32));
$token_expira = date('Y-m-d H:i:s', strtotime('+24 hours'));

$upd = $pdo->prepare("
    UPDATE usuarios 
    SET token = :token, token_expira = :expira 
    WHERE email = :email
");

$upd->execute([
    ':token' => $token,
    ':expira' => $token_expira,
    ':email' => $email
]);

$base = isset($url_sistema)
    ? rtrim($url_sistema, '/')
    : 'http://' . $_SERVER['HTTP_HOST'] . '/odontoclinic';

$link = $base . '/alterar-senha.php?email=' . urlencode($email) . '&token=' . $token;

// 🔥 RESPOSTA FINAL
echo json_encode([
    'status' => 'success',
    'message' => 'Link gerado com sucesso!',
    'link' => $link
]);
exit;
