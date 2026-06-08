DROP DATABASE IF EXISTS servicio_medico;
CREATE DATABASE servicio_medico;
USE servicio_medico;


CREATE TABLE `usuarios` (
  `cedula` integer PRIMARY KEY NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `tipo` tinyint NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `tlfprincipal` varchar(20) NOT NULL,
  `tlfemergencia` varchar(20) NOT NULL,
  `nombre_contacto_emergencia` text NOT NULL,
  `rol` tinyint NOT NULL DEFAULT 0,
  `sexo` tinyint NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;

CREATE TABLE `lista_patologias` (
  `codigo_icd` varchar(10) PRIMARY KEY NOT NULL,
  `patologia` text NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `lista_condiciones` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `condicion` varchar(255) UNIQUE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `lista_pnfs` (
  `id_pnf` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_pnf` text
) ENGINE=InnoDB;

CREATE TABLE `lista_nucleos` (
  `id_nucleo` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_nucleo` text
) ENGINE=InnoDB;

-- ========================================================
-- 2. CREACIÓN DE TABLAS DEPENDIENTES Y TRANSACCIONALES
-- ========================================================

CREATE TABLE `consulta_medica` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_usuario` integer NOT NULL,
  `id_medico` integer NOT NULL,
  `motivo_de_visita` text NOT NULL,
  `observaciones` text NOT NULL,
  `medicamento_suministrado` text,
  `fecha_consulta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;

CREATE TABLE `diagnosticos_consulta` (
  `id_consulta` integer PRIMARY KEY NOT NULL,
  `codigo_icd_diagnostico` varchar(10) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `sintomas_consulta` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_consulta` integer NOT NULL,
  `sintoma` text NOT NULL
) ENGINE=InnoDB;

-- ========================================================
-- 3. CREACIÓN DE TABLAS INTERMEDIAS (SIN LLAVES PRIMARIAS)
-- ========================================================



CREATE TABLE `condiciones_usuarios` (
  `cedula_usuario` integer NOT NULL,
  `id_condicion` integer NOT NULL,
  `fecha_registro` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`cedula_usuario`, `id_condicion`)
) ENGINE=InnoDB;

-- Esta tabla queda libre de Primary Key
CREATE TABLE `pnfs_usuarios` (
  `cedula_usuario` integer NOT NULL,
  `nucleo_id` integer NOT NULL,
  `pnf_id` integer NOT NULL
) ENGINE=InnoDB;

-- Esta tabla queda libre de Primary Key
CREATE TABLE `nucleo_pnf` (
  `id_nucleo` integer NOT NULL,
  `id_pnf` integer NOT NULL
) ENGINE=InnoDB;

-- ========================================================
-- 4. LLAVES FORÁNEAS REALES
-- ========================================================

-- Consultas Médicas
ALTER TABLE `consulta_medica` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `consulta_medica` ADD FOREIGN KEY (`id_medico`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Diagnósticos y Síntomas
ALTER TABLE `diagnosticos_consulta` ADD FOREIGN KEY (`id_consulta`) REFERENCES `consulta_medica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `diagnosticos_consulta` ADD FOREIGN KEY (`codigo_icd_diagnostico`) REFERENCES `lista_patologias` (`codigo_icd`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `sintomas_consulta` ADD FOREIGN KEY (`id_consulta`) REFERENCES `consulta_medica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Relaciones de Usuarios con Condiciones
ALTER TABLE `condiciones_usuarios` ADD FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `condiciones_usuarios` ADD FOREIGN KEY (`id_condicion`) REFERENCES `lista_condiciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Relaciones de PNF y Núcleos (Creando los índices manualmente para que MySQL permita las FK)
ALTER TABLE `pnfs_usuarios` ADD INDEX (`cedula_usuario`), ADD INDEX (`nucleo_id`), ADD INDEX (`pnf_id`);
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`nucleo_id`) REFERENCES `lista_nucleos` (`id_nucleo`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`pnf_id`) REFERENCES `lista_pnfs` (`id_pnf`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `nucleo_pnf` ADD INDEX (`id_nucleo`), ADD INDEX (`id_pnf`);
ALTER TABLE `nucleo_pnf` ADD FOREIGN KEY (`id_nucleo`) REFERENCES `lista_nucleos` (`id_nucleo`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `nucleo_pnf` ADD FOREIGN KEY (`id_pnf`) REFERENCES `lista_pnfs` (`id_pnf`) ON DELETE CASCADE ON UPDATE CASCADE;




-- INSERTAR DATOS LUEGO DE CREAR TODO

INSERT INTO `lista_nucleos` (`id_nucleo`, `nombre_nucleo`) VALUES
(2, 'Núcleo Carora (Torres)'),
(3, 'Núcleo El Tocuyo (Morán)'),
(4, 'Núcleo Sarare (Simón Planas)'),
(1, 'Sede Central (Barquisimeto)');

INSERT INTO `lista_pnfs` (`id_pnf`, `nombre_pnf`) VALUES
(1, 'Administración'),
(2, 'Agroalimentación'),
(3, 'Ciencias de la Información'),
(4, 'Contaduría Pública'),
(5, 'Deportes'),
(6, 'Distribución y Logística'),
(7, 'Higiene y Seguridad Laboral'),
(8, 'Informática'),
(9, 'Materiales Industriales'),
(10, 'Procesos Químicos'),
(11, 'Sistemas de Calidad y Ambiente'),
(12, 'Turismo');


-- RELACION ENTRE LOS NUCLEOS Y SUS PNFS

INSERT INTO `nucleo_pnf` (`id_nucleo`, `id_pnf`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(2, 1),
(2, 2),
(2, 4),
(2, 8),
(3, 1),
(3, 4),
(3, 8),
(3, 10),
(4, 1),
(4, 2),
(4, 4);


-- ========================================================
-- 5. DATOS DE PATOLOGÍAS (CÓDIGOS ICD-10 COMUNES)
-- ========================================================

INSERT INTO `lista_patologias` (`codigo_icd`, `patologia`) VALUES
('A09', 'Diarrea y gastroenteritis de presunto origen infeccioso'),
('B35', 'Dermatofitosis (micosis)'),
('E11', 'Diabetes mellitus no insulinodependiente'),
('G44', 'Otros síndromes de cefalea (dolor de cabeza)'),
('I10', 'Hipertensión esencial (primaria)'),
('J00', 'Rinofaringitis aguda (resfriado común)'),
('J02', 'Faringitis aguda'),
('J03', 'Amigdalitis aguda'),
('K21', 'Enfermedad por reflujo gastroesofágico'),
('K29', 'Gastritis y duodenitis'),
('L03', 'Celulitis (infección cutánea)'),
('M54', 'Dorsalgia (dolor de espalda)'),
('N39', 'Infección del tracto urinario, sitio no especificado'),
('R50', 'Fiebre de origen desconocido');









