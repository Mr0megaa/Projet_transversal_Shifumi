<?php
session_start();

//unset($_SESSION['']); // SI JE VEUX CLEAN UNE SESSION QUI N'EST PAS SENSE ETRE LA

if (!isset($_SESSION['tour'])) {
    $_SESSION['tour'] = 0;
}
if (!isset($_SESSION['hal'])) {
    $_SESSION['hal'] = NULL;
}
if (!isset($_SESSION['joueur'])) {
    $_SESSION['joueur'] = NULL;
}
if (!isset($_SESSION['nombre_de_victoire'])) {
    $_SESSION['nombre_de_victoire'] = 0;
}
if (!isset($_SESSION['nombre_de_defaite'])) {
    $_SESSION['nombre_de_defaite'] = 0;
}

$player = $_POST['choix'] ?? NULL;
$choix_hal = NULL;

$choix = array(
    'pierre',
    'feuille',
    'ciseaux',
);

$rand = array_rand($choix); //Creation d'un tableau puis array_rand tableau pour le random

$position = ($_SESSION['tour']) % 5;
if ($position == 0) {
    $choix_hal = $choix[$rand]; //Tour 1 
} elseif ($position == 1) {
    if ($_SESSION['joueur'][$_SESSION['tour'] - 1] == 'pierre')
        $choix_hal = 'feuille';
    elseif ($_SESSION['joueur'][$_SESSION['tour'] - 1] == 'feuille')
        $choix_hal = 'ciseaux';
    else
        $choix_hal == 'pierre'; //Tour 2
} elseif ($position == 2) {
    $choix_hal = $_SESSION['hal'][$_SESSION['tour'] - 2]; //Tour 3
} elseif ($position == 3) {
    if ($_SESSION['hal'][$_SESSION['tour'] - 1] == 'pierre' and $_SESSION['hal'][$_SESSION['tour'] - 2] == 'pierre')
        $choix_hal = 'feuille' or 'ciseaux';
    elseif ($_SESSION['hal'][$_SESSION['tour'] - 1] == 'feuille' and $_SESSION['hal'][$_SESSION['tour'] - 2] == 'feuille')
        $choix_hal = 'pierre' or 'ciseaux';
    elseif ($_SESSION['hal'][$_SESSION['tour'] - 1] == 'ciseaux' and $_SESSION['hal'][$_SESSION['tour'] - 2] == 'ciseaux')
        $choix_hal = 'pierre' or 'feuille';
    elseif ($_SESSION['hal'][$_SESSION['tour'] - 1] == 'pierre' and $_SESSION['hal'][$_SESSION['tour'] - 2] == 'feuille')
        $choix_hal = 'ciseaux';
    elseif ($_SESSION['hal'][$_SESSION['tour'] - 1] == 'pierre' and $_SESSION['hal'][$_SESSION['tour'] - 2] == 'ciseaux')
        $choix_hal = 'feuille';
    else
        $choix_hal = 'pierre'; //Tour 4 //TODO: Créer un tableau ou l'on mets toutes les possibilités puis retirer celles qui sont déja sortis
} elseif ($position == 4) {
    $choix_hal = $_SESSION['joueur'][$_SESSION['tour'] - 1]; //Tour 5
} else {
    $choix_hal = "ERREUR";
}
// creer un tab [feuille => pierre]
// $tab[$player] == $choix_hal

if ($player) { //Incrémenter les sessions après avoir fait un choix
    $_SESSION['joueur'][] = $player;
    $_SESSION['tour']++;
    $_SESSION['hal'][] = $choix_hal;
}


var_dump($_SESSION);
var_dump($choix_hal);








echo "On est au tour " . $_SESSION['tour'] . "<br>";

if ($player == 'pierre' and $choix_hal == 'ciseaux' or $player == 'feuille' and $choix_hal == 'pierre' or $player == 'ciseaux' and $choix_hal == 'feuille')
    $_SESSION['nombre_de_victoire'] = $_SESSION['nombre_de_victoire'] + 1;
if ($player == 'pierre' and $choix_hal == 'feuille' or $player == 'feuille' and $choix_hal == 'ciseaux' or $player == 'ciseaux' and $choix_hal == 'pierre')
    $_SESSION['nombre_de_defaite'] = $_SESSION['nombre_de_defaite'] + 1;

if (isset($_POST['reset_des_tentatives']))
    $_SESSION['tour'] = 0;

if (isset($_POST['reset_des_victoires']))
    $_SESSION['nombre_de_victoire'] = 0;

if (isset($_POST['reset_des_defaites']))
    $_SESSION['nombre_de_defaite'] = 0;

if (isset($_POST['tout_reset']))
    $_SESSION['nombre_de_defaite'] = 0;
if (isset($_POST['tout_reset']))
    $_SESSION['tour'] = 0;
if (isset($_POST['tout_reset']))
    $_SESSION['nombre_de_victoire'] = 0;
if (isset($_POST['tout_reset']))
    session_destroy();


if (($player == 'pierre' and $choix_hal == 'ciseaux') or ($player == 'feuille' and $choix_hal == 'pierre') or ($player == 'ciseaux' and $choix_hal == 'feuille'))
    $message_de_victoire = "Victoire";
elseif ($player == $choix_hal)
    $message_de_victoire = "Egalité";
else
    $message_de_victoire = "Perdu";

echo $message_de_victoire . ' <br>' . 'Nombre de tentatives : ' . $_SESSION['tour'] . '<br>' . 'Nombre de victoire : ' . $_SESSION['nombre_de_victoire'] . '<br>' . 'Nombre de défaites : ' . $_SESSION['nombre_de_defaite'];

//CONSEIL !! Déclarer des constantes
//define (Pierre,"Pierre")
//PIERRE
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shifumi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <form method="POST" action="#">
        <!-- TODO : Faire en sorte que les bouttons soit enable avec le boutton rejouer et disable avec les 3 bouttons choix / sinon faire en sorte que les bouttons choix apparaisse seulement si on clique sur jouer -->
        <button type="submit" class="btn btn-primary" id="pierre" name="choix" value="pierre">Pierre</button>
        <button type="submit" class="btn btn-primary" id="feuille" name="choix" value="feuille">Feuille</button>
        <button type="submit" class="btn btn-primary" id="ciseaux" name="choix" value="ciseaux">Ciseaux</button>
        <button type="submit" class="btn btn-primary" id="rejouer" name="rejouer">REJOUER</button>
        <button type="submit" class="btn btn-primary" id="fini" name="fini">FINI</button>
        <button type="submit" class="btn btn-primary" id="reset_des_tentatives" name="reset_des_tentatives">RESET DES TENTATIVES</button>
        <button type="submit" class="btn btn-primary" id="reset_des_victoires" name="reset_des_victoires">RESET DES VICTOIRES</button>
        <button type="submit" class="btn btn-primary" id="reset_des_defaites" name="reset_des_defaites">RESET DES DEFAITES</button>
        <button type="submit" class="btn btn-primary" id="tout_reset" name="tout_reset">RESET TOUT</button>
    </form>
</body>

</html>