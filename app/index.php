<?php
include "./header.php"
?>

<body class="h-14 bg-linear-to-r from-cyan-500 to-blue-500">
  <nav class="bg-white/15">
    <div class="max-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex justify-between w-full">
          <span></span>
          <div class="flex items-baseline space-x-4">
            <!--Button modale rules-->
            <button command="show-modal" commandfor="rules" class="text-gray-600 hover:bg-white/50 hover:text-black px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:cursor-pointer">Régle</button>
            <!--Button modale Leaderboard-->
            <button command="show-modal" commandfor="leaderboard" class="text-gray-600 hover:bg-white/50 hover:text-black px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:cursor-pointer">Classement</button>
            <!---->
            <div>
              <el-dropdown class="relative">
                <button class="text-gray-600 hover:bg-white/50 hover:text-black px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:cursor-pointer">Mode</button>
                <el-menu anchor="bottom end" popover class="w-35 rounded-md bg-blue-600/40 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(1)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                  <button class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">Classique</button>
                  <button
                    class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">Spécial</button>
                </el-menu>
              </el-dropdown>
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
                  <button command="show-modal" commandfor="dialog" class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">Profile</button>
                  <!--Button modale connection-->
                  <button command="show-modal" commandfor="login" class="w-full block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:text-white focus:outline-hidden hover:cursor-pointer">Se connecter</button>
                </div>
              </el-menu>
            </el-dropdown>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <div class="flex items-center justify-center p-6 mt-10">
    <div class="max-w-4xl w-full">
      <div class="rounded-xl p-8 bg-white/90 backdrop-blur-sm shadow-2xl">
        <div class="text-center">
          <p class="text-4xl text-gray-900 mb-4">Shifumi</p>
        </div>
        <div class="flex justify-around mb-8 bg-gray-100/100 rounded-xl p-4">
          <div class="text-center" id="player">
            <p class="text-sm text-black mb-3">vous</p>
            <p class="text-3x1 font-bold text-blue-700">0</p>
          </div>
          <div class="text-center" id="robot">
            <p class="text-sm text-black mb-3">robot</p>
            <p class="text-sm font-bold text-red-600">0</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4 mb-6">
          <button command="show-modal" commandfor="resultat-manche" class="hover:cursor-pointer bg-gradient-to-br from-stone-400 to-stone-700 hover:from-stone-700 hover:to-stone-900 text-white rounded-xl p-6 transform hover:scale-105 transition shadow-lg">
            <span class="text-6xl block mb-2">🪨</span>
            <span class="text-xl font-semibold">Pierre</span>
          </button>

          <button command="show-modal" commandfor="resultat-manche" class="hover:cursor-pointer bg-gradient-to-br from-green-400 to-green-700 hover:from-green-600 hover:to--900 text-white rounded-xl p-6 transform hover:scale-105 transition shadow-lg">
            <span class="text-6xl block mb-2">🍃​</span>
            <span class="text-xl font-semibold">Feuille</span>
          </button>

          <button command="show-modal" commandfor="resultat-manche" class="hover:cursor-pointer bg-gradient-to-br from-blue-400 to-blue-700 hover:from-blue-600 hover:to-blue-900 text-white rounded-xl p-6 transform hover:scale-105 transition shadow-lg">
            <span class="text-6xl block mb-2">​✂️​</span>
            <span class="text-xl font-semibold">Ciseaux</span>
          </button>
        </div>
        <div class="flex gab-3">
          <button class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 hover:cursor-pointer px-6 rounded-xl transition-colors transform hover:scale-105">reset</button>
        </div>
      </div>
    </div>
  </div>
</body>
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
<!--end modal leaderboard-->
<!--modal connection-->
<el-dialog>
  <dialog id="login" aria-labelledby="dialog-title"
    class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop
      class="fixed inset-0 bg-black/90 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
    <div tabindex="0"
      class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
      <el-dialog-panel
        class="relative transform overflow-hidden rounded-xl bg-gray-800 text-left shadow-2xl shadow-gray-900/50 outline-none transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-md data-closed:sm:translate-y-0 data-closed:sm:scale-95">
        <div class="bg-gray-800 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
              <h3 id="dialog-title" class="text-xl font-bold text-white">Connexion</h3>
              <div class="mt-4">
                <div class="flex-grow border-t border-gray-700 mb-6"></div>
                <form action="#" class="space-y-6">
                  <div>
                    <label for="Identifiant" class="block mb-2 text-sm font-medium text-gray-300">Identifiant</label>
                    <input type="text" id="Identifiant"
                      class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder:text-gray-400"
                      placeholder="Zengorax" required />
                  </div>
                  <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-300">Mot de
                      passe</label>
                    <input type="password" id="password"
                      class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder:text-gray-400"
                      placeholder="•••••••••" required />
                  </div>
                  <div class="flex items-start"></div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 rounded-b-xl">
          <button type="button" command="close" commandfor="login"
            class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-700 sm:ml-3 sm:w-auto hover:cursor-pointer">
            Se connecter
          </button>
        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal connection-->
<!--modal Rule-->
<el-dialog>
  <dialog id="rules" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
    <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
      <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
        <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 id="dialog-title" class="text-base font-semibold text-white">Régle</h3>
              <div class="mt-4">
                <div class="flex-grow border-t border-gray-400 mb-4"></div>
                <p class="text-sm text-gray-400">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Praesentium recusandae, commodi ducimus, saepe quasi fugiat voluptas reiciendis neque, porro repellendus quas quidem! Tenetur quis dolore eaque commodi esse vitae blanditiis.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-700/25 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" command="close" commandfor="rules" class="inline-flex w-full justify-center rounded-md bg-blue-500 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-400 sm:ml-3 sm:w-auto hover:cursor-pointer">Ok</button>
        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal rule-->
<!--modal screen result-->
<el-dialog>
  <dialog id="resultat-manche" aria-labelledby="resultat-title"
    class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
    <el-dialog-backdrop
      class="fixed inset-0 bg-black/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>
    <div tabindex="0"
      class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
      <el-dialog-panel
        class="relative transform overflow-hidden rounded-xl bg-gray-900 text-left shadow-2xl shadow-gray-900/50 outline-none transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
        <div class="bg-gray-900 px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
          <div class="text-center">
            <h3 id="resultat-title" class="text-4xl font-extrabold text-green-400 mb-6" data-result-type="victoire">
              VICTOIRE !
            </h3>
            <div class="flex justify-around items-center space-x-8 mb-8">
              <div class="text-center">
                <p class="text-lg font-semibold text-gray-300 mb-2">Vous</p>
                <span id="player-move-display" class="text-8xl block">🪨</span>
                <p class="text-xl font-bold text-white mt-2">Pierre</p>
              </div>
              <span class="text-4xl font-extrabold text-gray-500">VS</span>
              <div class="text-center">
                <p class="text-lg font-semibold text-gray-300 mb-2">Robot</p>
                <span id="robot-move-display" class="text-8xl block">🍃</span>
                <p class="text-xl font-bold text-white mt-2">Feuille</p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-800 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 rounded-b-xl">
          <button type="button" command="close" commandfor="resultat-manche"
            class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 sm:w-auto hover:cursor-pointer">
            Continuer
          </button>
        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
<!--end modal screen result-->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

</html>