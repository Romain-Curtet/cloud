<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html>
<html>

<head>

    <head>
        <title>Bienvenue sur le Cloud de Romain CURTET</title>
    </head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="style\style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>

<header>
    <div class="container" id="container-header">
        <div class="row">
            <nav class="nav">
                <?php
                if (isset($_SESSION["isConnected"]) == "Y") {
                    echo '<div class="col-lg-6 col-md-6 col-sm-6">
                            <h1>Cloud</h1>
                        </div>';
                    if ($_SESSION["login"] == "admin") {
                        echo "<div class='col-lg-2 col-md-2 col-sm-2'>
                                <a class='nav-link' href='listeProduits.php'>
                                    <h4>Liste des produits</h4>
                                </a>
                                <a class='nav-link' href='listeClients.php'>
                                    <h4>Liste des clients</h4>
                                </a>
                            </div>
                            <div class='col-lg-2 col-md-2 col-sm-2'>
                                <form class='contact-form' action='compte.php' method='post'>
                                    <h4><input type='submit' class='nav-link' value='" . htmlspecialchars($_SESSION['login']) . "'></h4>
                                </form>
                            </div>
                            <div class='col-lg-2 col-md-2 col-sm-2'>
                                <form class='contact-form' action='sendPost.php' method='post'>
                                    <input type='hidden' name='tache' value='checkDisconnect'>
                                    <h4><input type='submit' class='nav-link' value='Deconnexion'></h4>
                                </form>";
                    } else {
                        echo "<div class='col-lg-3 col-md-3 col-sm-3'>
                                <form class='contact-form' action='compte.php' method='post'>
                                    <h4><input type='submit' class='nav-link' value='" . htmlspecialchars($_SESSION['login']) . "'></h4>
                                </form>
                            </div>
                            <div class='col-lg-3 col-md-3 col-sm-3'>
                                <form class='contact-form' action='sendPost.php' method='post'>
                                    <input type='hidden' name='tache' value='checkDisconnect'>
                                    <h4><input type='submit' class='nav-link' value='Deconnexion'></h4>
                                </form>";
                    }
                } else {
                    echo "<div class='col-lg-12 col-md-12 col-sm-12'>
                                <h1>Bienvenue sur le cloud</h1>
                        </div>";
                }
                echo '</nav>
        </div>
    </div>
</header>
<header>';
                if (isset($_SESSION["isConnected"]) == "Y") {
                    if ($_SESSION["login"] == "R&S-CURT") {
                        echo '<div class="container" id="container-header">
                    <div class="row">
                        <nav class="nav">
                            <div class="col-lg-6">
                                <h4><a class="nav-link" href="documents.php">Documents</a></h4>
                            </div>
                            <div class="col-lg-6">
                                <h4><a class="nav-link" href="courses.php">Courses</a></h4>
                            </div>
                        </nav>
                    </div>
                </div>';
                    }
                }
                ?>
</header>