<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// 1. Ambil nama foto lama dari database
$stmt_select = $koneksi->prepare("SELECT bukti_foto FROM absensi_ukri WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$data = $result->fetch_assoc();
$stmt_select->close();

$nama_foto = $data['bukti_foto'] ?? null;

// 2. Hapus file fisik jika ada
if ($nama_foto && file_exists("uploads/" . $nama_foto)) {
    unlink("uploads/" . $nama_foto);
}

// 3. Hapus data dari database
$stmt_delete = $koneksi->prepare("DELETE FROM absensi_ukri WHERE id = ?");
$stmt_delete->bind_param("i", $id);

if ($stmt_delete->execute()) {
    header("Location: index.php?status=hapussukses");
} else {
    echo "Error: " . $stmt_delete->error;
}

$stmt_delete->close();
$koneksi->close();
?>