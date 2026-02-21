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
    $cnpj_sistema = '45.057.703/0001-94';
    $telefone_sistema = '(19) 99574-5466';
    $telefone_fixo = '(19) 97111-0538';
    $cep_sistema = '13843-184';
    $rua_sistema = 'Mocóca';
    $numero_sistema = '880';
    $bairro_sistema = 'Loteamento Parque Itacolomy';
    $cidade_sistema = 'Mogi Guaçu';
    $estado_sistema = 'SP';
    $instagram_sistema = 'https://www.instagram.com/fgodontologiaestetica/';
    $tipo_relatorio = 'pdf';
    $contatoZap = 'Sim';
    $desenvolvedor = 'Vetor256.';
    $site_dev = 'https://vetor256.com/';
    $url_sistema = 'http://localhost/odontoclinic/';
    $chave_pix = '45057703000194';
    $tipo_chave = 'CNPJ';
?>