<div class="px-3">
    <div class="fixed top-5 right-5 space-y-4 transition transform duration-300 opacity-0 translate-y-2" id="alerter">
        <?php if (getFieldError("general")): ?>
            <div role="alert" class="alert alert-error w-96 text-white">
                <i class="ri-error-warning-line"></i>
                <span><?= getFieldError("general") ?></span>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div role="alert" class="alert alert-success w-96 text-white">
                <i class="ri-checkbox-circle-fill"></i>
                <span><?= $message ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="p-2">
            <!-- Formulaire de filtres -->
            <form method="get" class="space-y-6">
                <input type="hidden" name="controllers" value="etudiant">
                <input type="hidden" name="page" value="absences">
                <div class="flex items-center gap-2 justify-end">
                    <!-- Filtre Date -->
                    <div class="">
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
                            <?php if (isset($_GET["date_debut"])): ?>
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
        <?php if (!empty($absences)): ?>
            <div class="space-y-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-3 gap-3">
                <?php foreach ($absences as $absence): ?>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer inset-0 bg-gradient-to-r from-indigo-50/50 to-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg"><?= htmlspecialchars($absence['module']) ?></h3>
                                <p class="text-gray-600"><?= htmlspecialchars($absence['professeur']) ?></p>
                            </div>
                            <span class="badge badge-soft badge-error text-sm font-medium">
                                Non justifiée
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-3 text-sm font-medium">
                            <div>
                                <p class="text-gray-500">Date</p>
                                <p><?= date('d/m/Y', strtotime($absence['date_absence'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Heure</p>
                                <p><?= substr($absence['heure_debut'], 0, 5) ?> - <?= substr($absence['heure_fin'], 0, 5) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Salle</p>
                                <p><?= htmlspecialchars($absence['salle']) ?></p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="<?= ROOT_URL ?>?controllers=etudiant&page=absences&details_absence=<?= $absence["id_absence"] ?>" class="btn btn-primary">Justifier</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                <i class="ri-checkbox-circle-line text-4xl text-green-500 mb-2"></i>
                <h3 class="text-lg font-medium">Aucune absence à justifier</h3>
                <p class="text-gray-500 mt-1">Vous n'avez aucune absence non justifiée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<!-- Modal de détails -->
<dialog id="detailsAbsenceModal" class="modal <?= !empty(getFieldErrors()) || isset($_GET["details_absence"]) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Demande de justifications</h3>
            <a href="<?= ROOT_URL ?>?controllers=etudiant&page=absences" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>

        <?php if (isset($_GET["details_absence"])): ?>
            <!-- Formulaire de justification -->
            <form method="post" action="<?= ROOT_URL ?>?controllers=etudiant&page=absences&details_absence=<?= $_GET["details_absence"] ?>" enctype="multipart/form-data" class="mt-4 border-t pt-4">
                <input type="hidden" name="id_absence" value="<?= $_GET["details_absence"] ?>">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Motif de l'absence *</label>
                    <textarea name="motif" class="w-full p-2 border rounded h-24 <?= getFieldError('motif') ? 'border-red-500' : '' ?>"
                        placeholder="Veuillez expliquer le motif de votre absence..."></textarea>
                    <p class="text-red-500 text-sm"><?= getFieldError("motif") ?? "" ?></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pièce justificative</label>
                    <input type="file" name="piece_jointe" class="w-full p-2 border rounded">
                    <p class="text-gray-500 text-xs mt-1">Formats acceptés : JPG, PNG (max 2Mo)</p>
                </div>

                <button type="submit" name="justifier_absence"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="ri-send-plane-line mr-1"></i> Envoyer la justification
                </button>
            </form>
        <?php endif; ?>
    </div>
</dialog>