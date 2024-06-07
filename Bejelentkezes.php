<?php
    require_once "classes/SqliteConnection.php";
    require_once "common/fgv.php";
    session_start();

    $pdo = (new SQLiteConnection())->connect();
    $sikeresBejelentkezes = true;

    if (isset($_POST["login_b"])) {
        $felhasznalonev = $_POST["username"];
        $jelszo = $_POST["password"];

        $signIn = "SELECT felhnev, jelszo FROM felhasznalo WHERE felhnev = ?";
        $sth = $pdo->prepare($signIn);
        $sth->bindValue(1, $felhasznalonev, PDO::PARAM_STR);
        $sth->execute();

        $row = $sth->fetch(PDO::FETCH_ASSOC);

        if ($row != false){

            if (password_verify($jelszo, $row['jelszo'])){
                $_SESSION["user"] = $row['felhnev'];
                header("Location: Profil.php");
            }
        }

        $sikeresBejelentkezes = false;
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
            echo "<a class='active' href='Bejelentkezes.php'>Bejelentkezés</a>";
        } else {
            echo "<a href='Profil.php'>Profil</a>
                  <a href='Kosar.php'>Kosár</a>";
        }
    ?>
        <img id="pen2" src="img/pen.png" alt="">

    </nav>

    <main class="bg">

        <?php
        if (isset($_GET["siker"])) {
            echo "<div class='success'><p>Sikeres regisztráció!</p></div>";
        }

        if (!$sikeresBejelentkezes) {
            echo "<div class='errors'><p>A belépési adatok nem megfelelők!</p></div>";
        }
        ?>

        <div class="form_container">
            <form action="Bejelentkezes.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="reg_grid" style="padding-bottom: 30px">
                    <label class="label" for="uname">Felhasználónév: </label>
                    <input class="inputText" required type="text" name="username" id="uname">
                    <label class="label" for="pswd">Jelszó: </label>
                    <input class="inputText" required type="password" name="password" id="pswd">
                </div>

                <input class="submit kozepre_igazit" type="submit" name="login_b" value="Bejelentkezés" style="margin-top: 20px">
            </form>
        </div>
    </main>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

</body>
</html>