-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 08, 2026 at 03:20 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(10) DEFAULT NULL,
  `nama_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Laptop Asus Vivobook', 7500000, 15, 1),
(2, 'BRG002', 'Mouse Wireless Logitech', 150000, 50, 2),
(3, 'BRG003', 'Keyboard Mechanical RGB', 450000, 30, 3),
(4, 'BRG004', 'Monitor LG 24 Inch', 1850000, 20, 4),
(5, 'BRG005', 'Headset Gaming HyperX', 850000, 25, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('PLG001', 'Budi Santoso', 'L', '081112223334', 'Jl. Kenanga No. 1, Gresik'),
('PLG002', 'Siti Aminah', 'P', '082223334445', 'Jl. Melati No. 5, Surabaya'),
('PLG003', 'Ahmad Dani', 'L', '083334445556', 'Jl. Kenjeran No. 22, Surabaya'),
('PLG004', 'Dewi Lestari', 'P', '084445556667', 'Jl. Pahlawan No. 9, Sidoarjo'),
('PLG005', 'Eko Prasetyo', 'L', '085556667778', 'Jl. Veteran No. 14, Malang');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `total` int DEFAULT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') DEFAULT NULL,
  `transaksi_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-05-01 10:30:00', 7650000, 'TRANSFER', 1),
(2, '2026-05-02 11:15:00', 900000, 'TUNAI', 2),
(3, '2026-05-03 13:20:00', 1850000, 'EDC', 3),
(4, '2026-05-04 14:00:00', 850000, 'TUNAI', 4),
(5, '2026-05-05 09:45:00', 300000, 'TUNAI', 5),
(6, '2026-05-06 15:10:00', 7500000, 'TRANSFER', 6),
(7, '2026-05-07 16:30:00', 600000, 'EDC', 7),
(8, '2026-05-08 10:05:00', 450000, 'TUNAI', 8),
(9, '2026-05-09 11:50:00', 1700000, 'TRANSFER', 9),
(10, '2026-05-10 12:00:00', 8100000, 'TUNAI', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tlp` varchar(12) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `tlp`, `alamat`) VALUES
(1, 'PT Sumber Makmur', '081234567890', 'Jl. Raya Industri No. 10, Jakarta'),
(2, 'CV Berkah Jaya', '085678901234', 'Jl. Merdeka No. 45, Bandung'),
(3, 'UD Maju Lancar', '081987654321', 'Jl. Ahmad Yani No. 12, Surabaya'),
(4, 'PT Sentosa Abadi', '082112233445', 'Jl. Diponegoro No. 88, Semarang'),
(5, 'CV Sejahtera Bersama', '087788990011', 'Jl. Gatot Subroto No. 5, Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `waktu_transaksi` date DEFAULT NULL,
  `keterangan` text,
  `total` int DEFAULT NULL,
  `pelanggan_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-05-01', 'Lunas', 7650000, 'PLG001'),
(2, '2026-05-02', 'Lunas', 900000, 'PLG002'),
(3, '2026-05-03', 'Lunas', 1850000, 'PLG003'),
(4, '2026-05-04', 'Lunas', 850000, 'PLG004'),
(5, '2026-05-05', 'Lunas', 300000, 'PLG005'),
(6, '2026-05-06', 'Lunas', 7500000, 'PLG001'),
(7, '2026-05-07', 'Lunas', 600000, 'PLG002'),
(8, '2026-05-08', 'Lunas', 450000, 'PLG003'),
(9, '2026-05-09', 'Lunas', 1700000, 'PLG004'),
(10, '2026-05-10', 'Lunas', 8100000, 'PLG005');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga` int DEFAULT NULL,
  `qyt` int DEFAULT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qyt`, `qty`) VALUES
(1, 1, 7500000, NULL, 1),
(1, 2, 150000, NULL, 1),
(2, 2, 150000, NULL, 3),
(2, 3, 450000, NULL, 1),
(3, 4, 1850000, NULL, 1),
(4, 5, 850000, NULL, 1),
(5, 2, 150000, NULL, 2),
(6, 1, 7500000, NULL, 1),
(7, 2, 150000, NULL, 1),
(7, 3, 450000, NULL, 1),
(8, 3, 450000, NULL, 1),
(9, 5, 850000, NULL, 2),
(10, 1, 7500000, NULL, 1),
(10, 2, 150000, NULL, 1),
(10, 3, 450000, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin01', 'admin123', 'Administrator', 'Jl. Sudirman No. 1', '081211112222', 1),
(2, 'kasir01', 'kasirina', 'Rina Kasir', 'Jl. Basuki Rahmat No. 10', '081322223333', 2),
(3, 'kasir02', 'jkkasir123', 'Joko Kasir', 'Jl. Pemuda No. 45', '081433334444', 2),
(4, 'gudang01', 'gudangmbmbg12', 'Bambang Gudang', 'Jl. Industri No. 7', '081544445555', 3),
(5, 'owner01', 'owner123', 'Pimpinan Agustin', 'Jl. Elite No. 99', '081655556666', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_barang_supplier` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pembayaran_transaksi` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`transaksi_id`,`barang_id`),
  ADD KEY `fk_detail_barang` (`barang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `fk_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
