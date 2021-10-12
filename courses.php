<body>
    <?php include("header.php");
    include("bdd.php");
    if ($_SESSION["login"] != "R&S-CURT") {
        header("Location: index.php");
    }
    $sql = doSQL("SELECT * from courses ORDER BY importance DESC", array());
    ?>
    <div class='container-list'>
        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste'>
            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                <input type='hidden' name='tache' value='addCourses'>
                <table class='table table-bordered'>
                    <tr>
                        <input type='text' name='produit' id='produit' value=''>
                        <select name="importance" id="importance">
                            <option value="Urgent">Urgent</option>
                            <option value="Normal">Normal</option>
                            <option value="Faible">Faible</option>
                        </select>
                        <input type='submit' class='action' value='Enregistrer'>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <?php
            foreach ($sql as $row) {
                echo "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 liste'>
                        <table class='table table-bordered'>
                            <tr>
                            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                                <td>
                                    <label for='produit'></label>
                                    <input type='text' name='produit' id='produit' value='" . $row["produit"] . "'>
                                </td>
                                <td>
                                    <select name='importance' id='importance'>";
                if ($row["importance"] == 'Urgent') {
                    echo "<option value='" . $row["importance"] . "' selected>" . $row["importance"] . "</option>
                                                <option value='Normal'>Normal</option>
                                                <option value='Faible'>Pas Faible</option>";
                } else if ($row["importance"] == 'Normal') {
                    echo "<option value='Urgent'>Urgent</option>
                                                <option value='" . $row["importance"] . "' selected>" . $row["importance"] . "</option>
                                                <option value='Faible'>Pas Faible</option>";
                } else if ($row["importance"] == 'Faible') {
                    echo "<option value='Urgent'>Urgent</option>
                                                <option value='Normal'>Normal</option>
                                                <option value='" . $row["importance"] . "' selected>" . $row["importance"] . "</option>";
                }
                echo "</select>
                                </td>
                                <td>
                                        <input type='hidden' name='tache' value='updateCourses'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <input type='submit' class='action' value='Modifier'>
                                    
                                </td>
                            </form>
                            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                                <td>
                                        <input type='hidden' name='tache' value='deleteCourses'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <input type='submit' class='btn btn-danger' value='Supprimer'>
                                </td>
                            </form>
                            </tr>
                        </table>
                    <br>
                </div>";
            } ?>
        </div>
    </div>
    <?php include("footer.php"); ?>