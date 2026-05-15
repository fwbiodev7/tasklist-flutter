-- ============================================================
--  Copa Sustentável — Script de criação do banco MySQL
--  Importar via phpMyAdmin: Importar > Escolher Arquivo > Executar
-- ============================================================

-- Cria e seleciona o banco
CREATE DATABASE IF NOT EXISTS `copa_sustentavel`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `copa_sustentavel`;

-- ------------------------------------------------------------
-- Tabela: teams
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `teams` (
    `id`           INT          NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(100) NOT NULL,
    `country`      VARCHAR(100) NOT NULL DEFAULT '',
    `total_points` INT          NOT NULL DEFAULT 0,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: donations
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `donations` (
    `id`             INT           NOT NULL AUTO_INCREMENT,
    `team_id`        INT           NOT NULL,
    `material_type`  VARCHAR(50)   NOT NULL,
    `quantity`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `points_awarded` INT           NOT NULL DEFAULT 0,
    `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_donations_team`
        FOREIGN KEY (`team_id`) REFERENCES `teams`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: admins
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `username`   VARCHAR(50)  NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  TIMES (espelhando o app.js — fonte verdade do projeto)
-- ============================================================
INSERT INTO `teams` (`id`, `name`, `country`, `total_points`) VALUES
( 1, '2º LOG',   'Brasil',          0),
( 2, '2º ELE',   'México',          0),
( 3, '1º LOG',   'Estados Unidos',  0),
( 4, '3º SIST',  'Nova Zelândia',   0),
( 5, '1º ELE',   'Marrocos',        0),
( 6, '2º PROP',  'França',          0),
( 7, '1º SIST',  'Portugal',        0),
( 8, '2º SIST',  'Alemanha',        0),
( 9, '3º PROP',  'Inglaterra',      0),
(10, '1º INF',   'Espanha',         0),
(11, '3º LOG',   'Catar',           0),
(12, '3º ELE',   'Coreia do Sul',   0);

-- ============================================================
--  ADMIN
--  Usuário : rodrigones
--  Senha   : 11018462
--  (hash gerado com password_hash('11018462', PASSWORD_BCRYPT))
-- ============================================================
INSERT INTO `admins` (`username`, `password`) VALUES
('rodrigones', '$2y$10$U4bZ1pMvbrocVZIQvJc8gOpfyrBDq3rDzZt2u85A7726t5s8LJE3i');

-- ============================================================
--  FIM DO SCRIPT
-- ============================================================
