<body>
    <?php include("header.php");
    if (!isset($_SESSION["isConnected"]) or $_SESSION["login"] != "R&S-CURT") {
        header("Location: index.php");
    }
    include("footer.php"); ?>