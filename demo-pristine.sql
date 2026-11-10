/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sister_api
-- ------------------------------------------------------
-- Server version	11.4.12-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `data_pribadi`
--

DROP TABLE IF EXISTS `data_pribadi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_pribadi` (
  `id_sdm` varchar(36) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `id_jenis_kelamin` char(1) DEFAULT NULL,
  `id_agama` int(11) DEFAULT NULL,
  `id_status_perkawinan` int(11) DEFAULT NULL,
  `nama_ibu_kandung` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_sdm`),
  KEY `idx_data_pribadi_nik` (`nik`),
  KEY `idx_data_pribadi_nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_pribadi`
--

LOCK TABLES `data_pribadi` WRITE;
/*!40000 ALTER TABLE `data_pribadi` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_pribadi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fakultas`
--

DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fakultas` (
  `kode_fak` varchar(20) NOT NULL,
  `nama_resmi` varchar(50) DEFAULT NULL,
  `nama_singkat` varchar(50) DEFAULT NULL,
  `nama_asing` varchar(50) DEFAULT NULL,
  `nama_asingsingkat` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `web` varchar(50) DEFAULT NULL,
  `dekan` int(11) DEFAULT NULL,
  `kode_nim` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`kode_fak`),
  KEY `dekan` (`dekan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fakultas`
--

LOCK TABLES `fakultas` WRITE;
/*!40000 ALTER TABLE `fakultas` DISABLE KEYS */;
INSERT INTO `fakultas` VALUES
('101','SYARIAH','FS','SYARIAH',NULL,'','',46,'01'),
('102','TARBIYAH DAN ILMU KEGURUAN','FTIK','',NULL,'','',29,'02'),
('103','EKONOMI DAN BISNIS ISLAM','FEBI','',NULL,'','',18,'04'),
('104','USHULUDDIN, ADAB DAN DAKWAH','FUAD','',NULL,'','',26,'03'),
('201','PASCASARJANA','PASCA','',NULL,'','',8,'05');
/*!40000 ALTER TABLE `fakultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inpassing`
--

DROP TABLE IF EXISTS `inpassing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inpassing` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_jenis_sdm` int(11) DEFAULT NULL,
  `no_sk_inpassing` varchar(100) DEFAULT NULL,
  `tanggal_sk_inpassing` date DEFAULT NULL,
  `id_jabatan_fungsional` varchar(36) DEFAULT NULL,
  `tmt_jabatan_fungsional` date DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_inpassing_id_sdm` (`id_sdm`),
  CONSTRAINT `inpassing_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inpassing`
--

LOCK TABLES `inpassing` WRITE;
/*!40000 ALTER TABLE `inpassing` DISABLE KEYS */;
/*!40000 ALTER TABLE `inpassing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa_pddikti`
--

DROP TABLE IF EXISTS `mahasiswa_pddikti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa_pddikti` (
  `id` varchar(36) NOT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `id_program_studi` varchar(36) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_mahasiswa_id_perguruan_tinggi` (`id_perguruan_tinggi`),
  KEY `idx_mahasiswa_nim` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa_pddikti`
--

LOCK TABLES `mahasiswa_pddikti` WRITE;
/*!40000 ALTER TABLE `mahasiswa_pddikti` DISABLE KEYS */;
/*!40000 ALTER TABLE `mahasiswa_pddikti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendidikan_formal`
--

DROP TABLE IF EXISTS `pendidikan_formal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendidikan_formal` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `id_bidang_studi` int(11) DEFAULT NULL,
  `id_jenjang_pendidikan` varchar(10) DEFAULT NULL,
  `gelar_akademik` varchar(100) DEFAULT NULL,
  `tanggal_lulus` date DEFAULT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `no_ijazah` varchar(100) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pendidikan_formal_id_sdm` (`id_sdm`),
  CONSTRAINT `pendidikan_formal_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendidikan_formal`
--

LOCK TABLES `pendidikan_formal` WRITE;
/*!40000 ALTER TABLE `pendidikan_formal` DISABLE KEYS */;
/*!40000 ALTER TABLE `pendidikan_formal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penugasan`
--

DROP TABLE IF EXISTS `penugasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penugasan` (
  `id` varchar(36) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `id_ikatan_kerja` varchar(10) DEFAULT NULL,
  `id_unit_kerja` varchar(36) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(36) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `no_sk_penugasan` varchar(100) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_penugasan_id_sdm` (`id_sdm`),
  CONSTRAINT `penugasan_ibfk_1` FOREIGN KEY (`id_sdm`) REFERENCES `data_pribadi` (`id_sdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penugasan`
--

LOCK TABLES `penugasan` WRITE;
/*!40000 ALTER TABLE `penugasan` DISABLE KEYS */;
/*!40000 ALTER TABLE `penugasan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pt_id`
--

DROP TABLE IF EXISTS `pt_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pt_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pt_id` varchar(200) NOT NULL,
  `data_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data_detail`)),
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pt_id` (`pt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pt_id`
--

LOCK TABLES `pt_id` WRITE;
/*!40000 ALTER TABLE `pt_id` DISABLE KEYS */;
INSERT INTO `pt_id` VALUES
(1,'8f3e7954-2f89-44d9-9c9e-37b59b0d5e15','{\"rt\": 0, \"rw\": 0, \"bujur\": 0, \"dusun\": null, \"email\": \"info@uinsyahada.ac.id\", \"jalan\": \"Jalan T.Rizal Nurdin Km 4,5 Sihitang\", \"lintang\": 0, \"telepon\": \"0634 22080\", \"website\": \"https://www.uinsyahada.ac.id\", \"faximile\": \"0634 24022\", \"kode_pos\": \"22733\", \"kelurahan\": \"-\", \"id_wilayah\": \"076600  \", \"nama_wilayah\": \"Kota Padang Sidempuan\", \"sk_pendirian\": \"Perpres No 87 Tahun 2022\", \"id_status_milik\": \"1\", \"nama_status_milik\": \"Pemerintah Pusat\", \"id_perguruan_tinggi\": \"8f3e7954-2f89-44d9-9c9e-37b59b0d5e15\", \"sk_izin_operasional\": null, \"tanggal_sk_pendirian\": \"2022-06-08\", \"kode_perguruan_tinggi\": \"201029  \", \"nama_perguruan_tinggi\": \"Universitas Islam Negeri Syekh AIi Hasan Ahmad Addary Padangsidimpuan\", \"status_perguruan_tinggi\": \"A\", \"tanggal_izin_operasional\": null}','2025-08-09 22:32:43');
/*!40000 ALTER TABLE `pt_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s3_storage`
--

DROP TABLE IF EXISTS `s3_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `s3_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `bucket` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `secret` varchar(200) NOT NULL,
  `max_size` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s3_storage`
--

LOCK TABLES `s3_storage` WRITE;
/*!40000 ALTER TABLE `s3_storage` DISABLE KEYS */;
INSERT INTO `s3_storage` VALUES
(1,'profil','https://is3.cloudhost.id','BUCKET-NAME','KEY','SECRET-CODE','512000'),
(2,'file','https://is3.cloudhost.id','BUCKET-NAME','KEY','SECRET-CODE','512000');
/*!40000 ALTER TABLE `s3_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_group_users`
--

DROP TABLE IF EXISTS `sys_group_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_group_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `deskripsi` varchar(50) DEFAULT NULL,
  `akses_prodi` text DEFAULT NULL,
  `is_show` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_group_users`
--

LOCK TABLES `sys_group_users` WRITE;
/*!40000 ALTER TABLE `sys_group_users` DISABLE KEYS */;
INSERT INTO `sys_group_users` VALUES
(1,'root','root','Super Administrator','{\"akses\":\"74230,86208,86232,61406\"}','N'),
(2,'administrator','administrator','administrator','{\"akses\":\"74230,86208,86232,61406\"}','Y');
/*!40000 ALTER TABLE `sys_group_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_lang`
--

DROP TABLE IF EXISTS `sys_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` enum('Active','Not Active') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_lang`
--

LOCK TABLES `sys_lang` WRITE;
/*!40000 ALTER TABLE `sys_lang` DISABLE KEYS */;
INSERT INTO `sys_lang` VALUES
(1,'en','English','Not Active'),
(2,'id','Indonesia','Active');
/*!40000 ALTER TABLE `sys_lang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_menu`
--

DROP TABLE IF EXISTS `sys_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_act` varchar(150) DEFAULT NULL,
  `page_name` varchar(150) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `main_table` varchar(150) DEFAULT NULL,
  `icon` varchar(150) DEFAULT NULL,
  `urutan_menu` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `dt_table` enum('Y','N') NOT NULL,
  `tampil` enum('Y','N') NOT NULL,
  `type_menu` enum('main','page','separator') NOT NULL,
  `hide` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_menu`
--

LOCK TABLES `sys_menu` WRITE;
/*!40000 ALTER TABLE `sys_menu` DISABLE KEYS */;
INSERT INTO `sys_menu` VALUES
(4,NULL,'system setting',NULL,NULL,'fa-gear',3,0,0,'Y','Y','main','N'),
(5,'page','page','page','sys_menu','fa-newspaper-o',1,4,1,'Y','Y','page','N'),
(6,'user_group','user group','user-group','sys_users','fa-users',2,4,1,'Y','Y','page','N'),
(7,'user_managements','user managements','user-managements','sys_menu_role','fa-user',5,4,1,'Y','Y','page','N'),
(8,'menu_management','group permission','menu-management','sys_users','fa-lock',3,4,1,'Y','Y','page','N'),
(9,'excel','excel generator','excel','sys_services','fa-file-excel-o',4,4,1,'Y','Y','page','N'),
(10,'service','web service','service','sys_services','fa-server',6,4,1,'Y','Y','page','N'),
(12,'service_permission','web service permission','service-permission','sys_services','fa-bullseye',7,4,1,'Y','Y','page','N'),
(43,'pengaturan_umum','pengaturan umum','pengaturan-umum','tb_master_pengaturan_umum','fa-gear',1,52,1,'Y','Y','page','N'),
(45,NULL,'data','#',NULL,'fa-newspaper-o',1,0,0,'Y','Y','main','N'),
(52,NULL,'master','#',NULL,'fa-book',2,0,0,'Y','Y','main','N'),
(75,'referensi_data','referensi data','referensi-data','reference_data','',2,52,1,'Y','Y','page','N'),
(76,'data_sdm','data sdm','data-sdm','tb_ref_sdm','fa-users',3,52,1,'Y','Y','page','N'),
(77,'publikasi','publikasi','publikasi','tb_data_publikasi','',1,45,1,'Y','Y','page','N'),
(78,'penelitian','penelitian','penelitian','tb_data_penelitian','',2,45,1,'Y','Y','page','N'),
(79,'pengabdian','pengabdian','pengabdian','tb_data_pengabdian','',3,45,1,'Y','Y','page','N'),
(80,'hki','hki','hki','tb_data_hki','',4,45,1,'Y','Y','page','N');
/*!40000 ALTER TABLE `sys_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_menu_role`
--

DROP TABLE IF EXISTS `sys_menu_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_menu_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) DEFAULT NULL,
  `group_level` varchar(50) DEFAULT NULL,
  `read_act` enum('Y','N') DEFAULT NULL,
  `insert_act` enum('Y','N') DEFAULT NULL,
  `update_act` enum('Y','N') DEFAULT NULL,
  `delete_act` enum('Y','N') DEFAULT NULL,
  `import_act` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_menu_role_sys_menu` (`id_menu`),
  KEY `FK_sys_menu_role_sys_users` (`group_level`),
  CONSTRAINT `FK_sys_menu_role_sys_group_users` FOREIGN KEY (`group_level`) REFERENCES `sys_group_users` (`level`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_menu_role_sys_menu` FOREIGN KEY (`id_menu`) REFERENCES `sys_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_menu_role`
--

LOCK TABLES `sys_menu_role` WRITE;
/*!40000 ALTER TABLE `sys_menu_role` DISABLE KEYS */;
INSERT INTO `sys_menu_role` VALUES
(10,4,'root','Y','Y','Y','Y',NULL),
(11,4,'administrator','N','N','N','N',NULL),
(13,5,'root','Y','Y','Y','Y','Y'),
(14,5,'administrator','N','N','N','N',NULL),
(16,6,'root','Y','Y','Y','Y','Y'),
(17,6,'administrator','Y','Y','Y','N','Y'),
(26,7,'root','Y','Y','Y','Y','Y'),
(27,7,'administrator','Y','Y','Y','Y','Y'),
(29,8,'root','Y','Y','Y','Y','Y'),
(30,8,'administrator','Y','Y','Y','Y','Y'),
(32,9,'root','Y','Y','Y','Y','Y'),
(33,9,'administrator','N','N','N','N',NULL),
(35,10,'root','Y','Y','Y','Y','Y'),
(36,10,'administrator','N','N','N','N',NULL),
(41,12,'root','Y','Y','Y','Y','Y'),
(42,12,'administrator','N','N','N','N',NULL),
(168,43,'root','Y','Y','Y','Y','Y'),
(169,43,'administrator','Y','Y','Y','Y','Y'),
(176,45,'root','Y','Y','Y','Y',NULL),
(177,45,'administrator','Y','N','N','N',NULL),
(198,52,'root','Y','Y','Y','Y',NULL),
(199,52,'administrator','Y','N','N','N',NULL),
(325,75,'root','Y','Y','Y','Y','Y'),
(326,75,'administrator','Y','Y','N','N',NULL),
(328,76,'root','Y','Y','Y','Y','Y'),
(329,76,'administrator','Y','Y','N','N',NULL),
(331,77,'root','Y','Y','Y','Y','Y'),
(332,77,'administrator','Y','N','N','N',NULL),
(334,78,'root','Y','Y','Y','Y','Y'),
(335,78,'administrator','Y','N','N','N',NULL),
(337,79,'root','Y','Y','Y','Y','Y'),
(338,79,'administrator','Y','N','N','N',NULL),
(340,80,'root','Y','Y','Y','Y','Y'),
(341,80,'administrator','Y','N','N','N',NULL);
/*!40000 ALTER TABLE `sys_menu_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_services`
--

DROP TABLE IF EXISTS `sys_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_act` varchar(150) DEFAULT NULL,
  `page_name` varchar(150) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `main_table` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_services`
--

LOCK TABLES `sys_services` WRITE;
/*!40000 ALTER TABLE `sys_services` DISABLE KEYS */;
INSERT INTO `sys_services` VALUES
(1,'tes','tes','tes','bulan_aktif');
/*!40000 ALTER TABLE `sys_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_setting`
--

DROP TABLE IF EXISTS `sys_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `setting_content` mediumtext NOT NULL,
  `short_content` varchar(50) NOT NULL,
  `setting_type` enum('text','image','radio') NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_setting`
--

LOCK TABLES `sys_setting` WRITE;
/*!40000 ALTER TABLE `sys_setting` DISABLE KEYS */;
INSERT INTO `sys_setting` VALUES
(1,'Logo','logo','https://is3.cloudhost.id/storageimporter/1669678238128073335.png','','image'),
(2,'app_name','app_name','TRACER STUDY','TRACER','text');
/*!40000 ALTER TABLE `sys_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_token`
--

DROP TABLE IF EXISTS `sys_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `enable_token_read` enum('Y','N') NOT NULL,
  `enable_token_create` enum('Y','N') NOT NULL,
  `enable_token_update` enum('Y','N') NOT NULL,
  `enable_token_delete` enum('Y','N') NOT NULL,
  `format_data` enum('json','xml') NOT NULL,
  `read_access` mediumtext DEFAULT NULL,
  `create_access` mediumtext DEFAULT NULL,
  `update_access` mediumtext DEFAULT NULL,
  `delete_access` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_token_sys_services` (`id_service`),
  CONSTRAINT `FK_sys_token_sys_services` FOREIGN KEY (`id_service`) REFERENCES `sys_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_token`
--

LOCK TABLES `sys_token` WRITE;
/*!40000 ALTER TABLE `sys_token` DISABLE KEYS */;
INSERT INTO `sys_token` VALUES
(1,1,'Y','Y','Y','Y','json','[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]','[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]','[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]','[{\"user_id\":1,\"access\":\"1\",\"token\":\"63a9f0ea7bb98050796b649e85481845\"},{\"user_id\":10,\"access\":\"0\",\"token\":\"21232f297a57a5a743894a0e4a801fc3\"}]');
/*!40000 ALTER TABLE `sys_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_users`
--

DROP TABLE IF EXISTS `sys_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `token_expiration` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_users_sys_group_users` (`group_level`),
  CONSTRAINT `FK_sys_users_sys_group_users` FOREIGN KEY (`group_level`) REFERENCES `sys_group_users` (`level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_users`
--

LOCK TABLES `sys_users` WRITE;
/*!40000 ALTER TABLE `sys_users` DISABLE KEYS */;
INSERT INTO `sys_users` VALUES
(10,'administrator','admin','0192023a7bbd73250516f069df18b500','admin@gmail.com',NULL,'https://is3.cloudhost.id/storageimporter/16888257911611795108.png','administrator','Y','Y',NULL,NULL);
/*!40000 ALTER TABLE `sys_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_data_hki`
--

DROP TABLE IF EXISTS `tb_data_hki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_data_hki` (
  `id` char(36) NOT NULL,
  `id_sdm` char(36) NOT NULL,
  `kategori_kegiatan` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `quartile` varchar(50) DEFAULT NULL,
  `bidang_keilmuan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bidang_keilmuan`)),
  `jenis_publikasi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_data_hki`
--

LOCK TABLES `tb_data_hki` WRITE;
/*!40000 ALTER TABLE `tb_data_hki` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_data_hki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_data_penelitian`
--

DROP TABLE IF EXISTS `tb_data_penelitian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_data_penelitian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penelitian` char(40) NOT NULL,
  `id_sdm` char(40) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `bidang_keilmuan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bidang_keilmuan`)),
  `tahun_pelaksanaan` year(4) NOT NULL,
  `lama_kegiatan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_sdm` (`id_sdm`),
  KEY `id_penelitian` (`id_penelitian`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_data_penelitian`
--

LOCK TABLES `tb_data_penelitian` WRITE;
/*!40000 ALTER TABLE `tb_data_penelitian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_data_penelitian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_data_pengabdian`
--

DROP TABLE IF EXISTS `tb_data_pengabdian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_data_pengabdian` (
  `id` char(36) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `id_sdm` varchar(36) NOT NULL,
  `bidang_keilmuan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bidang_keilmuan`)),
  `tahun_pelaksanaan` year(4) NOT NULL,
  `lama_kegiatan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sdm` (`id_sdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_data_pengabdian`
--

LOCK TABLES `tb_data_pengabdian` WRITE;
/*!40000 ALTER TABLE `tb_data_pengabdian` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_data_pengabdian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_data_publikasi`
--

DROP TABLE IF EXISTS `tb_data_publikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_data_publikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori_kegiatan` int(11) NOT NULL,
  `id_sdm` varchar(36) DEFAULT NULL,
  `a_klaim_bkd` tinyint(1) NOT NULL DEFAULT 0,
  `wkt_klaim_bkd` datetime DEFAULT NULL,
  `judul` varchar(500) NOT NULL,
  `quartile` varchar(50) DEFAULT NULL,
  `jenis_publikasi` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori_kegiatan` varchar(500) NOT NULL,
  `asal_data` varchar(100) NOT NULL,
  `bidang_keilmuan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bidang_keilmuan`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_sdm` (`id_sdm`),
  KEY `id_kategori_kegiatan` (`id_kategori_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_data_publikasi`
--

LOCK TABLES `tb_data_publikasi` WRITE;
/*!40000 ALTER TABLE `tb_data_publikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_data_publikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_master_fakultas`
--

DROP TABLE IF EXISTS `tb_master_fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_master_fakultas` (
  `id_fakultas` int(11) NOT NULL AUTO_INCREMENT,
  `kode_fakultas` varchar(10) NOT NULL,
  `nama_fakultas` varchar(255) NOT NULL,
  `nama_singkat` varchar(255) NOT NULL,
  `nip_signature` varchar(50) DEFAULT NULL,
  `tipe_signature` varchar(50) DEFAULT NULL,
  `jabatan_signature` varchar(50) DEFAULT NULL,
  `image_signature` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_fakultas`),
  KEY `kode_fakultas` (`kode_fakultas`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_master_fakultas`
--

LOCK TABLES `tb_master_fakultas` WRITE;
/*!40000 ALTER TABLE `tb_master_fakultas` DISABLE KEYS */;
INSERT INTO `tb_master_fakultas` VALUES
(1,'101','SYARIAH','FS',NULL,NULL,NULL,NULL),
(2,'102','TARBIYAH DAN ILMU KEGURUAN','FTIK',NULL,NULL,NULL,NULL),
(3,'103','EKONOMI DAN BISNIS ISLAM','FEBI',NULL,NULL,NULL,NULL),
(4,'104','USHULUDDIN, ADAB DAN DAKWAH','FUAD',NULL,NULL,NULL,NULL),
(5,'201','PASCASARJANA','PASCA',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `tb_master_fakultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_master_jurusan`
--

DROP TABLE IF EXISTS `tb_master_jurusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_master_jurusan` (
  `kode_jur` int(11) NOT NULL,
  `id_jenj_didik` int(11) NOT NULL,
  `id_fakultas` int(11) DEFAULT NULL,
  `status` enum('A','T') DEFAULT NULL,
  `kode_dikti` varchar(20) DEFAULT NULL,
  `nama_jur` varchar(100) DEFAULT NULL,
  `nama_singkat` varchar(100) DEFAULT NULL,
  `nama_jur_asing` varchar(250) DEFAULT NULL,
  `tanggal_sk_dikti` date DEFAULT NULL,
  `nomor_sk_dikti` varchar(100) DEFAULT NULL,
  `visi_misi` mediumtext DEFAULT NULL,
  `tanggal_berdiri` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`kode_jur`),
  KEY `FK_jurusan_jenjang_pendidikan` (`id_jenj_didik`),
  KEY `fak_kode` (`id_fakultas`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_master_jurusan`
--

LOCK TABLES `tb_master_jurusan` WRITE;
/*!40000 ALTER TABLE `tb_master_jurusan` DISABLE KEYS */;
INSERT INTO `tb_master_jurusan` VALUES
(60202,30,103,'A',NULL,'Ekonomi Syariah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(61205,30,103,'A',NULL,'Manajemen Bisnis Syariah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(61206,30,103,'A',NULL,'Perbankan Syariah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(61211,30,103,'A',NULL,'Manajemen Keuangan Syariah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(70230,30,104,'A',NULL,'Manajemen Dakwah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(70232,30,102,'A',NULL,'Bimbingan dan Konseling Pendidikan Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(71201,30,104,'A',NULL,'Ilmu Perpustakaan dan Informasi Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(74130,35,201,'A',NULL,'Hukum Keluarga Islam (Ahwal Syakhshiyyah)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-16 09:32:03'),
(74230,30,101,'A',NULL,'Hukum Keluarga Islam (Ahwal Syakhshiyyah)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(74231,30,101,'A',NULL,'Hukum Pidana Islam (Jinayah)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(74234,30,101,'A',NULL,'Hukum Ekonomi Syariah (Muamalah)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(74237,30,104,'A',NULL,'Pemikiran Politik Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(74239,30,101,'A',NULL,'Hukum Tatanegara (Siyasah Syar\'iyyah)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(76231,30,104,'A',NULL,'Ilmu Al-Qur`an dan Tafsir',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(80230,30,104,'A',NULL,'Sejarah Peradaban Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(84202,30,102,'A',NULL,'Tadris Matematika',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(84203,30,102,'A',NULL,'Tadris Fisika',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(84204,30,102,'A',NULL,'Tadris Kimia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(84205,30,102,'A',NULL,'Tadris Biologi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(86108,35,201,'A',NULL,'Pendidikan Agama Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-16 09:32:12'),
(86137,35,201,'A',NULL,'Manajemen Pendidikan Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-16 09:32:18'),
(86208,30,102,'A',NULL,'Pendidikan Agama Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-16 09:32:24'),
(86231,30,102,'A',NULL,'Manajemen Pendidikan Islam',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(86236,30,102,'A',NULL,'Pendidikan Islam Anak Usia Dini',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(86906,31,102,'A',NULL,'Pendidikan Profesi Guru',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(88004,30,102,'A',NULL,'Pendidikan Bahasa Arab',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(88203,30,102,'A',NULL,'Tadris Bahasa Inggris',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `tb_master_jurusan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_master_pengaturan_umum`
--

DROP TABLE IF EXISTS `tb_master_pengaturan_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_master_pengaturan_umum` (
  `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pengaturan` varchar(255) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `isi_pengaturan` mediumtext NOT NULL,
  `type_pengaturan` enum('text','image','radio','extend','sosmed','link') NOT NULL,
  `isi_extend` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `tampil` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_master_pengaturan_umum`
--

LOCK TABLES `tb_master_pengaturan_umum` WRITE;
/*!40000 ALTER TABLE `tb_master_pengaturan_umum` DISABLE KEYS */;
INSERT INTO `tb_master_pengaturan_umum` VALUES
(1,'Logo Kampus','logo','https://sister.ftipd.org/upload/pengaturan/17942646999753.png','image',NULL,'Y'),
(13,'app_name','app_name','DASHBOARD DOSEN ','text',NULL,'Y'),
(14,'nama kampus','nama_kampus','UNIVERSITAS FTIPD KEMENAG','text',NULL,'Y'),
(15,'Favicon','favicon','https://is3.cloudhost.id/sidimpuan/1754571431148243033.png','image',NULL,'Y'),
(16,'s3 url','s3_url','https://is3.cloudhost.id/storageimporter/','text',NULL,'N');
/*!40000 ALTER TABLE `tb_master_pengaturan_umum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_sdm`
--

DROP TABLE IF EXISTS `tb_ref_sdm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_sdm` (
  `id_sdm` varchar(36) NOT NULL,
  `nama_sdm` varchar(255) NOT NULL,
  `nidn` varchar(200) DEFAULT NULL,
  `nip` varchar(200) DEFAULT NULL,
  `nuptk` varchar(200) DEFAULT NULL,
  `nama_status_aktif` varchar(50) DEFAULT NULL,
  `nama_status_pegawai` varchar(50) DEFAULT NULL,
  `jenis_sdm` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_sdm`),
  KEY `idx_nidn` (`nidn`),
  KEY `idx_nip` (`nip`),
  KEY `idx_nuptk` (`nuptk`),
  KEY `idx_nama_sdm` (`nama_sdm`),
  KEY `idx_status_aktif` (`nama_status_aktif`),
  KEY `idx_status_pegawai` (`nama_status_pegawai`),
  KEY `idx_jenis_sdm` (`jenis_sdm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_sdm`
--

LOCK TABLES `tb_ref_sdm` WRITE;
/*!40000 ALTER TABLE `tb_ref_sdm` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_sdm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_sdm_jabatan_fungsional`
--

DROP TABLE IF EXISTS `tb_ref_sdm_jabatan_fungsional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_sdm_jabatan_fungsional` (
  `id_sdm` varchar(100) NOT NULL,
  `jabatan_fungsional` varchar(200) NOT NULL,
  `sk` varchar(200) NOT NULL,
  `tanggal_mulai` varchar(200) NOT NULL,
  KEY `id_sdm` (`id_sdm`),
  KEY `jabatan_fungsional` (`jabatan_fungsional`),
  KEY `tanggal_mulai` (`tanggal_mulai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_sdm_jabatan_fungsional`
--

LOCK TABLES `tb_ref_sdm_jabatan_fungsional` WRITE;
/*!40000 ALTER TABLE `tb_ref_sdm_jabatan_fungsional` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_sdm_jabatan_fungsional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_sdm_pendidikan`
--

DROP TABLE IF EXISTS `tb_ref_sdm_pendidikan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_sdm_pendidikan` (
  `id_sdm` varchar(200) NOT NULL,
  `jenjang_pendidikan` varchar(10) NOT NULL,
  `datas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`datas`)),
  KEY `id_sdm` (`id_sdm`),
  KEY `jenjang_pendidikan` (`jenjang_pendidikan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_sdm_pendidikan`
--

LOCK TABLES `tb_ref_sdm_pendidikan` WRITE;
/*!40000 ALTER TABLE `tb_ref_sdm_pendidikan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_sdm_pendidikan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_sdm_penugasan`
--

DROP TABLE IF EXISTS `tb_ref_sdm_penugasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_sdm_penugasan` (
  `id_sdm` varchar(100) NOT NULL,
  `unit_kerja` varchar(200) NOT NULL,
  KEY `id_sdm` (`id_sdm`),
  KEY `unit_kerja` (`unit_kerja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_sdm_penugasan`
--

LOCK TABLES `tb_ref_sdm_penugasan` WRITE;
/*!40000 ALTER TABLE `tb_ref_sdm_penugasan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_sdm_penugasan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_unit`
--

DROP TABLE IF EXISTS `tb_ref_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_unit` (
  `id` varchar(200) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `id_jenis_unit` int(11) NOT NULL,
  `detail_unit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detail_unit`)),
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nama` (`nama`),
  KEY `id_jenis_unit` (`id_jenis_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_unit`
--

LOCK TABLES `tb_ref_unit` WRITE;
/*!40000 ALTER TABLE `tb_ref_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ref_wilayah`
--

DROP TABLE IF EXISTS `tb_ref_wilayah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_ref_wilayah` (
  `id` varchar(100) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `id_induk_wilayah` varchar(100) DEFAULT NULL,
  `id_level_wilayah` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nama` (`nama`),
  KEY `id_induk_wilayah` (`id_induk_wilayah`),
  KEY `id_level_wilayah` (`id_level_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ref_wilayah`
--

LOCK TABLES `tb_ref_wilayah` WRITE;
/*!40000 ALTER TABLE `tb_ref_wilayah` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_ref_wilayah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_reference_data`
--

DROP TABLE IF EXISTS `tb_reference_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_reference_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_type` varchar(100) NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nama` varchar(255) GENERATED ALWAYS AS (json_unquote(json_extract(`data`,_utf8mb4'$.nama'))) VIRTUAL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_reference` (`reference_type`,`reference_id`),
  KEY `idx_reference_type` (`reference_type`),
  KEY `idx_reference_id` (`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_reference_data`
--

LOCK TABLES `tb_reference_data` WRITE;
/*!40000 ALTER TABLE `tb_reference_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_reference_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_token`
--

DROP TABLE IF EXISTS `tb_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refresh_token` mediumtext DEFAULT NULL,
  `access_token` mediumtext DEFAULT NULL,
  `login` enum('Y','N') NOT NULL,
  `email` varchar(50) NOT NULL,
  `client_secret` varchar(150) NOT NULL,
  `client_id` varchar(150) NOT NULL,
  `redirect_url` varchar(150) NOT NULL,
  `aktif` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_token`
--

LOCK TABLES `tb_token` WRITE;
/*!40000 ALTER TABLE `tb_token` DISABLE KEYS */;
INSERT INTO `tb_token` VALUES
(1,'1//0g7dk8ryKSJxlCgYIARAAGBASNwF-L9IrtvMxckaZcOnLr5HAYKaMEhetKvcs13q7ZcQlttIym17rnjGsdCRKqmT9_dxClBG_ONg','{\"access_token\":\"ya29.a0AS3H6NwPsSRXT4vItzk4RPcFArNWprtjGX5YRSHJTWF7wFKRE1_jhNEMlZvKH0vmBfb9OiHYiETs6t6GoPZbHzDVEo8-Rlit3F7SAHQo0txN0Yk9zxoXCwBp2rTuTEui0xyBvSTJj4_VAzrDrVxodsIy4WuiGOehRnYz20dS0I8aCgYKAaYSARASFQHGX2MioAutHeTUsDoJYZmxGYci1w0178\",\"expires_in\":3599,\"scope\":\"https:\\/\\/www.googleapis.com\\/auth\\/drive.file\",\"token_type\":\"Bearer\",\"created\":1755487359,\"refresh_token\":\"1\\/\\/0g7dk8ryKSJxlCgYIARAAGBASNwF-L9IrtvMxckaZcOnLr5HAYKaMEhetKvcs13q7ZcQlttIym17rnjGsdCRKqmT9_dxClBG_ONg\"}','Y','','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','775034104084-aknet1fgmq85gkqvo3ic702qvps9h2c4.apps.googleusercontent.com','','Y');
/*!40000 ALTER TABLE `tb_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_google`
--

DROP TABLE IF EXISTS `user_google`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_google` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_id` varchar(64) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_google_id` (`google_id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=428 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_google`
--

LOCK TABLES `user_google` WRITE;
/*!40000 ALTER TABLE `user_google` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_google` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_nama_gelar`
--

DROP TABLE IF EXISTS `view_nama_gelar`;
/*!50001 DROP VIEW IF EXISTS `view_nama_gelar`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_nama_gelar` AS SELECT
 NULL AS `id_sdm`,
 NULL AS `nama_dengan_gelar` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sdm`
--

DROP TABLE IF EXISTS `view_sdm`;
/*!50001 DROP VIEW IF EXISTS `view_sdm`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_sdm` AS SELECT
 NULL AS `id_sdm`,
 NULL AS `nama_sdm`,
 NULL AS `nidn`,
 NULL AS `nip`,
 NULL AS `nuptk`,
 NULL AS `nama_status_aktif`,
 NULL AS `nama_status_pegawai`,
 NULL AS `jenis_sdm`,
 NULL AS `created_at`,
 NULL AS `updated_at`,
 NULL AS `pendidikan_terakhir`,
 NULL AS `home_base`,
 NULL AS `jafung` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sdm_details`
--

DROP TABLE IF EXISTS `view_sdm_details`;
/*!50001 DROP VIEW IF EXISTS `view_sdm_details`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_sdm_details` AS SELECT
 NULL AS `id_sdm`,
 NULL AS `nama_sdm`,
 NULL AS `nidn`,
 NULL AS `nip`,
 NULL AS `nuptk`,
 NULL AS `nama_status_aktif`,
 NULL AS `nama_status_pegawai`,
 NULL AS `jenis_sdm`,
 NULL AS `created_at`,
 NULL AS `updated_at`,
 NULL AS `pendidikan_terakhir`,
 NULL AS `home_base`,
 NULL AS `jafung` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sdm_jabatan_fungsional`
--

DROP TABLE IF EXISTS `view_sdm_jabatan_fungsional`;
/*!50001 DROP VIEW IF EXISTS `view_sdm_jabatan_fungsional`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_sdm_jabatan_fungsional` AS SELECT
 NULL AS `id_sdm`,
 NULL AS `nama_sdm`,
 NULL AS `jafung` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sdm_pendidikan`
--

DROP TABLE IF EXISTS `view_sdm_pendidikan`;
/*!50001 DROP VIEW IF EXISTS `view_sdm_pendidikan`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_sdm_pendidikan` AS SELECT
 NULL AS `id_sdm`,
 NULL AS `nama_sdm`,
 NULL AS `pendidikan` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_nama_gelar`
--

/*!50001 DROP VIEW IF EXISTS `view_nama_gelar`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_nama_gelar` AS select `v`.`id_sdm` AS `id_sdm`,case when `v`.`jafung` = 'Profesor' then concat('Prof. ',if(`g`.`gelar_s3` is not null,'Dr. ',''),`v`.`nama_sdm`,if(`g`.`gelar_s2` is not null,concat(', ',`g`.`gelar_s2`),'')) when `g`.`gelar_s3` is not null then concat('Dr. ',`v`.`nama_sdm`,if(`g`.`gelar_s2` is not null,concat(', ',`g`.`gelar_s2`),'')) when `g`.`gelar_s2` is not null then concat(`v`.`nama_sdm`,if(`g`.`gelar_s1` is not null,concat(', ',`g`.`gelar_s1`),''),', ',`g`.`gelar_s2`) else concat(`v`.`nama_sdm`,', ',`g`.`gelar_s1`) end AS `nama_dengan_gelar` from (`view_sdm` `v` left join (select `tb_ref_sdm_pendidikan`.`id_sdm` AS `id_sdm`,max(case when `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S1' then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end) AS `gelar_s1`,max(case when `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S2' then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end) AS `gelar_s2`,max(case when `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` = 'S3' then json_unquote(json_extract(`tb_ref_sdm_pendidikan`.`datas`,'$.gelar_akademik')) end) AS `gelar_s3` from `tb_ref_sdm_pendidikan` group by `tb_ref_sdm_pendidikan`.`id_sdm`) `g` on(`v`.`id_sdm` = `g`.`id_sdm`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sdm`
--

/*!50001 DROP VIEW IF EXISTS `view_sdm`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sdm` AS select `tr`.`id_sdm` AS `id_sdm`,`tr`.`nama_sdm` AS `nama_sdm`,`tr`.`nidn` AS `nidn`,`tr`.`nip` AS `nip`,`tr`.`nuptk` AS `nuptk`,`tr`.`nama_status_aktif` AS `nama_status_aktif`,`tr`.`nama_status_pegawai` AS `nama_status_pegawai`,`tr`.`jenis_sdm` AS `jenis_sdm`,`tr`.`created_at` AS `created_at`,`tr`.`updated_at` AS `updated_at`,(select `vs`.`pendidikan` from `view_sdm_pendidikan` `vs` where `tr`.`id_sdm` = `vs`.`id_sdm` limit 1) AS `pendidikan_terakhir`,(select `vsp`.`unit_kerja` from `tb_ref_sdm_penugasan` `vsp` where `tr`.`id_sdm` = `vsp`.`id_sdm` limit 1) AS `home_base`,(select `jf`.`jafung` from `view_sdm_jabatan_fungsional` `jf` where `tr`.`id_sdm` = `jf`.`id_sdm` limit 1) AS `jafung` from `tb_ref_sdm` `tr` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sdm_details`
--

/*!50001 DROP VIEW IF EXISTS `view_sdm_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sdm_details` AS select `tr`.`id_sdm` AS `id_sdm`,`tr`.`nama_sdm` AS `nama_sdm`,`tr`.`nidn` AS `nidn`,`tr`.`nip` AS `nip`,`tr`.`nuptk` AS `nuptk`,`tr`.`nama_status_aktif` AS `nama_status_aktif`,`tr`.`nama_status_pegawai` AS `nama_status_pegawai`,`tr`.`jenis_sdm` AS `jenis_sdm`,`tr`.`created_at` AS `created_at`,`tr`.`updated_at` AS `updated_at`,(select `vs`.`pendidikan` from `view_sdm_pendidikan` `vs` where `tr`.`id_sdm` = `vs`.`id_sdm` limit 1) AS `pendidikan_terakhir`,(select `vsp`.`unit_kerja` from `tb_ref_sdm_penugasan` `vsp` where `tr`.`id_sdm` = `vsp`.`id_sdm` limit 1) AS `home_base`,(select `jf`.`jafung` from `view_sdm_jabatan_fungsional` `jf` where `tr`.`id_sdm` = `jf`.`id_sdm` limit 1) AS `jafung` from `tb_ref_sdm` `tr` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sdm_jabatan_fungsional`
--

/*!50001 DROP VIEW IF EXISTS `view_sdm_jabatan_fungsional`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sdm_jabatan_fungsional` AS select `tr`.`id_sdm` AS `id_sdm`,`tr`.`nama_sdm` AS `nama_sdm`,(select `tb_ref_sdm_jabatan_fungsional`.`jabatan_fungsional` from `tb_ref_sdm_jabatan_fungsional` where `tb_ref_sdm_jabatan_fungsional`.`id_sdm` = `tr`.`id_sdm` order by `tb_ref_sdm_jabatan_fungsional`.`tanggal_mulai` desc limit 1) AS `jafung` from `tb_ref_sdm` `tr` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sdm_pendidikan`
--

/*!50001 DROP VIEW IF EXISTS `view_sdm_pendidikan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sdm_pendidikan` AS select `tr`.`id_sdm` AS `id_sdm`,`tr`.`nama_sdm` AS `nama_sdm`,(select `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` from `tb_ref_sdm_pendidikan` where `tb_ref_sdm_pendidikan`.`id_sdm` = `tr`.`id_sdm` order by `tb_ref_sdm_pendidikan`.`jenjang_pendidikan` desc limit 1) AS `pendidikan` from `tb_ref_sdm` `tr` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-11-09 22:54:29
