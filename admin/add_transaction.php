<?php 

include "../config/koneksi.php";
include "../config/session.php";

function generateTransactionID($conn) {
    $date = date("Ymd"); // Format: 20250228 (TahunBulanTanggal)
    $query = "SELECT MAX(id) AS max_id FROM transactions";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $maxID = $row['max_id'];
    $newID = $maxID ? $maxID + 1 : 1;
    
    return "TRX-" . $date . str_pad($newID, 4, "0", STR_PAD_LEFT); 
    // Contoh hasil: TRX-202502280001
}

$alertMessage = ""; // Variabel untuk notifikasi

if (isset($_POST['save'])){
    $transaction_id = generateTransactionID($conn);
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'] . " " . date("H:i:s");
    $description = $_POST['description'];

    $query = "INSERT INTO transactions (id_transaction, type, category, amount, date, description) 
              VALUES ('$transaction_id', '$type', '$category', '$amount', '$date', '$description')";

    $sql = mysqli_query($conn, $query);

    if ($sql) {
        $alertMessage = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Transaksi berhasil ditambahkan!',
                showConfirmButton: false,
                timer: 2000
            });
            setTimeout(function(){ window.location.href='dashboard.php'; }, 2200);
        </script>";
        logActivity($conn, 1, "create", "Menambahkan data baru dengan ID : ". $transaction_id);
    } else {
        $alertMessage = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan, transaksi gagal ditambahkan.',
                showConfirmButton: false,
                timer: 2000
            });
        </script>";
    }
}




?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Transaksi</h2>
        <?= $alertMessage; ?> <!-- Menampilkan notifikasi -->

        <form method="post" action="">
            <div class="mb-3">
                <label>Jenis Transaksi</label>
                <select class="form-control" name="type" required>
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <input type="text" class="form-control" placeholder="Misal: Gaji, Makanan, Transportasi" name="category" required>
            </div>
            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" class="form-control" placeholder="Masukkan jumlah" name="amount" required>
            </div>
            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" class="form-control" name="date" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="save">Simpan</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
