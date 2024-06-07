<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: Bejelentkezes.php");
}

session_unset();
session_destroy();

header("Location: Bejelentkezes.php");

