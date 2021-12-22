<?php
session_start();
if (!isset($_SESSION["isConnected"]) || $_SESSION["login"] != "R&S-CURT") {
    header("Location: index.php");
}
include("header.php");
include("db.php");
$sql = doSQL("SELECT * from products ORDER BY importance DESC, product ASC", array());
?>

<body>
    <div class="container-list">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="addProducts">
                <table class="table table-bordered">
                    <tr>
                        <input type="text" name="product" id="product" value="">
                        <select name="importance" id="importance">
                            <option value="Urgent">Urgent</option>
                            <option value="Normal" selected>Normal</option>
                            <option value="Faible">Faible</option>
                        </select>
                        <input type="submit" class="action" value="Enregistrer">
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <?php
            foreach ($sql as $row) {
                echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 list">
                        <table class="table table-bordered table-products">
                            <tr>
                            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                                <td>
                                    <input type="text" name="product" id="product" value="' . $row["product"] . '">
                                </td>
                                <td>
                                    <select name="importance" id="importance">';
                if ($row["importance"] == "Urgent") {
                    echo '<option value="' . $row["importance"] . '" selected>' . $row["importance"] . '</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Faible">Faible</option>
                                        <option value="Bon">Bon</option>';
                } else if ($row["importance"] == "Normal") {
                    echo '<option value="Urgent">Urgent</option>
                                        <option value="' . $row["importance"] . '" selected>' . $row["importance"] . '</option>
                                        <option value="Faible">Faible</option>
                                        <option value="Bon">Bon</option>';
                } else if ($row["importance"] == "Faible") {
                    echo '<option value="Urgent">Urgent</option>
                                        <option value="Normal">Normal</option>
                                        <option value="' . $row["importance"] . '" selected>' . $row["importance"] . '</option>
                                        <option value="Bon">Bon</option>';
                } else if ($row["importance"] == "Bon") {
                    echo '<option value="Urgent">Urgent</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Faible">Faible</option>
                                        <option value="' . $row["importance"] . '" selected>' . $row["importance"] . '</option>';
                }
                echo '</select>
                                </td>
                                <td>
                                    <input type="hidden" name="task" value="updateProducts">
                                    <input type="hidden" name="id" value="' . $row["id"] . '">
                                    <input type="submit" class="action" value="Modifier">
                                    
                                </td>
                            </form>
                            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                                <td>
                                    <input type="hidden" name="task" value="checkProducts">
                                    <input type="hidden" name="id" value="' . $row["id"] . '">
                                    <input type="hidden" name="product" value="' . $row["product"] . '">
                                    <input type="submit" class="btn btn-success" value="OK">
                                </td>
                            </form>
                            </tr>
                        </table>
                    <br>
                </div>';
            } ?>
        </div>
    </div>
    <?php include("footer.php"); ?>