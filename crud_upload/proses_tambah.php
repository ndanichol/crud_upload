<?php
include 'koneksi.php';

// Ambil data dari form (Penyesuaian variabel $_POST)
$nama_mahasiswa = $_POST['nama_mahasiswa'];
$npm = $_POST['npm'];
$kelas = $_POST['kelas'];
$status_kehadiran = $_POST['status_kehadiran'];

$nama_foto = ''; // Inisialisasi

// Cek dan proses upload file
if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] != UPLOAD_ERR_NO_FILE) {
    $file_tmp = $_FILES['bukti_foto']['tmp_name'];
    $file_name_original = $_FILES['bukti_foto']['name'];
    
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));

    // Buat nama file unik
    $nama_foto = $npm . '_' . time() . '.' . $file_ext; // Menggunakan time() agar lebih unik
    
    $target_dir = "uploads/";
    // Pastikan folder 'uploads' ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . $nama_foto;

    if (!move_uploaded_file($file_tmp, $target_file)) {
        die("Gagal mengupload file.");
    }
}

// Query INSERT menggunakan Prepared Statement (ke tabel absensi_ukri)
$stmt = $koneksi->prepare("INSERT INTO absensi_ukri (nama_mahasiswa, npm, kelas, status_kehadiran, bukti_foto) VALUES (?, ?, ?, ?, ?)");

// Tipe data bind_param: sssss (Nama, NPM, Kelas, Status, Foto - Semuanya dianggap String)
$stmt->bind_param("sssss", $nama_mahasiswa, $npm, $kelas, $status_kehadiran, $nama_foto);

if ($stmt->execute()) {
    header("Location: index.php?status=sukses");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>