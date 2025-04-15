<div class="px-3">
    <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="p-2">
            <!-- Formulaire de filtres -->
            <form method="get" class="space-y-6">
                <input type="hidden" name="controllers" value="etudiant">
                <input type="hidden" name="page" value="cours">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Filtre Date -->
                    <div class="space-y-2">
                        <label class=" text-sm font-medium text-gray-700 flex items-center">
                            <i class="ri-calendar-line mr-2 text-indigo-500"></i>
                            Période
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input
                                    type="date"
                                    name="date_debut"
                                    value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="ri-calendar-event-line"></i>
                                </span>
                            </div>
                            <div class="relative">
                                <input
                                    type="date"
                                    name="date_fin"
                                    value="<?= htmlspecialchars($_GET['date_fin'] ?? '') ?>"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="ri-calendar-event-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="ri-pie-chart-line  mr-2 text-indigo-500"></i>
                            Semestre</label>
                        <select name="semestre" class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous</option>
                            <?php foreach ($semestres as $semestre): ?>
                                <option value="<?= $semestre['id_semestre'] ?>" <?= ($_GET['id_semestre'] ?? '') == $semestre['id_semestre'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semestre['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Filtre Statut -->
                    <div class="space-y-2">
                        <label class=" text-sm font-medium text-gray-700 flex items-center">
                            <i class="ri-time-line mr-2 text-indigo-500"></i>
                            Statut du cours
                        </label>
                        <div class="relative">
                            <select
                                name="statut"
                                class="w-full pl-10 pr-3 py-2 appearance-none border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                                <option value="">Tous les statuts</option>
                                <option value="planifié" <?= ($_GET['statut'] ?? '') === 'planifié' ? 'selected' : '' ?>>Planifié</option>
                                <option value="effectué" <?= ($_GET['statut'] ?? '') === 'effectué' ? 'selected' : '' ?>>Effectué</option>
                                <option value="annulé" <?= ($_GET['statut'] ?? '') === 'annulé' ? 'selected' : '' ?>>Annulé</option>
                            </select>
                            <span class="absolute left-3 top-2.5 text-gray-400">
                                <i class="ri-arrow-down-s-line"></i>
                            </span>
                        </div>
                    </div>
                    <!-- Filtre annee  -->
                    <div class="space-y-2">
                        <label class=" text-sm font-medium text-gray-700 flex items-center">
                            <i class="ri-time-line mr-2 text-indigo-500"></i>
                            Annee scolaire actuelle
                        </label>
                        <div class="relative">
                            <input type="text" class="input bg-blue-50" value="<?= $active_annees_scolaires["libelle"] ?>" readonly>
                        </div>
                    </div>
                </div>
                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="<?= ROOT_URL ?>?controllers=etudiant&page=cours" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="ri-close-line mr-2"></i>
                        Réinitialiser
                    </a>
                    <button
                        type="submit"
                        class="flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all shadow-sm">
                        <i class="ri-filter-line mr-2"></i>
                        Appliquer les filtres
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white shadow-sm rounded p-3 mt-3">
        <div class="overflow-x-auto">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>Professeur</th>
                        <th>Module</th>
                        <th>Semestre</th>
                        <th>Salle</th>
                        <th>Date cours</th>
                        <th>Debut</th>
                        <th>Fin</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($coursSuivit)): ?>
                        <?php foreach ($coursSuivit as $c) : ?>
                            <!-- row 1 -->
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="mask mask-squircle h-12 w-12">
                                                <img
                                                    src="uploads/professeur/<?= $c["avatar"] ?>"
                                                    alt="Avatar Tailwind CSS Component" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold"><?= $c["professeur"] ?></div>
                                            <div class="text-sm opacity-50"><?= $c["specialite"] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft badge-neutral"><?= $c["module"] ?></span>
                                </td>
                                <td><?= $c["semestre"] ?></td>
                                <td><?= $c["salle"] ?></td>
                                <td><?= $c["date_cours"] ?></td>
                                <td><?= $c["heure_debut"] ?></td>
                                <td><?= $c["heure_fin"] ?></td>
                                <td>
                                    <?php if ($c["statut"] == "planifié"): ?>
                                        <span class="badge badge-soft badge-success"><?= $c["statut"] ?></span>
                                    <?php elseif ($c["statut"] == "effectué"): ?>
                                        <span class="badge badge-soft badge-primary"><?= $c["statut"] ?></span>
                                    <?php elseif ($c["statut"] == "annulé"): ?>
                                        <span class="badge badge-soft badge-danger"><?= $c["statut"] ?></span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <img src="assets/recherche.png" alt="Aucun étudiant" class="">
                                    <div class="text-gray-500 text-sm font-medium">
                                        Aucune cors disponibles
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= renderPagination($pagination, array_merge(['controllers' => 'etudiant', 'page' => 'cours'])) ?>
        </div>
    </div>
</div>