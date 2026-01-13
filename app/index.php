<script>
  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
</script>
<?php



session_start();
include "./config.php";
include "./header.php";
date_default_timezone_set('Europe/Paris');

if (isset($_POST['login_submit'])) {
  $user = trim($_POST['username']);
  $pass = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$user]);
  $db_user = $stmt->fetch();

  if ($db_user && password_verify($pass, $db_user['password'])) {
    $_SESSION['user_id'] = $db_user['id'];
    $_SESSION['username'] = $db_user['username'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } else {
    $error = "Identifiants incorrects.";
  }
}

if (isset($_POST['register_submit'])) {
  $user = trim($_POST['username']);
  $pass = $_POST['password'];
  $conf_pass = $_POST['conf_password'];


  if (empty($user) || empty($pass)) {
    $error = "Veuillez remplir tous les champs.";
  } elseif ($pass !== $conf_pass) {
    $error = "Les mots de passe ne sont pas identiques.";
  } else {

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {

      $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      $stmt->execute([$user, $hashed_password]);


      $_SESSION['user_id'] = $pdo->lastInsertId();
      $_SESSION['username'] = $user;

      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
        $error = "Ce nom d'utilisateur est déjà pris.";
      } else {
        $error = "Une erreur est survenue lors de l'inscription.";
      }
    }
  }
}

$isLoggedIn = isset($_SESSION['user_id']);

if (!isset($_SESSION['mode'])) {
  $_SESSION['mode'] = null;
}

if (isset($_POST['select_mode'])) {
  $_SESSION['mode'] = $_POST['select_mode'];
}

//Mise en place des constantes
define('PIERRE',  'pierre');
define('FEUILLE', 'feuille');
define('CISEAUX', 'ciseaux');
define('LEZARD',  'lezard');
define('SPOCK',   'spock');

//set de la session a 0
if (!isset($_SESSION['tour'])) $_SESSION['tour'] = 0;
if (!isset($_SESSION['hal'])) $_SESSION['hal'] = [];
if (!isset($_SESSION['joueur'])) $_SESSION['joueur'] = [];
if (!isset($_SESSION['nombre_de_victoire'])) $_SESSION['nombre_de_victoire'] = 0;
if (!isset($_SESSION['nombre_de_defaite'])) $_SESSION['nombre_de_defaite'] = 0;
if (!isset($_SESSION['nombre_d_egalite'])) $_SESSION['nombre_d_egalite'] = 0;
if (!isset($_SESSION['debut_partie'])) {
  $_SESSION['debut_partie'] = date('H:i');
}

//Définition des victoires
$regles = [
  PIERRE  => [CISEAUX, LEZARD],
  FEUILLE => [PIERRE, SPOCK],
  CISEAUX => [FEUILLE, LEZARD],
  LEZARD  => [SPOCK, FEUILLE],
  SPOCK   => [CISEAUX, PIERRE],
];

//Set des possibilité en fonction du mode utilisé
$choix_possibles = [PIERRE, FEUILLE, CISEAUX];
if ($_SESSION['mode'] === 'special') {
  $choix_possibles = [PIERRE, FEUILLE, CISEAUX, LEZARD, SPOCK];
}


//Tableau des faiblesses
$contre_complet = [
  PIERRE  => [FEUILLE, SPOCK],
  FEUILLE => [CISEAUX, LEZARD],
  CISEAUX => [PIERRE, SPOCK],
  SPOCK   => [FEUILLE, LEZARD],
  LEZARD  => [PIERRE, CISEAUX]
];

//Anti Cheat pour Hal
$contre = [];
foreach ($contre_complet as $signe => $reponses) {
  $contre[$signe] = array_intersect($reponses, $choix_possibles);
}



$player = $_POST['choix'] ?? NULL;

