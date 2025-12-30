-- =========================================================
-- Proyecto FP.064 - Desarrollo back-end con PHP, framework MVC y gestor de contenidos
-- Asignatura: FP.064 (Jesuïtes - UOC)
--
-- Producto 3: Desarrollo de una aplicación de gestión de transfers con Laravel
--
-- Base de datos: uoc_transfers (versión adaptada por el grupo Code4Events)
-- Archivo original facilitado por el consultor: /mnt/data/UOC_transfers-1-1.sql
--
-- Descripción:
--   Script SQL adaptado por el grupo de trabajo para el desarrollo del
--   Producto 3. Incluye los ajustes necesarios para cumplir los requisitos
--   de negocio definidos en la asignatura.
--
-- Changelog:
--   v1.0 - 24-11-2025 - Grupo Code4Events
--       * Sustituido el encabezado generado por phpMyAdmin por un encabezado
--         propio documentando el proyecto y el origen del script.
--
--   v1.1 - 24-11-2025 - Grupo Code4Events
--       * Normalización de nombres de tablas a minúsculas y en plural.
--       * Normalización de nombres de columnas a minúsculas (snake_case cuando procede).
--       * Corrección del prefijo 'tranfer' a 'transfer' en la tabla de hoteles.
--
--   v1.2 - 24-11-2025 - Grupo Code4Events
--       * Ajuste de tipos de datos para alinearlos con la lógica de negocio.
--       * Rediseño de la tabla transfer_reservas para almacenar:
--           - hotel que realiza la reserva,
--           - viajero a nombre de quien va la reserva,
--           - creador y último modificador de la reserva,
--           - datos completos de vuelo de entrada y salida,
--           - estado de la reserva.
--       * Incorporación de login por email en hoteles, viajeros y conductores.
--       * Ampliación de longitud de password a varchar(255) para almacenar hashes.
--       * Mejora de la definición de zonas, tipos de reserva, hoteles, vehículos y viajeros.
--       * Definición de claves primarias, foráneas, índices y AUTO_INCREMENT
--         directamente en los bloques CREATE TABLE, estableciendo explícitamente
--         ON UPDATE RESTRICT y ON DELETE RESTRICT en las relaciones para
--         garantizar la integridad del histórico de reservas (las reservas no se
--         modifican ni eliminan automáticamente al borrar o actualizar datos maestros).
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!50503 SET NAMES utf8mb4 */;

-- Crear base de datos (si no existe) y usarla (modificar para servidor AWS de la UOc)
CREATE DATABASE IF NOT EXISTS `islatransfers_p3`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `islatransfers_p3`;

-- =========================================================
-- Eliminación de tablas existentes (si las hay)
-- =========================================================

DROP TABLE IF EXISTS `transfer_reservas`;
DROP TABLE IF EXISTS `transfer_precios`;
DROP TABLE IF EXISTS `transfer_tipos_reserva`;
DROP TABLE IF EXISTS `transfer_vehiculos`;
DROP TABLE IF EXISTS `transfer_viajeros`;
DROP TABLE IF EXISTS `transfer_hoteles`;
DROP TABLE IF EXISTS `transfer_zonas`;

-- =========================================================
-- Tabla: transfer_zonas
-- =========================================================

