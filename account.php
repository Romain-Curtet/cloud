<?php
session_start();
if (!isset($_SESSION["isConnected"])) {
    header("Location: index.php");
}
include("header.php");
include("db.php");
$login = $_SESSION["login"];
$sql = doSQL("SELECT * from account where login=?", array($login));
if (!isset($_SESSION["isConnected"]) == "N") {
    header("Location: index.php");
}
?>

<body>
    <div class="container-list">
        <h1 class="title">Informations</h1>
        <?php
        foreach ($sql as $row) {
            echo '<div class="col-lg-7 list">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Login</th>
                                <th>Email</th>
                            </tr>
                            <tr>
                                <td>' . $row["login"] . '</td>
                                <td>' . $row["email"] . '</td>
                                <td>
                                    <br>
                                    <form action="updateAccount.php" method="post">
                                        <input type="hidden" name="id" value="' . $row["id"] . '">
                                        <input type="submit" class="action" value="Modifier">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
            if ($login == "groupe") {
                echo
                '<div class="col-lg-7 list">
                    <a class="nav-link" href="music.php">Revenir aux chansons</a>
                </div>';
            }
        } ?>
    </div>
    <?php include("footer.php"); ?>