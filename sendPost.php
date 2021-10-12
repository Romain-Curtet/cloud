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
        $compte = doSQL("SELECT id, password from compte where login=?", array($login));
        if (empty($compte)) {
            header("Location: index.php?errorA=y");
        } else {
            if (password_verify($_POST["password"], $compte[0]["password"])) {
                if ($login == "admin") {
                    $_SESSION["isConnected"] = "Y";
                    $_SESSION["login"] = $login;
                    header("Location: admin.php");
                } else if ($login == "R&S-CURT") {
                    $_SESSION["isConnected"] = "Y";
                    $_SESSION["login"] = $login;
                    header("Location: privé.php");
                } else if ($login == "groupe") {
                    $_SESSION["isConnected"] = "Y";
                    $_SESSION["login"] = $login;
                    header("Location: musique.php");
                } else {
                    $_SESSION["isConnected"] = "Y";
                    $_SESSION["login"] = $login;
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
    header("Location: index.php");
} else if ($_POST["tache"] == "addCompte") {
    $login = $_POST["login"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];
    $confpswd = password_hash($_POST["confpswd"], PASSWORD_BCRYPT);
    $sql = doSQL("SELECT * FROM compte", array());

    $params = array(
        "login" => $login,
        "password" => $password,
        "mail" => $email
    );
    if (in_array_r($login, $sql)) {
        header("Location: index.php?errorC=y");
    } else if ($_POST["password"] == $_POST["confpswd"]) {
        doSQL("INSERT into compte (login, password, mail) VALUES (:login,:password,:mail)", $params);
        $_SESSION["isConnected"] = "Y";
        $_SESSION["login"] = $login;
        header("Location: index.php");
    } else {
        header("Location: index.php?errorB=y");
    }
} else if ($_POST["tache"] == "deleteCompte") {
    $id = $_POST['id'];
    $params = array("id" => $id);
    doSQL("DELETE from compte where id=:id", $params);
    header("Location: listeClients.php");
} else if ($_POST["tache"] == "updateCompte") {
    $id = $_POST['id'];
    $login = $_POST['login'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $oldpassword = $_POST['oldpassword'];
    if ($password != $oldpassword) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }
    $params = array(
        "id" => $id,
        "login" => $login,
        "mail" => $mail,
        "password" => $password,
    );
    $sql = doSQL("UPDATE compte SET login=:login, mail=:mail, password=:password WHERE id=:id", $params);
    $_SESSION["login"] = $login;
    header("Location: compte.php");
} else if ($_POST["tache"] == "addDocument") {
    if (isset($_FILES['document'])) {
        if ($_FILES['document']['size'] <= 1000000) {
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
    $sql = doSQL("UPDATE courses SET produit=:produit, importance=:importance WHERE id=:id", $params);
    header("Location: courses.php");
} else if ($_POST["tache"] == "deleteCourses") {
    $id = $_POST['id'];
    $params = array("id" => $id);
    doSQL("DELETE from courses where id=:id", $params);
    header("Location: courses.php");
} else if ($_POST["tache"] == "addChanson") {
    $chanson = $_POST['chanson'];
    mkdir('./chansons/' . $chanson, 0777);
    if (isset($_FILES['fichier'])) {
        if ($_FILES['fichier']['size'] <= 100000000000) {
            $infosfichier = pathinfo($_FILES['fichier']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('JPG', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp3', 'm4c');
            if (in_array($extension_upload, $extensions_autorisees)) {
                move_uploaded_file($_FILES['fichier']['tmp_name'], 'chansons/' . $chanson . '/' . basename($_FILES['fichier']['name']));
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
    $params = array(
        "chanson" => $chanson,
    );
    $sql = doSQL("SELECT * from musique where chanson ='" . $chanson . "'", $params);
    foreach ($sql as $row) {
        echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 liste'>
                <input type='text' name='fichier' id='fichier' value='" . $row["fichier"] . "'>
                <a href='chansons/" . $row["chanson"] . "/" . $row["fichier"] . "' download>Télécharger le document</a>
                <form action='sendPost.php' method='post' enctype='multipart/form-data'>
                    <input type='hidden' name='tache' value='deleteFichier'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='hidden' name='chanson' value='" . $row['chanson'] . "'>
                    <input type='hidden' name='fichier' value='" . $row['fichier'] . "'>
                    <input type='submit' class='btn btn-danger' value='Supprimer'>
                </form>
                </div>
                <br>";
    }
}