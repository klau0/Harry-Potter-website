<?php
    require_once "classes/SqliteConnection.php";
    require_once "common/fgv.php";
    session_start();

    if (!isset($_SESSION["user"])) {
        header("Location: Bejelentkezes.php");
    }
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
        <a href="Kategoriak.php">Kategóriák</a>
        <a href="Regisztracio.php">Regisztráció</a>
    <?php
        if (!isset($_SESSION["user"])){
            echo "<a href='Bejelentkezes.php'>Bejelentkezés</a>";
        } else {
            echo "<a href='Profil.php'>Profil</a>
                  <a class='active' href='Kosar.php'>Kosár</a>";
        }
    ?>
        <img id="pen2" src="img/pen.png" alt="">

    </nav>
    <?php
        $pdo = (new SQLiteConnection())->connect();

        if(isset($_POST['delete'])){
            $termekID = $_POST["termekID"];
            $meret = $_POST["meret"];

            deleteFromCart($pdo, $termekID, $meret);
        }

        if (isset($_POST['modify'])){
            $termekID = $_POST["termekID"];
            $db = $_POST["db"];
            $meret = $_POST["meret"];

            if (!empty($db)){
                updateCart($pdo, $db, $termekID, $meret);
            } else {
                deleteFromCart($pdo, $termekID, $meret);
            }
        }

        $selectCart = "SELECT termekID, db, meret FROM kosar WHERE felhasznalo = ?";
        $sth = $pdo->prepare($selectCart);
        $sth->bindValue(1, $_SESSION['user'], PDO::PARAM_STR);
        $sth->execute();

        echo "<main class='termek-grid-container' id='main'>";

        $kosar_ures = true;
        while ($cart_row = $sth->fetch(PDO::FETCH_ASSOC)) {

            $kosar_ures = false;
            $selectTermek = "SELECT * FROM termek WHERE id = ?";
            $stmt = $pdo->prepare($selectTermek);
            $stmt->bindValue(1, $cart_row['termekID'], PDO::PARAM_INT);
            $stmt->execute();

            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "<form class='kartya' method='post' autocomplete='off'>
                    <img class='kozepre_igazit' src='".$item['kep']."' alt='termék'>
                    <div>
                        <p class='bold kozepre_igazit kozepre nev'>".$item['nev']."</p>";
                        if ($cart_row['meret'] != null){
                            echo "<div class='kozepre_igazit font_outline meret'>".$cart_row['meret']."-es</div>";
                        }
                  echo "<div>
                            <p class='price kozepre'>Ár: ".$item['ar']."</p>
                        </div>
                        <div class='flex-inside-cards'>
                            <input type='text' class='db' name='db' value='".$cart_row['db']."' placeholder='0' min='0' size='".strlen((string)$cart_row['db'])."' oninput='dinamicNumberInput(this)'>
                            <input class='kosarba' type='submit' name='modify' value='Módosít' style='margin: 0 7px 0 7px'>
                        </div>
                        <input class='del_btn' type='submit' name='delete' value='Töröl'>
                        <input type='number' name='termekID' value='".$item['id']."' style='display: none'>
                        <input type='text' name='meret' value='".$cart_row['meret']."' style='display: none'>
                    </div>
                 </form>";

        }
        echo "</main>";

        if($kosar_ures){
            echo "<div id='empty_cart'>
                    <p id='empty_cart_text' class='bold kozepre_igazit'>A kosár jelenleg üres</p>
                  </div>";
        }
    ?>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

<script src="common/shared_functions.js"></script>
</body>
</html>