CREATE TABLE `transfer_zonas` (
  `id_zona`      INT(11)        NOT NULL AUTO_INCREMENT,
  `descripcion`  VARCHAR(100)   NOT NULL,
  `codigo`       VARCHAR(50)    NOT NULL,
  PRIMARY KEY (`id_zona`),
  UNIQUE KEY `uk_transfer_zonas_codigo` (`codigo`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_hoteles
-- =========================================================

CREATE TABLE `transfer_hoteles` (
  `id_hotel`   INT(11)         NOT NULL AUTO_INCREMENT,
  `id_zona`    INT(11)         DEFAULT NULL,
  `nombre`     VARCHAR(150)    NOT NULL,
  `email`      VARCHAR(150)    NOT NULL,
  `usuario`    VARCHAR(100)    DEFAULT NULL,
  `comision`   DECIMAL(5,2)    DEFAULT NULL,  -- porcentaje 0.00 - 100.00
  `telefono`   VARCHAR(50)     DEFAULT NULL,
  `password`   VARCHAR(255)    NOT NULL,
  PRIMARY KEY (`id_hotel`),
  UNIQUE KEY `uk_transfer_hoteles_email` (`email`),
  KEY `idx_transfer_hoteles_id_zona` (`id_zona`),
  CONSTRAINT `fk_transfer_hoteles_zona`
    FOREIGN KEY (`id_zona`)
    REFERENCES `transfer_zonas` (`id_zona`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_viajeros
-- =========================================================

CREATE TABLE `transfer_viajeros` (
  `id_viajero`     INT(11)         NOT NULL AUTO_INCREMENT,
  `nombre`         VARCHAR(100)    NOT NULL,
  `apellido1`      VARCHAR(100)    NOT NULL,
  `apellido2`      VARCHAR(100)    DEFAULT NULL,
  `direccion`      VARCHAR(150)    NOT NULL,
  `codigo_postal`  VARCHAR(20)     NOT NULL,
  `ciudad`         VARCHAR(100)    NOT NULL,
  `pais`           VARCHAR(100)    NOT NULL,
  `email`          VARCHAR(150)    NOT NULL,
  `telefono`       VARCHAR(50)     DEFAULT NULL,
  `password`       VARCHAR(255)    NOT NULL,
  `rol`            ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  `activo`         TINYINT(1)      NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_viajero`),
  UNIQUE KEY `uk_transfer_viajeros_email` (`email`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_vehiculos
-- =========================================================

CREATE TABLE `transfer_vehiculos` (
  `id_vehiculo`      INT(11)        NOT NULL AUTO_INCREMENT,
  `descripcion`      VARCHAR(100)   NOT NULL,    -- p.ej. "Sedán 4 pax"
  `email_conductor`  VARCHAR(150)   NOT NULL,
  `password`         VARCHAR(255)   NOT NULL,
  `matricula`        VARCHAR(20)    NOT NULL,
  `plazas`           INT(11)        NOT NULL,
  `activo`           TINYINT(1)     NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `uk_transfer_vehiculos_email_conductor` (`email_conductor`),
  UNIQUE KEY `uk_transfer_vehiculos_matricula` (`matricula`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_tipos_reserva
-- =========================================================

CREATE TABLE `transfer_tipos_reserva` (
  `id_tipo_reserva`  INT(11)        NOT NULL AUTO_INCREMENT,
  `descripcion`      VARCHAR(100)   NOT NULL,   -- p.ej.: "Ida", "Vuelta", "Ida y vuelta"
  `codigo`           VARCHAR(50)    NOT NULL,   -- p.ej.: "IDA", "VUELTA", "IDAVUELTA"
  PRIMARY KEY (`id_tipo_reserva`),
  UNIQUE KEY `uk_transfer_tipos_reserva_codigo` (`codigo`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_precios
-- =========================================================

CREATE TABLE `transfer_precios` (
  `id_precio`   INT(11)         NOT NULL AUTO_INCREMENT,
  `id_vehiculo` INT(11)         NOT NULL,
  `id_hotel`    INT(11)         NOT NULL,
  `precio`      DECIMAL(10,2)   NOT NULL,
  PRIMARY KEY (`id_precio`),
  UNIQUE KEY `uk_transfer_precios_hotel_vehiculo` (`id_hotel`, `id_vehiculo`),
  KEY `idx_transfer_precios_id_vehiculo` (`id_vehiculo`),
  KEY `idx_transfer_precios_id_hotel` (`id_hotel`),
  CONSTRAINT `fk_transfer_precios_hotel`
    FOREIGN KEY (`id_hotel`)
    REFERENCES `transfer_hoteles` (`id_hotel`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,
  CONSTRAINT `fk_transfer_precios_vehiculo`
    FOREIGN KEY (`id_vehiculo`)
    REFERENCES `transfer_vehiculos` (`id_vehiculo`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabla: transfer_reservas
-- =========================================================

CREATE TABLE `transfer_reservas` (
  `id_reserva`           INT(11)         NOT NULL AUTO_INCREMENT,
  `localizador`          VARCHAR(50)     NOT NULL,

  -- Quién realiza / gestiona la reserva
  `id_hotel`             INT(11)         DEFAULT NULL,  -- hotel que realiza la reserva (si aplica)
  `id_viajero`           INT(11)         NOT NULL,      -- viajero a nombre de quien va la reserva
  `id_creador`           INT(11)         NOT NULL,      -- usuario (cliente/admin) que creó la reserva
  `id_modificador`       INT(11)         DEFAULT NULL,  -- último usuario que modificó la reserva
  `id_tipo_reserva`      INT(11)         NOT NULL,

  -- Fechas de gestión
  `fecha_reserva`        DATETIME        NOT NULL,
  `fecha_modificacion`   DATETIME        DEFAULT NULL,

  -- Datos del servicio / destino
  `id_hotel_destino`     INT(11)         NOT NULL,      -- hotel donde se aloja el viajero
  `num_viajeros`         INT(11)         NOT NULL,
  `id_vehiculo`          INT(11)         NOT NULL,

  -- Vuelo de entrada (llegada)
  `fecha_entrada`        DATE            DEFAULT NULL,
  `hora_entrada`         TIME            DEFAULT NULL,
  `numero_vuelo_entrada` VARCHAR(50)     DEFAULT NULL,
  `origen_vuelo_entrada` VARCHAR(100)    DEFAULT NULL,

  -- Vuelo de salida (opcional)
  `fecha_vuelo_salida`   DATE            DEFAULT NULL,
  `hora_vuelo_salida`    TIME            DEFAULT NULL,
  `numero_vuelo_salida`  VARCHAR(50)     DEFAULT NULL,
  `destino_vuelo_salida` VARCHAR(100)    DEFAULT NULL,

  -- Control y estado
  `estado`               ENUM('pendiente','confirmada','cancelada','realizada')
                         NOT NULL DEFAULT 'pendiente',
  `observaciones`        TEXT            DEFAULT NULL,

  PRIMARY KEY (`id_reserva`),
  UNIQUE KEY `uk_transfer_reservas_localizador` (`localizador`),

  KEY `idx_transfer_reservas_id_hotel` (`id_hotel`),
  KEY `idx_transfer_reservas_id_viajero` (`id_viajero`),
  KEY `idx_transfer_reservas_id_creador` (`id_creador`),
  KEY `idx_transfer_reservas_id_modificador` (`id_modificador`),
  KEY `idx_transfer_reservas_id_tipo_reserva` (`id_tipo_reserva`),
  KEY `idx_transfer_reservas_id_hotel_destino` (`id_hotel_destino`),
  KEY `idx_transfer_reservas_id_vehiculo` (`id_vehiculo`),

  CONSTRAINT `fk_transfer_reservas_hotel`
    FOREIGN KEY (`id_hotel`)
    REFERENCES `transfer_hoteles` (`id_hotel`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_hotel_destino`
    FOREIGN KEY (`id_hotel_destino`)
    REFERENCES `transfer_hoteles` (`id_hotel`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_viajero`
    FOREIGN KEY (`id_viajero`)
    REFERENCES `transfer_viajeros` (`id_viajero`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_creador`
    FOREIGN KEY (`id_creador`)
    REFERENCES `transfer_viajeros` (`id_viajero`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_modificador`
    FOREIGN KEY (`id_modificador`)
    REFERENCES `transfer_viajeros` (`id_viajero`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_tipo`
    FOREIGN KEY (`id_tipo_reserva`)
    REFERENCES `transfer_tipos_reserva` (`id_tipo_reserva`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,

  CONSTRAINT `fk_transfer_reservas_vehiculo`
    FOREIGN KEY (`id_vehiculo`)
    REFERENCES `transfer_vehiculos` (`id_vehiculo`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

