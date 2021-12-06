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
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "groupe") {
    header("Location: index.php");
}
include("header.php");
include("bdd.php");
$sql = doSQL("SELECT DISTINCT titre from chansons", array());
$sql1 = doSQL("SELECT DISTINCT style from chansons", array());
$sql2 = doSQL("SELECT DISTINCT vitesse from chansons", array());
$sql3 = doSQL("SELECT DISTINCT difficulte from chansons", array());
?>

<body>
    <select name="sort" id="song">
        <option>Chanson</option>
        <?php
        foreach ($sql as $row) {
            echo '<option name="chanson" value="' . $row["titre"] . '">' . $row["titre"] . '</option>';
        } ?>
    </select>
    <select name="sort" id="style">
        <option>Style</option>
        <?php
        foreach ($sql1 as $row1) {
            echo '<option name="style" value="' . $row1["style"] . '">' . $row1["style"] . '</option>';
        } ?>
    </select>
    <select name="sort" id="speed">
        <option>Vitesse</option>
        <?php
        foreach ($sql2 as $row2) {
            echo '<option name="vitesse" value="' . $row2["vitesse"] . '">' . $row2["vitesse"] . '</option>';
        } ?>
    </select>
    <select name="sort" id="difficult">
        <option>Difficulté</option>
        <?php
        foreach ($sql3 as $row3) {
            echo '<option name="chanson" value="' . $row3["difficulte"] . '">' . $row3["difficulte"] . '</option>';
        }  ?>
    </select>
    <br><br>
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
    console.log(song.value);
    song.onclick = ftnChanson;

    function ftnDifficulte() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                tache: "afficherDifficulte",
                difficulte: difficult.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(difficult.value);
    difficult.onclick = ftnDifficulte;

    function ftnStyle() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                tache: "afficherStyle",
                style: style.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(style.value);
    style.onclick = ftnStyle;

    function ftnVitesse() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                tache: "afficherVitesse",
                vitesse: speed.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(speed.value);
    speed.onclick = ftnVitesse;
    </script>