<?php

/* $rutasApi = [
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
]; */
$rutasApi = [
    "GET" => [
        "api/consulta"                     => "ConsultaController@getConsultas",
        "api/consulta/{id}"                => "ConsultaController@getConsultas",
        "api/consultas"                    => "ConsultaController@getConsultas",
        "api/consultas/{id}"               => "ConsultaController@getConsultas",
        "api/consultas/detalle"            => "ConsultaController@obtenerConsulta",
        "api/consultas/detalle/{id}"       => "ConsultaController@obtenerConsulta",
        "api/consultas/reporte-morbilidad" => "ConsultaController@generarReporteMorbilidad",
        "api/consultas/patologias"         => "ConsultaController@buscarPatologia",
        "api/consultas/pacientes"          => "ConsultaController@buscarPaciente",
        "api/consultas/paciente/{cedula}"  => "ConsultaController@obtenerConsultasPaciente",
        "api/users/buscar/{id}"            => "UserController@buscarUsuario",
        "api/users/cedula"                 => "UserController@obtenerUsuario",
        "api/users/{id}"                   => "UserController@obtenerUsuario",
        "api/condiciones/buscar"           => "ConsultaController@buscarCondicion",
        "api/condiciones/paciente/{cedula}"=> "ConsultaController@obtenerCondicionesPaciente",
        "api/pnfs"                         => "UserController@buscarPnfs",
        "api/nucleos/pnfs/{id}"            => "NucleoPnfController@obtenerPnfsPorNucleo"
    ],
    "POST" => [
        "api/auth/login"                   => "UserController@login",
        "api/users"                        => "UserController@registrarUsuario",
        "api/consulta"                     => "ConsultaController@registroConsulta",
        "api/consultas"                    => "ConsultaController@registroConsulta",
        "api/consultas/reporte-morbilidad" => "ConsultaController@generarReporteMorbilidad",
        "api/roles"                        => "UserController@registrarRol",
        "api/condiciones"                  => "CondicionController@registrarCondicion",
        "api/nucleos"                      => "NucleoPnfController@registrarNucleo",
        "api/pnfs"                         => "NucleoPnfController@registrarPnf",
        "api/ofertas"                      => "NucleoPnfController@registrarOferta"
    ],
    "PUT" => [
        "api/users"                        => "UserController@actualizarUsuario",
        "api/consulta"                     => "ConsultaController@actualizarConsulta",
        "api/consultas"                    => "ConsultaController@actualizarConsulta",
        "api/roles"                        => "UserController@actualizarRol",
        "api/roles/permisos"               => "UserController@guardarRolesPermisos",
        "api/configuracion"                => "UserController@guardarConfiguracion",
        "api/condiciones"                  => "CondicionController@actualizarCondicion",
        "api/nucleos"                      => "NucleoPnfController@actualizarNucleo",
        "api/pnfs"                         => "NucleoPnfController@actualizarPnf"
    ],
    "PATCH" => [
    ],
    "DELETE" => [
        "api/users/{id}"                   => "UserController@eliminarUsuario",
        "api/roles"                        => "UserController@eliminarRol",
        "api/condiciones"                  => "CondicionController@eliminarCondicion",
        "api/nucleos/{id}"                 => "NucleoPnfController@eliminarNucleo",
        "api/pnfs/{id}"                    => "NucleoPnfController@eliminarPnf",
        "api/ofertas"                      => "NucleoPnfController@eliminarOferta"
    ]
];

return $rutasApi;