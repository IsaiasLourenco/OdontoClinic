<?php

    date_default_timezone_set('America/Sao_Paulo');

    $servidor = 'localhost';
    $banco = 'odontoclinic';
    $usuario = 'root';
    $senha = '';

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8mb4", "$usuario", "$senha");
    } catch (Exception $e) {
        echo "Erro ao conectar ao banco de dados!<br>" . $e;
    }

    $nome_sistema = 'FG Odontologia e Estética';
    $email_sistema = 'o.implantare@gmail.com';
    
?>