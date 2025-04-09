<div class="px-4 py-6">
    <!-- Alertes animées -->
    <div class="fixed top-5 right-5 space-y-4 z-50">
        <?php if (getFieldError("general")): ?>
            <div role="alert" class="alert alert-error shadow-lg transform transition-all duration-300 animate-bounce-in" id="error-alert">
                <i class="ri-error-warning-line"></i>
                <span><?= getFieldError("general") ?></span>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div role="alert" class="alert alert-success shadow-lg transform transition-all duration-300 animate-bounce-in" id="success-alert">
                <i class="ri-checkbox-circle-fill"></i>
                <span><?= $message ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Section Cours du Jour - Cartes animées -->
        <div class="lg:col-span-2">
            <?php if (!empty($coursDuJour)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2">
                    <?php foreach ($coursDuJour as $cours): ?>
                        <div class="p-3 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 
                        <?= $cours['statut'] === 'effectué' ? 'border-green-500' : '' ?>
                        <?= $cours['statut'] === 'annulé' ? 'border-orange-500' : '' ?>
                        <?= $cours['statut'] === 'planifié' ? 'border-blue-500' : '' ?> rounded">
                            <div class="">
                                <div class="flex justify-between items-start">
                                    <h3 class="card-title text-lg font-bold text-gray-800">
                                        <?= $cours['module_libelle'] ?>
                                    </h3>
                                    <span class="badge badge-soft 
                                    <?= $cours['statut'] === 'effectué' ? 'badge-success' : '' ?>
                                    <?= $cours['statut'] === 'annulé' ? 'badge-warning' : '' ?>
                                    <?= $cours['statut'] === 'planifié' ? 'badge-info' : '' ?>">
                                        <?= $cours['statut'] ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-gray-600 mb-2">
                                    <i class="ri-time-line"></i>
                                    <span>
                                        <?= substr($cours['heure_debut'], 0, 5) ?> - <?= substr($cours['heure_fin'], 0, 5) ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-gray-600 mb-4">
                                    <i class="ri-group-line"></i>
                                    <span><?= $cours['classes_list'] ?></span>
                                </div>

                                <div class="flex justify-end">
                                    <?php if (isMarquageDisponible($cours['date_cours'], $cours['heure_debut'])) : ?>
                                        <a href="?controllers=professeur&page=absences&marquer_absences=<?= $cours['id_cours'] ?>"
                                            class="btn btn-primary btn-sm">
                                            <i class="ri-user-unfollow-line mr-1"></i> Marquer absences
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-primary btn-sm cursor-not-allowed" disabled>
                                            <i class="ri-user-unfollow-line mr-1"></i> Marquer absences
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm flex justify-center items-center flex-col gap-2">
                    <img src="assets/recherche.png" alt="" class="object-cover">
                    <h3 class="text-sm font-medium text-gray-500">Aucun cours prévu aujourd'hui</h3>
                </div>
            <?php endif; ?>
        </div>
        <div class="w-full">
            <calendar-date class="cally bg-base-100 border border-base-300 shadow-lg rounded-box w-full">
                <svg aria-label="Previous" class="fill-current size-4" slot="previous" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                </svg>
                <svg aria-label="Next" class="fill-current size-4" slot="next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                </svg>
                <calendar-month></calendar-month>
            </calendar-date>
        </div>
    </div>

    <!-- Section Cours de la Semaine - Tableau moderne -->
    <div class="mt-5 bg-white p-2">
        <h2 class="text-xl font-medium mb-3 text-gray-700 flex items-center gap-2">
            <i class="ri-calendar-2-line"></i> Emploi du temps de la semaine
        </h2>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($coursDeLaSemaine as $cours): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= date('d/m/Y', strtotime($cours['date_cours'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        <?= substr($cours['heure_debut'], 0, 5) ?> - <?= substr($cours['heure_fin'], 0, 5) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= $cours['module_libelle'] ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach (explode(', ', $cours['classes_list']) as $classe): ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                <?= $classe ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        <?= $cours['statut'] === 'effectué' ? 'bg-green-100 text-green-800' : '' ?>
                                        <?= $cours['statut'] === 'annulé' ? 'bg-orange-100 text-orange-800' : '' ?>
                                        <?= $cours['statut'] === 'planifié' ? 'bg-blue-100 text-blue-800' : '' ?>">
                                        <?= $cours['statut'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <?php if (isMarquageDisponible($cours['date_cours'], $cours['heure_debut'])) : ?>
                                        <a href="?controllers=professeur&page=absences&marquer_absences=<?= $cours['id_cours'] ?>"
                                            class="btn btn-primary btn-xs">
                                            <i class="ri-user-unfollow-line mr-1"></i> Marquer
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-primary btn-xs disabled" disabled>
                                            <i class="ri-user-unfollow-line mr-1"></i> Marquer
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour marquer les absences -->
<dialog id="marquerAbsencesModal" class="modal <?= isset($_GET['marquer_absences']) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Marquer les absences</h3>
            <a href="<?= ROOT_URL ?>?controllers=professeur&page=absences" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>

        <?php if (!empty($coursDetails)): ?>
            <form method="POST" class="mt-4">
                <div class="mb-4">
                    <h4 class="text-lg font-semibold">
                        <?= $coursDetails['module_libelle'] ?? 'Cours' ?> -
                        <?= date('d/m/Y', strtotime($coursDetails['date_cours'] ?? '')) ?>
                    </h4>
                    <p class="text-gray-600">
                        <?= substr($coursDetails['heure_debut'] ?? '', 0, 5) ?> -
                        <?= substr($coursDetails['heure_fin'] ?? '', 0, 5) ?>
                    </p>
                </div>

                <?php if (!empty($etudiantsCours)): ?>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($etudiantsCours as $etudiant): ?>
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="w-8 rounded-full">
                                                        <img src="<?= $etudiant['avatar'] ?? 'assets/default-avatar.png' ?>"
                                                            alt="<?= $etudiant['prenom'] ?? '' ?> <?= $etudiant['nom'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-bold">
                                                        <?= $etudiant['prenom'] ?? '' ?> <?= $etudiant['nom'] ?? '' ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= $etudiant['matricule'] ?? '' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $etudiant['classe'] ?? 'N/A' ?></td>
                                        <td>
                                            <span class="badge <?= ($etudiant['absent'] ?? false) ? 'badge-error' : 'badge-success' ?>">
                                                <?= ($etudiant['absent'] ?? false) ? 'Absent' : 'Présent' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <label class="cursor-pointer label justify-start gap-2">
                                                <input type="checkbox"
                                                    name="absents[]"
                                                    value="<?= $etudiant['id_etudiant'] ?? '' ?>"
                                                    class="checkbox checkbox-error"
                                                    <?= ($etudiant['absent'] ?? false) ? 'checked' : '' ?>>
                                                <span class="label-text">Marquer absent</span>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="ri-information-line"></i>
                        <span>Aucun étudiant trouvé pour ce cours</span>
                    </div>
                <?php endif; ?>

                <div class="modal-action">
                    <a href="?controllers=professeur&page=absences" class="btn btn-ghost">Annuler</a>
                    <?php if (!empty($etudiantsCours)): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line mr-2"></i> Enregistrer
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        <?php endif; ?>
    </div>
</dialog>