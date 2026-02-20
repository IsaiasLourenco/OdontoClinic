<?php
session_start();
require_once("conexao.php");
$email = $_POST['email'];
$senha = $_POST['senha'];

$query  = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
$query->bindValue(":email", "$email");
$query->bindValue(":senha", "$senha");
$query->execute();
$res    = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = count($res);
if ($linhas > 0) {
    echo "Logado!";
} else {
    $conta_usuarios = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios");
    $conta_usuarios->execute();
    $total_usuarios = $conta_usuarios->fetch(PDO::FETCH_ASSOC)['total'];
    
    if ($total_usuarios == 0) {
        $verifica_cargo = $pdo->prepare("SELECT id FROM cargos WHERE nome = :nome LIMIT 1");    
        $verifica_cargo->bindValue(":nome", "Administrador");
        $verifica_cargo->execute();
        $cargo_existente = $verifica_cargo->fetch(PDO::FETCH_ASSOC);

        if ($cargo_existente) {
            $cargo_fake = $cargo_existente['id'];
        } else {
            $insere_cargo = $pdo->prepare("INSERT INTO cargos (nome) VALUES (:nome)");
            $insere_cargo->bindValue(":nome", "Administrador");
            $insere_cargo->execute();

            $cargo_fake = $pdo->lastInsertId();
        }

        $nome_fake   = "Usuário";
        $email_fake  = "usuario@email.com";
        $senha_fake  = "123";
        $telefone_fake = "(19) 99999-9999";
        $cep_fake    = "13843-184";
        $rua_fake    = "Mococa";
        $numero_fake = "880";
        $bairro_fake = "Itacolomy";
        $cidade_fake = "Mogi Guaçu";
        $estado_fake = "SP";
        $foto_fake   = " ";
        $ativo_fake  = 1;

        $insert = $pdo->prepare("INSERT INTO usuarios 
                (nome, email, senha, cargo, telefone, cep, rua, numero, bairro, cidade, estado, foto, ativo, data_criacao) 
                VALUES 
                (:nome, :email, :senha, :cargo, :telefone, :cep, :rua, :numero, :bairro, :cidade, :estado, :foto, :ativo, NOW())");

        $insert->bindValue(":nome", "$nome_fake");
        $insert->bindValue(":email", "$email_fake");
        $insert->bindValue(":senha", "$senha_fake");
        $insert->bindValue(":cargo", "$cargo_fake", PDO::PARAM_INT); // ID dinâmico do Administrador
        $insert->bindValue(":telefone", "$telefone_fake");
        $insert->bindValue(":cep", "$cep_fake");
        $insert->bindValue(":rua", "$rua_fake");
        $insert->bindValue(":numero", "$numero_fake");
        $insert->bindValue(":bairro", "$bairro_fake");
        $insert->bindValue(":cidade", "$cidade_fake");
        $insert->bindValue(":estado", "$estado_fake");
        $insert->bindValue(":foto", "$foto_fake");
        $insert->bindValue(":ativo", "$ativo_fake", PDO::PARAM_INT);
        $insert->execute();

        echo "Banco vazio! Usuário de teste criado automaticamente.<br>";
        echo "Use: <strong>usuario@email.com</strong> e senha <strong>123</strong> para acessar.<br>";
        echo "Cargo vinculado: <strong>Administrador</strong> (ID: $cargo_fake)<br><br>";
        echo '<a href="index.php">Voltar para o login</a>';
    } else {
        echo '<script>window.alert("Dados incorretos!")</script>';
        echo '<script>window.location="index.php"</script>';
    }
}
?>