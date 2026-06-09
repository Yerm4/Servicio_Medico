<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="assets/css/style.css" as="style">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/script/code.js" defer></script>
    
    <title>Consultorio</title>
</head>
<body>
    <header>
        <div class="top-menu">
            <div class="top-menu__perfil">
                <img class="top-menu__perfil-img" src="assets/media//img/mini/Mayano_Top_Gun.webp" alt="">
                <p class="top-menu__perfil-name">Hola!</p>
            </div>
            <div class="top-menu__links">
                <?php if (!isset($_SESSION["cedula"])): ?>
                <a href="inicio" class="top-menu__link">Login</a>
                <a href="form" class="top-menu__link">Registro</a>
                <?php endif ?>
                <?php if (isset($_SESSION["cedula"])) : ?>
                    <a href="perfil" class="top-menu__link">Perfil</a>
                <?php endif ?>
            </div>
            <div class="top-menu__login">
                <a href="#" class="top-menu__login-lang">ESP</a>
            </div>
        </div>
    </header>