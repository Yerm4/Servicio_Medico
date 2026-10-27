<?php

$rutasApi = [
    "GET" => [
        "api/consulta"                      => "getConsultas",
        "api/consulta/{id}"                 => "getConsultas",
        "api/consultas"                     => "getConsultas",
        "api/consultas/{id}"                => "getConsultas",
        "api/consultas/detalle"             => "obtenerConsulta",
        "api/consultas/detalle/{id}"        => "obtenerConsulta",
        "api/consultas/reporte-morbilidad"  => "generarReporteMorbilidad",
        "api/consultas/patologias"          => "buscarPatologia",
        "api/consultas/pacientes"           => "buscarPaciente",
        "api/consultas/paciente/{cedula}"   => "obtenerConsultasPaciente",
        "api/users/buscar/{id}"             => "buscarUsuario",
        "api/users/cedula"                  => "obtenerUsuario",
        "api/users/{id}"                    => "obtenerUsuario",
        "api/condiciones/buscar"            => "buscarCondicion",
        "api/condiciones/paciente/{cedula}" => "obtenerCondicionesPaciente",
        "api/pnfs"                          => "buscarPnfs",
        "api/nucleos/pnfs/{id}"             => "obtenerPnfsPorNucleo"
    ],
    "POST" => [
        "api/auth/login"                    => "login",
        "api/users"                         => "registrarUsuario",
        "api/consulta"                      => "registroConsulta",
        "api/consultas"                     => "registroConsulta",
        "api/consultas/reporte-morbilidad"  => "generarReporteMorbilidad",
        "api/roles"                         => "registrarRol",
        "api/condiciones"                   => "registrarCondicion",
        "api/nucleos"                       => "registrarNucleo",
        "api/pnfs"                          => "registrarPnf",
        "api/ofertas"                       => "registrarOferta"
    ],
    "PUT" => [
        "api/users"                         => "actualizarUsuario",
        "api/consulta"                      => "actualizarConsulta",
        "api/consultas"                     => "actualizarConsulta",
        "api/roles"                         => "actualizarRol",
        "api/roles/permisos"                => "guardarRolesPermisos",
        "api/configuracion"                 => "guardarConfiguracion",
        "api/condiciones"                   => "actualizarCondicion",
        "api/nucleos"                       => "actualizarNucleo",
        "api/pnfs"                          => "actualizarPnf"
    ],
    "PATCH" => [
    ],
    "DELETE" => [
        "api/users/{id}"                    => "eliminarUsuario",
        "api/roles"                         => "eliminarRol",
        "api/condiciones"                   => "eliminarCondicion",
        "api/nucleos/{id}"                       => "eliminarNucleo",
        "api/pnfs/{id}"                          => "eliminarPnf",
        "api/ofertas"                       => "eliminarOferta"
    ]
];

/*
<?php

$rutasApi = [
    "GET" => [
        "api/consulta"                      => "ConsultaApiController@getConsultas",
        "api/consulta/{id}"                 => "ConsultaApiController@getConsultas",
        "api/consultas"                     => "ConsultaApiController@getConsultas",
        "api/consultas/{id}"                => "ConsultaApiController@getConsultas",
        "api/consultas/detalle"             => "ConsultaApiController@obtenerConsulta",
        "api/consultas/detalle/{id}"        => "ConsultaApiController@obtenerConsulta",
        "api/consultas/reporte-morbilidad"  => "ConsultaApiController@generarReporteMorbilidad",
        "api/consultas/patologias"          => "ConsultaApiController@buscarPatologia",
        "api/consultas/pacientes"           => "ConsultaApiController@buscarPaciente",
        "api/consultas/paciente/{cedula}"   => "ConsultaApiController@obtenerConsultasPaciente",
        
        "api/users/buscar/{id}"             => "UserApiController@buscarUsuario",
        "api/users/cedula"                  => "UserApiController@obtenerUsuario",
        "api/users/{id}"                    => "UserApiController@obtenerUsuario",
        
        "api/condiciones/buscar"            => "CondicionApiController@buscarCondicion",
        "api/condiciones/paciente/{cedula}" => "CondicionApiController@obtenerCondicionesPaciente",
        
        "api/pnfs"                          => "NucleoPnfApiController@buscarPnfs",
        "api/nucleos/pnfs/{id}"             => "NucleoPnfApiController@obtenerPnfsPorNucleo"
    ],
    "POST" => [
        "api/auth/login"                    => "AuthApiController@login",
        "api/users"                         => "UserApiController@registrarUsuario",
        "api/consulta"                      => "ConsultaApiController@registroConsulta",
        "api/consultas"                     => "ConsultaApiController@registroConsulta",
        "api/consultas/reporte-morbilidad"  => "ConsultaApiController@generarReporteMorbilidad",
        "api/roles"                         => "RolePermissionApiController@registrarRol",
        "api/condiciones"                   => "CondicionApiController@registrarCondicion",
        "api/nucleos"                       => "NucleoPnfApiController@registrarNucleo",
        "api/pnfs"                          => "NucleoPnfApiController@registrarPnf",
        "api/ofertas"                       => "NucleoPnfApiController@registrarOferta"
    ],
    "PUT" => [
        "api/users"                         => "UserApiController@actualizarUsuario",
        "api/consulta"                      => "ConsultaApiController@actualizarConsulta",
        "api/consultas"                     => "ConsultaApiController@actualizarConsulta",
        "api/roles"                         => "RolePermissionApiController@actualizarRol",
        "api/roles/permisos"                => "RolePermissionApiController@guardarRolesPermisos",
        "api/configuracion"                 => "RolePermissionApiController@guardarConfiguracion",
        "api/condiciones"                   => "CondicionApiController@actualizarCondicion",
        "api/nucleos"                       => "NucleoPnfApiController@actualizarNucleo",
        "api/pnfs"                          => "NucleoPnfApiController@actualizarPnf"
    ],
    "PATCH" => [],
    "DELETE" => [
        "api/users/{id}"                    => "UserApiController@eliminarUsuario",
        "api/roles"                         => "RolePermissionApiController@eliminarRol",
        "api/condiciones"                   => "CondicionApiController@eliminarCondicion",
        "api/nucleos/{id}"                  => "NucleoPnfApiController@eliminarNucleo",
        "api/pnfs/{id}"                     => "NucleoPnfApiController@eliminarPnf",
        "api/ofertas"                       => "NucleoPnfApiController@eliminarOferta"
    ]
];

return $rutasApi;
*/

return $rutasApi;