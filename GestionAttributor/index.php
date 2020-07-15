<?php
    @session_start();
    @include('config.php');
    @include('controller/route.php');

    if (isset($_POST["user"]) && isset($_POST["pwrd"])) {
        $result = $bdd->query("SELECT * FROM `users` WHERE `User`='" . $_POST['user'] . "' AND `Role`=1");
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (password_verify($_POST['pwrd'], $row['Password'])) {
                $_SESSION["log"] = true;
                $_SESSION["user"] = $row['user_login'];
                $_SESSION["email"] = $row['user_email'];
                $_SESSION["token"] = password_hash("verif", PASSWORD_DEFAULT);
                $_SESSION["role"] = $row['user_role'];
                header('Location: '.DOMAINE."/");
                exit;
            } else {
                $errorlog = true;
            }
        } else {
            $errorlog = true;
        }
    }
    if (isset($_GET["logout"])) {
        if ($_GET["logout"] == 'true') {
            $_SESSION["log"] = false;
            $_SESSION["user"] = "";
            $_SESSION["email"] = "";
            $_SESSION["role"] = "";
            session_destroy();
            header('Location: '.DOMAINE."/");
            exit;
        }
    }
    $_POST["user"] = '';
    $_POST["pwrd"] = '';

    if (!(isset($_SESSION["log"]) && $_SESSION["log"])) {
        $template='template-login';
        $title='Login';
        $page="login";
    }
    if($template) require('templates/'.$template.'.php');
?>