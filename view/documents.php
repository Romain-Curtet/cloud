<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "R&S-CURT") {
    header("Location: index.php");
}
require("header.php");
require("./db.php");
if ($_SESSION["login"] != "R&S-CURT") {
    header("Location: index.php");
}
$sql = doSQL("SELECT * from private_files ORDER BY id DESC", array());
?>

<body>
    <div class="container-list">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="addFile">
                <table class="table table-bordered">
                    <tr>
                        <input type="file" name="file" id="file" value="">
                        <input type="submit" class="action" value="Enregistrer">
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <?php
            foreach ($sql as $row) {
                echo '<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 list">
                    <table class="table table-bordered">
                        <tr>
                            <input type="text" name="file" id="file" value="' . $row["file"] . '">
                        </tr>
                        <tr>
                            <a href="./public/files/' . $row["file"] . '" download>Télécharger le document</a>
                        </tr>
                        <tr>
                            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="task" value="deleteFile">
                                <input type="hidden" name="id" value="' . $row["id"] . '">
                                <input type="hidden" name="file" value="' . $row["file"] . '">
                                <input type="submit" class="btn btn-danger" value="Supprimer">
                            </form>
                        </tr>
                    </table>
                </div>';
            } ?>
        </div>
    </div>
    <?php require("footer.php"); ?>