<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "R&S-CURT") {
    header("Location: index.php");
}
include("header.php");
include("footer.php");