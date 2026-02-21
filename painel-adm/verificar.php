<?php
if (@$_SESSION['id_user'] == '') {
    echo '<script>window.alert("Você não tem permissão de acesso!!")</script>';
    echo '<script>window.location="../index.php"</script>';
    exit;
}
?>