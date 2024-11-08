<?php
require "koneksi.php";
session_start();

if (!isset($_SESSION['login'])) {
    echo "
        <script>
            alert('Please login first');
            window.location.href = 'login.php';
        </script>";
    exit;
}

// Check if `id_lokasi` is passed in the URL
if (isset($_GET['id_lokasi'])) {
    $id_lokasi = $_GET['id_lokasi'];

    // Retrieve the image filename from the `negara` table related to the `id_lokasi`
    $query = "SELECT negara.foto FROM negara 
              JOIN cuaca ON cuaca.id_negara = negara.id 
              WHERE cuaca.id_lokasi = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_lokasi);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $foto);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the file from the img folder if it exists
    if ($foto && file_exists("img/" . $foto)) {
        unlink("img/" . $foto); // Delete the image file
    }

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete records from `cuaca` table related to `id_lokasi`
        $deleteCuaca = "DELETE FROM cuaca WHERE id_lokasi = ?";
        $stmtCuaca = mysqli_prepare($conn, $deleteCuaca);
        mysqli_stmt_bind_param($stmtCuaca, "i", $id_lokasi);
        mysqli_stmt_execute($stmtCuaca);
        mysqli_stmt_close($stmtCuaca);

        // Delete the record from `lokasi` table
        $deleteLokasi = "DELETE FROM lokasi WHERE id = ?";
        $stmtLokasi = mysqli_prepare($conn, $deleteLokasi);
        mysqli_stmt_bind_param($stmtLokasi, "i", $id_lokasi);
        mysqli_stmt_execute($stmtLokasi);
        mysqli_stmt_close($stmtLokasi);

        // Delete records from `negara` table if no `cuaca` references it
        $deleteNegara = "DELETE n FROM negara n
                         LEFT JOIN cuaca c ON c.id_negara = n.id
                         WHERE c.id_negara IS NULL";
        mysqli_query($conn, $deleteNegara);

        // Commit the transaction
        mysqli_commit($conn);

        echo "
            <script>
                alert('Data deleted successfully');
                window.location.href = 'CRUD_admin.php';
            </script>";
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        echo "
            <script>
                alert('Failed to delete data');
                window.location.href = 'CRUD_admin.php';
            </script>";
    }
} else {
    echo "
        <script>
            alert('Invalid request');
            window.location.href = 'CRUD_admin.php';
        </script>";
}
?>
