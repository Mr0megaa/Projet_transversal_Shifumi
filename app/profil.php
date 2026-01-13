<?php
include "./header.php";
require_once "config.php";

$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT nb_victoire, nb_tour, timestamp FROM shifumi WHERE addresse_ip = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ip);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
?>

<body class="bg-gradient-to-br from-gray-950 via-gray-700 to-gray-400 min-h-screen">

    <div class="flex items-center justify-center py-10">
        <div class="bg-[#2E323F] w-full max-w-3xl flex flex-col items-center rounded-4xl shadow-2xl p-10">

            <h1 class="text-white text-3xl font-medium">Profil</h1>

            <img src="https://i.pinimg.com/1200x/a5/0f/6e/a50f6ee9652c2acbe120e5adf60a3e4f.jpg"
                alt="photo de profil"
                class="h-32 w-32 rounded-full object-cover mt-5 shadow-lg" />

            <button command="show-modal" commandfor="profil_image"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-xl transition transform hover:scale-105 mt-5">
                Changer
            </button>
            <div class="mt-12 w-full">
                <h2 class="text-white text-2xl font-medium text-center mb-6">
                    Vos statistiques
                </h2>

                <?php if ($stats) : ?>
                    <div class="grid grid-cols-3 gap-4 text-center">

                        <div class="bg-[#4D586F] p-4 rounded-xl shadow text-white">
                            <p class="text-lg font-semibold">Victoires</p>
                            <p class="text-[#1DD347] text-3xl font-bold"><?= $stats['nb_victoire']; ?></p>
                        </div>

                        <div class="bg-[#4D586F] p-4 rounded-xl shadow text-white">
                            <p class="text-lg font-semibold">Tours joués</p>
                            <p class="text-blue-300 text-3xl font-bold"><?= $stats['nb_tour']; ?></p>
                        </div>

                        <div class="bg-[#4D586F] p-4 rounded-xl shadow text-white">
                            <p class="text-lg font-semibold">Dernière activité</p>
                            <p class="text-gray-300 text-sm"><?= $stats['timestamp']; ?></p>
                        </div>

                    </div>
                <?php else : ?>
                    <p class="text-center text-white mt-6">
                        Aucune statistiques.
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <el-dialog>
        <dialog id="profil_image" aria-labelledby="dialog-title"
            class="fixed inset-0 overflow-y-auto bg-transparent backdrop:bg-transparent">

            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline outline-white/10 transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="grid grid-cols-5 grid-rows-2 gap-4 p-6">
                        <div class="rounded w-40 h-40 overflow-hidden">
                            <img class="object-cover w-full h-full"
                                src="https://en.meming.world/wiki/File:Cursed_Cat.jpg/"
                                alt="image choix">
                        </div>
                    </div>

                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>

</html>