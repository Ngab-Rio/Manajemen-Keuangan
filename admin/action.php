<?php

include "../config/koneksi.php";
include "../config/session.php";

// UPDATE DATA
if (isset($_POST['update_data'])) {
    $id = $_POST['id'];
    $id_transaction = $_POST['id_transaction'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = str_replace("T", " ", $_POST['date']) . ":00"; // Convert format to MySQL DATETIME
    $description = $_POST['description'];

    $query_update_data = "UPDATE transactions 
                          SET type='$type', category='$category', amount='$amount', date='$date', description='$description' 
                          WHERE id='$id'";

    $sql = mysqli_query($conn, $query_update_data);

    if ($sql) {
        header("Location: transactions.php?status=success_update");
        logActivity($conn, 1, "update", "Mengubah data dengan ID : ". $id_transaction);
        exit();
    } else {
        header("Location: transactions.php?status=error");
        exit();
    }
}


// DELETE DATA
if (isset($_POST['delete_data'])) {
    $id = $_POST['id'];

    $query = mysqli_query($conn, "DELETE FROM transactions WHERE id='$id'");

    header("Location: transactions.php?status=" . ($query ? "success_delete" : "error"));
    logActivity($conn, 1, "delete", "Menghapus data dengan ID : ". $id_transaction);
    exit();
}

?>