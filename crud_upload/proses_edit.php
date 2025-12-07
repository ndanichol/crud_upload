<?php
include 'koneksi.php';

// Ambil data dari form
$id = $_POST['id'];
$nama_mahasiswa = $_POST['nama_mahasiswa'];
$npm = $_POST['npm'];
$kelas = $_POST['kelas'];
$status_kehadiran = $_POST['status_kehadiran'];
$foto_lama = $_POST['foto_lama'];
$nama_foto = $foto_lama; // Default: tetap menggunakan foto lama

// Cek apakah ada file baru yang diupload
if (isset($_FILES['bukti_foto_baru']) && $_FILES['bukti_foto_baru']['error'] != UPLOAD_ERR_NO_FILE) {
    $file_tmp = $_FILES['bukti_foto_baru']['tmp_name'];
    $file_name_original = $_FILES['bukti_foto_baru']['name'];
    
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));

    // Buat nama file unik
    $nama_foto = $npm . '_' . time() . '.' . $file_ext; 
    
    $target_dir = "uploads/";
    $target_file = $target_dir . $nama_foto;

    // Pindahkan file baru
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Hapus foto lama jika ada dan file baru berhasil diupload
        if ($foto_lama && file_exists($target_dir . $foto_lama)) {
            unlink($target_dir . $foto_lama);
        }
    } else {
        die("Gagal mengupload file baru.");
    }
}

// Query UPDATE menggunakan Prepared Statement
$sql = "UPDATE absensi_ukri SET nama_mahasiswa = ?, npm = ?, kelas = ?, status_kehadiran = ?, bukti_foto = ? WHERE id = ?";
$stmt = $koneksi->prepare($sql);

// Tipe data bind_param: sssssi (5 string, 1 integer untuk ID)
$stmt->bind_param("sssssi", $nama_mahasiswa, $npm, $kelas, $status_kehadiran, $nama_foto, $id);

if ($stmt->execute()) {
    header("Location: index.php?status=updatesukses");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>