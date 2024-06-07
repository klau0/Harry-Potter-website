<?php
    require_once "common/fgv.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Harry Potter webshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" href="img/deathly-hallows-sign.jpg">
</head>
<body>

    <header>
        <img src="img/logo.png" alt="Harry Potter">
    </header>

    <nav class="flex-container">

        <img id="pen1" src="img/pen.png" alt="">
        <a href="index.php">Főoldal</a>
        <a class="active" href="Kategoriak.php">Kategóriák</a>
        <a href="Regisztracio.php">Regisztráció</a>
    <?php
        if (!isset($_SESSION["user"])){
            echo "<a href='Bejelentkezes.php'>Bejelentkezés</a>";
        } else {
            echo "<a href='Profil.php'>Profil</a>
                  <a href='Kosar.php'>Kosár</a>";
        }
    ?>
        <img id="pen2" src="img/pen.png" alt="">

    </nav>

    <main class="grid-container">
        <a href="Oltozek.php" class="category kozepre zoom" id="clothes">
            <div class="bold">Öltözék</div>
        </a>

        <a href="Konyv.php" class="category kozepre zoom" id="books">
            <div class="bold">Könyv</div>
        </a>

        <a href="Edesseg.php" class="category kozepre zoom" id="sweets">
            <div class="bold">Édesség</div>
        </a>

        <a href="Tanszer.php" class="category kozepre zoom" id="school_stuff">
            <div class="bold">Tanszer</div>
        </a>

        <a href="Sepru.php" class="category kozepre zoom" id="brooms">
            <div class="bold">Seprű</div>
        </a>
    </main>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

</body>
</html>