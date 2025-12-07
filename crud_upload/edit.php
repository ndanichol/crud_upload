<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data lama
$stmt = $koneksi->prepare("SELECT * FROM absensi_ukri WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

$statuses = ['Hadir', 'Sakit', 'Izin'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Absensi</title>
    <style> /* Styling Sederhana */
        body { font-family: Arial, sans-serif; }
        .container { width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], select, input[type="file"] { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        .radio-group label { display: inline-block; margin-right: 20px; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Absensi Mahasiswa</h2>
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data['bukti_foto']); ?>">

            <label for="nama_mahasiswa">Nama Mahasiswa:</label>
            <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" value="<?php echo htmlspecialchars($data['nama_mahasiswa']); ?>" required>

            <label for="npm">NPM:</label>
            <input type="text" id="npm" name="npm" value="<?php echo htmlspecialchars($data['npm']); ?>" required>

            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($data['kelas']); ?>" required>

            <label>Status Kehadiran:</label>
            <div class="radio-group">
                <?php foreach ($statuses as $status): ?>
                <input type="radio" id="<?php echo strtolower($status); ?>" name="status_kehadiran" value="<?php echo $status; ?>"
                    <?php echo ($data['status_kehadiran'] == $status) ? 'checked' : ''; ?> required>
                <label for="<?php echo strtolower($status); ?>"><?php echo $status; ?></label>
                <?php endforeach; ?>
            </div>

            <label>Bukti Foto Saat Ini:</label>
            <?php if ($data['bukti_foto']): ?>
                <img src="uploads/<?php echo htmlspecialchars($data['bukti_foto']); ?>" width="150" alt="Bukti Foto">
                <br><small>Kosongkan input di bawah jika tidak ingin mengganti foto.</small>
            <?php else: ?>
                <small>Belum ada bukti foto.</small>
            <?php endif; ?>

            <label for="bukti_foto_baru">Ganti Bukti Foto (Selfie/Surat):</label>
            <input type="file" id="bukti_foto_baru" name="bukti_foto_baru" accept="image/*">

            <button type="submit">Update Absensi</button>
            <a href="index.php">Batal</a>
        </form>
    </div>
</body>
</html>