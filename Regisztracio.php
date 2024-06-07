<?php
    require_once "classes/SqliteConnection.php";
    include_once "common/fgv.php";
    session_start();

    $pdo = (new SQLiteConnection())->connect();
    $hibak = [];

    if (isset($_POST["send_b"])) {
        $felhasznalonev = $_POST["username"];
        $jelszo = $_POST["password"];
        $ellenorzoJelszo = $_POST["password-check"];
        $email = $_POST["email"];
        $patronus = "";
        $csapat = "";
        $iskola = $_POST["school"];
        $jelolonegyzetek = [];

        $jo = FALSE;

        if (isset($_POST["confirmations"])){
            $jelolonegyzetek = $_POST["confirmations"];

            //elfogadja a feltételeket?
            foreach ($jelolonegyzetek as $elem){
                if ($elem == "elfogadom"){
                    $jo = TRUE;
                }
            }
            if (!$jo){
                $hibak[] = "El kell fogadni a felhasználási feltételeket!";
            }
        } else {
            $hibak[] = "El kell fogadni a felhasználási feltételeket!";
        }

        if (isset($_POST["quiddich"])){
            $csapat = $_POST["quiddich"];
        }

        if(isset($_POST['patronus'])){
            $patronus = $_POST['patronus'];
        }

        if (trim($felhasznalonev) === "" || trim($jelszo) === "" || trim($ellenorzoJelszo) === "" || trim($email) === "") {
            $hibak[] = "Minden kötelező mezőt meg kell adni!";
        }

        $unameTaken = "SELECT * FROM felhasznalo WHERE felhnev = ?";
        $sth = $pdo->prepare($unameTaken);
        $sth->bindValue(1, $felhasznalonev, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->fetchAll() != false){
            $hibak[] = "A felhasználónév már foglalt!";
        }

        $emailTaken = "SELECT * FROM felhasznalo WHERE email = ?";
        $sth = $pdo->prepare($emailTaken);
        $sth->bindValue(1, $email, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->fetchAll() != false){
            $hibak[] = "Az e-mail cím már foglalt!";
        }

        if (!preg_match("/[0-9a-z.-]+@([0-9a-z-]+\.)+[a-z]{2,4}/", $email)) {
            $hibak[] = "A megadott e-mail cím formátuma nem megfelelő!";
        }

        if ($jelszo !== $ellenorzoJelszo) {
            $hibak[] = "A két jelszó nem egyezik!";
        }

        profilkepFeltoltese($hibak, $felhasznalonev);

        if (count($hibak) === 0) {
            $jelszo = password_hash($jelszo, PASSWORD_DEFAULT);

            $insertUser = "INSERT INTO felhasznalo(felhnev, jelszo, email, patronus, iskola, csapat) VALUES (?,?,?,?,?,?)";
            $sth = $pdo->prepare($insertUser);
            $sth->bindValue(1, $felhasznalonev, PDO::PARAM_STR);
            $sth->bindValue(2, $jelszo, PDO::PARAM_STR);
            $sth->bindValue(3, $email, PDO::PARAM_STR);
            $sth->bindValue(4, $patronus, PDO::PARAM_STR);
            $sth->bindValue(5, $iskola, PDO::PARAM_STR);
            $sth->bindValue(6, $csapat, PDO::PARAM_STR);
            $sth->execute();

            if ($sth->rowCount() != 1){
                echo "Valami gebasz van";
            }

            header("Location: Bejelentkezes.php?siker=true");
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
        <a class="active" href="Regisztracio.php">Regisztráció</a>
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


    <main class="bg">

    <?php
        if (count($hibak) > 0) {
            echo "<div class='errors'>";

            foreach ($hibak as $hiba) {
                echo "<p>" . $hiba . "</p>";
            }

            echo "</div>";
        }
    ?>

        <div class="form_container">
            <form action="Regisztracio.php" method="POST" autocomplete="off" enctype="multipart/form-data">

                <div class="reg_grid">
                    <label class="required label" for="uname">Felhasználónév</label>
                    <input class="inputText" type="text" name="username" id="uname" required>
                    <label class="required label" for="pswd">Jelszó</label>
                    <input class="inputText" type="password" name="password" id="pswd" required>
                    <label class="required label" for="pswd-check">Jelszó ismét</label>
                    <input class="inputText" type="password" name="password-check" id="pswd-check" required>
                    <label class="required label" for="mail">E-mail cím</label>
                    <input class="inputText" type="email" name="email" id="mail" required placeholder="GilderoyLockhartFan@gmail.com">
                    <label for="animal label">Patrónus</label>
                    <input class="inputText" type="text" name="patronus" id="animal" maxlength="30">

                    <label for="school">Hol tanulsz?</label>
                    <select name="school" id="school">
                        <option value="Roxfort" selected>Roxfort</option>
                        <option value="Durmstrang">Durmstrang</option>
                        <option value="Beauxbatons">Beauxbatons</option>
                        <option value="">Nem tanulok</option>
                    </select>
                </div>

                <div class="kozepre_igazit" style="padding-top: 30px">Kinek szurkolsz az idei kviddics-világkupán?</div>
                <div class="radio_button kozepre_igazit">
                    <label style="color: #FF4900; letter-spacing: 1px" class="font_outline"><input type="radio" name="quiddich" value="Bulgária"> Bulgária</label>
                    <label style="color: #1FB32C; letter-spacing: 1px" class="font_outline"><input type="radio" name="quiddich" value="Írország"> Írország</label>
                </div>


                <div class="kozepre_igazit" style="padding-top: 10px">
                    <label class="custom-file-upload" style="background-color: #595959; border: 2px solid #444444; padding: 5px 10px">
                        Profilkép feltöltése
                        <input type="file" name="profile-picture" oninput="file_selected(this)">
                    </label>
                </div>


                <div class="checkbox kozepre_igazit" style="padding-bottom: 50px">
                    <label><input type="checkbox" name="confirmations[]" value="rosszban"><em class="dolt"> „Esküszöm, hogy rosszban sántikálok”</em></label><br>
                    <label><input type="checkbox" name="confirmations[]" value="elfogadom" checked> Elfogadom a felhasználási feltételeket.</label><br>
                </div>

                <div class="kozepre_igazit">
                    <input class="submit" type="submit" name="send_b" value="Küldés">
                </div>

            </form>
        </div>

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