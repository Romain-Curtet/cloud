<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "groupe") {
    header("Location: index.php");
}
include("header.php");
include("db.php");
$sql = doSQL("SELECT DISTINCT titre from songs", array());
$sql1 = doSQL("SELECT DISTINCT style from songs", array());
$sql2 = doSQL("SELECT DISTINCT speed from songs", array());
$sql3 = doSQL("SELECT DISTINCT difficult from songs", array());
?>

<body>
    <select name="sort" id="song">
        <option>Chanson</option>
        <?php
        foreach ($sql as $row) {
            echo '<option name="song" value="' . $row["title"] . '">' . $row["title"] . '</option>';
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
            echo '<option name="speed" value="' . $row2["speed"] . '">' . $row2["speed"] . '</option>';
        } ?>
    </select>
    <select name="sort" id="difficult">
        <option>Difficulté</option>
        <?php
        foreach ($sql3 as $row3) {
            echo '<option name="difficult" value="' . $row3["difficult"] . '">' . $row3["difficult"] . '</option>';
        }  ?>
    </select>
    <br><br>
    <div class="container-list" id="tabAjax">
        <?php

        ?>
    </div>
    <?php include("footer.php"); ?>

    <script>
    function ftnSong() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                task: "viewSong",
                song: song.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(song.value);
    song.onclick = ftnSongs;

    function ftnDifficult() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                task: "viewDifficult",
                difficulte: difficult.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(difficult.value);
    difficult.onclick = ftnDifficult;

    function ftnStyle() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                task: "aviewStyle",
                style: style.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(style.value);
    style.onclick = ftnStyle;

    function ftnSpeed() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                task: "viewSpeed",
                vitesse: speed.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    console.log(speed.value);
    speed.onclick = ftnSpeed;
    </script>