if ($player) {
  //calcule du cycle
  $position = $_SESSION['tour'] % 5;
  $hal = NULL;
  //tour 1 Hal utilise aléatoirement l'un des choix possibles.
  if ($position == 0) {
    $hal = $choix_possibles[array_rand($choix_possibles)];
    //tour 2 Hal prend en compte le choix du joueur lors du premier tour et le contre.
  } elseif ($position == 1) {
    $dernier_coup_joueur = end($_SESSION['joueur']);
    $possibilites = $contre[$dernier_coup_joueur] ?? [PIERRE];
    $hal = $possibilites[array_rand($possibilites)];
    //tour 3 Hal utilise le choix qu'il a utilisée lors du premier tour
  } elseif ($position == 2) {
    $tour2 = $_SESSION['tour'] - 2;
    $hal = $_SESSION['hal'][$tour2] ?? $choix_possibles[array_rand($choix_possibles)];
    //tour 4 Hal compte le nombre d'utilisation des choix et utilise l'un des choix les moins utilisés.
  } elseif ($position == 3) {
    // initiation du compteur de choix 
    $compte = [];
    foreach ($choix_possibles as $nom) {
      $compte[$nom] = 0;
      //chaque choix est set a 0
    }
    foreach ($_SESSION['hal'] as $coup) {
      if (isset($compte[$coup])) $compte[$coup]++;
      //pour chaque choix utilisé précédemment, ajoute +1
    }
    //trie de façon croisante le tableau de compte
    asort($compte);
    //recuperation de la possition 0 du tableau
    $hal = array_key_first($compte);
    //tour 5 Hal utilise le choix que le joueur a fait au tour précédent 
  } else {
    $tour_precedent = $_SESSION['tour'] - 1;
    $hal = $_SESSION['joueur'][$tour_precedent] ?? PIERRE;
  }

  $_SESSION['joueur'][] = $player;
  $_SESSION['hal'][] = $hal;
  $_SESSION['tour']++;

  //Calcule des victoires, égalités, défaites.
  if ($player == $hal) {
    $message = "Égalité";
    $_SESSION['nombre_d_egalite']++;
  } elseif (in_array($hal, $regles[$player])) {
    $message = "Victoire";
    $_SESSION['nombre_de_victoire']++;
  } else {
    $message = "Perdu";
    $_SESSION['nombre_de_defaite']++;
  }

  $_SESSION['dernier_message'] = $message;
  $_SESSION['dernier_coup_joueur'] = $player;
  $_SESSION['dernier_coup_robot'] = $hal;
  $_SESSION['taux_actuel'] = $taux_reussite;

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

//Envois des données pour la BDD
if (isset($_POST['reset'])) {
  $ip_adresse = $_SERVER['REMOTE_ADDR'];
  $tour_totals = $_SESSION['nombre_de_victoire'] + $_SESSION['nombre_de_defaite'] + $_SESSION['nombre_d_egalite'];

  $taux_reussite = 0;
  if ($tour_totals > 0) {
    $taux_reussite = round(($_SESSION['nombre_de_victoire'] / $tour_totals) * 100, 2);
  }

  try {
    $sql = "INSERT INTO stats_visiteurs (ip_address, victoires, defaites, egalites, tours_joues, taux_reussite) 
                VALUES (:ip, :vic, :def, :ega, :tours, :taux)
                ON DUPLICATE KEY UPDATE 
                victoires = victoires + :vic, 
                defaites = defaites + :def, 
                egalites = egalites + :ega,
                tours_joues = tours_joues + :tours, 
                taux_reussite = ((victoires + :vic) / (tours_joues + :tours)) * 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':ip'    => $ip_adresse,
      ':vic'   => $_SESSION['nombre_de_victoire'],
      ':def'   => $_SESSION['nombre_de_defaite'],
      ':ega'   => $_SESSION['nombre_d_egalite'],
      ':tours' => $tour_totals,
      ':taux'  => $taux_reussite
    ]);
  } catch (PDOException $e) {
    error_log("Erreur SQL lors du Reset : " . $e->getMessage());
  }

  unset(
    $_SESSION['mode'],
    $_SESSION['tour'],
    $_SESSION['taux_actuel'],
    $_SESSION['hal'],
    $_SESSION['joueur'],
    $_SESSION['nombre_d_egalite'],
    $_SESSION['nombre_de_victoire'],
    $_SESSION['nombre_de_defaite'],
    $_SESSION['debut_partie'],
    $_SESSION['last_shown']
  );

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}


if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$emojis = [PIERRE => "🪨", FEUILLE => "🍃", CISEAUX => "✂️", LEZARD => "🦎", SPOCK => "🖖"];
?>

