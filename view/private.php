<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "R&S-CURT") {
    header("Location: ../index.php");
}
require("header.php");
require("footer.php");