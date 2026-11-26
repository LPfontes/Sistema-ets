-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 nov. 2026 à 02:25
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sistema_ets`
--

-- --------------------------------------------------------

--
-- Structure de la table `acessos_usuarios`
--

CREATE TABLE `acessos_usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nome_usuario` varchar(255) NOT NULL,
  `email_usuario` varchar(255) NOT NULL,
  `perfil` varchar(20) NOT NULL,
  `evento` varchar(20) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `acessos_usuarios`
--

INSERT INTO `acessos_usuarios` (`id`, `user_id`, `nome_usuario`, `email_usuario`, `perfil`, `evento`, `ip`, `created_at`) VALUES
(1, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-19 08:40:02'),
(2, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'logout', '127.0.0.1', '2026-11-19 08:50:09'),
(3, 2, 'AMANDA V A SILVA', 'amandaangelocca@gmail.com', 'visitante', 'login', '127.0.0.1', '2026-11-19 08:50:19'),
(4, 2, 'AMANDA V A SILVA', 'amandaangelocca@gmail.com', 'visitante', 'logout', '127.0.0.1', '2026-11-19 08:50:46'),
(5, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-19 08:51:23'),
(6, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'logout', '127.0.0.1', '2026-11-19 09:02:17'),
(7, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-19 09:02:26'),
(8, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'logout', '127.0.0.1', '2026-11-19 09:05:29'),
(9, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-20 05:09:40'),
(10, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'logout', '127.0.0.1', '2026-11-20 06:22:27'),
(11, 2, 'AMANDA V A SILVA', 'amandaangelocca@gmail.com', 'visitante', 'login', '127.0.0.1', '2026-11-20 06:22:34'),
(12, 2, 'AMANDA V A SILVA', 'amandaangelocca@gmail.com', 'visitante', 'logout', '127.0.0.1', '2026-11-20 06:23:51'),
(13, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-20 06:26:48'),
(14, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'logout', '127.0.0.1', '2026-11-20 06:26:53'),
(15, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-20 07:08:53'),
(16, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-22 03:56:07'),
(17, 1, 'Admin', 'nouasse.ben@gmail.com', 'admin', 'login', '127.0.0.1', '2026-11-23 19:43:27');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1795452254),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1795452254;', 1795452254);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cursos`
--

CREATE TABLE `cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `codigo_estrutura` varchar(30) NOT NULL,
  `turno` varchar(255) NOT NULL,
  `periodo_vigencia` varchar(6) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `prazo_minimo` int(10) UNSIGNED DEFAULT NULL,
  `prazo_maximo` int(10) UNSIGNED DEFAULT NULL,
  `carga_horaria_total` int(10) UNSIGNED NOT NULL,
  `quantidade_modulos` int(10) UNSIGNED NOT NULL,
  `resolucao` varchar(255) DEFAULT NULL,
  `projeto_pedagogico` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipo_curso` varchar(30) NOT NULL DEFAULT 'Presencial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cursos`
--

INSERT INTO `cursos` (`id`, `nome`, `codigo_estrutura`, `turno`, `periodo_vigencia`, `ativo`, `prazo_minimo`, `prazo_maximo`, `carga_horaria_total`, `quantidade_modulos`, `resolucao`, `projeto_pedagogico`, `created_at`, `updated_at`, `tipo_curso`) VALUES
(1, 'Técnico em Cuidados de Idosos – PROEJA (MT)', '2', 'Noturno', '2022.2', 1, 12, 18, 810, 2, 'Res. CONSEPE 07/2023', 'https://leis.org/cfp/lei/resolucao-do-exercicio-profissional/2023/7/resolucao-do-exercicio-profissional-n-7-2023-estabelece-normas-para-o-exercicio-profissional-em-relacao-ao-carater-laico-da-pratica', '2026-10-30 05:27:06', '2026-10-30 05:34:01', 'Presencial'),
(3, 'Curso da Medecina', '3', 'Vespertino', '2023.2', 1, 12, 24, 520, 2, 'Res. CONSEPE 07/2023', 'https://leis.org/cfp/lei/resolucao-do-exercicio-profissional/2023/7/resolucao-do-exercicio-profissional-n-7-2023-estabelece-normas-para-o-exercicio-profissional-em-relacao-ao-carater-laico-da-pratica', '2026-10-30 05:52:35', '2026-10-30 05:59:43', 'Presencial'),
(4, 'Operadores de Computador', '01', 'Integral', '2026.1', 1, 12, 18, 165, 1, NULL, NULL, '2026-11-13 22:34:23', '2026-11-13 22:34:23', 'Presencial'),
(5, 'Medecina Veterinaria', '2', 'Integral', '2026.2', 1, 12, 28, 3450, 4, 'Res. CONSEPE 07/2024', 'https://leis.org/cfp/lei/resolucao-do-exercicio-profissional/2023/7/resolucao-do-exercicio-profissional-n-7-2023-estabelece-normas-para-o-exercicio-profissional-em-relacao-ao-carater-laico-da-pratica', '2026-11-20 05:34:44', '2026-11-20 05:34:44', 'Presencial');