<?php if ($_SESSION['mode'] === null): ?>
  <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-md rounded-2xl bg-gray-900 p-8 text-center shadow-2xl border border-white/10">
      <h2 class="text-3xl font-extrabold text-white mb-8">Configuration</h2>
      <form method="POST" class="space-y-4">
        <button type="submit" name="select_mode" value="classique" class="group w-full py-4 bg-gray-800 hover:bg-blue-600 rounded-xl border border-white/5 transition-all hover:cursor-pointer">
          <span class="text-lg font-bold text-white">Mode Classique</span>
        </button>
        <button type="submit" name="select_mode" value="special" class="group w-full py-4 bg-gray-800 hover:bg-purple-600 rounded-xl border border-white/5 transition-all hover:cursor-pointer">
          <span class="text-lg font-bold text-white">Mode Spécial</span>
        </button>
      </form>
    </div>
  </div>
<?php endif; ?>

<body class="h-14 bg-linear-to-r from-cyan-500 to-blue-500">
  <nav class="bg-white/15">
    <div class="max-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex justify-between w-full">
          <span></span>
          <div class="flex items-baseline space-x-4">
            <!--Button modale rules-->
            <button command="show-modal" commandfor="rules" class="text-white hover:bg-white/50 hover:text-black px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:cursor-pointer">Régle</button>
            <div class="flex items-center space-x-6">
              <div class="flex items-center">
                <div class="text-White px-3 py-2 text-sm font-medium border border-white/10 rounded-l-md bg-white/5 border-r-0">
                  Mode : <span class="text-white uppercase"><?= $_SESSION['mode'] ?? '...' ?></span>
                </div>
                <div class="text-White px-3 py-2 text-sm font-medium border border-white/10 rounded-r-md bg-white/5">
                  Début : <span class="text-white ml-1"><?= $_SESSION['debut_partie'] ?></span>
                </div>
              </div>
            </div>
          </div>
          <div>
            <el-dropdown class="relative">
              <button class="relative border hover:cursor-pointer border-white flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transform hover:scale-110">
                <span class="absolute -inset-1.5"></span>
                <img src="https://imgs.search.brave.com/1Zem1JG9j4rTqtM5HpbtvJ50uYCdd-7c4Zq6yoYM4N8/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmlt/Z2ZsaXAuY29tLzQv/M3UwNGg1LmpwZw" alt="" class="size-8 rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
              </button>
              <el-menu anchor="bottom end" popover class="w-56 origin-top-right rounded-md bg-gray-800 outline-1 -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                <div class="py-1">
                  <!--Button modale profil-->
                  <?php if ($isLoggedIn): ?>
                    <a href="./profil.php" class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">
                      Profil</a>
                    <!--Button déconnection-->
                    <form method="POST" action="">
                      <button type="submit" name="logout" class="w-full text-center block px-4 py-2 text-sm text-red-400 font-semibold hover:bg-red-500/10 hover:text-red-300 hover:cursor-pointer">
                        Se déconnecter
                      </button>
                    </form>
                  <?php else: ?>
                    <button command="show-modal" commandfor="login" class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">Se connecter</button>
                  <?php endif; ?>
                </div>
              </el-menu>
            </el-dropdown>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <form method="POST" action="#">
    <div class="flex items-center justify-center p-6 mt-10">
      <div class="max-w-4xl w-full">
        <div class="rounded-xl p-8 bg-white/90 backdrop-blur-sm shadow-2xl">
          <div class="text-center">
            <p class="text-4xl text-gray-900 mb-4">Shifumi</p>
          </div>
          <div class="flex justify-around mb-8 bg-gray-100/100 rounded-xl p-4">
            <div class="text-center" id="player">
              <p class="text-sm text-black mb-3">vous</p>
              <p class="text-3xl font-bold text-blue-700">
                <?= $_SESSION['nombre_de_victoire'] ?>
              </p>
            </div>

            <div class="text-center" id="tour">
              <p class="text-sm text-black mb-3">tour</p>
              <p class="text-3xl font-bold text-gray-800">
                <?= $_SESSION['tour'] ?>
              </p>
            </div>

            <div class="text-center" id="robot">
              <p class="text-sm text-black mb-3">robot</p>
              <p class="text-3xl font-bold text-red-600">
                <?= $_SESSION['nombre_de_defaite'] ?>
              </p>
            </div>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
            <button type="submit" name="choix" value="pierre" class="hover:cursor-pointer bg-gradient-to-br from-stone-400 to-stone-700 hover:from-stone-700 hover:to-stone-900 text-white rounded-xl p-4 sm:p-6 transform hover:scale-105 transition shadow-lg">
              <span class="text-4xl sm:text-6xl block mb-2">🪨</span>
              <span class="text-lg sm:text-xl font-semibold">Pierre</span>
            </button>

            <button type="submit" name="choix" value="feuille" class="hover:cursor-pointer bg-gradient-to-br from-green-400 to-green-700 hover:from-green-600 hover:to-green-900 text-white rounded-xl p-4 sm:p-6 transform hover:scale-105 transition shadow-lg">
              <span class="text-4xl sm:text-6xl block mb-2">🍃</span>
              <span class="text-lg sm:text-xl font-semibold">Feuille</span>
            </button>

            <button type="submit" name="choix" value="ciseaux" class="col-span-2 sm:col-span-1 hover:cursor-pointer bg-gradient-to-br from-blue-400 to-blue-700 hover:from-blue-600 hover:to-blue-900 text-white rounded-xl p-4 sm:p-6 transform hover:scale-105 transition shadow-lg">
              <span class="text-4xl sm:text-6xl block mb-2">✂️</span>
              <span class="text-lg sm:text-xl font-semibold">Ciseaux</span>
            </button>

            <?php if ($_SESSION['mode'] === 'special'): ?>
              <div id="special-container" class="col-span-2 sm:col-span-3 flex flex-row gap-3 sm:gap-4 justify-center">
                <button type="submit" name="choix" value="lezard" class="flex-1 hover:cursor-pointer bg-gradient-to-br from-purple-400 to-purple-700 hover:from-purple-600 hover:to-purple-900 text-white rounded-xl p-4 sm:p-6 transform hover:scale-105 transition shadow-lg">
                  <span class="text-4xl sm:text-6xl block mb-2">🦎</span>
                  <span class="text-lg sm:text-xl font-semibold">Lézard</span>
                </button>

                <button type="submit" name="choix" value="spock" class="flex-1 hover:cursor-pointer bg-gradient-to-br from-yellow-400 to-yellow-600 hover:from-yellow-600 hover:to-yellow-800 text-white rounded-xl p-4 sm:p-6 transform hover:scale-105 transition shadow-lg">
                  <span class="text-4xl sm:text-6xl block mb-2">🖖</span>
                  <span class="text-lg sm:text-xl font-semibold">Spock</span>
                </button>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex gab-3">
            <button type="submit" name="reset" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 hover:cursor-pointer px-6 rounded-xl transition-colors transform hover:scale-105">reset</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</body>
