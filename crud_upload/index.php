<?php
include 'koneksi.php';

$search_query = "";
$stmt = null; // Inisialisasi $stmt

// Mengubah fitur pencarian: berdasarkan Nama Mahasiswa atau NPM
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $search_param = "%" . $search_query . "%";
    
    // Siapkan query dengan Prepared Statement untuk keamanan
    $sql_template = "SELECT * FROM absensi_ukri WHERE nama_mahasiswa LIKE ? OR npm LIKE ? ORDER BY id DESC";
    $stmt = $koneksi->prepare($sql_template);
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    
    // AMBIL HASIL SATU KALI SAJA
    $result = $stmt->get_result(); 
} else {
    // Query default tanpa pencarian
    $sql = "SELECT * FROM absensi_ukri ORDER BY id DESC";
    $result = $koneksi->query($sql); // $result diset di sini juga
}

// Menangani pesan status setelah operasi CRUD
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses') {
        $status_message = '<div style="color:green; font-weight:bold;">Data absensi berhasil ditambahkan.</div>';
    } elseif ($_GET['status'] == 'updatesukses') {
        $status_message = '<div style="color:blue; font-weight:bold;">Data absensi berhasil diperbarui.</div>';
    } elseif ($_GET['status'] == 'hapussukses') {
        $status_message = '<div style="color:red; font-weight:bold;">Data absensi berhasil dihapus.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Absensi Mahasiswa</title>
    <style> /* Styling Sederhana */
        body { font-family: Arial, sans-serif; }
        .container { width: 90%; margin: 50px auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-add { background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-edit { background-color: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-delete { background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .search-form { margin: 20px 0; }
        .search-form input[type="text"] { padding: 8px; width: 300px; }
        .search-form button { padding: 8px 15px; background-color: #555; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Absensi Mahasiswa UKRI</h1>
        <?php echo $status_message; ?>
        <a href="tambah.php" class="btn-add">Tambah Data Absensi</a>

        <div class="search-form">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Cari Nama Mahasiswa atau NPM" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Cari</button>
                <?php if (!empty($search_query)): ?>
                    <a href="index.php">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Bukti Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                $no = 1; 
                
                if ($result && $result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['npm']); ?></td>
                    <td><a href="detail.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></a></td>
                    <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                    <td><?php echo htmlspecialchars($row['status_kehadiran']); ?></td>
                    <td>
                        <?php if ($row['bukti_foto']): ?>
                            <a href="uploads/<?php echo htmlspecialchars($row['bukti_foto']); ?>" target="_blank">Lihat Foto</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini? Semua foto terkait juga akan terhapus.');">Hapus</a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else: 
                ?>
                <tr>
                    <td colspan="7">Tidak ada data absensi yang ditemukan.</td>
                </tr>
                <?php 
                endif; 
                if (isset($stmt) && $stmt !== null) $stmt->close(); // Pastikan $stmt ditutup jika diset
                $koneksi->close(); 
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>