-- --------------------------------------------------------

--
-- Structure de la table `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `carga_horaria` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `modulo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `categoria` varchar(20) NOT NULL DEFAULT 'Regular'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `created_at`, `updated_at`, `nome`, `carga_horaria`, `tipo`, `curso_id`, `modulo_id`, `categoria`) VALUES
(1, '2026-10-31 08:05:26', '2026-10-31 08:25:09', 'Informática Básica', 60, 'Presencial', 1, 1, 'Regular'),
(3, '2026-10-31 08:29:33', '2026-10-31 08:29:33', 'CURSO TESTE EXCLUSÃO', 55, 'Presencial', 1, 1, 'Regular'),
(4, '2026-10-31 08:32:01', '2026-10-31 08:32:01', 'Programacao', 22, 'Presencial', 1, NULL, 'Complementar'),
(5, '2026-11-19 06:08:55', '2026-11-19 06:08:55', 'Cirugia', 62, 'Presencial', 3, 6, 'Regular');

-- --------------------------------------------------------

--
-- Structure de la table `docentes`
--

CREATE TABLE `docentes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `colegiado` varchar(255) NOT NULL,
  `siape` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `docentes`
--

INSERT INTO `docentes` (`id`, `nome`, `colegiado`, `siape`, `created_at`, `updated_at`) VALUES
(1, 'Amanda Angelo', 'Depart de Ciencias Veterinarias', '30824122', '2026-10-29 05:53:48', '2026-10-29 07:40:56'),
(3, 'ISIS SENAMI', 'DEPARTAMENTO DA MEDECINA', '203513699', '2026-10-29 07:41:46', '2026-10-29 07:42:37');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_29_021948_create_docentes_table', 2),
(5, '2026_10_30_013012_create_cursos_table', 3),
(6, '2026_10_30_031715_create_modulos_table', 4),
(7, '2026_10_31_034031_create_disciplinas_table', 5),
(8, '2026_10_31_035057_create_semestres_table', 5),
(9, '2026_10_31_042458_add_campos_to_disciplinas_table', 6),
(10, '2026_10_31_044607_add_categoria_to_disciplinas_table', 7),
(11, '2026_11_01_004011_create_oferta_disciplinas_table', 8),
(12, '2026_11_12_015049_create_oferta_cursos_table', 9),
(13, '2026_11_19_023441_add_semestre_fim_id_to_oferta_cursos_table', 10),
(14, '2026_11_19_120000_add_acompanhamento_to_oferta_disciplinas_table', 11),
(15, '2026_11_19_125000_add_horas_legadas_to_oferta_disciplinas', 12),
(16, '2026_11_19_130000_create_registros_aulas_table', 12),
(17, '2026_11_19_140000_add_perfil_to_users_table', 13),
(18, '2026_11_19_141000_create_acessos_usuarios_table', 13),
(19, '2026_11_19_142000_add_foto_perfil_to_users_table', 14),
(20, '2026_11_20_010000_add_tipo_curso_to_cursos_table', 15);

-- --------------------------------------------------------

--
-- Structure de la table `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `carga_horaria` int(10) UNSIGNED NOT NULL,
  `periodo_oferta` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `modulos`
--

