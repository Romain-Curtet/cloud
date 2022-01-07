<?php
session_start();
require("./model/model.php");
if ($_POST["task"] == "checkConnect") {
    checkConnect($_POST["login"], $_POST["password"]);
} elseif ($_POST["task"] == "checkDisconnect") {
    checkDisconnect();
} elseif ($_POST["task"] == "addAccount") {
    addAccount($_POST["login"], $_POST["password"], $_POST["email"], $_POST["confpswd"]);
} elseif ($_POST["task"] == "updateAccount") {
    updateAccount($_POST['id'], $_POST['login'], $_POST['email'], $_POST['password'], $_POST['oldpassword']);
} elseif ($_POST["task"] == "addFile") {
    addFile($_FILES['file']);
} elseif ($_POST["task"] == "deleteFile") {
    deleteFile($_POST['id'], $_POST['file']);
} elseif ($_POST["task"] == "addProducts") {
    addProducts($_POST['product'], $_POST['importance']);
} elseif ($_POST["task"] == "updateProducts") {
    updateProducts($_POST['id'], $_POST['product'], $_POST['importance']);
} elseif ($_POST["task"] == "checkProducts") {
    checkProducts($_POST['id'], $_POST['product']);
} elseif ($_POST["task"] == "addSong") {
    addSong($_POST['song'], $_POST['style'], $_POST['speed'], $_POST['difficult']);
} elseif ($_POST["task"] == "updateSong") {
    updateSong($_POST['id'], $_POST['song'], $_POST['style'], $_POST['speed'], $_POST['difficult']);
} elseif ($_POST["task"] == "addSongFile") {
    addSongFile($_POST['song'], $_FILES['file']);
} elseif ($_POST["task"] == "deleteSongFile") {
    deleteSongFile($_POST['id'], $_POST['song'], $_POST['file']);
} elseif ($_POST["task"] == "viewSong") {
    viewSong($_POST['song']);
} elseif ($_POST["task"] == "viewStyle") {
    viewStyle($_POST['style']);
} elseif ($_POST["task"] == "viewSpeed") {
    viewSpeed($_POST['speed']);
} elseif ($_POST["task"] == "viewDifficult") {
    viewDifficult($_POST['difficult']);
}