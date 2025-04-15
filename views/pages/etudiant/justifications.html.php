<div class="px-3">
    <div class="p-2 bg-white shadow-sm rounded mt-4">
        <div class="flex items-center justify-between">
            <div class="hidden lg:flex items-center gap-2">
                <div class="w-24 h-24 rounded-full bg-purple-100 flex items-center justify-center">
                    <img src="assets/main.png" alt="" class="object-cover">
                </div>
                <div class="flex flex-col gap-1">
                    <h1 class="font-medium text-2xl">Gerer vos justifications</h1>
                    <p class="text-gray-400 w-96 text-sm font-medium">
                        vous pouvez ici voir, chacune de vos justifications pour chaque cours sur chaque absences
                    </p>
                </div>
            </div>
            <!-- Formulaire de filtres -->
            <form method="get" class="space-y-6 border-b border-gray-50 pb-2">
                <input type="hidden" name="controllers" value="etudiant">
                <input type="hidden" name="page" value="justifications">
                <div class="flex items-center gap-2 justify-end">
                    <!-- Filtre Date -->
                    <div class="">
                        <label class=" text-sm font-medium text-gray-700 flex items-center">
                            <i class="ri-calendar-line mr-2 text-indigo-500"></i>
                            Statut
                        </label>
                        <div class="grid grid-cols-1 gap-3">
                            <select name="statut" class="w-full py-2 px-3 appearance-none border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                                <option value="">Choisir une option</option>
                                <option value="acceptée">Accepter</option>
                                <option value="en attente">En attente</option>
                                <option value="refusée">Refuser</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class=" text-sm font-medium text-gray-700 flex items-center">
                            <i class="ri-calendar-line mr-2 text-indigo-500"></i>
                            Filtres
                        </label>
                        <div class="flex flex-col md:flex-row items-center gap-3">
                            <button
                                type="submit"
                                class="flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all shadow-sm">
                                <i class="ri-filter-line mr-2"></i>
                                Appliquer les filtres
                            </button>
                            <?php if (isset($_GET["statut"])): ?>
                                <a href="<?= ROOT_URL ?>?controllers=etudiant&page=absences" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="ri-close-line mr-2"></i>
                                    Réinitialiser
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des absences -->
        <?php if (!empty($justificationsToShow)): ?>
            <div class="space-y-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-3 gap-3 mt-4">
                <?php foreach ($justificationsToShow as $j): ?>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer inset-0 bg-gradient-to-r from-indigo-50/50 to-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg"><?= htmlspecialchars($j['module_libelle']) ?></h3>
                                <div class="flex items-center gap-2">
                                    <div class="avatar">
                                        <div class="mask mask-squircle h-10 w-10">
                                            <img
                                                src="uploads/professeur/<?= $j["avatar"] ?>"
                                                alt="Avatar Tailwind CSS Component" />
                                        </div>
                                    </div>
                                    <p class="text-gray-600 font-medium text-sm"> Pr <?= htmlspecialchars($j['professeur_nom']) ?></p>
                                </div>
                            </div>
                            <div class="text-sm font-medium">
                                <?php if ($j['statut'] == "acceptée") : ?>
                                    <span class="badge badge-soft badge-success"><?= htmlspecialchars($j['statut']) ?></span>
                                <?php elseif ($j["statut"] == "en attente"): ?>
                                    <span class="badge badge-soft badge-neutral"><?= htmlspecialchars($j['statut']) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-soft badge-error"><?= htmlspecialchars($j['statut']) ?> </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-3 text-sm font-medium">
                            <div>
                                <p class="text-gray-500">Date</p>
                                <p><?= date('d/m/Y', strtotime($j['date_absence'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Cours</p>
                                <p><?= date('d/m/Y', strtotime($j['date_cours'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">justifications</p>
                                <p><?= date('d/m/Y', strtotime($j['date_justification'])) ?></p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="<?= ROOT_URL ?>?controllers=etudiant&page=justifications&details_justification=<?= $j["id_justification"] ?>" class="btn btn-primary">Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-8 rounded-lg shadow-sm text-center mt-4">
                <i class="ri-checkbox-circle-line text-4xl text-green-500 mb-2"></i>
                <h3 class="text-lg font-medium">Aucune absence à justifier</h3>
                <p class="text-gray-500 mt-1">Vous n'avez aucune absence non justifiée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de détails -->
<dialog id="detailsAbsenceModal" class="modal <?= isset($_GET["details_justification"]) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Details de la justifications</h3>
            <a href="<?= ROOT_URL ?>?controllers=etudiant&page=justifications" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div>
                <p class="text-sm font-medium badge badge-soft badge-primary"><i class="ri-attachment-2"></i> Pieces jointes</p>
                <?php if (isset($details["pieces_jointes"])): ?>
                    <img src="/uploads/jointures/<?= $details["pieces_jointes"] ?>" alt="" class="h-52 w-52 object-cover">
                <?php else: ?>
                    <div class="flex items-center justify-center h-52 w-52 bg-gray-50">
                        <i class="ri-file-add-line text-3xl text-gray-500"></i>
                        <span>Aucune pieces jointes</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-sm font-medium badge badge-soft badge-warning"><i class="ri-attachment-2"></i> Commentaires</p>
                <?php if (isset($details["commentaire_traitement"])): ?>
                    <p>
                        <?= $details["commentaire_traitement"] ?>
                    </p>
                <?php else: ?>
                    <div class="flex items-center justify-center h-full bg-gray-50">
                        <span class="text-gray-500">Aucune commentaires ...</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</dialog>