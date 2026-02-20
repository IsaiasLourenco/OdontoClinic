<?php
    $servidor = 'localhost';
    $banco = 'odontoclinic';
    $usuario = 'root';
    $senha = '';

    $pdo = new PDO("mysql:dbname    = $banco;
                          host      = $servidor;
                          charset   = utf8",
                          "$usuario",
                          "$senha");
?>