<div class="hidden" aria-hidden="true">
  <span class="text-green-400 text-red-500 text-yellow-400 text-gray-400"></span>
  <span class="bg-green-400 bg-red-500 bg-yellow-400"></span>
</div>
<!--modal leaderboard-->
<el-dialog>
  <dialog id="leaderboard" aria-labelledby="dialog-title"
    class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop
      class="fixed inset-0 bg-black/90 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
    <div tabindex="0"
      class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
      <el-dialog-panel
        class="relative transform overflow-hidden rounded-xl bg-gray-900 text-left shadow-2xl shadow-gray-900/50 outline-none transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
        <div class="bg-gray-900 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
          <div class="text-center sm:text-left">
            <h3 id="dialog-title" class="text-3xl font-extrabold text-white mb-6 flex items-center justify-center">Classement Global</h3>
          </div>
          <div class="overflow-hidden rounded-lg shadow-lg border border-gray-700">
            <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Rang
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Nom d'utilisateur
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Score
                  </th>
                </tr>
              </thead>
              <tbody class="bg-gray-800 divide-y divide-gray-700">
                <tr class="bg-yellow-900/40 font-semibold">
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-white"></td>
                </tr>
                <tr class="bg-gray-700/50 font-semibold">
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-white"></td>
                </tr>
                <tr class="bg-orange-900/40 font-semibold">
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-white"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-white"></td>
                </tr>
                <tr class="bg-blue-900/30 border-t-2 border-blue-600 font-bold">
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-blue-400"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-blue-400"></td>
                  <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-blue-400"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="bg-gray-800 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 rounded-b-xl">
          <button type="button" command="close" commandfor="leaderboard"
            class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 sm:ml-3 sm:w-auto hover:cursor-pointer">
            Fermer
          </button>
        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>

