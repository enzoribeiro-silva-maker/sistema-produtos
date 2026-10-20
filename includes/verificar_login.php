
<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (
    !isset($_SESSION["usuario_id"]) ||
    !isset($_SESSION["usuario_email"])
) {
    header("Location: /sistema-produtos/index.php");
    exit;
}
