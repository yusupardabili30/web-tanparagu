-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 08:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tanparaguapps_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ikk`
--

CREATE TABLE `ikk` (
  `ikk_id` int(11) NOT NULL,
  `sasaran_id` int(11) DEFAULT NULL,
  `kode_ikk` varchar(250) DEFAULT NULL,
  `nama_ikk` varchar(45) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ikk`
--

INSERT INTO `ikk` (`ikk_id`, `sasaran_id`, `kode_ikk`, `nama_ikk`, `last_update`) VALUES
(1, 1, '67', '[1.1] Jumlah guru dan tenaga kependidikan', '2025-10-04 14:44:51'),
(2, 1, 'U', 'HJHJHJ', '2025-10-03 05:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `indikator`
--

CREATE TABLE `indikator` (
  `indikator_id` int(11) NOT NULL,
  `instrumen_id` int(11) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `indikator_code` varchar(45) DEFAULT NULL,
  `indikator_name` varchar(200) DEFAULT NULL,
  `indikator_desc` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indikator`
--

INSERT INTO `indikator` (`indikator_id`, `instrumen_id`, `no_urut`, `indikator_code`, `indikator_name`, `indikator_desc`, `created_at`) VALUES
(1, 1, 1, '1.1', 'Lingkungan pembelajaran yang aman dan  nyaman bagi murid', 'Lingkungan pembelajaran yang aman dan  nyaman bagi murid', '2025-11-20 06:01:23'),
(2, 1, 2, '1.2', 'Pembelajaran efektif yang berpusat pada \r murid', 'Pembelajaran efektif yang berpusat pada ', '2025-11-20 06:01:23'),
(3, 1, 3, '1.3', 'Asesmen, umpan balik, dan pelaporan yang \r berpusat pada murid', 'Asesmen, umpan balik, dan pelaporan yang ', '2025-11-20 06:01:23');

-- --------------------------------------------------------

--
-- Table structure for table `instrumen`
--

CREATE TABLE `instrumen` (
  `instrumen_id` int(11) NOT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `instrumen_name` varchar(200) DEFAULT NULL,
  `instrumen_desc` varchar(200) DEFAULT NULL,
  `instrumen_user` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instrumen`
--

INSERT INTO `instrumen` (`instrumen_id`, `no_urut`, `instrumen_name`, `instrumen_desc`, `instrumen_user`, `created_at`) VALUES
(1, 1, 'Instrumen Pemetaan Kebutuhan Belajar Guru', 'Instrumen Pemetaan Kebutuhan Belajar Guru', 'GR', '2025-11-20 06:01:41'),
(2, 2, 'Instrumen Pemetaan Kebutuhan Belajar Kepala Sekolah', 'Instrumen Pemetaan Kebutuhan Belajar Kepala Sekolah', 'KS', '2025-11-20 06:01:41'),
(3, 3, 'Instrumen Pemetaan Kebutuhan Belajar Kepala Pengawas Sekolah', 'Instrumen Pemetaan Kebutuhan Belajar Kepala Pengawas Sekolah', 'PS', '2025-11-20 06:01:41');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `kegiatan_id` int(255) NOT NULL,
  `kegiatan_name` varchar(200) NOT NULL,
  `entity` varchar(45) DEFAULT NULL,
  `instrumen_url` varchar(200) DEFAULT NULL,
  `instrumen_token` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`kegiatan_id`, `kegiatan_name`, `entity`, `instrumen_url`, `instrumen_token`, `start_date`, `end_date`, `status`, `created_at`, `modified_at`) VALUES
(1, 'Instrumen Pemetaan Kebutuhan Belajar Bagi Guru2', 'Guru', 'lockscreen/gAXBNX4q', 'xyz', '2025-06-20', '2025-06-25', 'Active', '2025-06-20 11:31:17', '2025-11-28 07:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `kota_id` int(11) NOT NULL,
  `nama_kota` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`kota_id`, `nama_kota`) VALUES
(1, 'Serang'),
(2, 'Serang Kab'),
(3, 'Tangerang'),
(4, 'Tangerang Kab'),
(5, 'Tangerang Selatan'),
(6, 'Lebak Kab'),
(7, 'Pandeglang Kab'),
(8, 'Cilegon');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pangkat_golongan`
--

CREATE TABLE `pangkat_golongan` (
  `pangkat_golongan_id` int(11) NOT NULL,
  `pangkat` varchar(45) DEFAULT NULL,
  `golongan` varchar(2) DEFAULT NULL,
  `deskripsi` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pangkat_golongan`
--

INSERT INTO `pangkat_golongan` (`pangkat_golongan_id`, `pangkat`, `golongan`, `deskripsi`) VALUES
(1, 'Juru Muda', 'Ia', NULL),
(2, 'Juru Muda Tk', 'Ib', NULL),
(3, 'Juru', 'Ic', NULL),
(4, 'Juru Tk I', 'Id', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `pegawai_id` int(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nip` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `pangkat` varchar(100) DEFAULT NULL,
  `golongan` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) NOT NULL,
  `jenis_jabatan` varchar(45) DEFAULT NULL,
  `status_kepegawaian` varchar(100) NOT NULL,
  `lokasi_kantor` varchar(100) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`pegawai_id`, `user_id`, `nip`, `nama`, `pangkat`, `golongan`, `jabatan`, `jenis_jabatan`, `status_kepegawaian`, `lokasi_kantor`, `status`) VALUES
(1, 1, '1972081120021210', 'Dr. Sugito Adiwarsito, M.Pd.Or.', 'Pembina Tk I, IV/b', NULL, 'Kepala Balai Guru Penggerak', 'Struktural', 'PNS', 'Serang', 'Aktif'),
(2, 2, '1979040520031220', 'Apriana Anggraini, M.Pd.', 'Penata Tk I, III/d', NULL, 'Kepala Sub Bagian', 'Struktural', 'PNS', 'Serang', 'Aktif'),
(3, 3, '1980032120140910', 'Abd. Rohman,', 'Pengatur Muda, II/a', NULL, 'Operator Layanan Operasional', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(4, 4, '1967111719920310', 'Abdul Munir, S.IP.,M.Pd', 'Pembina, IV/a', NULL, 'Pranata Laboratorium Pendidikan Ahli Muda', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(5, 5, '1967101120021210', 'Abdul Rojak, S.H.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(6, 6, '1990111020190220', 'Afiah Nuraeni, S.Pd.', 'Penata Muda Tk I,III/b', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(7, 7, '1973031020021210', 'Agung Dewoto, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(8, 8, '1969081319920310', 'Agus Poniman, S.Sos.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(9, 9, '1969080120021210', 'Agustono, S.Kom.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(10, 10, '1998082720201220', 'Amelia Dwi Agustina, A.Md.Bns.', 'Pengatur Tk I, II/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(11, 11, '1977060220021210', 'Andri Wahman, S.Sos, M.AP', 'Penata Tk I, III/d', NULL, 'Pustakawan Ahli Muda', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(12, 12, '1986092920091210', 'Andria Kusuma, S.E', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(13, 13, '1990060720190220', 'Anggia Ruhika, S.Pd.', 'Penata Muda Tk I,III/b', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(14, 14, '1996110920220320', 'Anita Ismarani Nurjanah, S.Pd.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(15, 15, '1977031120140910', 'Budi Mulyawan,', 'Pengatur, II/c', NULL, 'Operator Layanan Operasional', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(16, 16, '1972100119940320', 'Devi Kurniawati, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(17, 17, '1989032120201220', 'Diensha Restu Nurhayati Sumpena, S.I.Kom.', 'Penata Muda Tk I,III/b', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(18, 18, '1974041920060420', 'Duma Nursiah Eriwati Silitonga, S.P., M.M.', 'Pembina, IV/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(19, 19, '1972010520050120', 'Elvira Ratna Sari, S.Pd.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Srengseng', 'Aktif'),
(20, 20, '1980100320070110', 'Hadi, S.Pd', 'Penata, III/c', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(21, 21, '1972031320021210', 'Handoko, S.Pd,', 'Pengatur Muda, II/a', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(22, 22, '1970052719980310', 'Jamal Duwila, S.Sos.', 'Penata Tk I, III/d', NULL, 'Analis Kepegawaian Ahli Muda', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(23, 23, '1970052719980310', 'Jamaluddin,', 'Pengatur, II/c', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(24, 24, '1992102620220320', 'Laras Tsamrotul Fuadi, S.Psi.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Srengseng', 'Aktif'),
(25, 25, '1972050820050120', 'Manisem,', 'Penata Muda, III/a', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(26, 26, '1974080220050110', 'Maryono, S.IKom.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(27, 27, '1979080120060420', 'Maya Nurini, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(28, 28, '1994103020220310', 'Mohammad Irfai, S.Pd.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(29, 29, '1971062319920310', 'MOHAMMAD RAFIK, S.Sos, M.Si', 'Pembina, IV/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(30, 30, '1976051720021210', 'Muhammad Awang Eli Basya, A.Md.', 'Penata, III/c', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(31, 31, '1971091119920310', 'Muhammad Koriban, SE.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(32, 32, '1971052719930320', 'Muliandari, S.E.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(33, 33, '1969071519920310', 'Niman Hasan Aman,', 'Penata Muda Tk I,III/b', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(34, 34, '1989052320220320', 'Noni Lara Sestia, S.Psi.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Srengseng', 'Aktif'),
(35, 35, '1996111820220320', 'Novi Wulansari, S.Pd.', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(36, 36, '1970090719941220', 'Nurul Shobah, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(37, 37, '1975091720060420', 'Rachmasari Wulandari, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(38, 38, '1973060519930310', 'Rd. Ahmad Saleh, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(39, 39, '1976050420021220', 'Rifa Jamilah Khoiridha, S.E.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(40, 40, '1981071820060410', 'Rizki,', 'Pengatur Tk I, II/d', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(41, 41, '1981092220011220', 'Rosmelani Septianingsih, S.Pd.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(42, 42, '1975040520021220', 'Rr. Ida Kuswardati, SE.', 'Penata Muda Tk I,III/b', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(43, 43, '1972112020021210', 'Rudi Bakti Lubis, ST., M.M.', 'Pembina, IV/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(44, 44, '1972112020021210', 'Rudi Bakti Lubis, ST., M.M.', 'Pembina, IV/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(45, 45, '1971053020140910', 'Sismulyadi, SM', 'Penata Muda, III/a', NULL, 'Operator Layanan Operasional', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(46, 46, '1980072920050120', 'Sri Handayani, S.Pd.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(47, 47, '1968122720021210', 'Subali, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(48, 48, '1971022419920310', 'Sukarna Sadja, S. Pd.', 'Penata Tk I, III/d', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(49, 49, '1980062620060420', 'Sulastri Handayani, S.Pd', 'Penata, III/c', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(50, 50, '1970091419930310', 'SURYA,', 'Penata Muda Tk I,III/b', NULL, 'Pengadministrasi Perkantoran', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(51, 51, '1967072020021210', 'Untung Sabaryanto, S.Pd.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(52, 52, '1982052120101210', 'Urdik Hadianto, S.Si.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(53, 53, '1981081520050110', 'Wendi, S.E.', 'Penata Muda Tk I,III/b', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(54, 54, '1970052120021210', 'wisnu subroto,', 'Penata Muda Tk I,III/b', NULL, 'Operator Layanan Operasional', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(55, 55, '1983051120031220', 'Yesi Indriyanti, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(56, 56, '1979061920060420', 'Yuni Purwanti, M.M.', 'Pembina, IV/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(57, 57, '1978060620021220', 'Yuni Tuningrum, S.H.', 'Penata Tk I, III/d', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(58, 58, '1990013020201210', 'Yusup Ardabili, A.Md.', 'Pengatur Tk I, II/d', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Serang', 'Aktif'),
(59, 59, '1981120820091210', 'Zaenal Abidin, A. Md.', 'Penata Muda Tk I,III/b', NULL, 'Pengolah Data dan Informasi', 'Pelaksana PNS', 'PNS', 'Parung', 'Aktif'),
(60, 60, '1992033120220310', 'Zakaria Budiman, S.Psi', 'Penata Muda, III/a', NULL, 'Penelaah Teknis Kebijakan', 'Pelaksana PNS', 'PNS', 'Srengseng', 'Aktif'),
(61, 61, '1972031520060410', 'Abdullah, M.Pd', 'Penata Muda, III/a', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(62, 62, '1977081720031210', 'Agustiana Ramdani, S.Si.,M.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(63, 63, '1978103020031210', 'Ali Winata, S.Pd, SF, M.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(64, 64, '1975062120011220', 'Ani Purwati, S.Pd, M.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(65, 65, '1976041820050120', 'Anita Priyatmi, S.Pd., M.Ed.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(66, 66, '1967040720011210', 'Aris Munandar, M.Pd', 'Penata, III/c', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(67, 67, '1972080520050110', 'ARIS SUPRIYANTO, M.Pd', 'Pembina, IV/a', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(68, 68, '1974100520021220', 'Ati Rosidah, S.Ag. M.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(69, 69, '1972072720070110', 'Dede Harsudin, SE,M.SI,MM', 'Penata Tk I, III/d', NULL, 'Perencana Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(70, 70, '1974111520060410', 'Dedi Supriyanto, M.Pd.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(71, 71, '1972080319930310', 'Dr Yudha Prapantja, M.Pd', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(72, 72, '1973050220021210', 'Dr. Ahmad Ghozi, M.Pd., M.A.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(73, 73, '1973010520050120', 'Dr. Dewi Setiawati, M.Pd', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(74, 74, '1969102320021220', 'Dr. Endah Ariani Madusari, M.Pd.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(75, 75, '1982122520090320', 'Dr. Eny Usmawati, M.Pd.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(76, 76, '1970092120021210', 'Dr. WIDIATMOKO, M.Pd.', 'Pembina Utama Muda, IV/c', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(77, 77, '1965081119850320', 'Dra ENDANG SUPRIATI, M.Pd.', 'Pembina, IV/a', NULL, 'Pengembang Teknologi Pembelajaran Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(78, 78, '1968040720021220', 'Dra. Emy Widiarti, M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(79, 79, '1967102920021210', 'Drs. Taufik Nugroho, M.Hum.', 'Pembina Utama Muda, IV/c', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(80, 80, '1968022520021210', 'Dwi Cahyo Widodo, S.Pd., M.Pd.', 'Penata Muda, III/a', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(81, 81, '1984013020060420', 'Dwi Hadi Mulyaningsih, M.Pd. .', 'Penata Muda Tk I,III/b', NULL, 'Widyaiswara Ahli Pertama', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(82, 82, '1971100719920320', 'Dwi Nindriyati, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(83, 83, '1979051420050120', 'Dwi Puspitasari, S.Pd., M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(84, 84, '1970010820021220', 'Dwi Yoga Peny Hadyanti, S.Pd., M.Pd.', 'Pembina Utama Muda, IV/c', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(85, 85, '1974120720021211', 'Endang Kurniawan, M.Pd.', 'Penata Muda, III/a', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(86, 86, '1968022320031220', 'Endang Setiariny, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(87, 87, '1973120720021220', 'Endang Setyorini, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(88, 88, '1974051220050120', 'Endang Susilowati, M.Pd.', 'Pengatur Tk I, II/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(89, 89, '1971121320021210', 'Gunawan Widiyanto, M.Hum.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(90, 90, '1976062220031220', 'Handini, SS, M.Pd.', 'Penata, III/c', NULL, 'Pengembang Teknologi Pembelajaran Ahli Pertama', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(91, 91, '1976100920050110', 'Hardiyanto, M. Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(92, 92, '1969020119990310', 'Hargio Santoso, M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(93, 93, '1976042520050110', 'Hari Wibowo, S.S., M.Pd.', 'Pembina Utama Muda, IV/c', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(94, 94, '1976042520050110', 'Hari Wibowo, S.S., M.Pd.', 'Pembina Utama Muda, IV/c', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(95, 95, '1973020520031210', 'Herwan Febriyadi, S.Si, M.Pd', 'Pembina, IV/a', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(96, 96, '1981110720091210', 'Iis Handoko, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(97, 97, '1977011820011220', 'Indriyati, S.S, MTrainDev.', 'Penata Muda, III/a', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(98, 98, '1978042820050120', 'Jehan Ananda Aliyah KH, M.A.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(99, 99, '1982050220050120', 'Kardina Pendikarini, S.S., M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(100, 100, '1969122520000310', 'Kristiawan, S.E., M.Si.', 'Pembina Tk I, IV/b', NULL, 'Pengembang Teknologi Pembelajaran Ahli Madya', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(101, 101, '1982040920060420', 'Lestari Puspitaningsih, S.S.,S.Sos.,M.A.', 'Pembina, IV/a', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(102, 102, '1977032420021220', 'Lia Herawaty, S.S., M.Pd., S.S., M.Pd.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(103, 103, '1975070720021210', 'M. Isnaini, S.Pd, M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(104, 104, '1983030320080120', 'Mulawarni, S.S.,M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(105, 105, '1976091720021210', 'Mulyadi, S.Ag., M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(106, 106, '1986022520150410', 'Naro Prasetyo, M.Pd.', 'Penata Muda Tk I,III/b', NULL, 'Pengembang Teknologi Pembelajaran Ahli Pertama', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(107, 107, '1972073020050120', 'Pininto Sarwendah, M.A.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(108, 108, '1969012619920310', 'Prasetiyo, M. Pd.', 'Pengatur Muda, II/a', NULL, 'Widyaiswara Ahli Pertama', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(109, 109, '1967121420011210', 'Raden Roy Miftahul Huda, M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(110, 110, '1987071820101210', 'Richard Amri, S.Pd., M.Pd.', 'Penata Muda Tk I,III/b', NULL, 'Pengembang Teknologi Pembelajaran Ahli Pertama', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(111, 111, '1976011122005012', 'Ririk Ratnasari, M.Pd.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(112, 112, '1974072520021220', 'Siti Nurhayati, S.Pd.,Sp.I.,M.Pd', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(113, 113, '1974122820031220', 'Sri Murwati, S.Si, M.Si.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(114, 114, '1972020220060410', 'Suhardi, M.Pd.', 'Pembina, IV/a', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(115, 115, '1968121520021210', 'Sutomo, S.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Parung', 'Aktif'),
(116, 116, '1976042820031210', 'Taufiq Rahman, S.Pd., M.Pd.', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(117, 117, '1972112720031220', 'Tisnuliyah, S.Si, M.Pd', 'Penata Tk I, III/d', NULL, 'Pengembang Teknologi Pembelajaran Ahli Muda', 'Fungsional', 'PNS', 'Serang', 'Aktif'),
(118, 118, '1974091220001220', 'Virgo Rita Furyani, S.Pd., M.Ed.', 'Penata Tk I, III/d', NULL, 'Widyaiswara Ahli Muda', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(119, 119, '1971062520050120', 'Wahyuningrum, S.Pd., M.Pd.', 'Pembina Tk I, IV/b', NULL, 'Widyaiswara Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(120, 120, '1972090420031210', 'Widyatmo , M.Pd.', 'Pembina, IV/a', NULL, 'Pengembang Teknologi Pembelajaran Ahli Madya', 'Fungsional', 'PNS', 'Srengseng', 'Aktif'),
(121, 121, '1981072601', 'Abdul Rosid', '-', NULL, 'Pengemudi', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(122, 122, '1990111502', 'Akhbarrulloh', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(123, 123, '1974012303', 'ALAM SYAFRUDIN', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(124, 124, '1998100204', 'Alishofi', '-', NULL, 'Petugas Keamanan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(125, 125, '1980011005', 'Andry Yanuar Arifin, S.T.', '-', NULL, 'Teknisi Sarana dan Prasarana', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(126, 126, '1990102806', 'Asep Nurul Hadi, SE', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(127, 127, '2024091407', 'Dahlan', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(128, 128, '1976121808', 'Darmayadi', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(129, 129, '1985082309', 'Eka Hermawan, A.Md.Kom', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(130, 130, '1988052510', 'Eka Saputra Jaya, S.E.,S.K.M', '-', NULL, 'Pengadministrasi Keuangan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(131, 131, '2025030711', 'Fitriyanto agung prastowo', '-', NULL, 'Pengemudi', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(132, 132, '1995122812', 'Global Ilham Sampurno S.Kom. M.Kom', '-', NULL, 'Analis Sistem Informasi dan Jaringan', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(133, 133, '1973030413', 'Haerul sholeh', '-', NULL, 'Petugas Keamanan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(134, 134, '1995100314', 'Linda oktavia', '-', NULL, 'Resepsionis', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(135, 135, '1985102015', 'M. Armin', '-', NULL, 'Pramu Kebersihan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(136, 136, '1976071616', 'Muchlis', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(137, 137, '1975040317', 'MUHAMAD SYARIFUDIN', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(138, 138, '1993090418', 'Muhammad Antoni', '-', NULL, 'Petugas Keamanan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(139, 139, '1979082419', 'Muhammad Anwar, Amd.,Kom', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(140, 140, '1993112120', 'Muhammad Khoerul Amri, S.I.Kom.', '-', NULL, 'Pengadministrasi Data Penyajian dan Publikasi', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(141, 141, '2001112621', 'Nabil Alwan', '-', NULL, 'Pramu Kebersihan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(142, 142, '1993060222', 'Nahri Juniyansyah, SM', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(143, 143, '1991031023', 'Nuris Azizah, S.Pd', '-', NULL, 'Pengadministrasi Keuangan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(144, 144, '1988021424', 'Ryaldi Ebri, S.Pd.', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(145, 145, '1973090425', 'Sarkim', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(146, 146, '1999050126', 'Selfy Alfia Wijayanti', '-', NULL, 'Resepsionis', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(147, 147, '1983021727', 'Sitanala vanhouten', '-', NULL, 'Pengemudi', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(148, 148, '1976040228', 'Syamsuddin', '-', NULL, 'Pramu Kebersihan', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(149, 149, '1994051529', 'Umapruhi', '-', NULL, 'Pramu Kebersihan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(150, 150, '1994071030', 'Umin Khotamin, S.Pd.', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(151, 151, '1994080531', 'Wanda Tendi', '-', NULL, 'Petugas Keamanan', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(152, 152, '1994011532', 'Yanuar Suwastito Widodo', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(153, 153, '1980071633', 'Yuli Irawati, S.Pd', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(154, 154, '1995092834', 'Yunda Gusman, S.A.P', '-', NULL, 'Pengadministrasi Umum', 'Pelaksana Non PNS', 'NON PNS', 'Serang', 'Aktif'),
(155, 155, '1976021035', 'YUYUN', '-', NULL, 'Pramubakti', 'Pelaksana Non PNS', 'NON PNS', 'Parung', 'Aktif'),
(156, 156, '2025010101', 'adm.persuratan01', '', NULL, 'Admin Persuratan', '', 'PNS', '', 'Aktif'),
(157, 157, '2025010102', 'adm.persuratan02', '', NULL, 'Admin Persuratan', '', 'PNS', '', 'Aktif'),
(158, 158, '2025010103', 'adm.persuratan03', '', NULL, 'Admin Persuratan', '', 'PNS', '', 'Aktif'),
(159, 159, '2025010104', 'adm.kepeg01', '', NULL, 'Admin Kepegawaian', '', 'PNS', '', 'Aktif'),
(160, 160, '2025010105', 'adm.kepeg02', '', NULL, 'Admin Kepegawaian', '', 'PNS', '', 'Aktif'),
(161, 161, '2025010106', 'adm.publikasi01', '', NULL, 'Admin Publikasi', '', 'PNS', '', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `peran_id` int(11) NOT NULL,
  `peran` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`peran_id`, `peran`) VALUES
(1, 'Pengarah'),
(2, 'Penanggung Jawab'),
(3, 'Panitia'),
(4, 'Pemonev'),
(5, 'Fasilitator'),
(6, 'Peserta');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(11) NOT NULL,
  `nama_program` varchar(100) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `nama_program`, `last_update`) VALUES
(1, 'Koding dan Kecerdasan Artifisial', NULL),
(2, 'Pembelajaran Mendalam', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ptk`
--

CREATE TABLE `ptk` (
  `ptk_id` int(11) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nip` varchar(19) DEFAULT NULL,
  `nuptk` varchar(19) DEFAULT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `jenis_kelamin` varchar(2) DEFAULT NULL,
  `tempat_lahir` varchar(45) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(45) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `no_hp` varchar(16) DEFAULT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat_rumah` varchar(200) DEFAULT NULL,
  `alamat_rumah_kota` varchar(45) DEFAULT NULL,
  `instansi` varchar(100) DEFAULT NULL,
  `alamat_kantor` varchar(200) DEFAULT NULL,
  `alamat_kantor_kota` varchar(45) DEFAULT NULL,
  `no_rekening` varchar(45) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ptk`
--

INSERT INTO `ptk` (`ptk_id`, `nik`, `nip`, `nuptk`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `agama`, `pendidikan`, `no_hp`, `npwp`, `email`, `alamat_rumah`, `alamat_rumah_kota`, `instansi`, `alamat_kantor`, `alamat_kantor_kota`, `no_rekening`, `last_update`) VALUES
(1, '123', '197010021990092001', '9334748650300053', 'Sopiah', 'P', NULL, NULL, 'Islam', NULL, '087888756443', '7656355547', 'sample@mail.com', 'GSI/KORPRI BLOK D9/2', '', NULL, NULL, NULL, NULL, NULL),
(2, '3601066802650001', '196502281986032008', '5560743644300002', 'M NAJAR', 'L', NULL, NULL, 'Islam', NULL, '0876543320', '7656355547', 'sample@mail.com', 'GSI/KORPRI BLOK D9/2', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role` varchar(45) DEFAULT NULL,
  `desc` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role`, `desc`) VALUES
(1, 'Administrator', 'Administrator'),
(2, 'Supervisor', 'Supervisor'),
(3, 'Katim', 'Ketua Tim Kerja'),
(4, 'Tim', 'Tim'),
(5, 'Persuratan', 'Persuratan');

-- --------------------------------------------------------

--
-- Table structure for table `soal`
--

CREATE TABLE `soal` (
  `soal_id` int(11) NOT NULL,
  `sub_indikator_id` int(11) DEFAULT NULL,
  `sub_indikator_code` varchar(45) DEFAULT NULL,
  `soal_case_id` int(11) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `soal` varchar(400) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soal`
--

INSERT INTO `soal` (`soal_id`, `sub_indikator_id`, `sub_indikator_code`, `soal_case_id`, `no_urut`, `level`, `soal`, `created_at`) VALUES
(1, 1, '1.1', 1, 1, 2, 'Jika Anda sebagai guru Titis, manajemen pengelolaan apa yang akan ldiakukan yang paling tepat dan efektif untuk mengurangi kebiasaan menyela saat guru menjelaskan dan mengganggu teman-temannya saat belajar.... ', '2025-11-20 06:00:29'),
(2, 1, '1.1', 1, 2, 3, 'Jika Anda guru Titis dan  ingin memahami akar masalah perilaku Titis, langkah analisis apa yang akan anda lakukan dan menurut anda yang paling tepat ? ', '2025-11-20 06:00:29'),
(3, 1, '1.1', 1, 3, 4, 'Jika Anda  mencoba menegur Titis di depan kelas untuk menghentikan perilakunya. Namun, perilaku tersebut tetap berulang . Maka evaluasi pendekatan yang Anda Lakukan  menunjukkan bahwa…', '2025-11-20 06:00:29'),
(4, 1, '1.1', 1, 4, 5, 'Jika Anda adalah Bu Anita pada situasi tersebut, langkah konkret apa yang akan Anda lakukan untuk memastikan semua murid terlibat aktif dalam diskusi kelompok?', '2025-11-20 06:00:29'),
(5, 2, '1.2', 2, 1, 2, 'Jika Anda adalah Bu Anita pada situasi tersebut, langkah konkret apa yang akan Anda lakukan untuk memastikan semua murid terlibat aktif dalam diskusi kelompok?', '2025-11-20 06:00:29'),
(6, 2, '1.2', 2, 2, 3, 'Jika Anda menilai secara objektif, strategi pengelolaan kelas apa yang paling kritis untuk segera diperbaiki oleh bu Anita agar pembelajaran menjadi lebih efektif? Jelaskan alasan analitisnya.', '2025-11-20 06:00:29'),
(7, 2, '1.2', 2, 3, 4, 'Bila Anda sebagai bu Anita, berikan keputusan berdasarkan bukti: perubahan apa yang paling prioritas yang perlu Anda lakukan untuk meningkatkan kualitas pengelolaan kelas dan mengapa perubahan tersebut lebih penting daripada perubahan lainnya?', '2025-11-20 06:00:29'),
(8, 2, '1.2', 2, 4, 5, 'Bagaimana Anda akan membimbing bu Anita yang mengalami kesulitan dalam memastikan seluruh murid terlibat aktif dalam diskusi kelompok, sehingga ia dapat meningkatkan pengelolaan kelas secara mandiri dan berkelanjutan?', '2025-11-20 06:00:29');

-- --------------------------------------------------------

--
-- Table structure for table `soal_case`
--

CREATE TABLE `soal_case` (
  `soal_case_id` int(11) NOT NULL,
  `sub_indikator_id` int(11) DEFAULT NULL,
  `sub_indikator_code` varchar(45) DEFAULT NULL,
  `tittle` varchar(200) DEFAULT NULL,
  `case` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soal_case`
--

INSERT INTO `soal_case` (`soal_case_id`, `sub_indikator_id`, `sub_indikator_code`, `tittle`, `case`, `created_at`) VALUES
(1, 1, '1.1', NULL, 'Titis adalah murid disebuah sekolah  di pinggiran kota.Ia dikenal cerdas dan kritis, namun sering menunjukkan perilaku yang mengganggu di kelas: Setiap guru menjelaskan dan menyampaikan materi Titis selalu menyela, selalu berisik dan mengganggu teman yang sedang belajar, setiap guru memberikan tugas baik individu maupun kelompok, selalu tidak selesai dan kadang enggan mengerjakan tugas. Ia juga sering duduk sendiri di barisan tengah, menjauh dari kelompok.teman dikelas juga enggan satu kelompok. Diakhir semester prestasi Titis turun dari kelas sebelumnya akibat perbuatannya selama dikelas.', '2025-11-16 09:45:46'),
(2, 2, '1.2', NULL, 'Bu Anita sedang melaksanakan pembelajaran dengan metode diskusi kelompok. Ia memulai kelas dengan penjelasan singkat, kemudian meminta murid bekerja dalam kelompok untuk menyelesaikan tugas analisis sederhana.\n\nNamun, selama kegiatan berlangsung muncul beberapa kondisi: Ada kelompok yang sangat cepat menyelesaikan tugas, sementara yang lain tampak bingung dan tidak tahu harus mulai dari mana, hanya 2–3 orang dalam tiap kelompok yang aktif bekerja, sementara anggota lainnya hanya menunggu instruksi atau mengobrol, bu Anita hanya memberi instruksi umum (“Diskusikan tugas ini dalam kelompok”), tanpa penjelasan langkah kerja, pembagian peran (misalnya pencatat, pembicara, penanya), atau kriteria keberhasilan, meja masih tersusun seperti barisan biasa sehingga murid harus memindahkan kursi sendiri secara tidak teratur, menghabiskan waktu, dan menciptakan suasana gaduh, sehingga kesalahan konsep atau miskomunikasi selama diskusi tidak tertangani sejak awal.', '2025-11-16 22:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `soal_jawaban`
--

CREATE TABLE `soal_jawaban` (
  `soal_jawaban_id` int(11) NOT NULL,
  `soal_id` int(11) DEFAULT NULL,
  `pilihan_jawaban` mediumtext DEFAULT NULL,
  `bobot` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soal_jawaban`
--

INSERT INTO `soal_jawaban` (`soal_jawaban_id`, `soal_id`, `pilihan_jawaban`, `bobot`, `created_at`) VALUES
(1, 1, 'Memberikan teguran langsung di depan kelas agar menjadi contoh bagi siswa lain, sehingga Titis akan merubah perperilakunya mengganggu teman-temannya saat belajar', 1, '2025-11-20 06:00:59'),
(2, 1, 'Memindahkan  tempat duduk Titis ke barisan paling depan sehingga   lebih mudah untuk diawasi dan memperhatikan  tingkah laku Titis\n', 2, '2025-11-20 06:00:59'),
(3, 1, 'Memberikan waktu kepada Titis untuk memahami bahwa  tingkah lakunya salah dan sehingga sadar dan  berhenti dengan sendirinya', 3, '2025-11-20 06:00:59'),
(4, 1, 'Meluangkan dan menyediakan waktu serta tugas khusus  kepada Titis secara individu, dan meminta untuk mempresentasikanhasilnya di depan kelas', 4, '2025-11-20 06:00:59'),
(5, 2, 'Saya akan meluangkan waktu dan memberikan tes kepribadian kepada Titis', 1, '2025-11-20 06:00:59'),
(6, 2, 'Saya akan menanyakan kepada  orang tua dan teman-teman Titis tentang kebiasaannya sehari-hari', 2, '2025-11-20 06:00:59'),
(7, 2, 'Saya akan menyusun laporan semua perilaku perilaku Titis dan menyerahkannya ke kepala sekolah dan orangtua', 3, '2025-11-20 06:00:59'),
(8, 2, 'Saya akan mengamati perilaku Titis setiap hari  secara sistematis dan dan mencatatnya untuk melakukan dialog pribadi', 4, '2025-11-20 06:00:59'),
(9, 3, 'Teguran langsung yang hanya bersifat verbal yang saya lakukan tidak berdampak, sehingga saya  akan mengganti dengan hukuman fisik ringan', 1, '2025-11-20 06:00:59'),
(10, 3, 'Saya akan terus memberikan teguran langsung  dan memberikan hukuman fisik ringan lebih sering agar berdampak', 2, '2025-11-20 06:00:59'),
(11, 3, 'Teguran langsung dan hukuman fisik ringan yang saya lakukan sangat efektif karena menunjukkan ketegasan guru', 3, '2025-11-20 06:00:59'),
(12, 3, 'Teguran langsung dan hukuman fisik ringan kurang tepat karena dapat mempermalukan dan memperburuk hubungan guru-siswa', 4, '2025-11-20 06:00:59'),
(13, 4, 'Memberikan hukuman setiap kali berulah agar Titis jera dan berubah', 1, '2025-11-20 06:00:59'),
(14, 4, 'Menyampaikan keluhan dan catatan perilaku kepada orang tua tanpa berbicara dengan Titis', 2, '2025-11-20 06:00:59'),
(15, 4, 'Menyusun laporan perilaku dan menyerahkannya ke kepala sekolah dan orang tua untuk dievaluasi', 3, '2025-11-20 06:00:59'),
(16, 4, 'Mengajak Titis berbicara secara pribadi untuk membangun kepercayaan dan memahami latar belakangnya', 4, '2025-11-20 06:00:59'),
(17, 5, 'Menegur murid yang tidak terlibat untuk kembali fokus pada kelompoknya dan alur diskusi yang sedang berlangsung.', 1, '2025-11-20 06:00:59'),
(18, 5, 'Membagi ulang anggota kelompok agar lebih seimbang,  sehingga interaksi tetap bergantung pada dinamika masing-masing kelompok.', 2, '2025-11-20 06:00:59'),
(19, 5, 'Memberikan peran spesifik (misalnya pencatat, pembicara, penanya, pengamat) dan lembar panduan tugas agar setiap murid memiliki tanggung jawab yang jelas dalam diskusi kelompok.', 3, '2025-11-20 06:00:59'),
(20, 5, 'Memfasilitasi peran dan panduan tugas, kemudian memantau proses, memberikan umpan balik, serta mengajak kelompok merefleksikan dinamika kerja mereka agar dapat menyesuaikan strategi kolaborasi pada kegiatan berikutnya.', 4, '2025-11-20 06:00:59'),
(21, 6, 'Terlebih dahulu memperketat aturan kelas, karena dengan menjalankan aturan secara lebih ketat murid akan menjadi lebih tertib dan diskusi kelompok akan berjalan dengan sendirinya.', 1, '2025-11-20 06:00:59'),
(22, 6, 'Memperbaiki pengelompokan murid dengan menata ulang kelompok yang dianggap tidak seimbang dalam kemampuan akademis agar terjadi dinamika interaksi di dalam kelompok itu sendiri.', 2, '2025-11-20 06:00:59'),
(23, 6, 'Memperbaiki struktur aktivitas diskusi dengan memberikan peran yang jelas kepada setiap anggota kelompok untuk mendorong tanggung jawab individu dan meningkatkan kontribusi.', 3, '2025-11-20 06:00:59'),
(24, 6, 'Memperbaiki keseluruhan strategi fasilitasi diskusi (penetapan peran, monitoring proses, umpan balik, dan refleksi), karena perubahan ini memungkinkan murid memahami cara berkolaborasi secara berkelanjutan dan meningkatkan kualitas \nketerlibatan.', 4, '2025-11-20 06:00:59'),
(25, 7, 'Segera menambah aturan dan larangan baru kedisiplinan di kelas, karena dengan memperketat kedisiplinan murid maka kegiatan diskusi akan menjadi lebih tertib dan berjalan dengan baik.', 1, '2025-11-20 06:00:59'),
(26, 7, 'Memprioritaskan perubahan pada komposisi ketidakseimbangan kemampuan akademik yang dianggap sebagai penyebab utama kurangnya partisipasi di beberapa kelompok.', 2, '2025-11-20 06:00:59'),
(27, 7, 'Memprioritaskan perbaikan struktur aktivitas, seperti menetapkan peran diskusi (pencatat, penyaji, penanya, pengatur waktu), karena bukti di kelas menunjukkan murid bingung dengan tugas masing-masing sehingga beberapa tidak terlibat.', 3, '2025-11-20 06:00:59'),
(28, 7, 'Memprioritaskan peningkatan kualitas fasilitasi diskusi meliputi peran yang jelas, mekanisme monitoring, umpan balik singkat, dan refleksi, karena bukti menunjukkan bahwa keterlibatan rendah disebabkan oleh kurangnya arahan proses dan tidak adanya kesempatan menilai efektivitas kolaborasi.', 4, '2025-11-20 06:00:59'),
(29, 8, 'Menyarankan Bu Anita untuk lebih tegas menegur murid yang pasif dalam diskusi kelompok, karena dengan memberi teguran kemungkinan besar mereka akan ikut aktif untuk terlibat dalam diskusi.', 1, '2025-11-20 06:00:59'),
(30, 8, 'Menyarankan Bu Anita mencoba untuk menata ulang kelompok-kelompok murid sehingga lebih seimbang terutama dalam kemampuan akademis.', 2, '2025-11-20 06:00:59'),
(31, 8, 'Mendampingi Bu Anita mengidentifikasi pola ketidakaktifan murid, lalu membantu merancang peran dalam kelompok (pencatat, penanya, penyaji) agar setiap murid memiliki tugas yang jelas dalam diskusi.', 3, '2025-11-20 06:00:59'),
(32, 8, 'Mengajak Bu Anita melakukan observasi, menganalisis bukti keterlibatan murid, merancang peran dan alur diskusi, dan mencoba strategi tersebut di kelas. Selanjutnya, melakukan refleksi untuk menentukan perbaikan yang bisa ia gunakan di pertemuan berikutnya.', 4, '2025-11-20 06:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(45) DEFAULT NULL,
  `status_desc` varchar(45) DEFAULT NULL,
  `status_desc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`, `status_desc`, `status_desc_id`) VALUES
(1, 'Status Approval SDM', 'Belum Ajuan', 1),
(2, 'Status Approval SDM', 'Menununggu Persetujuan', 2),
(3, 'Status Approval SDM', 'Disetujui', 3),
(4, 'Status Approval SDM', 'Revisi', 4);

-- --------------------------------------------------------

--
-- Table structure for table `sub_indikator`
--

CREATE TABLE `sub_indikator` (
  `sub_indikator_id` int(11) NOT NULL,
  `intrumen_id` int(11) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `indikator_id` int(11) DEFAULT NULL,
  `indikator_code` varchar(45) DEFAULT NULL,
  `sub_indikator_code` varchar(45) DEFAULT NULL,
  `sub_indikator_name` varchar(200) DEFAULT NULL,
  `sub_indikator_dec` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_indikator`
--

INSERT INTO `sub_indikator` (`sub_indikator_id`, `intrumen_id`, `no_urut`, `indikator_id`, `indikator_code`, `sub_indikator_code`, `sub_indikator_name`, `sub_indikator_dec`, `created_at`) VALUES
(1, 1, 1, 1, '1.1', '1.1.1', 'Pengelolaan perilaku murid yang sulit', NULL, '2025-11-20 05:59:46'),
(2, 1, 2, 1, '1.1', '1.1.2', 'Pengelolaan kelas untuk mencapai pembelajaran yang berpusat pada murid', NULL, '2025-11-20 05:59:46'),
(3, 1, 3, 1, '1.1', '1.1.3', 'Rasa aman dan nyaman murid dalam proses pembelajaran', NULL, '2025-11-20 05:59:46'),
(4, 1, 1, 2, '1.2', '1.2.1', 'Desain pembelajaran yang terstruktur dan berurutan untuk mencapai tujuan pembelajaran', NULL, '2025-11-20 05:59:46'),
(5, 1, 2, 2, '1.2', '1.2.2', 'Desain pembelajaran yang relevan dengan kondisi di sekitar sekolah dengan melibatkan murid', NULL, '2025-11-20 05:59:46'),
(6, 1, 3, 2, '1.2', '1.2.3', 'Pemilihan dan penggunaan sumber belajar yang sesuai dengan tujuan pembelajaran', NULL, '2025-11-20 05:59:46'),
(7, 1, 4, 2, '1.2', '1.2.4', 'Instruksi pembelajaran yang mencakup strategi dan komunikasi untuk menumbuhkan minat dan nalar kritis murid', NULL, '2025-11-20 05:59:46'),
(8, 1, 5, 2, '1.2', '1.2.5', 'Penggunaan teknologi informasi dan komunikasi (TIK) secara adaptif dalam pembelajaran', NULL, '2025-11-20 05:59:46'),
(9, 1, 1, 3, '1.3', '1.3.1', 'Perancangan asesmen yang berpusat pada murid', NULL, '2025-11-20 05:59:46'),
(10, 1, 2, 3, '1.3', '1.3.2', 'Pelaksanaan asesmen yang berpusat pada murid', NULL, '2025-11-20 05:59:46'),
(11, 1, 3, 3, '1.3', '1.3.3', 'Umpan balik terhadap murid mengenai pembelajarannya', NULL, '2025-11-20 05:59:46'),
(12, 1, 4, 3, '1.3', '1.3.4', 'Penyusunan laporan capaian belajar murid', NULL, '2025-11-20 05:59:46'),
(13, 1, 5, 3, '1.3', '1.3.5', 'Komunikasi laporan capaian belajar murid', NULL, '2025-11-20 05:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `tim_kerja`
--

CREATE TABLE `tim_kerja` (
  `tim_kerja_id` int(11) NOT NULL,
  `tim_kerja` varchar(45) DEFAULT NULL,
  `tim_kerja_desc` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tim_kerja`
--

INSERT INTO `tim_kerja` (`tim_kerja_id`, `tim_kerja`, `tim_kerja_desc`) VALUES
(1, 'TIM 1', 'Tim Kerja 1'),
(2, 'TIM 2', 'Tim Kerja 2'),
(3, 'TIM 3', 'Tim Kerja 3'),
(4, 'TIM 4', 'Subbagian Umum');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(255) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `tim_kerja_id` int(11) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nip` varchar(16) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `npsn` varchar(45) DEFAULT NULL,
  `nama_satuan_pendidikan` varchar(100) DEFAULT NULL,
  `alamat_satuan_pendidikan` varchar(100) DEFAULT NULL,
  `kab_kota` varchar(45) DEFAULT NULL,
  `bos` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `tim_kerja_id`, `user_name`, `nama`, `password`, `nip`, `nik`, `email`, `no_urut`, `npsn`, `nama_satuan_pendidikan`, `alamat_satuan_pendidikan`, `kab_kota`, `bos`) VALUES
(1, 2, NULL, '197208112002121001', 'Dr. Sugito Adiwarsito, M.Pd.Or.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972081120021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 4, '197904052003122006', 'Apriana Anggraini, M.Pd.', '$2y$12$yUxhL3BrOzXkNlVF9grmJ.oVmctp/n6p3VFruDBX0X.OaAgn2mChK', '1979040520031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 4, NULL, '198003212014091002', 'Abd. Rohman,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980032120140910', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, NULL, '196711171992031002', 'Abdul Munir, S.IP.,M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967111719920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 4, NULL, '196710112002121001', 'Abdul Rojak, S.H.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967101120021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 4, NULL, '199011102019022006', 'Afiah Nuraeni, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1990111020190220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 4, NULL, '197303102002121002', 'Agung Dewoto, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973031020021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 4, NULL, '196908131992031002', 'Agus Poniman, S.Sos.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969081319920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 4, NULL, '196908012002121002', 'Agustono, S.Kom.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969080120021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 4, NULL, '199808272020122006', 'Amelia Dwi Agustina, A.Md.Bns.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1998082720201220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, NULL, '197706022002121002', 'Andri Wahman, S.Sos, M.AP', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1977060220021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 4, NULL, '198609292009121005', 'Andria Kusuma, S.E', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1986092920091210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 4, NULL, '199006072019022006', 'Anggia Ruhika, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1990060720190220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 4, NULL, '199611092022032021', 'Anita Ismarani Nurjanah, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1996110920220320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 4, NULL, '197703112014091002', 'Budi Mulyawan,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1977031120140910', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 4, NULL, '197210011994032002', 'Devi Kurniawati, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972100119940320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 4, NULL, '198903212020122009', 'Diensha Restu Nurhayati Sumpena, S.I.Kom.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1989032120201220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 4, NULL, '197404192006042001', 'Duma Nursiah Eriwati Silitonga, S.P., M.M.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974041920060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 4, NULL, '197201052005012001', 'Elvira Ratna Sari, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972010520050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 4, NULL, '198010032007011004', 'Hadi, S.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980100320070110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 4, NULL, '197203132002121001', 'Handoko, S.Pd,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972031320021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 4, NULL, '197005271998031002', 'Jamal Duwila, S.Sos.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970052719980310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 4, NULL, '197005271998031002', 'Jamaluddin,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970052719980310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 4, NULL, '199210262022032009', 'Laras Tsamrotul Fuadi, S.Psi.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1992102620220320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 4, NULL, '197205082005012001', 'Manisem,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972050820050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 4, NULL, '197408022005011002', 'Maryono, S.IKom.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974080220050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 4, NULL, '197908012006042002', 'Maya Nurini, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1979080120060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 4, NULL, '199410302022031008', 'Mohammad Irfai, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1994103020220310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 4, NULL, '197106231992031001', 'MOHAMMAD RAFIK, S.Sos, M.Si', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971062319920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 4, NULL, '197605172002121001', 'Muhammad Awang Eli Basya, A.Md.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976051720021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 4, NULL, '197109111992031003', 'Muhammad Koriban, SE.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971091119920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 4, NULL, '197105271993032001', 'Muliandari, S.E.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971052719930320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 4, NULL, '196907151992031003', 'Niman Hasan Aman,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969071519920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 4, NULL, '198905232022032006', 'Noni Lara Sestia, S.Psi.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1989052320220320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 4, NULL, '199611182022032011', 'Novi Wulansari, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1996111820220320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 4, NULL, '197009071994122001', 'Nurul Shobah, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970090719941220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 4, NULL, '197509172006042002', 'Rachmasari Wulandari, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1975091720060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 4, NULL, '197306051993031002', 'Rd. Ahmad Saleh, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973060519930310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 4, NULL, '197605042002122001', 'Rifa Jamilah Khoiridha, S.E.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976050420021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 4, NULL, '198107182006041008', 'Rizki,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981071820060410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 4, NULL, '198109222001122001', 'Rosmelani Septianingsih, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981092220011220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 4, NULL, '197504052002122002', 'Rr. Ida Kuswardati, SE.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1975040520021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 4, NULL, '197211202002121007', 'Rudi Bakti Lubis, ST., M.M.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972112020021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 4, NULL, '197211202002121007', 'Rudi Bakti Lubis, ST., M.M.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972112020021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 4, NULL, '197105302014091001', 'Sismulyadi, SM', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971053020140910', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 4, NULL, '198007292005012005', 'Sri Handayani, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980072920050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 4, NULL, '196812272002121001', 'Subali, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1968122720021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 4, NULL, '197102241992031002', 'Sukarna Sadja, S. Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971022419920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 4, NULL, '198006262006042001', 'Sulastri Handayani, S.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980062620060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 4, NULL, '197009141993031002', 'SURYA,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970091419930310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 4, NULL, '196707202002121001', 'Untung Sabaryanto, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967072020021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 4, NULL, '198205212010121005', 'Urdik Hadianto, S.Si.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1982052120101210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 4, NULL, '198108152005011001', 'Wendi, S.E.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981081520050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 4, NULL, '197005212002121001', 'wisnu subroto,', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970052120021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 4, NULL, '198305112003122005', 'Yesi Indriyanti, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1983051120031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 4, NULL, '197906192006042001', 'Yuni Purwanti, M.M.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1979061920060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 4, NULL, '197806062002122003', 'Yuni Tuningrum, S.H.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1978060620021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 4, NULL, '199001302020121007', 'Yusup Ardabili, A.Md.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1990013020201210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 4, NULL, '198112082009121004', 'Zaenal Abidin, A. Md.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981120820091210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 4, NULL, '199203312022031006', 'Zakaria Budiman, S.Psi', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1992033120220310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 4, NULL, '197203152006041001', 'Abdullah, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972031520060410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 3, 2, '197708172003121003', 'Agustiana Ramdani, S.Si.,M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1977081720031210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 4, NULL, '197810302003121001', 'Ali Winata, S.Pd, SF, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1978103020031210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 4, NULL, '197506212001122001', 'Ani Purwati, S.Pd, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1975062120011220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 4, NULL, '197604182005012002', 'Anita Priyatmi, S.Pd., M.Ed.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976041820050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 4, NULL, '196704072001121001', 'Aris Munandar, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967040720011210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 4, NULL, '197208052005011014', 'ARIS SUPRIYANTO, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972080520050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 4, NULL, '197410052002122001', 'Ati Rosidah, S.Ag. M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974100520021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 4, NULL, '197207272007011017', 'Dede Harsudin, SE,M.SI,MM', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972072720070110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 4, NULL, '197411152006041001', 'Dedi Supriyanto, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974111520060410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 4, NULL, '197208031993031001', 'Dr Yudha Prapantja, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972080319930310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 4, NULL, '197305022002121004', 'Dr. Ahmad Ghozi, M.Pd., M.A.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973050220021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 4, NULL, '197301052005012001', 'Dr. Dewi Setiawati, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973010520050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 4, NULL, '196910232002122007', 'Dr. Endah Ariani Madusari, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969102320021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 4, NULL, '198212252009032009', 'Dr. Eny Usmawati, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1982122520090320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 4, NULL, '197009212002121005', 'Dr. WIDIATMOKO, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970092120021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 4, NULL, '196508111985032002', 'Dra ENDANG SUPRIATI, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1965081119850320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 4, NULL, '196804072002122001', 'Dra. Emy Widiarti, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1968040720021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 4, NULL, '196710292002121001', 'Drs. Taufik Nugroho, M.Hum.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967102920021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 4, NULL, '196802252002121003', 'Dwi Cahyo Widodo, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1968022520021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 4, NULL, '198401302006042001', 'Dwi Hadi Mulyaningsih, M.Pd. .', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1984013020060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 4, NULL, '197110071992032001', 'Dwi Nindriyati, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971100719920320', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 4, NULL, '197905142005012002', 'Dwi Puspitasari, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1979051420050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 4, NULL, '197001082002122001', 'Dwi Yoga Peny Hadyanti, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1970010820021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 4, NULL, '197412072002121105', 'Endang Kurniawan, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974120720021211', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 4, NULL, '196802232003122001', 'Endang Setiariny, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1968022320031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 4, NULL, '197312072002122001', 'Endang Setyorini, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973120720021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 4, NULL, '197405122005012002', 'Endang Susilowati, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974051220050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 4, NULL, '197112132002121001', 'Gunawan Widiyanto, M.Hum.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971121320021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 4, NULL, '197606222003122002', 'Handini, SS, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976062220031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 4, NULL, '197610092005011003', 'Hardiyanto, M. Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976100920050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 4, NULL, '196902011999031005', 'Hargio Santoso, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969020119990310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(93, 4, NULL, '197604252005011004', 'Hari Wibowo, S.S., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976042520050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(94, 4, NULL, '197604252005011004', 'Hari Wibowo, S.S., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976042520050110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(95, 3, 1, '197302052003121002', 'Herwan Febriyadi, S.Si, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973020520031210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(96, 4, NULL, '198111072009121003', 'Iis Handoko, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981110720091210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(97, 4, NULL, '197701182001122002', 'Indriyati, S.S, MTrainDev.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1977011820011220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 4, NULL, '197804282005012003', 'Jehan Ananda Aliyah KH, M.A.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1978042820050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 3, 3, '198205022005012004', 'Kardina Pendikarini, S.S., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1982050220050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 4, NULL, '196912252000031008', 'Kristiawan, S.E., M.Si.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969122520000310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(101, 4, NULL, '198204092006042002', 'Lestari Puspitaningsih, S.S.,S.Sos.,M.A.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1982040920060420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 4, NULL, '197703242002122001', 'Lia Herawaty, S.S., M.Pd., S.S., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1977032420021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 4, NULL, '197507072002121003', 'M. Isnaini, S.Pd, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1975070720021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 4, NULL, '198303032008012012', 'Mulawarni, S.S.,M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1983030320080120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(105, 4, NULL, '197609172002121003', 'Mulyadi, S.Ag., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976091720021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(106, 4, NULL, '198602252015041003', 'Naro Prasetyo, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1986022520150410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(107, 4, NULL, '197207302005012001', 'Pininto Sarwendah, M.A.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972073020050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(108, 4, NULL, '196901261992031002', 'Prasetiyo, M. Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1969012619920310', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(109, 4, NULL, '196712142001121001', 'Raden Roy Miftahul Huda, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1967121420011210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(110, 4, NULL, '198707182010121007', 'Richard Amri, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1987071820101210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 4, NULL, '1976011122005012001', 'Ririk Ratnasari, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976011122005012', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 4, NULL, '197407252002122001', 'Siti Nurhayati, S.Pd.,Sp.I.,M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974072520021220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 4, NULL, '197412282003122001', 'Sri Murwati, S.Si, M.Si.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974122820031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 4, NULL, '197202022006041002', 'Suhardi, M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972020220060410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 4, NULL, '196812152002121001', 'Sutomo, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1968121520021210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 4, NULL, '197604282003121001', 'Taufiq Rahman, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976042820031210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 4, NULL, '197211272003122001', 'Tisnuliyah, S.Si, M.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972112720031220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 4, NULL, '197409122000122002', 'Virgo Rita Furyani, S.Pd., M.Ed.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974091220001220', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 4, NULL, '197106252005012002', 'Wahyuningrum, S.Pd., M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1971062520050120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 4, NULL, '197209042003121001', 'Widyatmo , M.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1972090420031210', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 4, NULL, '1981072601', 'Abdul Rosid', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1981072601', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 4, NULL, '1990111502', 'Akhbarrulloh', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1990111502', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 4, NULL, '1974012303', 'ALAM SYAFRUDIN', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1974012303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 4, NULL, '1998100204', 'Alishofi', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1998100204', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 4, NULL, '1980011005', 'Andry Yanuar Arifin, S.T.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980011005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 4, NULL, '1990102806', 'Asep Nurul Hadi, SE', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1990102806', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 4, NULL, '2024091407', 'Dahlan', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2024091407', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 4, NULL, '1976121808', 'Darmayadi', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976121808', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 4, NULL, '1985082309', 'Eka Hermawan, A.Md.Kom', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1985082309', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 4, NULL, '1988052510', 'Eka Saputra Jaya, S.E.,S.K.M', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1988052510', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 4, NULL, '2025030711', 'Fitriyanto agung prastowo', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025030711', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 4, NULL, '1995122812', 'Global Ilham Sampurno S.Kom. M.Kom', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1995122812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 4, NULL, '1973030413', 'Haerul sholeh', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973030413', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 4, NULL, '1995100314', 'Linda oktavia', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1995100314', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 4, NULL, '1985102015', 'M. Armin', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1985102015', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 4, NULL, '1976071616', 'Muchlis', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976071616', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 4, NULL, '1975040317', 'MUHAMAD SYARIFUDIN', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1975040317', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 4, NULL, '1993090418', 'Muhammad Antoni', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1993090418', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 4, NULL, '1979082419', 'Muhammad Anwar, Amd.,Kom', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1979082419', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 4, NULL, '1993112120', 'Muhammad Khoerul Amri, S.I.Kom.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1993112120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(141, 4, NULL, '2001112621', 'Nabil Alwan', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2001112621', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(142, 4, NULL, '1993060222', 'Nahri Juniyansyah, SM', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1993060222', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 4, NULL, '1991031023', 'Nuris Azizah, S.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1991031023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 4, NULL, '1988021424', 'Ryaldi Ebri, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1988021424', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 4, NULL, '1973090425', 'Sarkim', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1973090425', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 4, NULL, '1999050126', 'Selfy Alfia Wijayanti', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1999050126', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 4, NULL, '1983021727', 'Sitanala vanhouten', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1983021727', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 4, NULL, '1976040228', 'Syamsuddin', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976040228', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 4, NULL, '1994051529', 'Umapruhi', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1994051529', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 4, NULL, '1994071030', 'Umin Khotamin, S.Pd.', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1994071030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 4, NULL, '1994080531', 'Wanda Tendi', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1994080531', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 4, NULL, '1994011532', 'Yanuar Suwastito Widodo', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1994011532', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 4, NULL, '1980071633', 'Yuli Irawati, S.Pd', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1980071633', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 4, NULL, '1995092834', 'Yunda Gusman, S.A.P', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1995092834', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 4, NULL, '1976021035', 'YUYUN', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '1976021035', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 4, NULL, '2025010101', 'adm.persuratan01', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010101', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 4, NULL, '2025010102', 'adm.persuratan02', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010102', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 4, NULL, '2025010103', 'adm.persuratan03', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010103', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 4, NULL, '2025010104', 'adm.kepeg01', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010104', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 4, NULL, '2025010105', 'adm.kepeg02', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010105', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 4, NULL, '2025010106', 'adm.publikasi01', '$2y$10$g/3YfJ4YiEtDCs4yV5wq5emexVZJldWaH/wlFQWzwMTbinNrWRIjG', '2025010106', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 2, NULL, '20601372', 'SD N CADASARI 2', '$2y$12$RKVgtEslUwXoUEPPaRB/.OGOl.btW.BbtcXOInTRxw6lnDO0JZkLe', NULL, NULL, NULL, NULL, '20601372', 'SD N CADASARI 2', 'Kab. Pandeglang', 'Kab. Pandeglang', 'BOS Kinerja');

-- --------------------------------------------------------

--
-- Table structure for table `users_mapping`
--

CREATE TABLE `users_mapping` (
  `user_mapping_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_mapping`
--

INSERT INTO `users_mapping` (`user_mapping_id`, `user_id`, `kegiatan_id`, `created_date`) VALUES
(1, 3, 13, NULL),
(2, 3, 17, NULL),
(3, NULL, 14, NULL),
(4, NULL, 14, NULL),
(5, 109, 14, '2025-03-13 14:52:35'),
(6, 1, 14, '2025-03-13 21:36:26'),
(7, 2, 14, '2025-03-13 21:45:33'),
(8, 3, 14, '2025-03-13 21:49:17'),
(9, 5, 14, '2025-03-13 21:58:36'),
(10, 91, 14, '2025-03-13 22:00:38'),
(11, 92, 14, '2025-03-13 22:00:38'),
(12, 93, 14, '2025-03-13 22:00:38'),
(13, 94, 14, '2025-03-13 22:00:38'),
(14, 95, 14, '2025-03-13 22:00:38'),
(15, 96, 14, '2025-03-13 22:00:38'),
(16, 97, 14, '2025-03-13 22:00:38'),
(17, 98, 14, '2025-03-13 22:00:38'),
(18, 99, 14, '2025-03-13 22:00:38'),
(19, 100, 14, '2025-03-13 22:00:38'),
(20, 1, 15, '2025-03-14 15:16:19'),
(21, 2, 15, '2025-03-14 15:16:34'),
(22, 109, 15, '2025-03-17 23:32:59'),
(23, 62, 15, '2025-03-17 23:33:18'),
(24, 2, 18, '2025-03-18 08:34:11'),
(25, 3, 18, '2025-03-18 08:34:23'),
(26, 109, 18, '2025-03-18 08:34:46'),
(27, 7, 18, '2025-03-18 08:42:57'),
(28, 8, 18, '2025-03-18 08:42:57'),
(29, 9, 18, '2025-03-18 08:42:57'),
(30, 42, 18, '2025-03-18 08:43:21'),
(31, 43, 18, '2025-03-18 08:43:21'),
(32, 91, 18, '2025-03-18 08:59:30'),
(33, 2, 18, '2025-03-18 09:48:49'),
(34, 1, 18, '2025-03-18 09:52:58'),
(35, 2, 18, '2025-03-18 09:53:07'),
(36, 2, 18, '2025-03-18 09:53:20'),
(37, 2, 18, '2025-03-18 09:54:02'),
(38, 2, 18, '2025-03-18 09:54:21'),
(39, 1, 18, '2025-03-18 09:55:31'),
(40, 2, 18, '2025-03-18 09:55:42'),
(41, 3, 18, '2025-03-18 09:55:59'),
(42, 109, 18, '2025-03-18 09:59:08'),
(43, 99, 18, '2025-03-18 09:59:31'),
(44, 1, 19, '2025-03-18 10:30:52'),
(45, 2, 19, '2025-03-18 10:30:58'),
(46, 109, 19, '2025-03-18 10:31:08'),
(47, 91, 19, '2025-03-18 10:31:20'),
(48, 2, 13, '2025-04-10 12:09:03'),
(49, 1, 20, '2025-04-22 13:51:31'),
(50, 2, 20, '2025-04-22 13:51:40'),
(51, 7, 20, '2025-04-22 13:51:55'),
(52, 1, 13, '2025-05-14 13:28:42'),
(53, 4, 13, '2025-05-14 13:29:55'),
(54, 3, 13, '2025-05-14 13:30:08'),
(55, 35, 13, '2025-05-14 13:30:44'),
(56, 36, 13, '2025-05-14 13:30:44'),
(57, 37, 13, '2025-05-14 13:30:44'),
(58, 38, 13, '2025-05-14 13:30:44'),
(59, 39, 13, '2025-05-14 13:30:44'),
(60, 5, 13, '2025-05-14 13:31:01'),
(61, 6, 13, '2025-05-14 13:31:01'),
(62, 7, 13, '2025-05-14 13:31:01'),
(63, 8, 13, '2025-05-14 13:31:01'),
(64, 109, 13, '2025-05-14 13:40:58'),
(65, 115, 24, '2025-05-19 21:54:34'),
(66, 117, 24, '2025-05-19 21:54:52'),
(67, 9, 24, '2025-05-19 21:55:17'),
(68, 28, 28, '2025-06-02 10:32:09'),
(69, 109, 28, '2025-06-03 00:46:34'),
(70, 56, 28, '2025-06-03 00:47:23'),
(71, 10, 28, '2025-06-03 01:01:03'),
(72, 1, 29, '2025-06-04 11:58:38'),
(73, 2, 29, '2025-06-04 11:59:00'),
(74, 3, 29, '2025-06-04 11:59:00'),
(75, 4, 29, '2025-06-04 11:59:00'),
(76, 11, 29, '2025-06-04 12:00:03'),
(77, 15, 29, '2025-06-04 12:00:18'),
(78, 5, 29, '2025-06-04 12:10:30'),
(79, 113, 29, '2025-06-04 12:10:45'),
(80, 74, 29, '2025-06-04 12:11:06'),
(81, 75, 29, '2025-06-04 12:11:06'),
(82, 1, 30, '2025-06-09 23:50:19'),
(83, 3, 30, '2025-06-09 23:58:20'),
(84, 2, 30, '2025-06-09 23:58:54'),
(85, 6, 30, '2025-06-10 01:03:03'),
(86, 1, 1, '2025-06-20 11:32:36'),
(87, 3, 1, '2025-06-20 11:32:52'),
(88, 4, 1, '2025-06-20 11:32:52'),
(89, 5, 1, '2025-06-20 11:32:52'),
(90, 121, 1, '2025-06-20 11:33:13'),
(91, 122, 1, '2025-06-20 11:33:13'),
(92, 123, 1, '2025-06-20 11:33:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ikk`
--
ALTER TABLE `ikk`
  ADD PRIMARY KEY (`ikk_id`);

--
-- Indexes for table `indikator`
--
ALTER TABLE `indikator`
  ADD PRIMARY KEY (`indikator_id`);

--
-- Indexes for table `instrumen`
--
ALTER TABLE `instrumen`
  ADD PRIMARY KEY (`instrumen_id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`kegiatan_id`);

--
-- Indexes for table `kota`
--
ALTER TABLE `kota`
  ADD PRIMARY KEY (`kota_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pangkat_golongan`
--
ALTER TABLE `pangkat_golongan`
  ADD PRIMARY KEY (`pangkat_golongan_id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`pegawai_id`);

--
-- Indexes for table `peran`
--
ALTER TABLE `peran`
  ADD PRIMARY KEY (`peran_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `ptk`
--
ALTER TABLE `ptk`
  ADD PRIMARY KEY (`ptk_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`soal_id`);

--
-- Indexes for table `soal_case`
--
ALTER TABLE `soal_case`
  ADD PRIMARY KEY (`soal_case_id`);

--
-- Indexes for table `soal_jawaban`
--
ALTER TABLE `soal_jawaban`
  ADD PRIMARY KEY (`soal_jawaban_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `sub_indikator`
--
ALTER TABLE `sub_indikator`
  ADD PRIMARY KEY (`sub_indikator_id`);

--
-- Indexes for table `tim_kerja`
--
ALTER TABLE `tim_kerja`
  ADD PRIMARY KEY (`tim_kerja_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_mapping`
--
ALTER TABLE `users_mapping`
  ADD PRIMARY KEY (`user_mapping_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ikk`
--
ALTER TABLE `ikk`
  MODIFY `ikk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `indikator`
--
ALTER TABLE `indikator`
  MODIFY `indikator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instrumen`
--
ALTER TABLE `instrumen`
  MODIFY `instrumen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `kegiatan_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kota`
--
ALTER TABLE `kota`
  MODIFY `kota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pangkat_golongan`
--
ALTER TABLE `pangkat_golongan`
  MODIFY `pangkat_golongan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `pegawai_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `peran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ptk`
--
ALTER TABLE `ptk`
  MODIFY `ptk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `soal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `soal_case`
--
ALTER TABLE `soal_case`
  MODIFY `soal_case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `soal_jawaban`
--
ALTER TABLE `soal_jawaban`
  MODIFY `soal_jawaban_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_indikator`
--
ALTER TABLE `sub_indikator`
  MODIFY `sub_indikator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tim_kerja`
--
ALTER TABLE `tim_kerja`
  MODIFY `tim_kerja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `users_mapping`
--
ALTER TABLE `users_mapping`
  MODIFY `user_mapping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
