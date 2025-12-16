-- ##################################################################
-- 1. CREACIÓN DE TABLAS (ESTRUCTURA)
-- ##################################################################

-- -----------------------------------------------------
-- 1.1. Estructura para la tabla `users` (Requerida para FKs)
-- Asumiendo la estructura básica de Laravel
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- 1.2. Estructura para la tabla `categorias`
-- Corresponde al modelo Categoria
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- 1.3. Estructura para la tabla `ubicaciones`
-- Corresponde al modelo Ubicacion
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ubicaciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- 1.4. Estructura para la tabla `articulos`
-- Corresponde al modelo Articulo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `codigo_uts` VARCHAR(100) UNIQUE NULL,
  `descripcion` TEXT NULL,
  `marca` VARCHAR(255) NULL,
  `modelo` VARCHAR(255) NULL,
  `numero_serie` VARCHAR(255) UNIQUE NULL,
  `estado` VARCHAR(50) NOT NULL,
  `calcomania` VARCHAR(255) UNIQUE NULL,
  `categoria_id` BIGINT UNSIGNED NOT NULL,
  `ubicacion_id` BIGINT UNSIGNED NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_articulos_categoria_idx` (`categoria_id` ASC),
  INDEX `fk_articulos_ubicacion_idx` (`ubicacion_id` ASC),
  CONSTRAINT `fk_articulos_categoria`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `categorias` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_articulos_ubicacion`
    FOREIGN KEY (`ubicacion_id`)
    REFERENCES `ubicaciones` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- 1.5. Estructura para la tabla `prestamos` (CORREGIDO: FKs a users)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prestamos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `articulo_id` BIGINT UNSIGNED NOT NULL,
  `usuario_solicitante_id` BIGINT UNSIGNED NOT NULL,
  `usuario_despacha_id` BIGINT UNSIGNED NULL,
  `usuario_recibe_id` BIGINT UNSIGNED NULL,
  `fecha_prestamo` DATETIME NOT NULL,
  `fecha_devolucion_estimada` DATETIME NULL,
  `fecha_devolucion_real` DATETIME NULL,
  `estado` VARCHAR(50) NOT NULL,
  `observaciones_prestamo` TEXT NULL,
  `observaciones_devolucion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_prestamos_articulo_idx` (`articulo_id` ASC),
  INDEX `fk_prestamos_solicitante_idx` (`usuario_solicitante_id` ASC),
  
  CONSTRAINT `fk_prestamos_articulo`
    FOREIGN KEY (`articulo_id`)
    REFERENCES `articulos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_prestamos_solicitante`
    FOREIGN KEY (`usuario_solicitante_id`)
    REFERENCES `users` (`id`) -- APUNTA A 'users'
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_prestamos_despacha`
    FOREIGN KEY (`usuario_despacha_id`)
    REFERENCES `users` (`id`) -- APUNTA A 'users'
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_prestamos_recibe`
    FOREIGN KEY (`usuario_recibe_id`)
    REFERENCES `users` (`id`) -- APUNTA A 'users'
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- 1.6. Estructura para la tabla `reportes_mantenimiento` (CORREGIDO: FKs a users)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reportes_mantenimiento` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `articulo_id` BIGINT UNSIGNED NOT NULL,
  `usuario_reporta_id` BIGINT UNSIGNED NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `descripcion_problema` TEXT NOT NULL,
  `descripcion_solucion` TEXT NULL,
  `fecha_reporte` DATETIME NOT NULL,
  `fecha_solucion` DATETIME NULL,
  `estado` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_reporte_articulo_idx` (`articulo_id` ASC),
  INDEX `fk_reporte_usuario_idx` (`usuario_reporta_id` ASC),
  
  CONSTRAINT `fk_reporte_articulo`
    FOREIGN KEY (`articulo_id`)
    REFERENCES `articulos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reporte_usuario`
    FOREIGN KEY (`usuario_reporta_id`)
    REFERENCES `users` (`id`) -- APUNTA A 'users'
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB;


-- ##################################################################
-- 2. INSERCIÓN DE DATOS DE EJEMPLO
-- ##################################################################

