<?php
session_start();
if (!isset($_SESSION["isConnected"])) {
    header("Location: index.php");
}
include("header.php");
include("db.php");
$id = $_POST["id"];
$sql = doSQL("SELECT * from account where id = ?", array($id));
?>

<body>
    <div class="container-list">
        <h1 class="title">Modifier les informations</h1>
        <?php
        foreach ($sql as $row) {
            echo '<div class="col-lg-10 list">
                <form action="sendPost.php" method="post">
                    <input type="hidden" name="task" value="updateAccount">
                    <input type="hidden" name="id" value="' . $row["id"] . '">
                    <input type="hidden" name="oldpassword" value="' . $row["password"] . '">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <label for="login">Login</label>
                                <input type="text" name="login" id="login" value="' . $row["login"] . '">
                            </td>
                            <td>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="' . $row["email"] . '">
                            </td>
                            <td>
                                <label for="password">Mot de passe</label>
                                <input type="text" name="password" id="password" value="">
                            </td>
                            <td>
                                <br>
                                <input type="submit" class="action" value="Enregistrer"></br>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>';
        } ?>
    </div>
    <?php include("footer.php"); ?>