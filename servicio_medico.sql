SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `usuarios` (
  `cedula` integer PRIMARY KEY NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `tipo` integer NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `tlfprincipal` varchar(20) NOT NULL,
  `tlfemergencia` varchar(20) NOT NULL,
  `nombre_contacto_emergencia` text NOT NULL,
  `direccion` text NOT NULL,
  `activo` tinyint NOT NULL DEFAULT 1,
  `rol` tinyint NOT NULL DEFAULT 0,
  `sexo` tinyint NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;

CREATE TABLE `lista_roles` (
  `id_rol` tinyint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_rol` text NOT NULL,
  `descripcion_rol` text
) ENGINE=InnoDB;

CREATE TABLE `lista_permisos` (
  `id_permiso` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_permiso` varchar(100) UNIQUE NOT NULL,
  `descripcion_permiso` text
) ENGINE=InnoDB;

CREATE TABLE `roles_permisos` (
  `id_rol` tinyint NOT NULL,
  `id_permiso` integer NOT NULL,
  PRIMARY KEY (`id_rol`, `id_permiso`)
) ENGINE=InnoDB;

CREATE TABLE `consulta_medica` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_usuario` integer NOT NULL,
  `id_medico` integer NOT NULL,
  `motivo_de_visita` text NOT NULL,
  `observaciones` text NOT NULL,
  `medicamento_suministrado` text,
  `fecha_consulta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;

CREATE TABLE `lista_patologias` (
  `codigo_icd` varchar(10) PRIMARY KEY NOT NULL,
  `patologia` text NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `diagnosticos_consulta` (
  `id_consulta` integer NOT NULL,
  `codigo_icd_diagnostico` varchar(10) NOT NULL,
  PRIMARY KEY (`id_consulta`, `codigo_icd_diagnostico`)
) ENGINE=InnoDB;

CREATE TABLE `sintomas_consulta` (
  `id_consulta` integer NOT NULL,
  `id_sintoma` integer NOT NULL,
  PRIMARY KEY (`id_consulta`, `id_sintoma`)
) ENGINE=InnoDB;

CREATE TABLE `patologias_usuarios` (
  `cedula_usuario` integer NOT NULL,
  `codigo_icd` varchar(10) NOT NULL,
  PRIMARY KEY (`cedula_usuario`, `codigo_icd`)
) ENGINE=InnoDB;

CREATE TABLE `lista_condiciones` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_condicion` varchar(255) UNIQUE NOT NULL,
  `descripcion_condicion` text
) ENGINE=InnoDB;

CREATE TABLE `condiciones_usuarios` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `cedula_usuario` integer NOT NULL,
  `id_condicion` integer NOT NULL,
  `fecha_registro` timestamp DEFAULT current_timestamp()
) ENGINE=InnoDB;

CREATE TABLE `pnfs_usuarios` (
  `cedula_usuario` integer PRIMARY KEY NOT NULL,
  `nucleo_id` integer NOT NULL,
  `pnf_id` integer NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `nucleo_pnf` (
  `id_nucleo` integer NOT NULL,
  `id_pnf` integer NOT NULL,
  PRIMARY KEY (`id_nucleo`, `id_pnf`)
) ENGINE=InnoDB;

CREATE TABLE `lista_pnfs` (
  `id_pnf` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_pnf` text NOT NULL,
  `descripcion_pnf` text
) ENGINE=InnoDB;

CREATE TABLE `lista_sintomas` (
  `id_sintoma` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_sintoma` text NOT NULL,
  `descripcion_sintoma` text
) ENGINE=InnoDB;

CREATE TABLE `lista_nucleos` (
  `id_nucleo` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_nucleo` text NOT NULL,
  `descripcion_nucleo` text
) ENGINE=InnoDB;

CREATE TABLE `lista_tipos` (
  `id_tipo` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre_tipo` text NOT NULL,
  `descripcion_tipo` text
) ENGINE=InnoDB;

CREATE TABLE `configuracion` (
  `rol_defecto` tinyint NOT NULL
) ENGINE=InnoDB;

ALTER TABLE `usuarios` ADD FOREIGN KEY (`tipo`) REFERENCES `lista_tipos` (`id_tipo`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `usuarios` ADD FOREIGN KEY (`rol`) REFERENCES `lista_roles` (`id_rol`) ON DELETE SET DEFAULT ON UPDATE CASCADE;
ALTER TABLE `roles_permisos` ADD FOREIGN KEY (`id_rol`) REFERENCES `lista_roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `roles_permisos` ADD FOREIGN KEY (`id_permiso`) REFERENCES `lista_permisos` (`id_permiso`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `consulta_medica` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `consulta_medica` ADD FOREIGN KEY (`id_medico`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `diagnosticos_consulta` ADD FOREIGN KEY (`id_consulta`) REFERENCES `consulta_medica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `diagnosticos_consulta` ADD FOREIGN KEY (`codigo_icd_diagnostico`) REFERENCES `lista_patologias` (`codigo_icd`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `sintomas_consulta` ADD FOREIGN KEY (`id_consulta`) REFERENCES `consulta_medica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `sintomas_consulta` ADD FOREIGN KEY (`id_sintoma`) REFERENCES `lista_sintomas` (`id_sintoma`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `patologias_usuarios` ADD FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `patologias_usuarios` ADD FOREIGN KEY (`codigo_icd`) REFERENCES `lista_patologias` (`codigo_icd`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `condiciones_usuarios` ADD FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `condiciones_usuarios` ADD FOREIGN KEY (`id_condicion`) REFERENCES `lista_condiciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`nucleo_id`) REFERENCES `lista_nucleos` (`id_nucleo`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `pnfs_usuarios` ADD FOREIGN KEY (`pnf_id`) REFERENCES `lista_pnfs` (`id_pnf`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `nucleo_pnf` ADD FOREIGN KEY (`id_nucleo`) REFERENCES `lista_nucleos` (`id_nucleo`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `nucleo_pnf` ADD FOREIGN KEY (`id_pnf`) REFERENCES `lista_pnfs` (`id_pnf`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `configuracion` ADD FOREIGN KEY (`rol_defecto`) REFERENCES `lista_roles` (`id_rol`) ON DELETE RESTRICT ON UPDATE CASCADE;

INSERT INTO `lista_nucleos` (`id_nucleo`, `nombre_nucleo`) VALUES
(1, 'Sede Central (Barquisimeto)'),
(2, 'Núcleo Carora (Torres)'),
(3, 'Núcleo El Tocuyo (Morán)'),
(4, 'Núcleo Sarare (Simón Planas)');

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

INSERT INTO `nucleo_pnf` (`id_nucleo`, `id_pnf`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12),
(2, 1), (2, 2), (2, 4), (2, 8),
(3, 1), (3, 4), (3, 8), (3, 10),
(4, 1), (4, 2), (4, 4);

INSERT INTO `lista_roles` (`id_rol`, `nombre_rol`, `descripcion_rol`) VALUES
(0, 'Visitante/Invitado', 'Solo lectura de perfil propio'),
(1, 'Usuario', 'Rol de paciente regular'),
(2, 'Enfermero', 'Registro de usuarios'),
(3, 'Médico', 'Gestión de consultas y diagnósticos'),
(4, 'Director', 'Control total del sistema');

INSERT INTO `lista_permisos` (`id_permiso`, `nombre_permiso`, `descripcion_permiso`) VALUES
(1, 'gestionar_usuarios', 'Permite registrar, actualizar y eliminar usuarios'),
(2, 'ver_consultas', 'Permite ver y buscar el historial de consultas médicas'),
(3, 'realizar_consulta', 'Permite registrar una nueva consulta médica'),
(4, 'modificar_consulta', 'Permite actualizar y modificar consultas médicas'),
(5, 'generar_reportes', 'Permite generar reportes de morbilidad médica'),
(6, 'gestionar_roles_permisos', 'Permite administrar roles, permisos y configuración del sistema'),
(7, 'gestionar_condiciones', 'Permite añadir, modificar y borrar condiciones');

INSERT INTO `roles_permisos` (`id_rol`, `id_permiso`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 7),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 7),
(4, 1),
(4, 5),
(4, 6),
(4, 7);

INSERT INTO `configuracion` (`rol_defecto`) VALUES (1);

INSERT INTO `lista_tipos` (`id_tipo`, `nombre_tipo`, `descripcion_tipo`) VALUES
(1, 'Estudiante', ''),
(2, 'Docente', ''),
(3, 'Administrativo', ''),
(4, 'Obrero', ''),
(6, 'Personal Médico', '');

