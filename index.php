<?php
session_start();
include("header.php");
?>

<body>
    <div class="container-list">
        <h1 class="title">Se connecter</h1>
        <?php
        if (isset($_GET["errorA"]) == true) {
            echo '<div class="col-md-12 error"> * Ces informations sont requises</div>';
        } else if (isset($_GET["errorD"]) == true) {
            echo '<div class="col-md-12 error">Le login est le mot de passe ne correspondent pas</div>';
        }
        ?>
        <form class="contact-form" action="sendPost.php" method="post">
            <input type="hidden" name="task" value="checkConnect">
            <div class="row">
                <div class="col-md-6">
                    <label for="login" class="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control" placeholder="Votre login">
                </div>
                <div class="col-md-6">
                    <label for="password" class="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Votre mot de passe">
                    <div class="password-icon">
                        <i data-feather="eye"></i>
                        <i data-feather="eye-off"></i>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="submit" value="Login">
                </div>
            </div>
        </form>
        <h1 class="title">Créer un compte</h1>
        <?php
        if (isset($_GET["errorC"]) == true) {
            echo '<div class="col-md-12 error">Ce login existe déjà. Merci de choisir un nouveau login</div>';
        } else if (isset($_GET["errorB"]) == true) {
            echo '<div class="col-md-12 error"> * Les mots de passe ne correspondent pas</div>';
        }
        ?>
        <form class="contact-form" action="sendPost.php" method="post">
            <input type="hidden" name="task" value="addCompte">
            <div class="row">
                <div class="col-md-6">
                    <label for="login" class="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control" placeholder="Votre login" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Votre email" required>
                </div>
                <div class="col-md-6">
                    <label for="password" class="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Votre mot de passe" required>
                    <div class="password-icon">
                        <i data-feather="eye"></i>
                        <i data-feather="eye-off"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="confpswd" class="confpswd">Confirmation mot de passe</label>
                    <input type="password" name="confpswd" id="confpswd" class="form-control"
                        placeholder="Confirmer votre mot de passe" required>
                    <div class="password-icon">
                        <i data-feather="eye"></i>
                        <i data-feather="eye-off"></i>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="submit" value="Créer">
                </div>
            </div>
        </form>
    </div>
    <?php include("footer.php"); ?>

    <script>
    feather.replace();

    const eye = document.querySelector(".feather-eye");
    const eyeoff = document.querySelector(".feather-eye-off");
    const passwordField = document.querySelector("input[type=password]");

    eye.addEventListener("click", () => {
        eye.style.display = "none";
        eyeoff.style.display = "block";
        passwordField.type = "text";
    });

    eyeoff.addEventListener("click", () => {
        eyeoff.style.display = "none";
        eye.style.display = "block";
        passwordField.type = "password";
    });
    </script>