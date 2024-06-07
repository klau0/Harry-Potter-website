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
    <script>
        document.onkeydown= function(event){
            if(event.keyCode===48) alert("oldal szelessege: "+document.getElementsByTagName('body')[0].offsetWidth+', magassaga: '
                +document.getElementsByTagName('body')[0].offsetHeight);}
    </script>
</head>
<body>
    <header>
        <img src="img/logo.png" alt="Harry Potter">
    </header>

    <nav class="flex-container">

        <img id="pen1" src="img/pen.png" alt="">
        <a class="active" href="index.php">Főoldal</a>
        <a href="Kategoriak.php">Kategóriák</a>
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

    <main class="paper_bg">

        <blockquote class="dolt">
            „Azt bizonyára valamennyien tudják, hogy a Roxfortot több mint ezer éve
            – a pontos dátumot nem ismerjük –, alapította a kor négy legjelentősebb varázslója:
            <span id="godrik" class="font_outline">Griffendél Godrik</span>,<span id="helga" class="font_outline"> Hugrabug Helga</span>,
            <span id="hedvig" class="font_outline"> Hollóháti Hedvig</span> és <span id="malazar" class="font_outline">Mardekár Malazár</span>.
            Iskolánk négy háza az ő nevüket őrzi. Együtt építették fel ezt a kastélyt,
            távol a muglik lakta vidéktől. Akkoriban ugyanis az egyszerű emberek rettegtek a mágiától,
            s ezért tűzzel-vassal üldözték a boszorkányokat és varázslókat.”<br>
            /Binns professzor/
        </blockquote>

        <section>
            <span id="welcome">Köszöntünk minden varázsló- és boszorkánytanoncot!</span><br>
            Leszögeznénk, hogy ehhez az oldalhoz muglik nem tudnak hozzáférni (erről Roxfort igazgatója személyesen
            kezeskedett), így emiatt a továbbiakban nem kell aggódniuk.<br>
            Ez az oldal azért jött létre, hogy megkönnyítse a diákok számára az évkezdést. Korábban nem volt
            lehetőség online megrendelni az iskolai tanszereket, ezért hoztuk létre ezt a webshopot.
            Az oldalon számos Abszol úti bolt termékeiből lehet válogatni, de természetesen személyesen is
            benézhetnek az üzletekbe. (A Zsebpiszok közt lehetőleg kerüljék)
        </section>

        <aside>
            <span class="kozepre" id="aside">Megjegyzés</span><br>
            Ollivander a Szent Mungó 4.emeletén lábadozik, mert eltalálta egy helytelenül alkalmazott bűbáj.
            A gyógyítója szerint már jobban van, mostanra vendégeket is fogadhat.
            Azonban amíg távol van, Stan Shunpike (a Kóbor Grimbusz egykori kalauza) veszi át a bolt vezetését.
            Mivel Stan nem igazán ért a pálcák készítéséhez, így Ollivander visszatéréséig új darabok sajnos nem kaphatók.
        </aside>

        <div id='index_table_div'>
        <table id="index_table">
            <caption class="font_outline">Nyitvatartás:</caption>
            <thead>
                <tr>
                    <th id="shop">Üzlet</th>
                    <th id="H-P">Hétfő-Péntek</th>
                    <th id="Sz">Szombat</th>
                    <th id="V">Vasárnap</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td headers="shop">Czikornyai & Patza</td>
                    <td headers="H-P">7:00-17:00</td>
                    <td headers="Sz">8:00-14:00</td>
                    <td headers="V">Zárva</td>
                </tr>
                <tr>
                    <td headers="shop">Apotikária</td>
                    <td headers="H-P">8:00-19:00</td>
                    <td headers="Sz">8:00-18:00</td>
                    <td headers="V">8:00-18:00</td>
                </tr>
                <tr>
                    <td headers="shop">Madam Malkin Talárszabászata</td>
                    <td headers="H-P">10:00-20:00</td>
                    <td headers="Sz V" colspan="2">Zárva</td>
                </tr>
                <tr>
                    <td headers="shop">Weasley Varázsvicc Vállalat</td>
                    <td headers="H-P">10:00-22:00</td>
                    <td headers="Sz">11:00-20:00</td>
                    <td headers="V">12:00-20:00</td>
                </tr>
                <tr>
                    <td headers="shop">Huntzut & Zsupsz</td>
                    <td headers="H-P Sz V" colspan="3">Átmenetileg zárva :(</td>
                </tr>
                <tr>
                    <td headers="shop">Kviddics a javából</td>
                    <td headers="H-P">9:00-16:00</td>
                    <td headers="Sz V" colspan="2">Zárva</td>
                </tr>
                <tr>
                    <td headers="shop">Mágikus Menazséria</td>
                    <td headers="H-P">7:00-15:00</td>
                    <td headers="Sz">10:00-17:00</td>
                    <td headers="V">Zárva</td>
                </tr>
                <tr>
                    <td headers="shop">Uklopsz Bagolyszalon</td>
                    <td headers="H-P">6:00-16:00</td>
                    <td headers="Sz">8:00-15:30</td>
                    <td headers="V">9:00-15:00</td>
                </tr>
                <tr>
                    <td headers="shop">Ollivander</td>
                    <td headers="H-P">8:00-20:00</td>
                    <td headers="Sz">8:00-18:30</td>
                    <td headers="V">10:00-18:00</td>
                </tr>
                <tr>
                    <td headers="shop">Üstbolt</td>
                    <td headers="H-P">8:00-19:00</td>
                    <td headers="Sz">8:00-17:00</td>
                    <td headers="V">Zárva</td>
                </tr>
                <tr>
                    <td headers="shop">Írószerbolt</td>
                    <td headers="H-P">8:00-20:30</td>
                    <td headers="Sz">10:00-19:00</td>
                    <td headers="V">10:00-18:00</td>
                </tr>
            </tbody>
        </table>
        </div>

        <section>
            Az évkezdéssel kapcsolatos kérdéseiket az alábbi tanároknak tudják megírni:
            <ul>
                <li>
                    Filius Flitwick prof.
                </li>
                <li>
                    Minerva McGalagony prof.
                </li>
                <li>
                    Pomona Bimba prof.
                </li>
            </ul>
        </section>

    </main>

    <footer class="kozepre">
        <img class="float" src="img/hogwarts_crest.png" alt="Hogwarts">
        <p>„Draco dormiens nunquam titillandus” (Ne csiklandozd az alvó sárkányt)</p>
    </footer>

</body>
</html>