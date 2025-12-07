<?php
include 'koneksi.php';

// Cek apakah parameter ID ada di URL
if (!isset($_GET['id'])) {
    // Jika tidak ada ID, kembalikan ke halaman utama
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data absensi spesifik menggunakan Prepared Statement
$stmt = $koneksi->prepare("SELECT * FROM absensi_ukri WHERE id = ?");
$stmt->bind_param("i", $id); // "i" untuk integer (ID)
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Cek jika data tidak ditemukan
if (!$data) {
    echo "<h2>Data Absensi tidak ditemukan.</h2>";
    echo "<p><a href='index.php'>Kembali ke Daftar Absensi</a></p>";
    $koneksi->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Absensi: <?php echo htmlspecialchars($data['nama_mahasiswa']); ?></title>
    <style> /* Styling Sederhana */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { 
            width: 700px; 
            margin: 50px auto; 
            padding: 25px; 
            background: white; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 { border-bottom: 2px solid #ccc; padding-bottom: 10px; color: #333; }
        .detail-row { margin-bottom: 10px; padding: 5px 0; border-bottom: 1px dotted #eee; }
        .detail-row strong { display: inline-block; width: 150px; color: #555; }
        .status-hadir { color: green; font-weight: bold; }
        .status-sakit { color: orange; font-weight: bold; }
        .status-izin { color: blue; font-weight: bold; }
        .btn-back { background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 20px; }
        .foto-bukti { max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Data Absensi</h2>

        <div class="detail-row">
            <strong>Nama Mahasiswa:</strong> 
            <?php echo htmlspecialchars($data['nama_mahasiswa']); ?>
        </div>
        <div class="detail-row">
            <strong>NPM:</strong> 
            <?php echo htmlspecialchars($data['npm']); ?>
        </div>
        <div class="detail-row">
            <strong>Kelas:</strong> 
            <?php echo htmlspecialchars($data['kelas']); ?>
        </div>
        <div class="detail-row">
            <strong>Status Kehadiran:</strong> 
            <?php
            $status_class = 'status-' . strtolower($data['status_kehadiran']);
            echo "<span class='{$status_class}'>" . htmlspecialchars($data['status_kehadiran']) . "</span>";
            ?>
        </div>

        <div class="detail-row">
            <strong>Bukti Foto:</strong><br>
            <?php if ($data['bukti_foto']): ?>
                <img src="uploads/<?php echo htmlspecialchars($data['bukti_foto']); ?>" alt="Bukti Foto" class="foto-bukti">
                <p><small>Nama File: <?php echo htmlspecialchars($data['bukti_foto']); ?></small></p>
            <?php else: ?>
                <p>Tidak ada bukti foto yang dilampirkan.</p>
            <?php endif; ?>
        </div>

        <a href="index.php" class="btn-back">← Kembali ke Daftar Absensi</a>
        <a href="edit.php?id=<?php echo htmlspecialchars($data['id']); ?>" style="background-color: #ffc107;" class="btn-back">Edit Data</a>
    </div>
</body>
</html>
<?php
$koneksi->close();
?>