<?php

session_start();
include "config/koneksi.php";

// $loginError = "";
$loginSuccess = false;

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query_login = "SELECT * FROM users WHERE email='$email' AND password='$password';";
    $sql = $conn->query($query_login);

    if($sql->num_rows > 0){
        $data = $sql->fetch_assoc();
        $_SESSION["email"] = $data["email"];
        $_SESSION["is_login"] = true;

        $loginSuccess = true;
        echo "<script>
            setTimeout(function() {
                window.location.href = 'admin/dashboard.php';
            }, 2000);
        </script>";
        
    } else {
        $loginError = "Username atau Password Salah!!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2><i class="bi bi-person-circle"></i> Masuk</h2>

    <form method="POST">
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
    </form>
</div>

<!-- Notifikasi Toast -->
<div class="toast-container">
    <?php if (!empty($loginError)) : ?>
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-circle"></i> <?php echo $loginError; ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($loginSuccess) : ?>
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle"></i> Login berhasil! Mengalihkan...
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Menampilkan toast selama 3 detik lalu menyembunyikannya
    document.addEventListener("DOMContentLoaded", function() {
        let toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(toast => {
            let toastInstance = new bootstrap.Toast(toast);
            toastInstance.show();
            setTimeout(() => toastInstance.hide(), 2000);
        });
    });
</script>

</body>
</html>