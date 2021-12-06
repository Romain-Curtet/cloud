<?php
session_start();
setcookie(
    "login",
    $_SESSION["login"],
    [
        'expires' => time() + 365 * 24 * 3600,
        'secure' => true,
        'httponly' => true,
    ]
);
setcookie(
    "password",
    $_SESSION["password"],
    [
        'expires' => time() + 365 * 24 * 3600,
        'secure' => true,
        'httponly' => true,
    ]
);
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "R&S-CURT") {
    header("Location: index.php");
}
include("header.php");
include("footer.php");