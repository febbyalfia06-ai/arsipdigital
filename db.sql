CREATE DATABASE IF NOT EXISTS arsip_digital;
USE arsip_digital;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    foto VARCHAR(255) NULL,
    role ENUM('admin', 'sekretaris', 'lurah') DEFAULT 'admin'
);

-- Password default: febi12345 (MD5 hash)
INSERT INTO users (username, password, nama, role) VALUES ('febi1', 'c84e8c33eb88470dc138a0444326aa98', 'Febi Administrator', 'admin');
INSERT INTO users (username, password, nama, role) VALUES ('seketaris1', MD5('seketaris12345'), 'Sekretaris', 'sekretaris');
INSERT INTO users (username, password, nama, role) VALUES ('lurah1', MD5('lurah12345'), 'Bapak Lurah', 'lurah');

CREATE TABLE IF NOT EXISTS surat_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(100) NOT NULL,
    no_agenda VARCHAR(100) NULL,
    tanggal_surat DATE NOT NULL,
    tanggal_diterima DATE NOT NULL,
    pengirim VARCHAR(150) NOT NULL,
    kepada TEXT NULL,
    kategori VARCHAR(100) NOT NULL,
    sifat VARCHAR(50) NULL,
    perihal TEXT NOT NULL,
    file_surat VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    catatan_validasi TEXT NULL
);

CREATE TABLE IF NOT EXISTS surat_keluar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(100) NOT NULL,
    tanggal_surat DATE NOT NULL,
    tujuan VARCHAR(150) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    perihal TEXT NOT NULL,
    file_surat VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    catatan_validasi TEXT NULL
);

CREATE TABLE IF NOT EXISTS divisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_divisi VARCHAR(150) NOT NULL,
    keterangan TEXT NULL
);

CREATE TABLE IF NOT EXISTS sarana_simpan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sarana VARCHAR(150) NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    keterangan TEXT NULL
);

CREATE TABLE IF NOT EXISTS profil_kelurahan (
    id INT PRIMARY KEY DEFAULT 1,
    alamat TEXT,
    telepon VARCHAR(50),
    email VARCHAR(100),
    lurah VARCHAR(100),
    sekretaris VARCHAR(100),
    kepala_sekretariat VARCHAR(100),
    kasi_pemerintahan VARCHAR(100),
    kasi_permasbang VARCHAR(100),
    kasi_kesejahteraan VARCHAR(100)
);

INSERT IGNORE INTO profil_kelurahan (id, alamat, telepon, email, lurah, sekretaris, kepala_sekretariat, kasi_pemerintahan, kasi_permasbang, kasi_kesejahteraan) 
VALUES (1, 'Jl. Anyer Timur IV, RT.004/RW.016, Pengasinan, Kecamatan Rawalumbu, Kota Bekasi, Jawa Barat 17115', '(021) 82404278', 'kelurahanpengasinan567@gmail.com', 'RAMA ANGKASA, S.H., M.Si.', 'YOSEP DURAHMAN, SP.', 'M. NURDIN', 'M. DEDE SURAHMAN', 'FEBRI SUSANTO', 'YADIH');