INSERT INTO `modulos` (`id`, `curso_id`, `nome`, `carga_horaria`, `periodo_oferta`, `created_at`, `updated_at`) VALUES
(1, 1, 'UNIDADE CURRICULAR I', 422, 1, '2026-10-30 06:51:56', '2026-10-30 07:08:25'),
(4, 1, 'UNIDADE CURRICULAR II', 650, 2, '2026-10-30 07:16:04', '2026-10-30 07:16:04'),
(6, 3, 'UNIDADE CURRICULAR I', 650, 1, '2026-11-19 06:07:19', '2026-11-19 06:07:19'),
(7, 5, 'Unidade 1', 500, 1, '2026-11-20 05:35:30', '2026-11-20 05:35:30'),
(8, 5, 'Unidade 2', 800, 1, '2026-11-20 05:36:06', '2026-11-20 05:36:06');

-- --------------------------------------------------------

--
-- Structure de la table `oferta_cursos`
--

CREATE TABLE `oferta_cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `semestre_id` bigint(20) UNSIGNED NOT NULL,
  `quantidade_turmas` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `situacao` varchar(30) NOT NULL DEFAULT 'Planejada',
  `observacao` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `semestre_fim_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `oferta_cursos`
--

INSERT INTO `oferta_cursos` (`id`, `curso_id`, `semestre_id`, `quantidade_turmas`, `situacao`, `observacao`, `created_at`, `updated_at`, `semestre_fim_id`) VALUES
(5, 1, 2, 1, 'Em andamento', NULL, '2026-11-19 06:06:13', '2026-11-19 08:13:41', 8),
(6, 3, 2, 1, 'Planejada', 'Teste de geração automática', '2026-11-19 06:12:28', '2026-11-19 06:12:28', 8);

-- --------------------------------------------------------

--
-- Structure de la table `oferta_disciplinas`
--

CREATE TABLE `oferta_disciplinas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disciplina_id` bigint(20) UNSIGNED NOT NULL,
  `semestre_id` bigint(20) UNSIGNED NOT NULL,
  `docente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `carga_horaria_prevista` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `oferta_curso_id` bigint(20) UNSIGNED DEFAULT NULL,
  `carga_horaria_cumprida` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `horas_legadas` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `oferta_disciplinas`
--

INSERT INTO `oferta_disciplinas` (`id`, `disciplina_id`, `semestre_id`, `docente_id`, `carga_horaria_prevista`, `created_at`, `updated_at`, `oferta_curso_id`, `carga_horaria_cumprida`, `horas_legadas`) VALUES
(1, 1, 2, 1, 65, '2026-11-01 04:30:42', '2026-11-01 05:03:30', NULL, 0, 0),
(2, 4, 2, 3, 22, '2026-11-01 04:31:05', '2026-11-19 07:28:11', NULL, 0, 0),
(4, 3, 2, 3, 55, '2026-11-01 05:34:21', '2026-11-19 08:14:40', 5, 25, 25),
(5, 1, 2, 1, 60, '2026-11-13 22:39:04', '2026-11-13 22:39:04', NULL, 0, 0),
(6, 5, 2, 1, 62, '2026-11-19 06:10:11', '2026-11-19 07:57:25', NULL, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `registros_aulas`
--

CREATE TABLE `registros_aulas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `oferta_disciplina_id` bigint(20) UNSIGNED NOT NULL,
  `data_aula` date NOT NULL,
  `horas_realizadas` int(10) UNSIGNED NOT NULL,
  `tipo_atividade` varchar(30) NOT NULL,
  `conteudo_ministrado` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `registros_aulas`
--

INSERT INTO `registros_aulas` (`id`, `oferta_disciplina_id`, `data_aula`, `horas_realizadas`, `tipo_atividade`, `conteudo_ministrado`, `created_at`, `updated_at`) VALUES
(1, 6, '2026-11-19', 4, 'Teórica', 'Como reconhecer um cancer', '2026-11-19 07:54:03', '2026-11-19 07:57:25');

-- --------------------------------------------------------

--
-- Structure de la table `semestres`
--

