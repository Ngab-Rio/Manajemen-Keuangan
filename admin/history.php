<?php

include "../config/koneksi.php";
include "../config/session.php";
// READ DATA
function read_data_transactions($conn) {
    $query_read = "SELECT * FROM logs ORDER BY id DESC";
    return mysqli_query($conn, $query_read);
}

$sql_read_transaction = read_data_transactions($conn);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


</head>
<body>
    <div class="container mt-5">
        <h2>Daftar History</h2>

        <!-- Toast Notifikasi -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="deleteToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Transaksi berhasil dihapus!
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Gagal menghapus transaksi!
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <a href="/MANAJEMEN-KEUANGAN/admin/export_excel_history.php" class="btn btn-success mb-3">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </a>

        <!-- Tabel Transaksi dengan Desain Lebih Bagus -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data_transactions = mysqli_fetch_array($sql_read_transaction)) { ?>
                    <tr>
                        <td class="text-center"><?php echo date("d M Y, H:i", strtotime($data_transactions['timestamp'])); ?></td>
                        <td class="text-center">
                            <?php if ($data_transactions['action'] == 'create') { ?>
                                <span class="badge bg-success">Tambah</span>
                            <?php } elseif ($data_transactions['action'] == 'delete'){ ?>
                                <span class="badge bg-danger">Hapus</span>
                            <?php } else {?>
                                <span class="badge bg-primary">Update</span>
                            <?php } ?>
                        </td>
                        <td class="text-center"><?php echo htmlspecialchars($data_transactions['description']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            document.getElementById("deleteConfirmBtn").href = "?confirm_delete_id=" + id;
            var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }

        // Tampilkan notifikasi jika ada status penghapusan
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('deleted')) {
                var toast = new bootstrap.Toast(document.getElementById('deleteToast'));
                toast.show();

                // Hapus parameter `deleted` dari URL setelah toast muncul
                setTimeout(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 2000);
            }
        };
    </script>
</body>
</html>