-- -----------------------------------------------------
-- 2.1. Insertar usuarios (users)
-- (La contraseña debe ser hasheada en un entorno real)
-- -----------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password`) VALUES
('Juan Pérez', 'juan.perez@uts.edu', 'password_hasheada_1'),
('Ana López', 'ana.lopez@uts.edu', 'password_hasheada_2'),
('Admin Inventario', 'admin@uts.edu', 'password_hasheada_3');

-- -----------------------------------------------------
-- 2.2. Insertar categorías
-- -----------------------------------------------------
INSERT INTO `categorias` (`nombre`, `descripcion`) VALUES
('Equipo de Cómputo', 'Laptops, desktops, y monitores.'),
('Periféricos', 'Teclados, ratones, impresoras y webcams.'),
('Mobiliario', 'Sillas, escritorios y estanterías.');

-- -----------------------------------------------------
-- 2.3. Insertar ubicaciones
-- -----------------------------------------------------
INSERT INTO `ubicaciones` (`nombre`, `descripcion`) VALUES
('Sala 101 - Laboratorio de Redes', 'Ubicado en el primer piso del bloque B.'),
('Oficina de Coordinación', 'Tercer piso, área administrativa.'),
('Almacén Central', 'Área de inventario principal.');

-- -----------------------------------------------------
-- 2.4. Insertar artículos
-- -----------------------------------------------------
INSERT INTO `articulos` (`nombre`, `codigo_uts`, `descripcion`, `marca`, `modelo`, `numero_serie`, `estado`, `calcomania`, `categoria_id`, `ubicacion_id`) VALUES
('Laptop Dell Inspiron', 'UTS-LPT-001', 'Portátil para uso en laboratorio, 16GB RAM.', 'Dell', 'Inspiron 15', 'SN-0A1B2C3D4E', 'Disponible', 'C-0001', 1, 1),
('Monitor Samsung 24"', 'UTS-MON-005', 'Monitor LED de 24 pulgadas.', 'Samsung', 'S24F350FHL', 'SN-F5G6H7I8J9', 'Disponible', 'C-0002', 1, 1),
('Impresora Láser HP', 'UTS-PRT-010', 'Impresora multifuncional blanco y negro.', 'HP', 'LaserJet Pro M102w', 'SN-K1L2M3N4O5', 'Disponible', 'C-0003', 2, 2);

-- -----------------------------------------------------
-- 2.5. Insertar préstamos
-- usuario_solicitante_id (1=Juan Pérez), usuario_despacha_id (3=Admin)
-- -----------------------------------------------------
INSERT INTO `prestamos` (`articulo_id`, `usuario_solicitante_id`, `usuario_despacha_id`, `usuario_recibe_id`, `fecha_prestamo`, `fecha_devolucion_estimada`, `fecha_devolucion_real`, `estado`, `observaciones_prestamo`, `observaciones_devolucion`) VALUES
(1, 1, 3, NULL, '2025-12-15 10:00:00', '2025-12-18 18:00:00', NULL, 'Activo', 'Préstamo solicitado para proyecto de tesis.', NULL),
(2, 2, 3, 3, '2025-12-01 14:30:00', '2025-12-05 17:00:00', '2025-12-04 16:45:00', 'Finalizado', 'Préstamo para evento académico.', 'Devuelto en perfectas condiciones.');

-- -----------------------------------------------------
-- 2.6. Insertar reportes de mantenimiento
-- usuario_reporta_id (2=Ana López)
-- -----------------------------------------------------
INSERT INTO `reportes_mantenimiento` (`articulo_id`, `usuario_reporta_id`, `tipo`, `descripcion_problema`, `descripcion_solucion`, `fecha_reporte`, `fecha_solucion`, `estado`) VALUES
(3, 2, 'Correctivo', 'La impresora no toma el papel, error de bandeja.', 'Se reemplazó el rodillo de alimentación de papel.', '2025-12-10 09:00:00', '2025-12-12 15:30:00', 'Resuelto'),
(1, 1, 'Preventivo', 'El ventilador del CPU hace mucho ruido.', NULL, '2025-12-15 11:30:00', NULL, 'Pendiente');