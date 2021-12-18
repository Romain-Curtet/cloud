<?php
session_start();
include("bdd.php");

function in_array_r($valeur, $tableau, $strict = false)
{
    foreach ($tableau as $item) {
        if (($strict ? $item === $valeur : $item == $valeur) || (is_array($item) && in_array_r($valeur, $item, $strict))) {
            return true;
        }
    }
    return false;
}

if ($_POST["tache"] == "checkConnect") {
    if (isset($_POST["login"]) == true && isset($_POST["password"]) == true) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $compte = doSQL("SELECT id, password from compte where login=?", array($login));
        if (empty($compte)) {
            header("Location: index.php?errorA=y");
        } else {
            if (password_verify($password, $compte[0]["password"])) {
                $_SESSION["isConnected"] = "Y";
                $_SESSION["login"] = $login;
                $_SESSION["password"] = $password;
                setcookie(
                    "login",
                    $login,
                    [
                        'expires' => time() + 365 * 24 * 3600,
                        'secure' => true,
                        'httponly' => true,
                    ]
                );
                setcookie(
                    "password",
                    $password,
                    [
                        'expires' => time() + 365 * 24 * 3600,
                        'secure' => true,
                        'httponly' => true,
                    ]
                );
                if ($login == "admin") {
                    header("Location: admin.php");
                } else if ($login == "R&S-CURT") {
                    header("Location: privé.php");
                } else if ($login == "groupe") {
                    header("Location: musique.php");
                } else {
                    header("Location: index.php");
                }
            } else {
                header("Location: index.php?errorD=y");
            }
        }
    } else {
        header("Location: index.php");
    }
} else if ($_POST["tache"] == "checkDisconnect") {
    session_destroy();
    setcookie("login");
    unset($_COOKIE['login']);
    setcookie("password");
    unset($_COOKIE['password']);
    header("Location: index.php");
} else if ($_POST["tache"] == "addCompte") {
    $login = htmlspecialchars($_POST["login"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];
    $confpswd = password_hash($_POST["confpswd"], PASSWORD_BCRYPT);
    $sql = doSQL("SELECT login FROM compte", array());

    $params = array(
        "login" => $login,
        "password" => $password,
        "email" => $email
    );
    if (in_array_r($login, $sql)) {
        header("Location: index.php?errorC=y");
    } else if ($_POST["password"] == $_POST["confpswd"]) {
        doSQL("INSERT into compte (login, password, email) VALUES (:login,:password,:email)", $params);
        $_SESSION["isConnected"] = "Y";
        $_SESSION["login"] = $login;
        header("Location: index.php");
    } else {
        header("Location: index.php?errorB=y");
    }
} else if ($_POST["tache"] == "updateCompte") {
    $id = $_POST['id'];
    $login = htmlspecialchars($_POST['login']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $oldpassword = $_POST['oldpassword'];
    if ($password != $oldpassword) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }
    $params = array(
        "id" => $id,
        "login" => $login,
        "email" => $email,
        "password" => $password,
    );
    doSQL("UPDATE compte SET login=:login, email=:email, password=:password WHERE id=:id", $params);
    $_SESSION["login"] = $login;
    header("Location: compte.php");
} else if ($_POST["tache"] == "addDocument") {
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        if ($_FILES['document']['size'] <= 10000000) {
            $infosfichier = pathinfo($_FILES['document']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('JPG', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx');
            if (in_array($extension_upload, $extensions_autorisees)) {
                move_uploaded_file($_FILES['document']['tmp_name'], 'documents/' . basename($_FILES['document']['name']));
                $document = $_FILES['document']['name'];
            }
        }
    }
    $params = array(
        "document" => $document,
    );
    doSQL("INSERT into documents_privés (document) VALUES (:document)", $params);
    header("Location: documents.php");
} else if ($_POST["tache"] == "deleteDocument") {
    $id = $_POST['id'];
    $document = $_POST['document'];
    $params = array("id" => $id);
    doSQL("DELETE from documents_privés where id=:id", $params);
    unlink('documents/' . $document);
    header("Location: documents.php");
} else if ($_POST["tache"] == "addCourses") {
    $produit = $_POST['produit'];
    $importance = $_POST['importance'];
    $params = array(
        "produit" => $produit,
        "importance" => $importance,
    );
    doSQL("INSERT into courses (produit, importance) VALUES (:produit, :importance)", $params);
    header("Location: courses.php");
} else if ($_POST["tache"] == "updateCourses") {
    $id = $_POST['id'];
    $produit = $_POST['produit'];
    $importance = $_POST['importance'];
    $params = array(
        "id" => $id,
        "produit" => $produit,
        "importance" => $importance,
    );
    doSQL("UPDATE courses SET produit=:produit, importance=:importance WHERE id=:id", $params);
    header("Location: courses.php");
} else if ($_POST["tache"] == "checkCourses") {
    $id = $_POST['id'];
    $produit = $_POST['produit'];
    $params = array(
        "id" => $id,
        "produit" => $produit,
    );
    doSQL("UPDATE courses SET produit=:produit, importance='Bon' WHERE id=:id", $params);
    header("Location: courses.php");
} else if ($_POST["tache"] == "addChanson") {
    $chanson = htmlspecialchars($_POST['chanson']);
    $style = htmlspecialchars($_POST['style']);
    $vitesse = htmlspecialchars($_POST['vitesse']);
    $difficulte = htmlspecialchars($_POST['difficulte']);
    mkdir('./chansons/' . $chanson, 0777);
    $params = array(
        "chanson" => $chanson,
        "style" => $style,
        "vitesse" => $vitesse,
        "difficulte" => $difficulte,
    );
    doSQL("INSERT into chansons (titre, style, vitesse, difficulte) VALUES (:chanson, :style, :vitesse, :difficulte)", $params);
    header("Location: musique.php");
} else if ($_POST["tache"] == "updateChanson") {
    $id = htmlspecialchars($_POST['id']);
    $chanson = htmlspecialchars($_POST['chanson']);
    $style = htmlspecialchars($_POST['style']);
    $vitesse = htmlentities($_POST['vitesse']);
    $difficulte = htmlspecialchars($_POST['difficulte']);
    $params = array(
        "id" => $id,
        "chanson" => $chanson,
        "style" => $style,
        "vitesse" => $vitesse,
        "difficulte" => $difficulte,
    );
    doSQL("UPDATE chansons AS c, musique AS m SET m.chanson=:chanson, c.titre=:chanson, c.style=:style, c.vitesse=:vitesse, c.difficulte=:difficulte WHERE c.id=:id AND m.chanson=c.titre", $params);
    header("Location: musique.php");
} else if ($_POST["tache"] == "addFichier") {
    $chanson = $_POST['chanson'];
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
        if ($_FILES['fichier']['size'] <= 100000000000) {
            $infosfichier = pathinfo($_FILES['fichier']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('JPG', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp3', 'm4a');
            if (in_array($extension_upload, $extensions_autorisees)) {
                move_uploaded_file($_FILES['fichier']['tmp_name'], 'chansons/' . $chanson . '/' . basename($_FILES["fichier"]["name"]));
                $fichier = $_FILES['fichier']['name'];
            }
        }
    }
    $params = array(
        "chanson" => $chanson,
        "fichier" => $fichier,
    );
    doSQL("INSERT into musique (chanson, fichier) VALUES (:chanson, :fichier)", $params);
    header("Location: musique.php");
} else if ($_POST["tache"] == "deleteFichier") {
    $id = $_POST['id'];
    $chanson = $_POST['chanson'];
    $fichier = $_POST['fichier'];
    $params = array("id" => $id);
    doSQL("DELETE from musique where id=:id", $params);
    unlink('chansons/' . $chanson . '/' . $fichier);
    header("Location: musique.php");
} else if ($_POST["tache"] == "afficherChanson") {
    $chanson = $_POST['chanson'];
    $sql = doSQL('SELECT * from musique where chanson ="' . $chanson . '"', array());
    $sql1 = doSQL('SELECT * from chansons where titre="' . $chanson . '"', array());
    if ($chanson == "Chanson" or !isset($chanson)) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="addChanson">
                <input type="text" name="chanson" id="chanson" value="" placeholder="Titre de la chanson">
                <input type="text" name="style" id="style" value="" placeholder="Style">
                <input type="text" name="vitesse" id="vitesse" value="" placeholder="Vitesse">
                <input type="text" name="difficulte" id="difficulte" value="" placeholder="Difficulté">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>';
    } else {
        foreach ($sql1 as $row1) {
            echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="updateChanson">
                <input type="hidden" name="id" value="' . $row1["id"] . '">
                <input type="text" name="chanson" value="' . $row1["titre"] . '">
                <input type="text" name="style" value="' . $row1["style"] . '" placeholder="Style">
                <input type="text" name="vitesse" value="' . $row1["vitesse"] . '" placeholder="Vitesse">
                <input type="text" name="difficulte" value="' . $row1["difficulte"] . '" placeholder="Difficulté">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>
        <br><br>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="addFichier">
                <input type="hidden" name="chanson" value="' . $row1["titre"] . '">
                <input type="file" name="fichier" id="fichier" value="">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>
        <br><br>';
        }
        foreach ($sql as $row) {
            echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
                <input type="text" name="fichier" id="fichier" value="' . $row["fichier"] . '">';
            if (strchr($row["fichier"], "mp3")) {
                echo '<audio controls>
                            <source src="chansons/' . $row["chanson"] . '/' . $row["fichier"] . '" type="audio/mp3">
                        </audio>';
            } else {
                echo '<a href="chansons/' . $row["chanson"] . '/' . $row["fichier"] . '" download>Télécharger le document</a>';
            }
            echo '<form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="deleteFichier">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <input type="hidden" name="chanson" value="' . $row['chanson'] . '">
                <input type="hidden" name="fichier" value="' . $row['fichier'] . '">
                <input type="submit" class="btn btn-danger" value="Supprimer">
            </form>
        </div>
        <br><br>';
        }
    }
} else if ($_POST["tache"] == "afficherStyle") {
    $style = $_POST['style'];
    $params = array(
        "style" => $style,
    );
    $sql = doSQL('SELECT * from chansons where style="' . $style . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="afficherChanson">
                <input type="text" name="chanson" value="' . $row["titre"] . '">
                <input type="text" name="vitesse" value="' . $row["vitesse"] . '">
                <input type="text" name="difficulte" value="' . $row["difficulte"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
} else if ($_POST["tache"] == "afficherVitesse") {
    $vitesse = $_POST['vitesse'];
    $params = array(
        "vitesse" => $vitesse,
    );
    $sql = doSQL('SELECT * from chansons where vitesse="' . $vitesse . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="afficherChanson">
                <input type="text" name="chanson" value="' . $row["titre"] . '">
                <input type="text" name="vitesse" value="' . $row["vitesse"] . '">
                <input type="text" name="difficulte" value="' . $row["difficulte"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
} else if ($_POST["tache"] == "afficherDifficulte") {
    $difficulte = $_POST['difficulte'];
    $params = array(
        "difficulte" => $difficulte,
    );
    $sql = doSQL('SELECT * from chansons where difficulte="' . $difficulte . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tache" value="afficherChanson">
                <input type="text" name="chanson" value="' . $row["titre"] . '">
                <input type="text" name="vitesse" value="' . $row["vitesse"] . '">
                <input type="text" name="difficulte" value="' . $row["difficulte"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
}