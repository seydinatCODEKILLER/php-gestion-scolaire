<div class="px-3 flex justify-between items-center mt-4">
    <!-- Alertes -->
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

    <!-- Titre -->
    <div class="hidden lg:flex items-center gap-2">
        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center">
            <img src="assets/absent.png" alt="Icône absences" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des absences</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Marquez et consultez les absences pour vos cours
            </p>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white p-4 rounded-lg shadow-sm mt-2">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <input type="hidden" name="controllers" value="professeur">
        <input type="hidden" name="page" value="absences">

        <div class="form-control">
            <label class="label">Module</label>
            <select name="module" class="select select-bordered">
                <option value="">Tous les modules</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?= $module['id_module'] ?>"
                        <?= ($filtered['module'] ?? '') == $module['id_module'] ? 'selected' : '' ?>>
                        <?= $module['libelle'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-control flex flex-col">
            <label class="label">Date</label>
            <input type="date" name="date" class="input input-bordered" value="<?= $filtered['date'] ?? '' ?>">
        </div>

        <div class="form-control md:col-span-3">
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="?controllers=professeur&page=absences" class="btn btn-ghost">Réinitialiser</a>
        </div>
    </form>
</div>

<!-- Liste des cours -->
<div class="px-3 mt-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($coursEffectues as $cours): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($cours['date_cours'])) ?></td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?= substr($cours['heure_debut'], 0, 5) ?> - <?= substr($cours['heure_fin'], 0, 5) ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $cours['module_libelle'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $cours['classes_list'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?php if ($cours['statut'] === "effectué"): ?>
                                <span class="badge badge-soft badge-primary"><?= $cours['statut'] ?></span>
                            <?php elseif ($cours['statut'] === "annulé"): ?>
                                <span class="badge badge-soft badge-warning"><?= $cours['statut'] ?></span>
                            <?php elseif ($cours['statut'] === "planifié"): ?>
                                <span class="badge badge-soft badge-success"><?= $cours['statut'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-right">
                            <a href="?controllers=professeur&page=absences&marquer_absences=<?= $cours['id_cours'] ?>"
                                class="btn btn-sm btn-primary">
                                <i class="ri-user-unfollow-line"></i> Marquer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

<script>
    // Gestion des alertes
    setTimeout(() => {
        const alerter = document.getElementById('alerter');
        if (alerter) {
            alerter.classList.remove('opacity-0', 'translate-y-2');
            alerter.classList.add('opacity-100', 'translate-y-0');
        }
    }, 100);
</script>