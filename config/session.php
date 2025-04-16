<?php
session_start();

if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true) {
    $_SESSION["login_error"] = "Anda harus login terlebih dahulu!";
    header("Location: ../login.php"); // Redirect ke halaman login
    exit();
}
?>
