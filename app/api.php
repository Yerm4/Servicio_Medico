<?php
$rutasApi = [
    "GET" => [
        "api/consultas"                    => "ConsultaController@consultar",
        "api/consultas/detalle"            => "ConsultaController@obtenerConsulta",
        "api/consultas/detalle/{id}"       => "ConsultaController@obtenerConsulta",
        "api/consultas/reporte-morbilidad" => "ConsultaController@generarReporteMorbilidad",
        "api/consultas/patologias"         => "ConsultaController@buscarPatologia",
        "api/consultas/pacientes"          => "ConsultaController@buscarPaciente",
        "api/consultas/paciente/{cedula}"  => "ConsultaController@obtenerConsultasPaciente",
        "api/consultas/{id}"               => "ConsultaController@consultar",
        "api/users/buscar/{id}"            => "UserController@consultar",
        "api/users/cedula"                 => "UserController@obtenerUsuario",
        "api/users/{id}"                   => "UserController@obtenerUsuario",
        "api/auth/{id}"                    => "UserController@verificarCedula",
        "api/condiciones/buscar"           => "ConsultaController@buscarCondicion",
        "api/condiciones/paciente/{cedula}"=> "ConsultaController@obtenerCondicionesPaciente",
        "api/pnfs"                         => "UserController@buscarPnfs",
        "api/nucleos/pnfs/{id}"            => "NucleoPnfController@obtenerPnfsPorNucleo"
    ],
    "POST" => [
        "api/auth/login"                   => "UserController@login",
        "api/users"                        => "UserController@registrar",
        "api/consultas"                    => "ConsultaController@registrar",
        "api/consultas/reporte-morbilidad" => "ConsultaController@generarReporteMorbilidad",
        "api/roles"                        => "UserController@registrarRol",
        "api/condiciones"                  => "CondicionController@registrarCondicion",
        "api/nucleos"                      => "NucleoPnfController@registrarNucleo",
        "api/pnfs"                         => "NucleoPnfController@registrarPnf",
        "api/ofertas"                      => "NucleoPnfController@registrarOferta"
    ],
    "PUT" => [
        "api/users"                        => "UserController@actualizar",
        "api/consultas"                    => "ConsultaController@actualizar",
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
        "api/users/{id}"                   => "UserController@eliminar",
        "api/roles"                        => "UserController@eliminarRol",
        "api/condiciones"                  => "CondicionController@eliminarCondicion",
        "api/nucleos/{id}"                 => "NucleoPnfController@eliminarNucleo",
        "api/pnfs/{id}"                    => "NucleoPnfController@eliminarPnf",
        "api/ofertas"                      => "NucleoPnfController@eliminarOferta"
    ]
];

return $rutasApi;