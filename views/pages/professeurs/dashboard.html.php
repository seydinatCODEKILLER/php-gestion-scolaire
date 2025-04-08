<div class="px-3 mt-5">
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <!-- Carte 1 : Nombre total de cours -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-blue-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-book-line text-blue-500 font-medium"></i>
                </div>
                <p class="text-blue-500 font-medium text-lg">Cours Total</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $stats['nb_cours'] ?? 0 ?></p>
                <span class="text-gray-400 font-medium">Total de vos cours</span>
            </div>
        </div>

        <!-- Carte 2 : Heures enseignées -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-green-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-time-line text-green-500 font-medium"></i>
                </div>
                <p class="text-green-500 font-medium text-lg">Heures enseignées</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $stats['heures_enseignees'] ?? 0 ?></p>
                <span class="text-gray-400 font-medium">Heures cette année</span>
            </div>
        </div>

        <!-- Carte 3 : Taux d'absence moyen -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-orange-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-user-unfollow-line text-orange-500 font-medium"></i>
                </div>
                <p class="text-orange-500 font-medium text-lg">Absences moyennes</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $stats['taux_absence'] ?? 0 ?>%</p>
                <span class="text-gray-400 font-medium">Par cours</span>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et tableaux -->
<div class="px-3 mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Graphique en courbe pour les absences par module -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-3">
            <p class="text-gray-600">Absences par module</p>
            <canvas id="absencesChart" height="250"></canvas>
        </div>

        <!-- Tableau des top absents -->
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-3">
            <p class="text-gray-600">Top 5 des étudiants les plus absents</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Absences</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heures manquées</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach (($top_absents ?? []) as $etudiant): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $etudiant['nom'] ?? '' ?> <?= $etudiant['prenom'] ?? '' ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $etudiant['nb_absences'] ?? 0 ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $etudiant['heures_manquees'] ?? 0 ?>h</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const absencesCtx = document.getElementById('absencesChart')?.getContext('2d');
    if (absencesCtx) {
        new Chart(absencesCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($absences_par_module ?? [], 'libelle')) ?>,
                datasets: [{
                    label: 'Nombre d\'absences',
                    data: <?= json_encode(array_column($absences_par_module ?? [], 'nb_absences')) ?>,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const moy = <?= json_encode(array_column($absences_par_module ?? [], 'moyenne_absences')) ?>[context.dataIndex];
                                return moy ? `Moyenne: ${moy.toFixed(1)} absences/cours` : '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'absences'
                        }
                    }
                }
            }
        });
    }
</script>