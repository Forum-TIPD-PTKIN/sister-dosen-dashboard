-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 15 Jul 2026 pada 04.46
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sister_api`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pribadi`
--

CREATE TABLE `data_pribadi` (
  `id_sdm` varchar(36) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `id_jenis_kelamin` char(1) DEFAULT NULL,
  `id_agama` int DEFAULT NULL,
  `id_status_perkawinan` int DEFAULT NULL,
  `nama_ibu_kandung` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `kode_fak` varchar(20) NOT NULL,
  `nama_resmi` varchar(50) DEFAULT NULL,
  `nama_singkat` varchar(50) DEFAULT NULL,
  `nama_asing` varchar(50) DEFAULT NULL,
  `nama_asingsingkat` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `web` varchar(50) DEFAULT NULL,
  `dekan` int DEFAULT NULL,
  `kode_nim` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`kode_fak`, `nama_resmi`, `nama_singkat`, `nama_asing`, `nama_asingsingkat`, `email`, `web`, `dekan`, `kode_nim`) VALUES
('101', 'SYARIAH', 'FS', 'SYARIAH', NULL, '', '', 46, '01'),
('102', 'TARBIYAH DAN ILMU KEGURUAN', 'FTIK', '', NULL, '', '', 29, '02'),
('103', 'EKONOMI DAN BISNIS ISLAM', 'FEBI', '', NULL, '', '', 18, '04'),
('104', 'USHULUDDIN, ADAB DAN DAKWAH', 'FUAD', '', NULL, '', '', 26, '03'),
('201', 'PASCASARJANA', 'PASCA', '', NULL, '', '', 8, '05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inpassing`
--

CREATE TABLE `inpassing` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_jenis_sdm` int DEFAULT NULL,
  `no_sk_inpassing` varchar(100) DEFAULT NULL,
  `tanggal_sk_inpassing` date DEFAULT NULL,
  `id_jabatan_fungsional` varchar(36) DEFAULT NULL,
  `tmt_jabatan_fungsional` date DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa_pddikti`
--

CREATE TABLE `mahasiswa_pddikti` (
  `id` varchar(36) NOT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `id_program_studi` varchar(36) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendidikan_formal`
--

CREATE TABLE `pendidikan_formal` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `id_bidang_studi` int DEFAULT NULL,
  `id_jenjang_pendidikan` varchar(10) DEFAULT NULL,
  `gelar_akademik` varchar(100) DEFAULT NULL,
  `tanggal_lulus` date DEFAULT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `no_ijazah` varchar(100) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penugasan`
--

CREATE TABLE `penugasan` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_ikatan_kerja` varchar(10) DEFAULT NULL,
  `id_unit_kerja` varchar(36) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `no_sk_penugasan` varchar(100) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pt_id`
--

CREATE TABLE `pt_id` (
  `id` int NOT NULL,
  `pt_id` varchar(200) NOT NULL,
  `data_detail` json NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pt_id`
--

INSERT INTO `pt_id` (`id`, `pt_id`, `data_detail`, `created_at`) VALUES
(1, '8f3e7954-2f89-44d9-9c9e-37b59b0d5e15', '{\"rt\": 0, \"rw\": 0, \"bujur\": 0, \"dusun\": null, \"email\": \"info@uinsyahada.ac.id\", \"jalan\": \"Jalan T.Rizal Nurdin Km 4,5 Sihitang\", \"lintang\": 0, \"telepon\": \"0634 22080\", \"website\": \"https://www.uinsyahada.ac.id\", \"faximile\": \"0634 24022\", \"kode_pos\": \"22733\", \"kelurahan\": \"-\", \"id_wilayah\": \"076600  \", \"nama_wilayah\": \"Kota Padang Sidempuan\", \"sk_pendirian\": \"Perpres No 87 Tahun 2022\", \"id_status_milik\": \"1\", \"nama_status_milik\": \"Pemerintah Pusat\", \"id_perguruan_tinggi\": \"8f3e7954-2f89-44d9-9c9e-37b59b0d5e15\", \"sk_izin_operasional\": null, \"tanggal_sk_pendirian\": \"2022-06-08\", \"kode_perguruan_tinggi\": \"201029  \", \"nama_perguruan_tinggi\": \"Universitas Islam Negeri Syekh AIi Hasan Ahmad Addary Padangsidimpuan\", \"status_perguruan_tinggi\": \"A\", \"tanggal_izin_operasional\": null}', '2025-08-09 22:32:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `s3_storage`
--

CREATE TABLE `s3_storage` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `bucket` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `secret` varchar(200) NOT NULL,
  `max_size` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `s3_storage`
--

INSERT INTO `s3_storage` (`id`, `type`, `url`, `bucket`, `key`, `secret`, `max_size`) VALUES
(1, 'profil', 'https://is3.cloudhost.id', 'BUCKET-NAME', 'KEY', 'SECRET-CODE', '512000'),
(2, 'file', 'https://is3.cloudhost.id', 'BUCKET-NAME', 'KEY', 'SECRET-CODE', '512000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_group_users`
--

CREATE TABLE `sys_group_users` (
  `id` int NOT NULL,
  `level` varchar(50) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `deskripsi` varchar(50) DEFAULT NULL,
  `akses_prodi` text,
  `is_show` enum('Y','N') DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_group_users`
--

INSERT INTO `sys_group_users` (`id`, `level`, `level_name`, `deskripsi`, `akses_prodi`, `is_show`) VALUES
(1, 'root', 'root', 'Super Administrator', '{\"akses\":\"74230,86208,86232,61406\"}', 'N'),
(2, 'administrator', 'administrator', 'administrator', '{\"akses\":\"74230,86208,86232,61406\"}', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_lang`
--

CREATE TABLE `sys_lang` (
  `id` int NOT NULL,
  `language` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` enum('Active','Not Active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_lang`
--

INSERT INTO `sys_lang` (`id`, `language`, `description`, `status`) VALUES
(1, 'en', 'English', 'Not Active'),
(2, 'id', 'Indonesia', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_menu`
--

CREATE TABLE `sys_menu` (
  `id` int NOT NULL,
  `nav_act` varchar(150) DEFAULT NULL,
  `page_name` varchar(150) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `main_table` varchar(150) DEFAULT NULL,
  `icon` varchar(150) DEFAULT NULL,
  `urutan_menu` int DEFAULT NULL,
  `parent` int DEFAULT NULL,
  `depth` int DEFAULT NULL,
  `dt_table` enum('Y','N') NOT NULL,
  `tampil` enum('Y','N') NOT NULL,
  `type_menu` enum('main','page','separator') NOT NULL,
  `hide` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_menu`
--

INSERT INTO `sys_menu` (`id`, `nav_act`, `page_name`, `url`, `main_table`, `icon`, `urutan_menu`, `parent`, `depth`, `dt_table`, `tampil`, `type_menu`, `hide`) VALUES
(4, NULL, 'system setting', NULL, NULL, 'fa-gear', 3, 0, 0, 'Y', 'Y', 'main', 'N'),
(5, 'page', 'page', 'page', 'sys_menu', 'fa-newspaper-o', 1, 4, 1, 'Y', 'Y', 'page', 'N'),
(6, 'user_group', 'user group', 'user-group', 'sys_users', 'fa-users', 2, 4, 1, 'Y', 'Y', 'page', 'N'),
(7, 'user_managements', 'user managements', 'user-managements', 'sys_menu_role', 'fa-user', 5, 4, 1, 'Y', 'Y', 'page', 'N'),
(8, 'menu_management', 'group permission', 'menu-management', 'sys_users', 'fa-lock', 3, 4, 1, 'Y', 'Y', 'page', 'N'),
(9, 'excel', 'excel generator', 'excel', 'sys_services', 'fa-file-excel-o', 4, 4, 1, 'Y', 'Y', 'page', 'N'),
(10, 'service', 'web service', 'service', 'sys_services', 'fa-server', 6, 4, 1, 'Y', 'Y', 'page', 'N'),
(12, 'service_permission', 'web service permission', 'service-permission', 'sys_services', 'fa-bullseye', 7, 4, 1, 'Y', 'Y', 'page', 'N'),
(43, 'pengaturan_umum', 'pengaturan umum', 'pengaturan-umum', 'tb_master_pengaturan_umum', 'fa-gear', 1, 52, 1, 'Y', 'Y', 'page', 'N'),
(45, NULL, 'data', '#', NULL, 'fa-newspaper-o', 1, 0, 0, 'Y', 'Y', 'main', 'N'),
(52, NULL, 'master', '#', NULL, 'fa-book', 2, 0, 0, 'Y', 'Y', 'main', 'N'),
(75, 'referensi_data', 'referensi data', 'referensi-data', 'reference_data', '', 2, 52, 1, 'Y', 'Y', 'page', 'N'),
(76, 'data_sdm', 'data sdm', 'data-sdm', 'tb_ref_sdm', 'fa-users', 3, 52, 1, 'Y', 'Y', 'page', 'N'),
(77, 'publikasi', 'publikasi', 'publikasi', 'tb_data_publikasi', '', 1, 45, 1, 'Y', 'Y', 'page', 'N'),
(78, 'penelitian', 'penelitian', 'penelitian', 'tb_data_penelitian', '', 2, 45, 1, 'Y', 'Y', 'page', 'N'),
(79, 'pengabdian', 'pengabdian', 'pengabdian', 'tb_data_pengabdian', '', 3, 45, 1, 'Y', 'Y', 'page', 'N'),
(80, 'hki', 'hki', 'hki', 'tb_data_hki', '', 4, 45, 1, 'Y', 'Y', 'page', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_menu_role`
--

CREATE TABLE `sys_menu_role` (
  `id` int NOT NULL,
  `id_menu` int DEFAULT NULL,
  `group_level` varchar(50) DEFAULT NULL,
  `read_act` enum('Y','N') DEFAULT NULL,
  `insert_act` enum('Y','N') DEFAULT NULL,
  `update_act` enum('Y','N') DEFAULT NULL,
  `delete_act` enum('Y','N') DEFAULT NULL,
  `import_act` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_menu_role`
--

INSERT INTO `sys_menu_role` (`id`, `id_menu`, `group_level`, `read_act`, `insert_act`, `update_act`, `delete_act`, `import_act`) VALUES
(10, 4, 'root', 'Y', 'Y', 'Y', 'Y', NULL),
(11, 4, 'administrator', 'N', 'N', 'N', 'N', NULL),
(13, 5, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(14, 5, 'administrator', 'N', 'N', 'N', 'N', NULL),
(16, 6, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(17, 6, 'administrator', 'Y', 'Y', 'Y', 'N', 'Y'),
(26, 7, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(27, 7, 'administrator', 'Y', 'Y', 'Y', 'Y', 'Y'),
(29, 8, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(30, 8, 'administrator', 'Y', 'Y', 'Y', 'Y', 'Y'),
(32, 9, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(33, 9, 'administrator', 'N', 'N', 'N', 'N', NULL),
(35, 10, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(36, 10, 'administrator', 'N', 'N', 'N', 'N', NULL),
(41, 12, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(42, 12, 'administrator', 'N', 'N', 'N', 'N', NULL),
(168, 43, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(169, 43, 'administrator', 'Y', 'Y', 'Y', 'Y', 'Y'),
(176, 45, 'root', 'Y', 'Y', 'Y', 'Y', NULL),
(177, 45, 'administrator', 'Y', 'N', 'N', 'N', NULL),
(198, 52, 'root', 'Y', 'Y', 'Y', 'Y', NULL),
(199, 52, 'administrator', 'Y', 'N', 'N', 'N', NULL),
(325, 75, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(326, 75, 'administrator', 'Y', 'Y', 'N', 'N', NULL),
(328, 76, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(329, 76, 'administrator', 'Y', 'Y', 'N', 'N', NULL),
(331, 77, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(332, 77, 'administrator', 'Y', 'N', 'N', 'N', NULL),
(334, 78, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(335, 78, 'administrator', 'Y', 'N', 'N', 'N', NULL),
(337, 79, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(338, 79, 'administrator', 'Y', 'N', 'N', 'N', NULL),
(340, 80, 'root', 'Y', 'Y', 'Y', 'Y', 'Y'),
(341, 80, 'administrator', 'Y', 'N', 'N', 'N', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_services`
--

CREATE TABLE `sys_services` (
  `id` int NOT NULL,
  `nav_act` varchar(150) DEFAULT NULL,
  `page_name` varchar(150) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `main_table` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `sys_services`
--

INSERT INTO `sys_services` (`id`, `nav_act`, `page_name`, `url`, `main_table`) VALUES
(1, 'tes', 'tes', 'tes', 'bulan_aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_setting`
--

CREATE TABLE `sys_setting` (
  `setting_id` int NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `setting_content` mediumtext NOT NULL,
  `short_content` varchar(50) NOT NULL,
  `setting_type` enum('text','image','radio') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_setting`
--

INSERT INTO `sys_setting` (`setting_id`, `setting_name`, `short_name`, `setting_content`, `short_content`, `setting_type`) VALUES
(1, 'Logo', 'logo', 'https://is3.cloudhost.id/storageimporter/1669678238128073335.png', '', 'image'),
(2, 'app_name', 'app_name', 'TRACER STUDY', 'TRACER', 'text');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_token`
--

CREATE TABLE `sys_token` (
  `id` int NOT NULL,
  `id_service` int NOT NULL,
  `enable_token_read` enum('Y','N') NOT NULL,
  `enable_token_create` enum('Y','N') NOT NULL,
  `enable_token_update` enum('Y','N') NOT NULL,
  `enable_token_delete` enum('Y','N') NOT NULL,
  `format_data` enum('json','xml') NOT NULL,
  `read_access` mediumtext,
  `create_access` mediumtext,
  `update_access` mediumtext,
  `delete_access` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_token`
--

INSERT INTO `sys_token` (`id`, `id_service`, `enable_token_read`, `enable_token_create`, `enable_token_update`, `enable_token_delete`, `format_data`, `read_access`, `create_access`, `update_access`, `delete_access`) VALUES
(1, 1, 'Y', 'Y', 'Y', 'Y', 'json', '[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]', '[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]', '[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]', '[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sys_users`
--

CREATE TABLE `sys_users` (
  `id` int NOT NULL,
  `full_name` varchar(50) NOT NULL DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `foto_user` varchar(150) DEFAULT '	https://is3.cloudhost.id/storageimporter/user.png',
  `group_level` varchar(50) DEFAULT NULL,
  `aktif` enum('Y','N') NOT NULL,
  `photo_changed` enum('Y','N') DEFAULT 'N',
  `token` varchar(150) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sys_users`
--

INSERT INTO `sys_users` (`id`, `full_name`, `username`, `password`, `email`, `date_created`, `foto_user`, `group_level`, `aktif`, `photo_changed`, `token`, `token_expiration`) VALUES
(10, 'administrator', 'admin', '0192023a7bbd73250516f069df18b500', 'admin@gmail.com', NULL, 'https://is3.cloudhost.id/storageimporter/16888257911611795108.png', 'administrator', 'Y', 'Y', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_hki`
--

CREATE TABLE `tb_data_hki` (
  `id` char(36) NOT NULL,
  `id_sdm` char(36) NOT NULL,
  `kategori_kegiatan` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `quartile` varchar(50) DEFAULT NULL,
  `bidang_keilmuan` json DEFAULT NULL,
  `jenis_publikasi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_penelitian`
--

CREATE TABLE `tb_data_penelitian` (
  `id` int NOT NULL,
  `id_penelitian` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_sdm` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(500) NOT NULL,
  `bidang_keilmuan` json DEFAULT NULL,
  `tahun_pelaksanaan` year NOT NULL,
  `lama_kegiatan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_pengabdian`
--

CREATE TABLE `tb_data_pengabdian` (
  `id` char(36) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `id_sdm` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bidang_keilmuan` json DEFAULT NULL,
  `tahun_pelaksanaan` year NOT NULL,
  `lama_kegiatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_publikasi`
--

CREATE TABLE `tb_data_publikasi` (
  `id` int NOT NULL,
  `id_kategori_kegiatan` int NOT NULL,
  `id_sdm` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_klaim_bkd` tinyint(1) NOT NULL DEFAULT '0',
  `wkt_klaim_bkd` datetime DEFAULT NULL,
  `judul` varchar(500) NOT NULL,
  `quartile` varchar(50) DEFAULT NULL,
  `jenis_publikasi` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori_kegiatan` varchar(500) NOT NULL,
  `asal_data` varchar(100) NOT NULL,
  `bidang_keilmuan` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_master_fakultas`
--

CREATE TABLE `tb_master_fakultas` (
  `id_fakultas` int NOT NULL,
  `kode_fakultas` varchar(10) NOT NULL,
  `nama_fakultas` varchar(255) NOT NULL,
  `nama_singkat` varchar(255) NOT NULL,
  `nip_signature` varchar(50) DEFAULT NULL,
  `tipe_signature` varchar(50) DEFAULT NULL,
  `jabatan_signature` varchar(50) DEFAULT NULL,
  `image_signature` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_master_fakultas`
--

INSERT INTO `tb_master_fakultas` (`id_fakultas`, `kode_fakultas`, `nama_fakultas`, `nama_singkat`, `nip_signature`, `tipe_signature`, `jabatan_signature`, `image_signature`) VALUES
(1, '101', 'SYARIAH', 'FS', NULL, NULL, NULL, NULL),
(2, '102', 'TARBIYAH DAN ILMU KEGURUAN', 'FTIK', NULL, NULL, NULL, NULL),
(3, '103', 'EKONOMI DAN BISNIS ISLAM', 'FEBI', NULL, NULL, NULL, NULL),
(4, '104', 'USHULUDDIN, ADAB DAN DAKWAH', 'FUAD', NULL, NULL, NULL, NULL),
(5, '201', 'PASCASARJANA', 'PASCA', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_master_jurusan`
--

CREATE TABLE `tb_master_jurusan` (
  `kode_jur` int NOT NULL,
  `id_jenj_didik` int NOT NULL,
  `id_fakultas` int DEFAULT NULL,
  `status` enum('A','T') DEFAULT NULL,
  `kode_dikti` varchar(20) DEFAULT NULL,
  `nama_jur` varchar(100) DEFAULT NULL,
  `nama_singkat` varchar(100) DEFAULT NULL,
  `nama_jur_asing` varchar(250) DEFAULT NULL,
  `tanggal_sk_dikti` date DEFAULT NULL,
  `nomor_sk_dikti` varchar(100) DEFAULT NULL,
  `visi_misi` mediumtext,
  `tanggal_berdiri` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_master_jurusan`
--

INSERT INTO `tb_master_jurusan` (`kode_jur`, `id_jenj_didik`, `id_fakultas`, `status`, `kode_dikti`, `nama_jur`, `nama_singkat`, `nama_jur_asing`, `tanggal_sk_dikti`, `nomor_sk_dikti`, `visi_misi`, `tanggal_berdiri`, `created_date`, `updated_date`) VALUES
(60202, 30, 103, 'A', NULL, 'Ekonomi Syariah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61205, 30, 103, 'A', NULL, 'Manajemen Bisnis Syariah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61206, 30, 103, 'A', NULL, 'Perbankan Syariah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61211, 30, 103, 'A', NULL, 'Manajemen Keuangan Syariah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70230, 30, 104, 'A', NULL, 'Manajemen Dakwah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70232, 30, 102, 'A', NULL, 'Bimbingan dan Konseling Pendidikan Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71201, 30, 104, 'A', NULL, 'Ilmu Perpustakaan dan Informasi Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74130, 35, 201, 'A', NULL, 'Hukum Keluarga Islam (Ahwal Syakhshiyyah)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 09:32:03'),
(74230, 30, 101, 'A', NULL, 'Hukum Keluarga Islam (Ahwal Syakhshiyyah)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74231, 30, 101, 'A', NULL, 'Hukum Pidana Islam (Jinayah)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74234, 30, 101, 'A', NULL, 'Hukum Ekonomi Syariah (Muamalah)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74237, 30, 104, 'A', NULL, 'Pemikiran Politik Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74239, 30, 101, 'A', NULL, 'Hukum Tatanegara (Siyasah Syar\'iyyah)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76231, 30, 104, 'A', NULL, 'Ilmu Al-Qur`an dan Tafsir', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80230, 30, 104, 'A', NULL, 'Sejarah Peradaban Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84202, 30, 102, 'A', NULL, 'Tadris Matematika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84203, 30, 102, 'A', NULL, 'Tadris Fisika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84204, 30, 102, 'A', NULL, 'Tadris Kimia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84205, 30, 102, 'A', NULL, 'Tadris Biologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86108, 35, 201, 'A', NULL, 'Pendidikan Agama Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 09:32:12'),
(86137, 35, 201, 'A', NULL, 'Manajemen Pendidikan Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 09:32:18'),
(86208, 30, 102, 'A', NULL, 'Pendidikan Agama Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 09:32:24'),
(86231, 30, 102, 'A', NULL, 'Manajemen Pendidikan Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86236, 30, 102, 'A', NULL, 'Pendidikan Islam Anak Usia Dini', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86906, 31, 102, 'A', NULL, 'Pendidikan Profesi Guru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88004, 30, 102, 'A', NULL, 'Pendidikan Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88203, 30, 102, 'A', NULL, 'Tadris Bahasa Inggris', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_master_pengaturan_umum`
--

CREATE TABLE `tb_master_pengaturan_umum` (
  `id_pengaturan` int NOT NULL,
  `nama_pengaturan` varchar(255) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `isi_pengaturan` mediumtext NOT NULL,
  `type_pengaturan` enum('text','image','radio','extend','sosmed','link') NOT NULL,
  `isi_extend` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `tampil` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_master_pengaturan_umum`
--

INSERT INTO `tb_master_pengaturan_umum` (`id_pengaturan`, `nama_pengaturan`, `short_name`, `isi_pengaturan`, `type_pengaturan`, `isi_extend`, `tampil`) VALUES
(1, 'Logo Kampus', 'logo', 'https://is3.cloudhost.id/sidimpuan/17555274961795202471.png', 'image', NULL, 'Y'),
(13, 'app_name', 'app_name', 'DASHBOARD DOSEN ', 'text', NULL, 'Y'),
(14, 'nama kampus', 'nama_kampus', 'UIN Syekh Ali Hasan Ahmad Addary Padangsidimpuan', 'text', NULL, 'Y'),
(15, 'Favicon', 'favicon', 'https://is3.cloudhost.id/sidimpuan/1754571431148243033.png', 'image', NULL, 'Y'),
(16, 's3 url', 's3_url', 'https://is3.cloudhost.id/storageimporter/', 'text', NULL, 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_reference_data`
--

CREATE TABLE `tb_reference_data` (
  `id` int NOT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reference_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci GENERATED ALWAYS AS (json_unquote(json_extract(`data`,_utf8mb4'$.nama'))) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_sdm`
--

CREATE TABLE `tb_ref_sdm` (
  `id_sdm` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_sdm` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nidn` varchar(200) DEFAULT NULL,
  `nip` varchar(200) DEFAULT NULL,
  `nuptk` varchar(200) DEFAULT NULL,
  `nama_status_aktif` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_status_pegawai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_sdm` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_sdm_jabatan_fungsional`
--

CREATE TABLE `tb_ref_sdm_jabatan_fungsional` (
  `id_sdm` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan_fungsional` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sk` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_mulai` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_sdm_pendidikan`
--

CREATE TABLE `tb_ref_sdm_pendidikan` (
  `id_sdm` varchar(200) NOT NULL,
  `jenjang_pendidikan` varchar(10) NOT NULL,
  `datas` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_sdm_penugasan`
--

CREATE TABLE `tb_ref_sdm_penugasan` (
  `id_sdm` varchar(100) NOT NULL,
  `unit_kerja` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_unit`
--

CREATE TABLE `tb_ref_unit` (
  `id` varchar(200) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `id_jenis_unit` int NOT NULL,
  `detail_unit` json DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ref_wilayah`
--

CREATE TABLE `tb_ref_wilayah` (
  `id` varchar(100) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `id_induk_wilayah` varchar(100) DEFAULT NULL,
  `id_level_wilayah` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_token`
--

CREATE TABLE `tb_token` (
  `id` int NOT NULL,
  `refresh_token` mediumtext,
  `access_token` mediumtext,
  `login` enum('Y','N') NOT NULL,
  `email` varchar(50) NOT NULL,
  `client_secret` varchar(150) NOT NULL,
  `client_id` varchar(150) NOT NULL,
  `redirect_url` varchar(150) NOT NULL,
  `aktif` enum('Y','N') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_token`
--

INSERT INTO `tb_token` (`id`, `refresh_token`, `access_token`, `login`, `email`, `client_secret`, `client_id`, `redirect_url`, `aktif`) VALUES
(1, '1//0g7dk8ryKSJxlCgYIARAAGBASNwF-L9IrtvMxckaZcOnLr5HAYKaMEhetKvcs13q7ZcQlttIym17rnjGsdCRKqmT9_dxClBG_ONg', '{\"access_token\":\"ya29.a0AS3H6NwPsSRXT4vItzk4RPcFArNWprtjGX5YRSHJTWF7wFKRE1_jhNEMlZvKH0vmBfb9OiHYiETs6t6GoPZbHzDVEo8-Rlit3F7SAHQo0txN0Yk9zxoXCwBp2rTuTEui0xyBvSTJj4_VAzrDrVxodsIy4WuiGOehRnYz20dS0I8aCgYKAaYSARASFQHGX2MioAutHeTUsDoJYZmxGYci1w0178\",\"expires_in\":3599,\"scope\":\"https:\\/\\/www.googleapis.com\\/auth\\/drive.file\",\"token_type\":\"Bearer\",\"created\":1755487359,\"refresh_token\":\"1\\/\\/0g7dk8ryKSJxlCgYIARAAGBASNwF-L9IrtvMxckaZcOnLr5HAYKaMEhetKvcs13q7ZcQlttIym17rnjGsdCRKqmT9_dxClBG_ONg\"}', 'Y', '', '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', '775034104084-aknet1fgmq85gkqvo3ic702qvps9h2c4.apps.googleusercontent.com', '', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_google`
--

CREATE TABLE `user_google` (
  `id` int NOT NULL,
  `google_id` varchar(64) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `picture` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_nama_gelar`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_nama_gelar` (
`id_sdm` varchar(36)
,`nama_dengan_gelar` longtext
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_sdm`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_sdm` (
`id_sdm` varchar(36)
,`nama_sdm` varchar(255)
,`nidn` varchar(200)
,`nip` varchar(200)
,`nuptk` varchar(200)
,`nama_status_aktif` varchar(50)
,`nama_status_pegawai` varchar(50)
,`jenis_sdm` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`pendidikan_terakhir` varchar(10)
,`home_base` varchar(200)
,`jafung` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_sdm_details`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_sdm_details` (
`id_sdm` varchar(36)
,`nama_sdm` varchar(255)
,`nidn` varchar(200)
,`nip` varchar(200)
,`nuptk` varchar(200)
,`nama_status_aktif` varchar(50)
,`nama_status_pegawai` varchar(50)
,`jenis_sdm` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`pendidikan_terakhir` varchar(10)
,`home_base` varchar(200)
,`jafung` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_sdm_jabatan_fungsional`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_sdm_jabatan_fungsional` (
`id_sdm` varchar(36)
,`nama_sdm` varchar(255)
,`jafung` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_sdm_pendidikan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_sdm_pendidikan` (
`id_sdm` varchar(36)
,`nama_sdm` varchar(255)
,`pendidikan` varchar(10)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_nama_gelar`
--
DROP TABLE IF EXISTS `view_nama_gelar`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_nama_gelar`  AS SELECT `v`.`id_sdm` AS `id_sdm`, (case when (`v`.`jafung` = 'Profesor') then concat('Prof. ',if((`g`.`gelar_s3` is not null),'Dr. ',''),`v`.`nama_sdm`,if((`g`.`gelar_s2` is not null),concat(', ',`g`.`gelar_s2`),'')) when (`g`.`gelar_s3` is not null) then concat('Dr. ',`v`.`nama_sdm`,if((`g`.`gelar_s2` is not null),concat(', ',`g`.`gelar_s2`),'')) when (`g`.`gelar_s2` is not null) then concat(`v`.`nama_sdm`,if((`g`.`gelar_s1` is not null),concat(', ',`g`.`gelar_s1`),''),', ',`g`.`gelar_s2`) else concat(`v`.`nama_sdm`,', ',`g`.`gelar_s1`) end) AS `nama_dengan_gelar` FROM (`view_sdm` `v` left join (select `tb_ref_sdm_pendidikan`.`id_sdm` AS `id_sdm`,max((case when (`tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S1') then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end)) AS `gelar_s1`,max((case when (`tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S2') then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end)) AS `gelar_s2`,max((case when (`tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S3') then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end)) AS `gelar_s3` from `tb_ref_sdm_pendidikan` group by `tb_ref_sdm_pendidikan`.`id_sdm`) `g` on((`v`.`id_sdm` = `g`.`id_sdm`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_sdm`
--
DROP TABLE IF EXISTS `view_sdm`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_sdm`  AS SELECT `tr`.`id_sdm` AS `id_sdm`, `tr`.`nama_sdm` AS `nama_sdm`, `tr`.`nidn` AS `nidn`, `tr`.`nip` AS `nip`, `tr`.`nuptk` AS `nuptk`, `tr`.`nama_status_aktif` AS `nama_status_aktif`, `tr`.`nama_status_pegawai` AS `nama_status_pegawai`, `tr`.`jenis_sdm` AS `jenis_sdm`, `tr`.`created_at` AS `created_at`, `tr`.`updated_at` AS `updated_at`, (select `vs`.`pendidikan` from `view_sdm_pendidikan` `vs` where (`tr`.`id_sdm` = `vs`.`id_sdm`) limit 1) AS `pendidikan_terakhir`, (select `vsp`.`unit_kerja` from `tb_ref_sdm_penugasan` `vsp` where (`tr`.`id_sdm` = `vsp`.`id_sdm`) limit 1) AS `home_base`, (select `jf`.`jafung` from `view_sdm_jabatan_fungsional` `jf` where (`tr`.`id_sdm` = `jf`.`id_sdm`) limit 1) AS `jafung` FROM `tb_ref_sdm` AS `tr` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_sdm_details`
--
DROP TABLE IF EXISTS `view_sdm_details`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_sdm_details`  AS SELECT `tr`.`id_sdm` AS `id_sdm`, `tr`.`nama_sdm` AS `nama_sdm`, `tr`.`nidn` AS `nidn`, `tr`.`nip` AS `nip`, `tr`.`nuptk` AS `nuptk`, `tr`.`nama_status_aktif` AS `nama_status_aktif`, `tr`.`nama_status_pegawai` AS `nama_status_pegawai`, `tr`.`jenis_sdm` AS `jenis_sdm`, `tr`.`created_at` AS `created_at`, `tr`.`updated_at` AS `updated_at`, (select `vs`.`pendidikan` from `view_sdm_pendidikan` `vs` where (`tr`.`id_sdm` = `vs`.`id_sdm`) limit 1) AS `pendidikan_terakhir`, (select `vsp`.`unit_kerja` from `tb_ref_sdm_penugasan` `vsp` where (`tr`.`id_sdm` = `vsp`.`id_sdm`) limit 1) AS `home_base`, (select `jf`.`jafung` from `view_sdm_jabatan_fungsional` `jf` where (`tr`.`id_sdm` = `jf`.`id_sdm`) limit 1) AS `jafung` FROM `tb_ref_sdm` AS `tr` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_sdm_jabatan_fungsional`
--
DROP TABLE IF EXISTS `view_sdm_jabatan_fungsional`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_sdm_jabatan_fungsional`  AS SELECT `tr`.`id_sdm` AS `id_sdm`, `tr`.`nama_sdm` AS `nama_sdm`, (select `tb_ref_sdm_jabatan_fungsional`.`jabatan_fungsional` from `tb_ref_sdm_jabatan_fungsional` where (`tb_ref_sdm_jabatan_fungsional`.`id_sdm` = `tr`.`id_sdm`) order by `tb_ref_sdm_jabatan_fungsional`.`tanggal_mulai` desc limit 1) AS `jafung` FROM `tb_ref_sdm` AS `tr` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_sdm_pendidikan`
--
DROP TABLE IF EXISTS `view_sdm_pendidikan`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `view_sdm_pendidikan`  AS SELECT `tr`.`id_sdm` AS `id_sdm`, `tr`.`nama_sdm` AS `nama_sdm`, (select `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` from `tb_ref_sdm_pendidikan` where (`tb_ref_sdm_pendidikan`.`id_sdm` = `tr`.`id_sdm`) order by `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` desc limit 1) AS `pendidikan` FROM `tb_ref_sdm` AS `tr` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_pribadi`
--
ALTER TABLE `data_pribadi`
  ADD PRIMARY KEY (`id_sdm`),
  ADD KEY `idx_data_pribadi_nik` (`nik`),
  ADD KEY `idx_data_pribadi_nama` (`nama`);

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`kode_fak`),
  ADD KEY `dekan` (`dekan`);

--
-- Indeks untuk tabel `inpassing`
--
ALTER TABLE `inpassing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inpassing_id_sdm` (`id_sdm`);

--
-- Indeks untuk tabel `mahasiswa_pddikti`
--
ALTER TABLE `mahasiswa_pddikti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mahasiswa_id_perguruan_tinggi` (`id_perguruan_tinggi`),
  ADD KEY `idx_mahasiswa_nim` (`nim`);

--
-- Indeks untuk tabel `pendidikan_formal`
--
ALTER TABLE `pendidikan_formal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pendidikan_formal_id_sdm` (`id_sdm`);

--
-- Indeks untuk tabel `penugasan`
--
ALTER TABLE `penugasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_penugasan_id_sdm` (`id_sdm`);

--
-- Indeks untuk tabel `pt_id`
--
ALTER TABLE `pt_id`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pt_id` (`pt_id`);

--
-- Indeks untuk tabel `s3_storage`
--
ALTER TABLE `s3_storage`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sys_group_users`
--
ALTER TABLE `sys_group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`);

--
-- Indeks untuk tabel `sys_lang`
--
ALTER TABLE `sys_lang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sys_menu`
--
ALTER TABLE `sys_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sys_menu_role`
--
ALTER TABLE `sys_menu_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_sys_menu_role_sys_menu` (`id_menu`),
  ADD KEY `FK_sys_menu_role_sys_users` (`group_level`);

--
-- Indeks untuk tabel `sys_services`
--
ALTER TABLE `sys_services`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sys_setting`
--
ALTER TABLE `sys_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indeks untuk tabel `sys_token`
--
ALTER TABLE `sys_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_sys_token_sys_services` (`id_service`);

--
-- Indeks untuk tabel `sys_users`
--
ALTER TABLE `sys_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_sys_users_sys_group_users` (`group_level`);

--
-- Indeks untuk tabel `tb_data_hki`
--
ALTER TABLE `tb_data_hki`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_data_penelitian`
--
ALTER TABLE `tb_data_penelitian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sdm` (`id_sdm`),
  ADD KEY `id_penelitian` (`id_penelitian`);

--
-- Indeks untuk tabel `tb_data_pengabdian`
--
ALTER TABLE `tb_data_pengabdian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sdm` (`id_sdm`);

--
-- Indeks untuk tabel `tb_data_publikasi`
--
ALTER TABLE `tb_data_publikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sdm` (`id_sdm`),
  ADD KEY `id_kategori_kegiatan` (`id_kategori_kegiatan`);

--
-- Indeks untuk tabel `tb_master_fakultas`
--
ALTER TABLE `tb_master_fakultas`
  ADD PRIMARY KEY (`id_fakultas`),
  ADD KEY `kode_fakultas` (`kode_fakultas`);

--
-- Indeks untuk tabel `tb_master_jurusan`
--
ALTER TABLE `tb_master_jurusan`
  ADD PRIMARY KEY (`kode_jur`),
  ADD KEY `FK_jurusan_jenjang_pendidikan` (`id_jenj_didik`),
  ADD KEY `fak_kode` (`id_fakultas`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `tb_master_pengaturan_umum`
--
ALTER TABLE `tb_master_pengaturan_umum`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indeks untuk tabel `tb_reference_data`
--
ALTER TABLE `tb_reference_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_reference` (`reference_type`,`reference_id`),
  ADD KEY `idx_reference_type` (`reference_type`),
  ADD KEY `idx_reference_id` (`reference_id`);

--
-- Indeks untuk tabel `tb_ref_sdm`
--
ALTER TABLE `tb_ref_sdm`
  ADD PRIMARY KEY (`id_sdm`),
  ADD KEY `idx_nidn` (`nidn`),
  ADD KEY `idx_nip` (`nip`),
  ADD KEY `idx_nuptk` (`nuptk`),
  ADD KEY `idx_nama_sdm` (`nama_sdm`),
  ADD KEY `idx_status_aktif` (`nama_status_aktif`),
  ADD KEY `idx_status_pegawai` (`nama_status_pegawai`),
  ADD KEY `idx_jenis_sdm` (`jenis_sdm`);

--
-- Indeks untuk tabel `tb_ref_sdm_jabatan_fungsional`
--
ALTER TABLE `tb_ref_sdm_jabatan_fungsional`
  ADD KEY `id_sdm` (`id_sdm`),
  ADD KEY `jabatan_fungsional` (`jabatan_fungsional`),
  ADD KEY `tanggal_mulai` (`tanggal_mulai`);

--
-- Indeks untuk tabel `tb_ref_sdm_pendidikan`
--
ALTER TABLE `tb_ref_sdm_pendidikan`
  ADD KEY `id_sdm` (`id_sdm`),
  ADD KEY `jenjang_pendidikan` (`jenjang_pendidikan`);

--
-- Indeks untuk tabel `tb_ref_sdm_penugasan`
--
ALTER TABLE `tb_ref_sdm_penugasan`
  ADD KEY `id_sdm` (`id_sdm`),
  ADD KEY `unit_kerja` (`unit_kerja`);

--
-- Indeks untuk tabel `tb_ref_unit`
--
ALTER TABLE `tb_ref_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `id_jenis_unit` (`id_jenis_unit`);

--
-- Indeks untuk tabel `tb_ref_wilayah`
--
ALTER TABLE `tb_ref_wilayah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `id_induk_wilayah` (`id_induk_wilayah`),
  ADD KEY `id_level_wilayah` (`id_level_wilayah`);

--
-- Indeks untuk tabel `tb_token`
--
ALTER TABLE `tb_token`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_google`
--
ALTER TABLE `user_google`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_google_id` (`google_id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pt_id`
--
ALTER TABLE `pt_id`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `s3_storage`
--
ALTER TABLE `s3_storage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sys_group_users`
--
ALTER TABLE `sys_group_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sys_lang`
--
ALTER TABLE `sys_lang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sys_menu`
--
ALTER TABLE `sys_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT untuk tabel `sys_menu_role`
--
ALTER TABLE `sys_menu_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT untuk tabel `sys_services`
--
ALTER TABLE `sys_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `sys_setting`
--
ALTER TABLE `sys_setting`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sys_token`
--
ALTER TABLE `sys_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `sys_users`
--
ALTER TABLE `sys_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT untuk tabel `tb_data_penelitian`
--
ALTER TABLE `tb_data_penelitian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT untuk tabel `tb_data_publikasi`
--
ALTER TABLE `tb_data_publikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_master_fakultas`
--
ALTER TABLE `tb_master_fakultas`
  MODIFY `id_fakultas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_master_pengaturan_umum`
--
ALTER TABLE `tb_master_pengaturan_umum`
  MODIFY `id_pengaturan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tb_reference_data`
--
ALTER TABLE `tb_reference_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_token`
--
ALTER TABLE `tb_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user_google`
--
ALTER TABLE `user_google`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=428;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `inpassing`
--
ALTER TABLE `inpassing`
  ADD CONSTRAINT `inpassing_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`);

--
-- Ketidakleluasaan untuk tabel `pendidikan_formal`
--
ALTER TABLE `pendidikan_formal`
  ADD CONSTRAINT `pendidikan_formal_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`);

--
-- Ketidakleluasaan untuk tabel `penugasan`
--
ALTER TABLE `penugasan`
  ADD CONSTRAINT `penugasan_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`);

--
-- Ketidakleluasaan untuk tabel `sys_menu_role`
--
ALTER TABLE `sys_menu_role`
  ADD CONSTRAINT `FK_sys_menu_role_sys_group_users` FOREIGN KEY (`group_level`) REFERENCES `sys_group_users` (`level`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_sys_menu_role_sys_menu` FOREIGN KEY (`id_menu`) REFERENCES `sys_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sys_token`
--
ALTER TABLE `sys_token`
  ADD CONSTRAINT `FK_sys_token_sys_services` FOREIGN KEY (`id_service`) REFERENCES `sys_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sys_users`
--
ALTER TABLE `sys_users`
  ADD CONSTRAINT `FK_sys_users_sys_group_users` FOREIGN KEY (`group_level`) REFERENCES `sys_group_users` (`level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
