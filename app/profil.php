<?php
session_start();
include "./header.php";
require_once "config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT nb_victoire, nb_tour, timestamp FROM shifumi WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $stats = false;
}
?>

<body class="bg-gradient-to-br from-gray-950 via-gray-700 to-gray-400 min-h-screen">

    <div class="flex items-center justify-center py-10">
        <div class="bg-[#2E323F] w-full max-w-3xl flex flex-col items-center rounded-4xl shadow-2xl p-10">

            <h1 class="text-white text-3xl font-medium uppercase">Profil de <?= htmlspecialchars($_SESSION['username']); ?></h1>

            <img src="https://i.pinimg.com/1200x/a5/0f/6e/a50f6ee9652c2acbe120e5adf60a3e4f.jpg"
                alt="photo de profil"
                class="h-32 w-32 rounded-full object-cover mt-5 shadow-lg border-2 border-white/10" />

            <button command="show-modal" commandfor="profil_image"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-xl transition transform hover:scale-105 mt-5 cursor-pointer">
                Changer la photo
            </button>

            <div class="mt-12 w-full">
                <h2 class="text-white text-2xl font-medium text-center mb-6">Tes statistiques personnelles</h2>

                <?php if ($stats) : ?>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                        <div class="bg-[#4D586F] p-4 rounded-xl shadow-inner text-white border border-white/5">
                            <p class="text-xs uppercase tracking-widest text-gray-300 mb-2">Victoires</p>
                            <p class="text-[#1DD347] text-3xl font-black"><?= $stats['nb_victoire']; ?></p>
                        </div>

                        <div class="bg-[#4D586F] p-4 rounded-xl shadow-inner text-white border border-white/5">
                            <p class="text-xs uppercase tracking-widest text-gray-300 mb-2">Tours joués</p>
                            <p class="text-blue-300 text-3xl font-black"><?= $stats['nb_tour']; ?></p>
                        </div>

                        <div class="bg-[#4D586F] p-4 rounded-xl shadow-inner text-white border border-white/5">
                            <p class="text-xs uppercase tracking-widest text-gray-300 mb-2">Dernière partie</p>
                            <p class="text-gray-300 text-sm font-medium mt-2">
                                <?= date('d/m/Y H:i', strtotime($stats['timestamp'])); ?>
                            </p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bg-white/5 p-6 rounded-2xl border border-dashed border-white/20 text-center">
                        <p class="text-white/60 italic">Tu n'as pas encore de parties enregistrées sur ton compte.</p>
                    </div>
                <?php endif; ?>
            </div>

            <a href="index.php" class="mt-10 text-white/50 hover:text-white text-sm transition">
                ← Retour au jeux
            </a>
        </div>
    </div>


</body>

</html>