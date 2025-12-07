<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Absensi</title>
    <style> /* Styling Sederhana */
        body { font-family: Arial, sans-serif; }
        .container { width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], select, input[type="file"] { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        .radio-group label { display: inline-block; margin-right: 20px; }
        button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Data Absensi Mahasiswa</h2>
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

            <label for="nama_mahasiswa">Nama Mahasiswa:</label>
            <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" required>

            <label for="npm">NPM:</label>
            <input type="text" id="npm" name="npm" required>

            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" required>

            <label>Status Kehadiran:</label>
            <div class="radio-group">
                <input type="radio" id="hadir" name="status_kehadiran" value="Hadir" required>
                <label for="hadir">Hadir</label>

                <input type="radio" id="sakit" name="status_kehadiran" value="Sakit">
                <label for="sakit">Sakit</label>

                <input type="radio" id="izin" name="status_kehadiran" value="Izin">
                <label for="izin">Izin</label>
            </div>

            <label for="bukti_foto">Bukti Foto (Selfie/Surat):</label>
            <input type="file" id="bukti_foto" name="bukti_foto" accept="image/*">

            <button type="submit">Simpan Absensi</button>
            <a href="index.php">Batal</a>
        </form>
    </div>
</body>
</html>