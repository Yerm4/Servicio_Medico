<?php 
if (isset($_SESSION["cedula"])) {
    header("Location: perfil");
    exit();
}
?>

<main class="registro">
        <section class="section-1">
            <?php include_once __DIR__."/modalRegistrarUsuario.php" ?>
        </section>
    </main>
    <footer>

    </footer>
</body>
</html>