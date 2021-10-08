<body>
    <?php include("header.php");
    if ($_SESSION["login"] != "R&S-CURT") {
        header("Location: index.php");
    }
    include("footer.php"); ?>