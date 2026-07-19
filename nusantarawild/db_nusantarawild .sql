-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Jul 2026 pada 10.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nusantarawild`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_destinasi` int(11) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `nama_pemesan` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_kunjungan` date DEFAULT NULL,
  `jumlah_orang` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `status` enum('Menunggu','Dikonfirmasi','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_user`, `id_destinasi`, `lokasi`, `nama_pemesan`, `email`, `tanggal_kunjungan`, `jumlah_orang`, `metode_pembayaran`, `catatan`, `total_harga`, `status`, `created_at`) VALUES
(7, 'CCE73361', NULL, 3, 'Jawa Barat', 'arul', 'arul@gmail.com', '2026-06-27', 3, 'QRIS', 'wah pemandangannya mantap\r\n', 1050000, 'Selesai', '2026-06-26 07:20:56'),
(8, '93C3335A', NULL, 17, 'Ciamis', 'Siti', 'Siti@gmail.com', '2026-07-10', 3, 'Virtual Account', 'tolong di persiap kan, saya segara kesana...terima kasih', 300000, 'Dibatalkan', '2026-06-28 06:13:28'),
(9, '5DF41965', 7, 6, 'Kalimantan Tengah', 'Sitiolif', 'sitiolif@gmail.com', '2026-07-09', 1, 'QRIS', 'siap otw isekai', 800000, 'Menunggu', '2026-06-29 19:55:49'),
(10, 'B70ECE1D', 7, 2, 'Jawa Barat', 'ridwan', 'ridwan@gmail.com', '2026-07-30', 1, 'QRIS', 'siap otw pnd gass, with my friends', 200000, 'Selesai', '2026-07-02 17:29:30'),
(12, 'D0352630', 10, 10, 'Papua Barat', 'yoshi', 'yoshi@gmail.com', '2026-07-07', 1, 'Transfer Bank', 'udang', 1500000, 'Dibatalkan', '2026-07-07 06:33:35'),
(13, '8E5314C6', 9, 4, 'Rancah, Ciamis Jawa Barat', 'dadan', 'dadan@gmail.com', '2026-07-25', 1, 'QRIS', '', 600000, 'Menunggu', '2026-07-09 05:56:37'),
(14, 'D8A1A243', 12, 3, 'Jawa Barat', 'Wina', 'wina@gmail.com', '2026-07-27', 5, 'Transfer Bank', '', 1750000, 'Menunggu', '2026-07-18 18:39:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `destinasi`
--

CREATE TABLE `destinasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `terbaik_dikunjungi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `destinasi`
--

INSERT INTO `destinasi` (`id`, `nama`, `lokasi`, `harga`, `deskripsi`, `foto`, `kategori`, `rating`, `terbaik_dikunjungi`) VALUES
(2, 'Grand Canyon Pangandaran', 'Jawa Barat', 200000, 'Nama Green Canyon sendiri merupakan pelesetan dari Grand Canyon di Amerika Serikat karena kemiripan ngarai batu yang diapit sungai hijau toska yang jernih.', 'green canyon.jpg', 'Bahari', 4.5, NULL),
(3, 'Pantai Pangandaran', 'Jawa Barat', 350000, 'Spot utama untuk melihat terumbu karang dan ikan hias dan atraksi ikonik kapal yang ditenggelamkan, terlihat jelas saat air surut.', 'pasir.png', 'Pantai', 4.9, NULL),
(4, 'Puncak Bangku', 'Rancah, Ciamis Jawa Barat', 600000, 'Negeri di atas awan. Tempat ini mudah dijangkau tanpa harus mendaki gunung sehingga populer bagi yang ingin menikmati kabut, sunrise, dan udara sejuk.', 'foto2.jpg', 'Taman Nasional', 4.9, NULL),
(5, 'Danau Toba', 'Sumatera Utara', 250000, 'Danau vulkanik terbesar di dunia dengan Pulau Samosir di tengahnya. Kebudayaan Batak yang kaya menambah pesona.', 'danau toba.jpg', 'Danau', 4.7, NULL),
(6, 'Hutan Kalimantan', 'Kalimantan Tengah', 800000, 'Hutan hujan tropis purba, rumah bagi orangutan dan ribuan spesies flora fauna langka yang tidak ada di tempat lain.', 'hutan.png', 'Hutan', 4.6, NULL),
(7, 'Pantai Pink Lombok', 'Nusa Tenggara Barat', 150000, 'Pantai berpasir merah muda unik, satu dari hanya tujuh pantai pink di seluruh dunia.', 'lombok.jpeg', 'Pantai', 4.7, NULL),
(8, 'Gunung Ciremai', 'Jawa Barat', 450000, 'Puncak tertinggi di Jawa Barat (3078 mdpl). Gunung ini terkenal sebagai destinasi pendakian menantang dengan kawasan hutan, savana, dan edelweiss.', 'ciremai.jpeg', 'Gunung', 4.8, NULL),
(9, 'Gunung Bromo', 'Jawa Timur', 350000, 'Kawah berapi aktif yang memukau dengan lautan pasir dan pemandangan matahari terbit yang spektakuler.', 'bromo.jpg', 'Gunung', 4.8, NULL),
(10, 'Raja Ampat', 'Papua Barat', 1500000, 'Surga bawah laut dengan keanekaragaman hayati terkaya di dunia. Temukan ribuan spesies ikan dan koral yang memukau.', 'raja.jpeg', 'Bahari', 4.9, NULL),
(11, 'Wakatobi', 'Sulawesi Tenggara', 900000, 'Taman nasional laut dengan tutupan terumbu karang terlengkap, surga menyelam kelas dunia.', 'photo-1544551763-46a013bb70d5.jpeg', 'Bahari', 4.9, NULL),
(12, 'Kawah Ijen', 'Jawa Timur', 200000, 'Kawah belerang dengan api biru misterius yang hanya terlihat di malam hari, fenomena alam langka.', 'kawah.webp', 'Gunung', 4.7, NULL),
(13, 'Lembah Harau', 'Sumatera Barat', 30000, 'Lembah Harau di Sumatera Barat adalah destinasi wisata alam dan konservasi berbentuk ngarai yang terkenal dengan tebing granit terjal setinggi 100-500 meter, air terjun, dan pemandangan asri.', 'lembah.webp', 'Taman Nasional', 4.8, NULL),
(17, 'Curug Ngelay', 'Ciamis', 100000, 'pemandangan yang sangat sejuk dan cocok buat cemping', '104871303_2612315152375121_4868608855444104133_n.jpg', 'Gunung', 4.5, 'April-Juni'),
(18, ' Pemandian Air Panas Ciater Subang', 'Bandung Barat', 120000, 'Pemandian air panas alami merupakan salah satu destinasi wisata favorit di Indonesia yang sering digunakan untuk relaksasi tubuh, merilekskan otot, dan terapi kesehatan kulit karena kandungan belerangnya.', '4043819450.webp', 'Gunung', 4.8, 'Mei-Agustus');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `subjek` varchar(200) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id_kontak`, `nama`, `email`, `no_hp`, `subjek`, `kategori`, `pesan`, `tanggal`) VALUES
(1, 'Niswah', 'niswah@gmail.com', '085178096534', 'mau ke pantai', 'Pemesanan & Tiket', 'min, saya mau ke pantai pangandaran. tapi saya bingung cara pesen tiketnya gimana??', '2026-06-28 11:24:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `staf`
--

CREATE TABLE `staf` (
  `id_staf` int(11) NOT NULL,
  `nama_staf` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `staf`
--

INSERT INTO `staf` (`id_staf`, `nama_staf`, `jabatan`, `email`, `no_hp`, `foto`, `deskripsi`) VALUES
(1, 'Dadan Ramdani', 'Web Developer', 'dadanIT@gmail.com', '082246659712', '1782617785_wawancara.jpeg', 'Spesialis PHP,JS dan Backend'),
(2, 'Raffifah', 'Head of Content', 'Raffiah@gmial.com', '081233225687', '1782626895_wawancara1.jpg', 'Desain moderen & user_freindly'),
(3, 'Nadia', 'Community Manager', 'Nadia@gmail.com', '085109871234', '1783780048_wawancara2.jpg', 'Mengelola comunitas yang tersebar di Indonesia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `no_hp`, `provinsi`, `password`, `created_at`, `role`) VALUES
(3, 'DADAN Ramdani', 'dadanramdani1610@gmail.com', '08123299393', 'Jawa Timur', '$2y$10$FfpAYycLAGgzwlITuge7rOsUSqCuOVCL2neMcSA0317D5OWrs56Nq', '2026-06-25 14:35:43', 'user'),
(4, 'admin', 'admin@gmail.com', '08123299393', 'jakarta', '$2y$10$GwzfAaHBIGIJ6YgK1pcR3.jeJlkroo7zO/5ToDURzl64yYZgiM6FW', '2026-06-25 15:00:40', 'admin'),
(6, 'administrator', 'administrator@gmail.com', '082246651766', 'jawa barat', '$2y$10$hOo5SZiNVcq9Q8O5osTDuuDYHSjjLs8Ld4aah/ONuw.YNvbZ.FD2.', '2026-06-28 06:21:49', 'admin'),
(9, 'dadan', 'dadan@gmail.com', '082278907654', 'Bali', '$2y$10$za8H/UqihZxjwOoxD3JRdONfc2ofMLZ3FmOegYUDkiPz3YppKDCaK', '2026-07-06 16:39:56', 'user'),
(10, 'yoshi', 'yoshi@gmail.com', '0882390123', 'DKI Jakarta', '$2y$10$0gIzf6B960yWybMO924anu6DhWNoZcdC4KxXbtJwFP1xTV1eVLpBW', '2026-07-07 06:33:02', 'user'),
(11, 'fajar', 'fajar@gmail.com', '085167852342', 'Jawa Barat', '$2y$10$3Hp5oyEYzZ7t3OePIH/mFe1eCnzR5rBZmN42noBVB6TlEhnGueAGa', '2026-07-11 16:07:51', 'user'),
(12, 'Wina', 'wina@gmail.com', '08123299393', 'Bali', '$2y$10$zvZpAdA4jWW.UGBA2ePUr.6YB6X4QVL8arQaiWSPo3TGFW47aV5q2', '2026-07-18 18:11:39', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`);

--
-- Indeks untuk tabel `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`);

--
-- Indeks untuk tabel `staf`
--
ALTER TABLE `staf`
  ADD PRIMARY KEY (`id_staf`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id_kontak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `staf`
--
ALTER TABLE `staf`
  MODIFY `id_staf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
