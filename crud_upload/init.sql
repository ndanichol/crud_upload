CREATE DATABASE IF NOT EXISTS db_toko;
USE db_toko;

-- Hapus tabel produk lama jika ada
DROP TABLE IF EXISTS produk;

-- Buat tabel absensi_ukri
CREATE TABLE absensi_ukri (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_mahasiswa VARCHAR(100) NOT NULL,
    npm VARCHAR(20) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    status_kehadiran ENUM('Hadir', 'Sakit', 'Izin') NOT NULL,
    bukti_foto VARCHAR(255) DEFAULT NULL
);