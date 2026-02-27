<?php
require_once("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_sistema ?></title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <!-- jQuery (opcional, mas mantido para compatibilidade) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>

    <!-- Bootstrap 5.3.3 JS (bundle com Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/icone.ico" type="image/x-icon">
</head>

<body>
    <div class="login">
        <img src="img/Logo.png" alt="Logotipo" class="logo-img">
        <div class="form">
            <form method="POST" action="autenticar.php" class="registro">
                <input type="email" name="email" placeholder="Usuário:" class="userLogin">
                <input type="password" name="senha" placeholder="Senha:">
                <button>Login</button>
            </form>
            <p class="recuperar">
                <a title="Clique para recuperar a senha." href="" data-bs-toggle="modal" data-bs-target="#modalRecuperar">Recuperar a Senha</a>
            </p>
        </div>
    </div>
</body>

</html>

<!-- Modal Recupera Senha-->
<div class="modal fade" id="modalRecuperar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content form">
            <form method="post" id="form-recuperar">
                <div class="modal-body">
                    <input placeholder="Digite seu Email" class="form-control" type="email" name="email" id="email-recuperar" required>
                    <button type="submit">Recuperar</button>
                </div>
                <br>
                <div id="mensagem-recuperar" class="centro-pequeno"></div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal Recupera Senha-->

<!-- CDN AJAX -->
<!-- jQuery 3.7.1 (CDN com integrity) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

<!-- SCRIPT PARA RECUPERAÇÃO DE SENHA VIA AJAX -->
<script type="text/javascript">
    $("#form-recuperar").submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "recuperar-senha.php",
            type: "POST",
            data: formData,
            dataType: "json", // 🔥 ESSENCIAL
            cache: false,
            contentType: false,
            processData: false,

            success: function(resposta) {
                $('#mensagem-recuperar').removeClass().text('');

                if (resposta.status === "success") {
                    $('#email-recuperar').val('');

                    $('#mensagem-recuperar')
                        .addClass('text-success')
                        .html(`
                        <p>${resposta.message}</p>
                        <input class="form-control mt-2" type="text" 
                               value="${resposta.link}" readonly onclick="this.select()">
                        <small class="text-muted">
                            (Em produção, esse link será enviado por e-mail)
                        </small>
                    `);
                } else {
                    $('#mensagem-recuperar')
                        .addClass('text-danger')
                        .text(resposta.message);
                }
            },

            error: function() {
                $('#mensagem-recuperar')
                    .addClass('text-danger')
                    .text('Erro ao processar a solicitação.');
            }
        });
    });
</script>