CREATE TABLE `semestres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identificador` varchar(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `semestres`
--

INSERT INTO `semestres` (`id`, `identificador`, `created_at`, `updated_at`) VALUES
(2, '2026.2', '2026-10-31 07:17:10', '2026-10-31 07:17:10'),
(3, '2027.1', '2026-11-12 06:39:19', '2026-11-12 06:39:19'),
(4, '2026.1', '2026-11-12 06:39:25', '2026-11-12 06:39:25'),
(5, '2027.2', '2026-11-12 06:39:35', '2026-11-12 06:39:35'),
(6, '2025.1', '2026-11-12 06:39:45', '2026-11-12 06:39:45'),
(7, '2025.2', '2026-11-12 06:39:55', '2026-11-12 06:39:55'),
(8, '2028.1', '2026-11-19 06:05:08', '2026-11-19 06:05:08');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('c0ZJ2QfmmEWQTmrQkPvRxEGrs3xW7ZRAc623p8Vt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiS2NrWFB2OEpWdUlxazYya3hTVHlGNUl2bGdJYjFmWUJQZndaeHhHdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/Y3Vyc29faWQ9MSZvZmVydGFfaWQ9JnNlbWVzdHJlX2lkPTIiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiN2JlMTUzMmFlZmRjNjBlYjgwZTgzNDJlZmMzMTk0ZmU5NzBkYmU5ZWMwZGNlMjkwZjhkN2Q2NDIzNGIyYzc1MSI7fQ==', 1795147835),
('HAVgtkeEsUZshfGayyvNNwBlcts9qPey2szR2SMv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVhnYnlxczZZY2p3ZWxjTmFINWdJeGdaNEN6cUdhdElkUEE0cmhUUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2N1cnNvcy9jcmVhdGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1795215358),
('I6RjP7BgOUnSQkOxorY3kNIjODMzXVbtQWJIWLhI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoieTdlN1BRZWpQQXVSeTJuN01RZ2hsbko3ZXVCYXpsb2ZSWHZsc1NPbCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiN2JlMTUzMmFlZmRjNjBlYjgwZTgzNDJlZmMzMTk0ZmU5NzBkYmU5ZWMwZGNlMjkwZjhkN2Q2NDIzNGIyYzc1MSI7fQ==', 1795452218),
('N6rr9DFe7IH3mubjb4hti9cSIAybJadz5lsyavu3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU3BkcDNSUDJiT2d1QzRGUExBN3lkUDRyMGxCblhVVU1SQzBVNlRldyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjdiZTE1MzJhZWZkYzYwZWI4MGU4MzQyZWZjMzE5NGZlOTcwZGJlOWVjMGRjZTI5MGY4ZDdkNjQyMzRiMmM3NTEiO30=', 1795311871),
('uLDcSO1D6d6aMqzOnlThEcewITRIPxUPZypwGuGn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib0FYYTJEbGNWbkhtbnBkdVliU2psYkV4ZWI2R09uYmVmZ1kwZHFZSCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1795452580),
('UzFSFs8suH9DSyzkT1ZA0g7DFMhLMxLEwQvU2t00', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTTM3OE1pV1JjVmNNeEJkTXB4VjNOdGhhYkkwMlJINU1pUUx0dTVIaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwLz9jdXJzb19pZD0xJm9mZXJ0YV9pZD0mc2VtZXN0cmVfaWQ9MiI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1795214644),
('WTVffmsZzF19d9eAoDmOMP6CoMUqBu5z1Q8NL8Px', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFIyWlh2RGFPVlBUQ2pXSm42Wm1vbWhrcVBRNnNjanAwQTI3djVhTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1795214642);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `perfil` varchar(20) NOT NULL DEFAULT 'visitante',
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `perfil`, `foto_perfil`) VALUES
(1, 'Admin', 'nouasse.ben@gmail.com', NULL, '$2y$12$HQXxYbVEsQstsowvmBaaNefAWwmNiso8qIwxulryDdTyEPkhCUkza', NULL, '2026-11-19 08:39:18', '2026-11-19 09:03:54', 'admin', 'fotos-perfil/NBPWuXYKuuYuQMrusx8WWW4MDURQmkttk1NEOFVW.jpg'),
(2, 'AMANDA V A SILVA', 'amandaangelocca@gmail.com', NULL, '$2y$12$LPJBDMD0gmPH.LKYiOw5F.gwQHd3O1G.gCPlOMK07Ge7E1tlx5li.', NULL, '2026-11-19 08:49:32', '2026-11-20 06:22:19', 'visitante', NULL),
(3, 'Isis Nouasse', 'Isis@gmailcom', NULL, '$2y$12$T5Icr3OtQNDB1WpEIzzBnOJ3NODTZRpKyBvVt0VZSsHGFyGfMCyNa', NULL, '2026-11-19 09:03:12', '2026-11-19 09:03:12', 'visitante', NULL),
(4, 'Noah Sedjro', 'noahsedjro@gmail.com', NULL, '$2y$12$PEDzZRDIh72nQDSBORy/e.HhDmkz8.IUmKEI8amwqUb6IP8HXZl2W', NULL, '2026-11-20 05:54:53', '2026-11-20 05:54:53', 'visitante', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `acessos_usuarios`
--
ALTER TABLE `acessos_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acessos_usuarios_user_id_foreign` (`user_id`),
  ADD KEY `acessos_usuarios_perfil_created_at_index` (`perfil`,`created_at`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disciplinas_curso_id_foreign` (`curso_id`),
  ADD KEY `disciplinas_modulo_id_foreign` (`modulo_id`);

--
-- Index pour la table `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `docentes_siape_unique` (`siape`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modulos_curso_id_foreign` (`curso_id`);

--
-- Index pour la table `oferta_cursos`
--
ALTER TABLE `oferta_cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oferta_cursos_curso_id_foreign` (`curso_id`),
  ADD KEY `oferta_cursos_semestre_id_foreign` (`semestre_id`),
  ADD KEY `oferta_cursos_semestre_fim_id_foreign` (`semestre_fim_id`);

--
-- Index pour la table `oferta_disciplinas`
--
ALTER TABLE `oferta_disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oferta_disciplinas_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `oferta_disciplinas_semestre_id_foreign` (`semestre_id`),
  ADD KEY `oferta_disciplinas_docente_id_foreign` (`docente_id`),
  ADD KEY `oferta_disciplinas_oferta_curso_id_foreign` (`oferta_curso_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `registros_aulas`
--
ALTER TABLE `registros_aulas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_aulas_oferta_disciplina_id_data_aula_index` (`oferta_disciplina_id`,`data_aula`);

--
-- Index pour la table `semestres`
--
ALTER TABLE `semestres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semestres_identificador_unique` (`identificador`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `acessos_usuarios`
--
ALTER TABLE `acessos_usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `oferta_cursos`
--
ALTER TABLE `oferta_cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `oferta_disciplinas`
--
ALTER TABLE `oferta_disciplinas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `registros_aulas`
--
ALTER TABLE `registros_aulas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `semestres`
--
ALTER TABLE `semestres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `acessos_usuarios`
--
ALTER TABLE `acessos_usuarios`
  ADD CONSTRAINT `acessos_usuarios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD CONSTRAINT `disciplinas_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `disciplinas_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`);

--
-- Contraintes pour la table `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);

--
-- Contraintes pour la table `oferta_cursos`
--
ALTER TABLE `oferta_cursos`
  ADD CONSTRAINT `oferta_cursos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `oferta_cursos_semestre_fim_id_foreign` FOREIGN KEY (`semestre_fim_id`) REFERENCES `semestres` (`id`),
  ADD CONSTRAINT `oferta_cursos_semestre_id_foreign` FOREIGN KEY (`semestre_id`) REFERENCES `semestres` (`id`);

--
-- Contraintes pour la table `oferta_disciplinas`
--
ALTER TABLE `oferta_disciplinas`
  ADD CONSTRAINT `oferta_disciplinas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `oferta_disciplinas_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`),
  ADD CONSTRAINT `oferta_disciplinas_oferta_curso_id_foreign` FOREIGN KEY (`oferta_curso_id`) REFERENCES `oferta_cursos` (`id`),
  ADD CONSTRAINT `oferta_disciplinas_semestre_id_foreign` FOREIGN KEY (`semestre_id`) REFERENCES `semestres` (`id`);

--
-- Contraintes pour la table `registros_aulas`
--
ALTER TABLE `registros_aulas`
  ADD CONSTRAINT `registros_aulas_oferta_disciplina_id_foreign` FOREIGN KEY (`oferta_disciplina_id`) REFERENCES `oferta_disciplinas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