<!--modal connection-->
<el-dialog>
  <dialog id="login" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop class="fixed inset-0 bg-black/90 transition-opacity"></el-dialog-backdrop>
    <div tabindex="0" class="flex min-h-full items-center justify-center p-4 text-center focus:outline-none">
      <el-dialog-panel class="relative transform overflow-hidden rounded-xl bg-gray-800 text-left shadow-2xl sm:my-8 sm:w-full sm:max-w-md w-full">

        <form action="" method="POST">
          <div class="bg-gray-800 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                <h3 id="dialog-title" class="text-xl font-bold text-white">Connexion</h3>
                <div class="mt-4">
                  <div class="flex-grow border-t border-gray-700 mb-6"></div>

                  <div class="space-y-6">
                    <div>
                      <label for="Identifiant" class="block mb-2 text-sm font-medium text-gray-300">Identifiant</label>
                      <input type="text" id="Identifiant" name="username" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Zengorax" required />
                    </div>
                    <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Mot de passe</label>
                      <input type="password" id="password" name="password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="•••••••••" required />
                    </div>
                  </div>

                  <div class="mt-4 text-center">
                    <p class="text-sm text-gray-400">
                      Pas encore de compte ?
                      <button type="button" command="show-modal" commandfor="register" class="text-blue-400 hover:underline">Créer un compte</button>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 rounded-b-xl">
            <button type="submit" name="login_submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-blue-700 sm:ml-3 sm:w-auto">
              Se connecter
            </button>
          </div>
        </form>

      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal connection-->
