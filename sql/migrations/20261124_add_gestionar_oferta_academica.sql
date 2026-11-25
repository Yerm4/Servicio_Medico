START TRANSACTION;

INSERT INTO `lista_permisos` (`nombre_permiso`, `descripcion_permiso`)
VALUES ('gestionar_oferta_academica', 'Permite administrar los núcleos, PNFs y la oferta académica global')
ON DUPLICATE KEY UPDATE
  `descripcion_permiso` = 'Permite administrar los núcleos, PNFs y la oferta académica global';

INSERT IGNORE INTO `roles_permisos` (`id_rol`, `id_permiso`)
SELECT 4, `id_permiso`
FROM `lista_permisos`
WHERE `nombre_permiso` = 'gestionar_oferta_academica';

INSERT IGNORE INTO `roles_permisos` (`id_rol`, `id_permiso`)
SELECT 5, `id_permiso`
FROM `lista_permisos`
WHERE `nombre_permiso` = 'gestionar_oferta_academica';

COMMIT;
