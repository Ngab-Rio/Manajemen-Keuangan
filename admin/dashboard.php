<?php

include "../config/koneksi.php";
include "../config/session.php";


function saldo_akhir(){
    global $conn;
    $query = "SELECT (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE type = 'income') - (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE type = 'expense') AS saldo_akhir;";  
    $result_saldo = mysqli_query($conn, $query);
    $row_saldo = mysqli_fetch_array($result_saldo);
    return($row_saldo['saldo_akhir']);
}

function total_pemasukan(){
    global $conn;
    $query = "SELECT (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE type = 'income') AS total_pemasukan;";
    $hasil_income = mysqli_query($conn, $query);
    $row_income = mysqli_fetch_array($hasil_income);
    return($row_income['total_pemasukan']);
}

function total_pengeluaran(){
    global $conn;
    $query = "SELECT (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE type = 'expense') AS total_pengeluaran;";
    $hasil_expense = mysqli_query($conn, $query);
    $row_expense = mysqli_fetch_array($hasil_expense);
    return($row_expense['total_pengeluaran']);
}


$saldo_akhir = saldo_akhir();
$total_income = total_pemasukan();
$total_expense = total_pengeluaran();

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Catatan Keuangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Catatan Keuangan</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h2 class="text-center">Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pemasukan</h5>
                        <p class="card-text">Rp <?php echo number_format($total_income, 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pengeluaran</h5>
                        <p class="card-text">Rp <?php echo number_format($total_expense, 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Saldo Akhir</h5>
                        <p class="card-text">Rp <?php echo number_format($saldo_akhir, 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <a href="add_transaction.php" class="btn btn-success">Tambah Transaksi</a>
        <a href="transactions.php" class="btn btn-primary">Lihat Transaksi</a>
        <a href="history.php" class="btn btn-dark">Aktivitas User</a>
    </div> 
</body>
</html>
