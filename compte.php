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
if (!isset($_SESSION["isConnected"])) {
    header("Location: index.php");
}
include("header.php");
include("bdd.php");
$login = $_SESSION["login"];
$sql = doSQL("SELECT * from compte where login=?", array($login));
if (!isset($_SESSION["isConnected"]) == "N") {
    header("Location: index.php");
}
?>

<body>
    <div class="container-list">
        <h1 class="title">Informations</h1>
        <?php
        foreach ($sql as $row) {
            echo '<div class="col-lg-7 liste">
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
                                    <form action="modifCompte.php" method="post">
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
                '<div class="col-lg-7 liste">
                    <a class="nav-link" href="musique.php">Revenir aux chansons</a>
                </div>';
            }
        } ?>
    </div>
    <?php include("footer.php"); ?>