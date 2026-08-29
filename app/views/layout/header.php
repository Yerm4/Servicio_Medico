<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/media/img/uptaeb.png" type="image/x-icon">
    <link rel="preload" href="assets/css/style.css" as="style">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/script/code.js" defer></script>
    <script src="assets/script/regex.js" defer></script>
    <title><?= e(!empty($titulo) ? $titulo : "UPTAEB") ?></title>
</head>
<body>
    <header>
        <div class="top-menu">
            <div class="top-menu__perfil">
                <img class="top-menu__perfil-img" src="assets/media/img/uptaeb.png" alt="">
                <p class="top-menu__perfil-name">Bienvenido!</p>
            </div>
            <div class="top-menu__links">
                <?php if (!isset($_SESSION["cedula"])): ?>
                <a href="login" class="top-menu__link">Login</a>
                <?php endif ?>
                <?php if (isset($_SESSION["cedula"])) : ?>
                <a href="perfil" class="top-menu__link">Perfil</a>
                <?php endif ?>
            </div>
            <div class="top-menu__login">
                <?php if (isset($_SESSION["cedula"])): ?>
                <svg id="avatar" width="30px" height="30px" class="avatar" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <path d="m 8 1 c -1.65625 0 -3 1.34375 -3 3 s 1.34375 3 3 3 s 3 -1.34375 3 -3 s -1.34375 -3 -3 -3 z m -1.5 7 c -2.492188 0 -4.5 2.007812 -4.5 4.5 v 0.5 c 0 1.109375 0.890625 2 2 2 h 8 c 1.109375 0 2 -0.890625 2 -2 v -0.5 c 0 -2.492188 -2.007812 -4.5 -4.5 -4.5 z m 0 0" fill="#2e3436"/>
                </svg>
                <div id="avatarMenu" class="avatar__menu">
                    <a class="avatar__link" href="logout">Cerrar sesion</a>
                </div>
                <?php else: ?>
                    <a href="#" class="top-menu__login-lang">ESP</a>
                <?php endif ?>
            </div>
        </div>
    </header>