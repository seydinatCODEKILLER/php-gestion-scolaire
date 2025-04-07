<div class="px-3">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <!-- Cartes statistiques -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-blue-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-book-line text-blue-500 font-medium"></i>
                </div>
                <p class="text-blue-500 font-medium text-lg">Classe gerer</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $nb_classes ?></p>
                <span class="text-gray-400 font-medium">Total de vos classes</span>
            </div>
        </div>
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-orange-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-user-unfollow-line text-orange-500 font-medium"></i>
                </div>
                <p class="text-orange-500 font-medium text-lg">Nombre d'etudiants</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $nb_etudiants ?></p>
                <span class="text-gray-400 font-medium">Total de vos etudiants</span>
            </div>
        </div>
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-green-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-time-line text-green-500 font-medium"></i>
                </div>
                <p class="text-green-500 font-medium text-lg">Justifications en attente</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $justifications_en_attente ?></p>
                <span class="text-gray-400 font-medium">Total justifications</span>
            </div>
        </div>
    </div>

    <!-- Dernières absences -->
    <div class="overflow-x-auto mt-12">
        <h1 class="text-gray-400 font-medium text-lg">Les absences les plus recentes</h1>

        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prenom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($recentAbsences as $r): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-2 whitespace-nowrap"><?= $r['nom'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $r['prenom'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($r['date_absence'])) ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $r['classe'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?= $r['module'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>