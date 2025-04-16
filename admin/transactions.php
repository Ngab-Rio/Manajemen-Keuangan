<?php

include "../config/koneksi.php";
include "../config/session.php";

$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transactions");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);


// READ DATA
function read_data_transactions($conn) {
    $query_read = "SELECT * FROM transactions ORDER BY id DESC";
    return mysqli_query($conn, $query_read);
}

$sql_read_transaction = read_data_transactions($conn);


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .form-group.gap {
            margin-right: 20px; /* Sesuaikan jarak sesuai kebutuhan */
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h2>Daftar Transaksi</h2>

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

        <a href="/MANAJEMEN-KEUANGAN/admin/export_excel_transaction.php" class="btn btn-success mb-3">
            <i class="bi bi-file-earmark-excel"></i> Download Excel
        </a>
        <div class="row mt-4">
            <div class="col">
                <form action="" class="form-inline d-flex align-items-center" method="post">
                    <div class="form-group gap mr-4"> <!-- Tambahkan margin kanan lebih besar -->
                        <input type="date" name="filter_by_date" class="form-control">
                    </div>
                    <div class="form-group gap mr-4"> <!-- Tambahkan margin kanan lebih besar -->
                        <select class="form-select" aria-label="Default select example" name="filter_by_type">
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <br>

        <!-- Tabel Transaksi dengan Desain Lebih Bagus -->
        <div class="table-responsive">  
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data_transactions = mysqli_fetch_array($sql_read_transaction)) { ?>
                    <tr>
                        <td class="text-center fw-bold"><?php echo $data_transactions['id_transaction']; ?></td>
                        <td class="text-center"><?php echo date("d M Y, H:i", strtotime($data_transactions['date'])); ?></td>
                        <td class="text-center">
                            <?php if ($data_transactions['type'] == 'income') { ?>
                                <span class="badge bg-success">Pemasukan</span>
                            <?php } else { ?>
                                <span class="badge bg-danger">Pengeluaran</span>
                            <?php } ?>
                        </td>
                        <td class="text-center"><?php echo htmlspecialchars($data_transactions['category']); ?></td>
                        <td class="text-end fw-bold">Rp <?php echo number_format($data_transactions['amount'], 0, ',', '.'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($data_transactions['description']); ?></td>
                        <td class="text-center">
                                <!-- Button trigger modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateData<?= $data_transactions['id'];?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteData<?= $data_transactions['id'];?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Modal Update Data-->
                        <div class="modal fade" id="updateData<?= $data_transactions['id'];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                                <form method="post" action="action.php">
                                    <div class="modal-body">
                                          <legend>Update Data</legend>
                                          <div class="mb-3">
                                            <input type="hidden" name="id" value="<?= $data_transactions['id']; ?>">
                                            <input type="hidden" name="id_transaction" value="<?= $data_transactions['id_transaction']; ?>">
                                            <label class="form-label">Jenis Transaksi</label>
                                            <select class="form-select" aria-label="Default select example" name="type">
                                              <option value="income" <?= $data_transactions['type'] == 'income' ? 'selected' : ''; ?>>Pemasukan</option>
                                              <option value="expense" <?= $data_transactions['type'] == 'expense' ? 'selected' : ''; ?>>Pengeluaran</option>
                                            </select>
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Kategori</label>
                                            <input type="text" class="form-control" placeholder="Misal: Gaji, Makanan, Transportasi" name="category" value="<?= $data_transactions['category']; ?>" required >
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Jumlah</label>
                                            <input type="text" class="form-control" placeholder="Masukkan Jumlah" name="amount" value="<?= $data_transactions['amount']; ?>" required >
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="datetime-local" class="form-control" name="date" value="<?= date('Y-m-d\TH:i', strtotime($data_transactions['date'])); ?>" required >
                                          </div>
                                          <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control" name="description" required><?= $data_transactions['description']; ?></textarea>
                                          </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-primary" name="update_data">Simpan Perubahan</button>
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>                    
                            </div>
                          </div>
                        </div>
                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteData<?= $data_transactions['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form action="action.php" method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <input type="hidden" name="id" value="<?= $data_transactions['id']?>">
                                        <h5 class="text-center">Yakin Ingin Menghapus Data dengan <br> ID : <?= $data_transactions['id_transaction']; ?></h5>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" class="btn btn-danger" name="delete_data">Hapus</button>
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-right">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>

        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </div>



    
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus transaksi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    </script>
        <?php if (isset($_GET['status'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($_GET['status'] == 'success_create'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php elseif ($_GET['status'] == 'success_update'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Update Berhasil!',
                    text: 'Data berhasil diperbarui!',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php elseif ($_GET['status'] == 'success_delete'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Hapus Berhasil!',
                    text: 'Data telah dihapus!',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php else: ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Terjadi kesalahan, silakan coba lagi!',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.location.search.includes("status=")) {
            // Hapus parameter 'status' dari URL tanpa reload halaman
            setTimeout(() => {
                window.history.replaceState(null, "", window.location.pathname);
            }, 2000); // Hapus setelah alert tampil (2.5 detik)
        }
    });
</script>
</body>
</html>
