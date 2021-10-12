<body>
    <?php include("header.php");
    include("bdd.php");
    if ($_SESSION["login"] != "R&S-CURT") {
        header("Location: index.php");
    }
    $sql = doSQL("SELECT * from documents_privés ORDER BY id DESC", array());
    ?>
    <div class='container-list'>
        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste'>
            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                <input type='hidden' name='tache' value='addDocument'>
                <table class='table table-bordered'>
                    <tr>
                        <input type='file' name='document' id='document' value=''>
                        <input type='submit' class='action' value='Enregistrer'>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <?php
            foreach ($sql as $row) {
                echo "<div class='col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 liste'>
                    <table class='table table-bordered'>
                        <tr>
                            <input type='text' name='document' id='document' value='" . $row["document"] . "'>
                        </tr>
                        <tr>
                            <a href='documents/" . $row["document"] . "' download>Télécharger le document</a>
                        </tr>
                        <tr>
                            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                                <input type='hidden' name='tache' value='deleteDocument'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <input type='hidden' name='document' value='" . $row['document'] . "'>
                                <input type='submit' class='btn btn-danger' value='Supprimer'>
                            </form>
                        </tr>
                    </table>
                </div>";
            } ?>
        </div>
    </div>
    <?php include("footer.php"); ?>