<!--modal inscription-->
<el-dialog>
  <dialog id="register" aria-labelledby="register-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop class="fixed inset-0 bg-black/90 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

    <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
      <el-dialog-panel class="relative transform overflow-hidden rounded-xl bg-gray-800 text-left shadow-2xl outline-none transition-all sm:my-8 sm:w-full sm:max-w-md">

        <form action="#" method="POST">
          <div class="bg-gray-800 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
            <h3 id="register-title" class="text-xl font-bold text-white">Création du compte</h3>
            <div class="mt-4">
              <div class="flex-grow border-t border-gray-700 mb-6"></div>

              <div class="space-y-4">
                <div>
                  <label for="reg_username" class="block mb-2 text-sm font-medium text-gray-300">Identifiant</label>
                  <input type="text" id="reg_username" name="username" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder:text-gray-400" placeholder="Zengorax" required />
                </div>

                <div>
                  <label for="reg_password" class="block mb-2 text-sm font-medium text-gray-300">Mot de passe</label>
                  <input type="password" id="reg_password" name="password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder:text-gray-400" placeholder="•••••••••" required />
                </div>

                <div>
                  <label for="conf_password" class="block mb-2 text-sm font-medium text-gray-300">Confirmation du Mot de passe</label>
                  <input type="password" id="conf_password" name="conf_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder:text-gray-400" placeholder="•••••••••" required />
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 rounded-b-xl">
            <button type="submit" name="register_submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-blue-500 transition sm:ml-3 sm:w-auto hover:cursor-pointer">
              Créer le compte
            </button>
          </div>
          <?php if (isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-2 rounded-lg text-sm mb-4 text-center">
              <?= $error ?>
            </div>
          <?php endif; ?>
        </form>

      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal inscription-->
<!--modal Rule-->
<el-dialog>
  <dialog id="rules" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"></el-dialog-backdrop>

    <div tabindex="0" class="flex min-h-full items-center justify-center p-4 text-center focus:outline-none">

      <el-dialog-panel class="relative transform overflow-hidden rounded-2xl bg-gray-800 text-left shadow-2xl border border-white/10 transition-all w-full max-w-md sm:max-w-lg">

        <div class="bg-gray-800 px-6 pt-6 pb-5 sm:p-8">
          <div class="sm:flex sm:items-start">
            <div class="w-full text-center sm:text-left">
              <h3 id="dialog-title" class="text-2xl font-bold text-white flex items-center justify-center sm:justify-start gap-2">
                <span></span> Règles du Jeu
              </h3>

              <div class="mt-4">
                <div class="w-full border-t border-gray-700 mb-6"></div>

                <div class="space-y-4 text-gray-300 text-sm sm:text-base leading-relaxed">
                  <p>
                    Le but est de battre le robot en choisissant un signe qui l'emporte sur le sien.
                  </p>
                  <ul class="list-disc list-inside space-y-1 text-gray-400 italic">
                    <li>La <strong>Pierre</strong> écrase les ciseaux.</li>
                    <li>La <strong>Feuille</strong> enveloppe la pierre.</li>
                    <li>Les <strong>Ciseaux</strong> coupent la feuille.</li>
                  </ul>
                  <?php if ($_SESSION['mode'] === 'special'): ?>
                    <p class="pt-2 border-t border-gray-700/50 text-purple-400 font-medium">
                      Mode Spécial : Le lézard et Spock s'ajoutent aux règles classiques !
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-900/50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8">
          <button type="button" command="close" commandfor="rules"
            class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-6 py-3 text-base font-bold text-white shadow-lg hover:bg-blue-500 transition-all active:scale-95 sm:ml-3 sm:w-auto hover:cursor-pointer">
            J'ai compris
          </button>
        </div>

      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal rule-->
<!--modal screen result-->
<el-dialog>
  <dialog id="resultat-manche" aria-labelledby="resultat-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop class="fixed inset-0 bg-black/75 transition-opacity"></el-dialog-backdrop>

    <div tabindex="0" class="flex min-h-full items-center justify-center p-4 text-center focus:outline-none">

      <el-dialog-panel class="relative transform overflow-hidden rounded-xl bg-gray-900 text-left shadow-2xl w-full max-w-sm sm:max-w-lg">
        <div class="bg-gray-900 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
          <div class="text-center">
            <?php
            $res = $_SESSION['dernier_message'] ?? 'En attente...';
            $p_move = $_SESSION['joueur'][array_key_last($_SESSION['joueur'])] ?? '';
            $r_move = $_SESSION['hal'][array_key_last($_SESSION['hal'])] ?? '';
            $colorClass = ($res == 'Victoire') ? 'text-green-400' : (($res == 'Perdu') ? 'text-red-400' : 'text-yellow-400');
            ?>

            <h3 id="resultat-title" class="text-3xl sm:text-4xl font-extrabold mb-6 <?= $colorClass ?>">
              <?= $res ?>
            </h3>

            <div class="flex flex-col sm:flex-row justify-around items-center gap-6 sm:gap-8 mb-8">
              <div class="text-center">
                <p class="text-sm sm:text-lg font-semibold text-gray-400 mb-1">Vous</p>
                <span class="text-7xl sm:text-8xl block">
                  <?= isset($emojis[$p_move]) ? $emojis[$p_move] : '❓' ?>
                </span>
                <p class="text-lg sm:text-xl font-bold text-white mt-1"><?= ucfirst($p_move) ?></p>
              </div>

              <span class="text-2xl sm:text-4xl font-black text-gray-700 sm:text-gray-500 italic">VS</span>

              <div class="text-center">
                <p class="text-sm sm:text-lg font-semibold text-gray-400 mb-1">Robot</p>
                <span class="text-7xl sm:text-8xl block">
                  <?= isset($emojis[$r_move]) ? $emojis[$r_move] : '❓' ?>
                </span>
                <p class="text-lg sm:text-xl font-bold text-white mt-1"><?= ucfirst($r_move) ?></p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 px-6 py-4 rounded-b-xl">
          <button type="button" command="close" commandfor="resultat-manche"
            class="w-full justify-center rounded-lg bg-blue-600 px-4 py-3 text-base font-semibold text-white shadow-md hover:bg-blue-500 hover:cursor-pointer transition">
            Continuer
          </button>
        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal screen result-->
<script>
  const btnSpecial = document.getElementById('btn-special');
  const btnClassique = document.getElementById('btn-classique');

  btnSpecial.addEventListener('click', (e) => {
    e.preventDefault();
    specialContainer.classList.remove('hidden');
    specialContainer.classList.add('flex');
    console.log("Mode Spécial Activé");
  });

  btnClassique.addEventListener('click', (e) => {
    e.preventDefault();
    specialContainer.classList.add('hidden');
    specialContainer.classList.remove('flex');
    console.log("Mode Classique Activé");
  });
</script>
<?php
if ($_SESSION['tour'] > 0) {
  if (!isset($_SESSION['last_shown']) || $_SESSION['last_shown'] < $_SESSION['tour']) {
    echo "<script>
              window.addEventListener('DOMContentLoaded', () => {
                  document.querySelector('#resultat-manche').open = true;
              });
          </script>";
    $_SESSION['last_shown'] = $_SESSION['tour'];
  }
}
?>

</html>