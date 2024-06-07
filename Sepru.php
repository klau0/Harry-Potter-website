<?php
    require_once "classes/SqliteConnection.php";
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
    <?php
        $pdo = (new SQLiteConnection())->connect();

        if (isset($_POST["kosarba"])){
            if (!isset($_SESSION["user"])) {
                header("Location: Bejelentkezes.php");
            }

            $termekID = $_POST["termekID"];
            $db = $_POST["db"];

            if (!empty($db)){
                $item = checkForSameItem($pdo, $termekID, "");

                if ($item == false){
                    addToCart($pdo, $termekID, $db, "");
                } else {
                    $uj_db = $db + $item['db'];
                    updateCart($pdo, $uj_db, $termekID, "");
                }
            }

        }

        showItems($pdo, 19, 23, 'seprű');
    ?>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

<script src="common/shared_functions.js"></script>
</body>
</html>