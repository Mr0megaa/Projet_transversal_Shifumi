# Shifumi - Jeu de Pierre-Feuille-Ciseaux

Un jeu de Shifumi moderne et interactif développé en **PHP**, **Tailwind CSS** et **JavaScript**. Ce projet propose un mode classique ainsi qu'un mode spécial incluant le "Lézard" et "Spock", inspiré de la culture populaire.

## Fonctionnalités

- **Deux Modes de Jeu :** _Classique_ : Pierre, Feuille, Ciseaux.
  - _Spécial_ : Ajoute Lézard et Spock pour plus de stratégie.
- **Intelligence Artificielle :** Le robot utilise un algorithme basé sur vos coups précédents (séquence de 5 positions) pour tenter de vous battre.
- **Tableau de Bord en temps réel :**
  - Compteur de victoires (Joueur vs Robot).
  - Compteur de tours dynamique.
  - Heure de début de session (Fuseau horaire : Europe/Paris).
- **Classement & Statistiques :** Enregistrement des statistiques par adresse IP lors de la réinitialisation de la session (via base de données MySQL).
- **Interface Responsive :** Design moderne conçu avec **Tailwind CSS**, entièrement compatible mobile et tablette.

## Technologies utilisées

- **Backend :** PHP 8.x
- **Base de données :** MySQL (PDO)
- **Frontend :** Tailwind CSS, JavaScript (ES6)
- **Gestion de session :** PHP Sessions

## Installation & Configuration

### 1. Prérequis

- Un serveur local (XAMPP, WAMP, MAMP) ou un hébergement distant (type InfinityFree).
- Une base de données MySQL.

### 2. Configuration de la Base de Données

Importez les tables nécessaires dans votre base de données :

```sql
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `stats_visiteurs` (
  `ip_address` varchar(45) NOT NULL PRIMARY KEY,
  `victoires` int(11) DEFAULT 0,
  `defaites` int(11) DEFAULT 0,
  `egalites` int(11) DEFAULT 0,
  `tours_joues` int(11) DEFAULT 0,
  `taux_reussite` decimal(5,2) DEFAULT 0,
  `last_update` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 3. Connexion (config.php)

Créez un fichier config.php à la racine et configurez vos accès :

```PHP

<?php
$host = 'votre_serveur';
$db   = 'votre_bdd';
$user = 'votre_utilisateur';
$pass = 'votre_mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
```

## Auteur

Zengorax
Mr0megaa
giregls
