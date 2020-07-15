<?php

include 'route_funtion.php';

/*----VALEUR PAR DEFAULT-----*/
$currenturl;
$title="Gestion Attributor";
$page="home";
$template="template";
/*---------------------------*/
/*-----check() -> l'url est la meme /// has() -> url contient le meme parent-------*/

if(check("")){
    //PAGE d'accueil par default
}
elseif(has("/manage-user")){
    $title='Manager Utilisateur';
    $page="manag-user";
}
elseif(has("/manage-pc")){
    $title='Manager PC';
    $page="manag-pc";
}
elseif(has("/planning")){
    $title='Planning';
    $page="planning";
}
elseif(has("/help")){
    $title='Aide';
    $page="help";
}
else{
    $title='Page non trouvé';
    $page="404";
    //header('Location: '.DOMAINE);
    //exit;
}