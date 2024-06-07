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
    <script>

    </script>
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

        function ruhatKosarba($meret){
            global $termekID;
            global $pdo;
            $db = $_POST[$meret."db"];

            if (!empty($db)){
                $item = checkForSameItem($pdo, $termekID, $meret);

                if ($item == false){
                    addToCart($pdo, $termekID, $db, $meret);
                } else {
                    $uj_db = $db + $item['db'];
                    updateCart($pdo, $uj_db, $termekID, $meret);
                }
            }
        }

        ruhatKosarba("S");
        ruhatKosarba("M");
        ruhatKosarba("L");
        ruhatKosarba("XL");
    }

    $select = "SELECT * FROM termek WHERE id BETWEEN 29 AND 33";
    $results = $pdo->query($select);

    echo "<main class='termek-grid-container'>";

    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {

        echo "<form class='kartya' method='post' autocomplete='off'>
                <img class='kozepre_igazit' src='".$row['kep']."' alt='öltözék'>
                <div>
                    <p class='bold kozepre_igazit kozepre nev'>".$row['nev']."</p>
                    <div>
                        <p class='price kozepre'>Ár: ".$row['ar']."</p>
                        <p class='kozepre' style='margin: 8px'>Rendelhető méretek:</p>
                        <div class='sizes kozepre_igazit'>
                            <input type='button' class='block w-negyed' name='S' value='S' onclick='meretKlikk(this)'>
                            <input type='button' class='block w-negyed' name='M' value='M' onclick='meretKlikk(this)'>
                            <input type='button' class='block w-negyed' name='L' value='L' onclick='meretKlikk(this)'>
                            <input type='button' class='block w-negyed' name='XL' value='XL' onclick='meretKlikk(this)'>
                        </div>
                    </div>
                    <div class='meretek'>
                        <label>S: <input type='text' class='db' name='Sdb' placeholder='0' min='0' size='1' oninput='dinamicNumberInput(this)'></label>
                        <label>M: <input type='text' class='db' name='Mdb' placeholder='0' min='0' size='1' oninput='dinamicNumberInput(this)'></label>
                        <label>L: <input type='text' class='db' name='Ldb' placeholder='0' min='0' size='1' oninput='dinamicNumberInput(this)'></label>
                        <label>XL: <input type='text' class='db' name='XLdb' placeholder='0' min='0' size='1' oninput='dinamicNumberInput(this)'></label>
                    </div>
                </div>
                <div>
                    <input class='kosarba' type='submit' name='kosarba' value='Kosárba'>
                    <input type='number' name='termekID' value='".$row['id']."' style='display: none'>
                </div>
             </div>
            </form>";

    }

    echo "</main>";
    ?>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

<script src="common/shared_functions.js"></script>
<script>
    // csak megjelenítésre
    function meretKlikk(akt_meret){
        let dbname = akt_meret.name + 'db';
        let meretek = akt_meret.parentElement.parentElement.nextElementSibling.children;

        if (akt_meret.style.backgroundColor === "rgb(106, 106, 106)") {
            akt_meret.style.backgroundColor = "rgb(256, 256, 256)";
            akt_meret.style.color = "rgb(0, 0, 0)";
        } else {
            akt_meret.style.backgroundColor = "rgb(106, 106, 106)";
            akt_meret.style.color = "rgb(256, 256, 256)";
        }

        for (let i = 0; i < meretek.length; i++){
            if (meretek[i].firstElementChild.name === dbname){
                if (akt_meret.style.backgroundColor === "rgb(106, 106, 106)"){
                    meretek[i].style.display = "block";
                } else {
                    meretek[i].style.display = "none";
                }
            }
        }

    }

    // innentől csak dom elemeket generáltam le js-el a méretekhez tanulásképp
    /*function createSizes($meret){
        let input = document.createElement("input");
        input.setAttribute("type", "button");
        input.setAttribute("class", "block w-negyed");
        input.setAttribute("name", $meret);
        input.setAttribute("value", $meret);
        return input;
    }

    function createSizes2($meret){
        let divek = document.getElementsByClassName("meretek");

        for (let i = 0; i < divek.length; i++) {
            let label = document.createElement("label");
            label.innerText = $meret + ": ";

            let input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("class", "db");
            input.setAttribute("name", $meret + "db");
            input.setAttribute("placeholder", "0");
            input.setAttribute("min", "0");
            input.setAttribute("size", "1");
            input.addEventListener('input', dinamicNumberInput(this));

            label.append(input);
            divek[i].append(label);
        }
    }

    let szulo = document.getElementsByClassName("kartya_1");

    for (let i = 0; i < szulo.length; i++) {
        let p = document.createElement("p");
        p.setAttribute("class", "sizes kozepre");
        p.innerText = "Rendelhető méretek :";
        let i1 = createSizes("S");
        let i2 = createSizes("M");
        let i3 = createSizes("L");
        let i4 = createSizes("XL");
        szulo[i].append(p, i1, i2, i3, i4);

        let div = document.createElement("div");
        div.setAttribute("class", "meretek");
        szulo[i].after(div);
    }

    createSizes2("S");
    createSizes2("M");
    createSizes2("L");
    createSizes2("XL");

    for (let i = 0; i < szulo.length; i++) {
        let gyerekek = szulo[i].children;

        for (let j = 2; j < gyerekek.length; j++){
            gyerekek[j].addEventListener('click', function(){ meretKlikk(this); });
        }
    }*/
</script>
</body>
</html>