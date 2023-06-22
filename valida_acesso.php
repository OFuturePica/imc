<?php
// Start session
session_start();

// Check if user is not logged in
if (!isset($_SESSION["usuario"])) {
    $erros = ["Acesso não permitido. Favor efetuar o login!"];
    $_SESSION["erros"] = $erros;
    header("HTTP/1.1 302 Found");
    header("Location: login.php");
    exit(); // Terminate script execution after redirection
}
