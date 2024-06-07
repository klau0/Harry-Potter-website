<?php
    require_once "classes/SqliteConnection.php";
    include_once "common/fgv.php";
    session_start();

    $pdo = (new SQLiteConnection())->connect();

    if (!isset($_SESSION["user"])) {
        header("Location: Bejelentkezes.php");
    }

    $hibak = [];

    const DEFAULT_PROFILKEP = "img/profilkepek/dobby.jpeg";
    $profilkep = DEFAULT_PROFILKEP;

    //frissíti a meglévő képet
    $utvonal = "img/profilkepek/".$_SESSION["user"];
    $utvonal = str_replace(" ", "", $utvonal);
    $engedelyezettKiterjesztesek = ["png", "jpg", "jpeg"];

    foreach ($engedelyezettKiterjesztesek as $kit) {
        if (file_exists("$utvonal.$kit")) {
            $profilkep = "$utvonal.$kit";
        }
    }

    //módosított profilkép feldolgozása
    if (isset($_POST["upload_b"]) && is_uploaded_file($_FILES["profile-picture"]["tmp_name"])) {
        profilkepFeltoltese($hibak, $_SESSION["user"]);

        if (count($hibak) === 0) {
            // Lekérdezzük az elmentett, új profilkép elérési útonalát az $utvonal változóba.

            $kit = strtolower(pathinfo($_FILES["profile-picture"]["name"], PATHINFO_EXTENSION));
            $utvonal = "img/profilkepek/".$_SESSION["user"].".".$kit;

            //ha más a kiterjesztés töröljük az előzőt
            if ($utvonal !== $profilkep && $profilkep !== DEFAULT_PROFILKEP) {
                unlink($profilkep);
            }

            //frissítés
            header("Location: Profil.php");
        }
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
            echo "<a class='active' href='Profil.php'>Profil</a>
                  <a href='Kosar.php'>Kosár</a>";
        }
    ?>
        <img id="pen2" src="img/pen.png" alt="">

    </nav>

    <main class="profil_bg">
        <?php
            if (count($hibak) > 0) {
                echo "<div class='errors'>";

                foreach ($hibak as $hiba) {
                    echo "<p>" . $hiba . "</p>";
                }

                echo "</div>";
            }
        ?>

        <table id="profile-table">
            <tr>
                <td colspan="2">
                    <img id="profilkep" src="<?php echo $profilkep; ?>" alt="Profilkép" height="300">

                    <form action="Profil.php" method="POST" enctype="multipart/form-data">
                        <label class="custom-file-upload">
                            Új kép
                            <input type="file" name="profile-picture" oninput="file_selected(this)">
                        </label>
                        <input class="profil_btn" type="submit" name="upload_b" value="Feltöltés">
                    </form>
                </td>
            </tr>
            <tr>
                <th colspan="2">Felhasználói adatok</th>
            </tr>
        <?php
            $selectUserData = "SELECT * FROM felhasznalo WHERE felhnev = ?";
            $sth = $pdo->prepare($selectUserData);
            $sth->bindValue(1, $_SESSION['user'], PDO::PARAM_STR);
            $sth->execute();

            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if ($row == false){
                echo "Hoppá, nem található a felhasználó az adatbázisban :(";
            }

      echo "<tr>
                <th>Felhasználónév</th>
                <td>".$row['felhnev']."</td>
            </tr>
            <tr>
                <th>E-mail cím</th>
                <td>".$row['email']."</td>
            </tr>";

            if ($row['patronus'] != ""){
                echo "<tr>
                        <th>Patrónus</th>
                        <td>".$row['patronus']."</td>
                      </tr>";
            }
            if ($row['csapat'] != ""){
                echo "<tr>
                        <th>Kviddics csapat</th>
                        <td>".$row['csapat']."</td>
                      </tr>";
            }
            if ($row['iskola'] != ""){
                echo "<tr>
                        <th>Iskola</th>
                        <td>".$row['iskola']."</td>
                      </tr>";
            }
        ?>
        </table>

        <form class="kozepre_igazit logout-form" action="Kijelentkezes.php" method="POST">
            <input class="profil_btn" type="submit" name="logout_b" value="Kijelentkezés" style="background-color: white; color: black; border-color: whitesmoke">
        </form>

    </main>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

<script>
    function file_selected(btn){
        btn.parentElement.style.backgroundColor = "#ffffff";
        btn.parentElement.style.color = "#000000";
        btn.parentElement.style.borderColor = "#32CD32FF";
        btn.parentElement.style.borderWidth = "3px";
    }
</script>
</body>
</html>

