<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "groupe") {
    header("Location: index.php");
}
include("header.php");
include("bdd.php");
$sql = doSQL("SELECT * from musique", array());
$sql1 = doSQL("SELECT DISTINCT chanson from musique ORDER BY chanson ASC", array());
?>

<body>
    <select name="sort" id="song">
        <option> -- </option>
        <?php
        foreach ($sql1 as $row1) {
            echo '<option name="chanson" value="' . $row1["chanson"] . '">' . $row1["chanson"] . '</option>';
        } ?>
    </select>
    <br><br>
    <div class="container-list">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="addChanson">
                <input type="text" name="chanson" id="chanson" value="" placeholder="Titre de la chanson">
                <input type="file" name="fichier" id="fichier" value="">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>
    </div>
    <div class="container-list" id="tabAjax">
        <?php

        ?>
    </div>
    <?php include("footer.php"); ?>

    <script>
    function ftnChanson() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                tache: "afficherChanson",
                chanson: song.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    song.onclick = ftnChanson;
    </script>