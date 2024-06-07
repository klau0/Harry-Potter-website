<?php
function showItems($pdo, $from, $to, $alt){
    $select = "SELECT * FROM termek WHERE id BETWEEN ? AND ?";
    $sth = $pdo->prepare($select);
    $sth->bindValue(1, $from, PDO::PARAM_INT);
    $sth->bindValue(2, $to, PDO::PARAM_INT);
    $sth->execute();

    echo "<main class='termek-grid-container'>";

    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

        echo "<form class='kartya' method='post' autocomplete='off'>
                <img class='kozepre_igazit' src='".$row['kep']."' alt=$alt>
                <div>
                    <p class='bold kozepre_igazit kozepre nev'>".$row['nev']."</p>
                    <div>
                        <p class='price kozepre'>Ár: ".$row['ar']."</p>
                    </div>
                    <div class='flex-inside-cards'>
                        <input type='text' class='db' name='db' placeholder='0' min='0' size='1' oninput='dinamicNumberInput(this)'>
                        <input class='kosarba' type='submit' name='kosarba' value='Kosárba' style='margin: 0 0 0 7px'>
                    </div>
                    <input type='number' name='termekID' value='".$row['id']."' style='display: none'>
                </div>
             </form>";

    }

    echo "</main>";
}

// return: db-ot tartalmazó asszociációs tömb, vagy false ha nincs még ilyen termék az adatbázisban
function checkForSameItem($pdo, $termekID, $meret){
    $checkForSameItem = "SELECT db FROM kosar WHERE felhasznalo = ? AND termekID = ? AND meret = ?";
    $stmt = $pdo->prepare($checkForSameItem);
    $stmt->bindValue(1, $_SESSION["user"], PDO::PARAM_STR);
    $stmt->bindValue(2, $termekID, PDO::PARAM_INT);
    $stmt->bindValue(3, $meret, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteFromCart($pdo, $termekID, $meret){
    $deleteFromCart = "DELETE FROM kosar WHERE felhasznalo = ? AND termekID = ? AND meret = ?";
    $sth = $pdo->prepare($deleteFromCart);
    $sth->bindValue(1, $_SESSION["user"], PDO::PARAM_STR);
    $sth->bindValue(2, $termekID, PDO::PARAM_INT);
    $sth->bindValue(3, $meret, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount() != 1){
        echo "Valami gebasz van";
    }
}

function updateCart($pdo, $uj_db, $termekID, $meret){
    $updateItem = "UPDATE kosar SET db = ? WHERE felhasznalo = ? AND termekID = ? AND meret = ?";
    $sth = $pdo->prepare($updateItem);
    $sth->bindValue(1, $uj_db, PDO::PARAM_INT);
    $sth->bindValue(2, $_SESSION["user"], PDO::PARAM_STR);
    $sth->bindValue(3, $termekID, PDO::PARAM_INT);
    $sth->bindValue(4, $meret, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount() != 1){
        echo "Valami gebasz van";
    }
}

function addToCart($pdo, $termekID, $db, $meret){
    $insertItemInCart = "INSERT INTO kosar(felhasznalo, termekID, db, meret) VALUES (?,?,?,?)";
    $sth = $pdo->prepare($insertItemInCart);
    $sth->bindValue(1, $_SESSION["user"], PDO::PARAM_STR);
    $sth->bindValue(2, $termekID, PDO::PARAM_INT);
    $sth->bindValue(3, $db, PDO::PARAM_INT);
    $sth->bindValue(4, $meret, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount() != 1){
        echo "Valami gebasz van";
    }
}

function profilkepFeltoltese(array &$hibak, string $felhasznalonev) {
    if (isset($_FILES["profile-picture"]) && is_uploaded_file($_FILES["profile-picture"]["tmp_name"])) {

        //fájlfeltöltés során adódó esetleges hibák
        if ($_FILES["profile-picture"]["error"] !== 0) {
            $hibak[] = "Hiba történt a fájlfeltöltés során!";
        }

        $engedelyezettKiterjesztesek = ["png", "jpg" , "jpeg"];

        $kiterjesztes = strtolower(pathinfo($_FILES["profile-picture"]["name"], PATHINFO_EXTENSION));

        //nem megfelelő kiterjesztés
        if (!in_array($kiterjesztes, $engedelyezettKiterjesztesek)) {
            $hibak[] = "A profilkép kiterjesztése hibás! Engedélyezett formátumok: " .
                implode(", ", $engedelyezettKiterjesztesek) . "!";
        }

        //túl nagy (5 MB-nál nagyobb) fájl
        if ($_FILES["profile-picture"]["size"] > 5242880) {
            $hibak[] = "A fájl mérete túl nagy!";
        }


        if (count($hibak) === 0) {
            $utvonal = "img/profilkepek/"."$felhasznalonev.$kiterjesztes";
            $flag = move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $utvonal);

            //sikertelen átmozgatás
            if (!$flag) {
                $hibak[] = "A profilkép elmentése nem sikerült!";
            }
        }
    }
}
