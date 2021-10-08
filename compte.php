<body>
    <?php include("header.php");
    include("bdd.php");
    $login = $_SESSION["login"];
    $sql = doSQL("SELECT * from compte where login=?", array($login));
    ?>
    <div class="container-list">
        <h1 class="title">Informations</h1>
        <?php
        foreach ($sql as $row) {
            echo "<div class='col-lg-7 liste'>
                    <table class='table table-bordered'>
                        <tbody>
                            <tr>
                                <th>Login</th>
                                <th>Email</th>
                            </tr>
                            <tr>
                                <td>" . $row["login"] . "</td>
                                <td>" . $row["mail"] . "</td>
                                <td>
                                    <br>
                                    <form action='modifCompte.php' method='post'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <input type='submit' class='action' value='Modifier'>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class='col-lg-5 liste'>
                <a class='nav-link' href='index.php'>Revenir à la liste des produits</a>
                </div>";
        } ?>
    </div>
    <?php include("footer.php"); ?>