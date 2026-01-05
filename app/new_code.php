<?php
session_start();

define('PIERRE', 'pierre');
define('FEUILLE', 'feuille');
define('CISEAUX', 'ciseaux');
define('SPOCK', 'spock');
define('LEZARD', 'lezard');

$position = $_SESSION['tour'] % 5;
$regle = [
    PIERRE => [FEUILLE, SPOCK],
    FEUILLE => [CISEAUX, LEZARD],
    CISEAUX => [PIERRE, SPOCK],
    SPOCK => [FEUILLE, LEZARD],
    LEZARD => [PIERRE, CISEAUX]
];
$choix = [
    PIERRE,
    FEUILLE,
    CISEAUX,
    SPOCK,
    LEZARD
];

if ($position == 0) {
    $hal = $choix[array_rand($choix)];
} elseif ($position == 1) {
    $dernier_coup = end($_SESSION['joueur']);
    $possibilite = $regle[$dernier_coup];
    $hal = $possibilite[array_rand($possibilite)];
} elseif ($position == 2) {
    $tour1 = $_SESSION['tour'] - 2;
    $hal = $_SESSION['hal'][$tour1];
} elseif ($position == 3) {
    $historique_hal = $_SESSION['hal'] ?? [];
    $compte = array_count_values($historique_hal);

    foreach ($choix as $nom_choix) {
        if (!isset($compte[$nom_choix])) {
            $compte[$nom_choix] = 0;
        }
    }
    asort($compte);
    $hal = array_key_first($compte);
} else {
    $tour4 = $_SESSION['tour'] - 1;
    $hal = $_SESSION['joueur'][$tour4];
}
