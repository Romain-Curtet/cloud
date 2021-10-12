<body>
    <?php include("header.php");
    include("bdd.php");
    $sql = doSQL("SELECT * from musique", array());
    $sql1 = doSQL("SELECT DISTINCT chanson from musique", array());
    ?>
    <select name="sort" id="song">
        <option> -- </option>
        <?php
        foreach ($sql1 as $row1) {
            echo "<option name='chanson' value='" . $row1["chanson"] . "'>" . $row1["chanson"] . "</option>";
        } ?>
    </select>
    <br><br>
    <div class=" container-list">
        <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste'>
            <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                <input type='hidden' name='tache' value='addChanson'>
                <input type='text' name='chanson' id='chanson' value='' placeholder='Titre de la chanson'>
                <input type='file' name='fichier' id='fichier' value=''>
                <input type='submit' class='action' value='Enregistrer'>
            </form>
        </div>
    </div>
    <div class=" container-list" id="tabAjax">
        <?php
        /* foreach ($sql as $row) {
            echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste'>
                <table class='table table-bordered'>
                    <tr>
                        <input type='text' name='fichier' id='fichier' value='" . $row["fichier"] . "'>
                    </tr>
                    <tr>
                        <a href='chansons/" . $row["chanson"] . "/" . $row["fichier"] . "' download>Télécharger le document</a>
                    </tr>
                    <tr>
                        <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                            <input type='hidden' name='tache' value='deleteFichier'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='hidden' name='chanson' value='" . $row['chanson'] . "'>
                            <input type='hidden' name='fichier' value='" . $row['fichier'] . "'>
                            <input type='submit' class='btn btn-danger' value='Supprimer'>
                        </form>
                    </tr>
                </table>
            </div>";
        } */
        ?>
    </div>
    <?php include("footer.php"); ?>

    <script>
    function ftnChanson() {
        $.ajax("sendPost.php", {
            type: "POST",
            data: {
                tache: 'afficherChanson',
                chanson: song.value,
            },
            success: function(resp) {
                tabAjax.innerHTML = resp;
            }
        });
    }
    song.onclick = ftnChanson;
    </script>