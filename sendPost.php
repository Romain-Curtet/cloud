<?php
session_start();
include("db.php");

function in_array_r($valeur, $tableau, $strict = false)
{
    foreach ($tableau as $item) {
        if (($strict ? $item === $valeur : $item == $valeur) || (is_array($item) && in_array_r($valeur, $item, $strict))) {
            return true;
        }
    }
    return false;
}

if ($_POST["task"] == "checkConnect") {
    if (isset($_POST["login"]) == true && isset($_POST["password"]) == true) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $account = doSQL("SELECT id, password from account where login=?", array($login));
        if (empty($account)) {
            header("Location: index.php?errorA=y");
        } else {
            if (password_verify($password, $account[0]["password"])) {
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
                    header("Location: private.php");
                } else if ($login == "groupe") {
                    header("Location: music.php");
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
} else if ($_POST["task"] == "checkDisconnect") {
    session_destroy();
    setcookie("login");
    unset($_COOKIE['login']);
    setcookie("password");
    unset($_COOKIE['password']);
    header("Location: index.php");
} else if ($_POST["task"] == "addAccount") {
    $login = htmlspecialchars($_POST["login"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];
    $confpswd = password_hash($_POST["confpswd"], PASSWORD_BCRYPT);
    $sql = doSQL("SELECT login FROM account", array());

    $params = array(
        "login" => $login,
        "password" => $password,
        "email" => $email
    );
    if (in_array_r($login, $sql)) {
        header("Location: index.php?errorC=y");
    } else if ($_POST["password"] == $_POST["confpswd"]) {
        doSQL("INSERT into account (login, password, email) VALUES (:login,:password,:email)", $params);
        $_SESSION["isConnected"] = "Y";
        $_SESSION["login"] = $login;
        header("Location: index.php");
    } else {
        header("Location: index.php?errorB=y");
    }
} else if ($_POST["task"] == "updateAccount") {
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
    doSQL("UPDATE account SET login=:login, email=:email, password=:password WHERE id=:id", $params);
    $_SESSION["login"] = $login;
    header("Location: account.php");
} else if ($_POST["task"] == "addFile") {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        if ($_FILES['file']['size'] <= 10000000) {
            $infosfile = pathinfo($_FILES['file']['name']);
            $extension_upload = $infosfile['extension'];
            $extensions_autorisees = array('JPG', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx');
            if (in_array($extension_upload, $extensions_autorisees)) {
                move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . basename($_FILES['file']['name']));
                $file = $_FILES['file']['name'];
            }
        }
    }
    $params = array(
        "file" => $file,
    );
    doSQL("INSERT into private_files (file) VALUES (:file)", $params);
    header("Location: documents.php");
} else if ($_POST["task"] == "deleteFile") {
    $id = $_POST['id'];
    $file = $_POST['file'];
    $params = array("id" => $id);
    doSQL("DELETE from private_files where id=:id", $params);
    unlink('files/' . $file);
    header("Location: documents.php");
} else if ($_POST["task"] == "addProducts") {
    $product = $_POST['product'];
    $importance = $_POST['importance'];
    $params = array(
        "product" => $product,
        "importance" => $importance,
    );
    doSQL("INSERT into products (product, importance) VALUES (:product, :importance)", $params);
    header("Location: shopping.php");
} else if ($_POST["task"] == "updateProducts") {
    $id = $_POST['id'];
    $product = $_POST['product'];
    $importance = $_POST['importance'];
    $params = array(
        "id" => $id,
        "product" => $product,
        "importance" => $importance,
    );
    doSQL("UPDATE products SET product=:product, importance=:importance WHERE id=:id", $params);
    header("Location: shopping.php");
} else if ($_POST["task"] == "checkProducts") {
    $id = $_POST['id'];
    $product = $_POST['product'];
    $params = array(
        "id" => $id,
        "product" => $product,
    );
    doSQL("UPDATE products SET product=:product, importance='Bon' WHERE id=:id", $params);
    header("Location: shopping.php");
} else if ($_POST["task"] == "addSong") {
    $song = htmlspecialchars($_POST['song']);
    $style = htmlspecialchars($_POST['style']);
    $speed = htmlspecialchars($_POST['speed']);
    $difficult = htmlspecialchars($_POST['difficult']);
    mkdir('./songs/' . $song, 0777);
    $params = array(
        "song" => $song,
        "style" => $style,
        "speed" => $speed,
        "difficult" => $difficult,
    );
    doSQL("INSERT into songs (title, style, speed, difficult) VALUES (:song, :style, :speed, :difficult)", $params);
    header("Location: music.php");
} else if ($_POST["task"] == "updateSong") {
    $id = htmlspecialchars($_POST['id']);
    $song = htmlspecialchars($_POST['song']);
    $style = htmlspecialchars($_POST['style']);
    $speed = htmlentities($_POST['speed']);
    $difficult = htmlspecialchars($_POST['difficult']);
    $params = array(
        "id" => $id,
        "song" => $song,
        "style" => $style,
        "speed" => $speed,
        "difficult" => $difficult,
    );
    doSQL("UPDATE songs AS c, music AS m SET m.song=:song, c.title=:song, c.style=:style, c.speed=:speed, c.difficult=:difficult WHERE c.id=:id AND m.song=c.title", $params);
    header("Location: music.php");
} else if ($_POST["task"] == "addFile") {
    $song = $_POST['song'];
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        if ($_FILES['file']['size'] <= 100000000000) {
            $infosfile = pathinfo($_FILES['file']['name']);
            $extension_upload = $infosfile['extension'];
            $extensions_autorisees = array('JPG', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp3', 'm4a');
            if (in_array($extension_upload, $extensions_autorisees)) {
                move_uploaded_file($_FILES['file']['tmp_name'], 'songs/' . $song . '/' . basename($_FILES["file"]["name"]));
                $file = $_FILES['file']['name'];
            }
        }
    }
    $params = array(
        "song" => $song,
        "file" => $file,
    );
    doSQL("INSERT into music (song, file) VALUES (:song, :file)", $params);
    header("Location: music.php");
} else if ($_POST["task"] == "deleteFile") {
    $id = $_POST['id'];
    $song = $_POST['song'];
    $file = $_POST['file'];
    $params = array("id" => $id);
    doSQL("DELETE from music where id=:id", $params);
    unlink('songs/' . $song . '/' . $file);
    header("Location: music.php");
} else if ($_POST["task"] == "viewSong") {
    $song = $_POST['song'];
    $sql = doSQL('SELECT * from music where song ="' . $song . '"', array());
    $sql1 = doSQL('SELECT * from songs where title="' . $song . '"', array());
    if ($song == "Chanson" or !isset($song)) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="addSong">
                <input type="text" name="song" id="song" value="" placeholder="Titre de la chanson">
                <input type="text" name="style" id="style" value="" placeholder="Style">
                <input type="text" name="speed" id="speed" value="" placeholder="Vitesse">
                <input type="text" name="difficult" id="difficult" value="" placeholder="Difficulté">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>';
    } else {
        foreach ($sql1 as $row1) {
            echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="updateSong">
                <input type="hidden" name="id" value="' . $row1["id"] . '">
                <input type="text" name="song" value="' . $row1["title"] . '" placeholder="Titre de la chanson">
                <input type="text" name="style" value="' . $row1["style"] . '" placeholder="Style">
                <input type="text" name="speed" value="' . $row1["speed"] . '" placeholder="Vitesse">
                <input type="text" name="difficult" value="' . $row1["difficult"] . '" placeholder="Difficulté">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>
        <br><br>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
            <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="addFile">
                <input type="hidden" name="song" value="' . $row1["title"] . '">
                <input type="file" name="file" id="file" value="">
                <input type="submit" class="action" value="Enregistrer">
            </form>
        </div>
        <br><br>';
        }
        foreach ($sql as $row) {
            echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
                <input type="text" name="file" id="file" value="' . $row["file"] . '">';
            if (strchr($row["file"], "mp3")) {
                echo '<audio controls>
                            <source src="songs/' . $row["song"] . '/' . $row["file"] . '" type="audio/mp3">
                        </audio>';
            } else {
                echo '<a href="songs/' . $row["song"] . '/' . $row["file"] . '" download>Télécharger le document</a>';
            }
            echo '<form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="deleteFile">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <input type="hidden" name="song" value="' . $row['song'] . '">
                <input type="hidden" name="file" value="' . $row['file'] . '">
                <input type="submit" class="btn btn-danger" value="Supprimer">
            </form>
        </div>
        <br><br>';
        }
    }
} else if ($_POST["task"] == "viewStyle") {
    $style = $_POST['style'];
    $params = array(
        "style" => $style,
    );
    $sql = doSQL('SELECT * from songs where style="' . $style . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="viewSong">
                <input type="text" name="song" value="' . $row["title"] . '">
                <input type="text" name="style" value="' . $row["style"] . '">
                <input type="text" name="speed" value="' . $row["speed"] . '">
                <input type="text" name="difficult" value="' . $row["difficult"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
} else if ($_POST["task"] == "viewSpeed") {
    $speed = $_POST['speed'];
    $params = array(
        "speed" => $speed,
    );
    $sql = doSQL('SELECT * from songs where speed="' . $speed . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="viewSong">
                <input type="text" name="song" value="' . $row["title"] . '">
                <input type="text" name="style" value="' . $row["style"] . '">
                <input type="text" name="speed" value="' . $row["speed"] . '">
                <input type="text" name="difficult" value="' . $row["difficult"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
} else if ($_POST["task"] == "viewDifficult") {
    $difficult = $_POST['difficult'];
    $params = array(
        "difficult" => $difficult,
    );
    $sql = doSQL('SELECT * from songs where difficult="' . $difficult . '"', $params);
    foreach ($sql as $row) {
        echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 list">
                <form action="sendPost.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="task" value="viewSong">
                <input type="text" name="song" value="' . $row["title"] . '">
                <input type="text" name="style" value="' . $row["style"] . '">
                <input type="text" name="speed" value="' . $row["speed"] . '">
                <input type="text" name="difficult" value="' . $row["difficult"] . '">
                <input type="submit" class="action" value="Voir">
            </form>
        </div>
        <br><br>';
    }
}