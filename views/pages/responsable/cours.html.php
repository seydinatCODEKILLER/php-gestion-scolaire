<div class="px-3 flex justify-between items-center mt-4">
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

    <div class="hidden lg:flex items-center gap-2">
        <div class="w-24 h-24 rounded-full bg-purple-100 flex items-center justify-center">
            <img src="assets/cours.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des cours</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Planifiez et gérez les cours de votre établissement
            </p>
        </div>
    </div>

    <button onclick="addCoursModal.showModal()" class="px-4 py-2 shadow-xl font-medium rounded-md bg-purple-500 hover:bg-purple-600 text-white">
        <span>Planifier un cours</span>
        <i class="ri-calendar-line"></i>
    </button>
</div>

<!-- Filtres -->
<div class="bg-white p-4 rounded-lg shadow-sm mt-2">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="controllers" value="responsable">
        <input type="hidden" name="page" value="cours">

        <div class="form-control">
            <label class="label">Statut</label>
            <select name="statut" class="select select-bordered">
                <option value="">Tous</option>
                <option value="planifié" <?= ($filtered['statut'] ?? '') == 'planifié' ? 'selected' : '' ?>>Planifié</option>
                <option value="effectué" <?= ($filtered['statut'] ?? '') == 'effectué' ? 'selected' : '' ?>>Effectué</option>
                <option value="annulé" <?= ($filtered['statut'] ?? '') == 'annulé' ? 'selected' : '' ?>>Annulé</option>
            </select>
        </div>

        <div class="form-control">
            <label class="label">Période (début)</label>
            <input type="date" name="date_debut" class="input input-bordered" value="<?= $filtered['date_debut'] ?? '' ?>">
        </div>

        <div class="form-control">
            <label class="label">Période (fin)</label>
            <input type="date" name="date_fin" class="input input-bordered" value="<?= $filtered['date_fin'] ?? '' ?>">
        </div>

        <div class="form-control">
            <label class="label">Classe</label>
            <select name="id_classe" class="select select-bordered">
                <option value="">Toutes</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id_classe'] ?>" <?= ($filtered['id_classe'] ?? '') == $classe['id_classe'] ? 'selected' : '' ?>>
                        <?= $classe['libelle'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-control md:col-span-4">
            <button type="submit" class="btn btn-primary">
                <i class="ri-filter-line"></i> Filtrer
            </button>
            <a href="?controllers=responsable&page=cours" class="btn btn-ghost ml-2">
                <i class="ri-close-line"></i> Réinitialiser
            </a>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Professeur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($cours as $c): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($c['date_cours'])) ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= substr($c['heure_debut'], 0, 5) ?> - <?= substr($c['heure_fin'], 0, 5) ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $c['module_libelle'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $c['professeur_prenom'] ?> <?= $c['professeur_nom'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap"><?= $c['salle'] ?></td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?php if ($c['statut'] == 'planifié'): ?>
                                <span class="badge badge-soft badge-info"><?= $c['statut'] ?></span>
                            <?php elseif ($c['statut'] == 'effectué'): ?>
                                <span class="badge badge-soft badge-success"><?= $c['statut'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-soft badge-error"><?= $c['statut'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-right">
                            <div class="dropdown dropdown-end">
                                <button tabindex="0" class="btn btn-ghost btn-sm">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-52">
                                    <li>
                                        <a href="?controllers=responsable&page=cours&details_cours_id=<?= $c['id_cours'] ?>">
                                            <i class="ri-eye-line"></i> Détails
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?controllers=responsable&page=cours&edit_cours_id=<?= $c['id_cours'] ?>">
                                            <i class="ri-pencil-line"></i> Modifier
                                        </a>
                                    </li>
                                    <?php if ($c['statut'] == 'planifié'): ?>
                                        <li>
                                            <a href="?controllers=responsable&page=cours&confirm_cancel=<?= $c['id_cours'] ?>">
                                                <i class="ri-close-circle-line"></i> Annuler
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= renderPagination($pagination, array_merge(['controllers' => 'responsable', 'page' => 'cours'])) ?>
    </div>
</div>

<!-- Modal d'ajout/modification -->
<dialog id="addCoursModal" class="modal <?= !empty(getFieldErrors()) || isset($coursToEdit) ? 'modal-open' : '' ?> overflow-y-auto">
    <div class="modal-box w-full max-w-2xl max-h-[95vh] overflow-y-auto">
        <div class="flex items-center ">
            <span class="py-1 px-2 rounded-3xl bg-purple-200 text-purple-500">
                <i class="ri-calendar-line"></i>
                <?= isset($coursToEdit) ? 'Modifier le cours' : 'Planifier un cours' ?>
            </span>
        </div>

        <form method="POST" action="<?= ROOT_URL ?>?controllers=responsable&page=cours" class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
            <input type="hidden" name="id_cours" value="<?= $coursToEdit['id_cours'] ?? '' ?>">
            <div class="form-control md:col-span-2">
                <label class="label font-medium">Module</label>
                <select name="id_module" class="select w-full <?= getFieldError('id_module') ? 'select-error' : '' ?>">
                    <option value="">Sélectionnez un module</option>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?= $module['id_module'] ?>"
                            <?= ($coursToEdit['id_module'] ?? $_POST['id_module'] ?? '') == $module['id_module'] ? 'selected' : '' ?>>
                            <?= $module['libelle'] ?> (<?= $module['code_module'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-red-500"><?= getFieldError("id_module") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Professeur</label>
                <select name="id_professeur" class="select select-bordered w-full <?= getFieldError('id_professeur') ? 'select-error' : '' ?>">
                    <option value="">Sélectionnez un professeur</option>
                    <?php foreach ($professeurs as $professeur): ?>
                        <option value="<?= $professeur['id_professeur'] ?>"
                            <?= ($coursToEdit['id_professeur'] ?? $_POST['id_professeur'] ?? '') == $professeur['id_professeur'] ? 'selected' : '' ?>>
                            <?= $professeur['prenom'] ?> <?= $professeur['nom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-red-500"><?= getFieldError("id_professeur") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Semestre</label>
                <select name="id_semestre" class="select select-bordered w-full <?= getFieldError('id_semestre') ? 'select-error' : '' ?>">
                    <option value="">Sélectionnez un semestre</option>
                    <?php foreach ($semestres as $semestre): ?>
                        <option value="<?= $semestre['id_semestre'] ?>"
                            <?= ($coursToEdit['id_semestre'] ?? $_POST['id_semestre'] ?? '') == $semestre['id_semestre'] ? 'selected' : '' ?>>
                            <?= $semestre['libelle'] ?> (<?= $semestre['annee_scolaire'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-red-500"><?= getFieldError("id_semestre") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Date</label>
                <input type="date" name="date_cours"
                    value="<?= htmlspecialchars($coursToEdit['date_cours'] ?? $_POST['date_cours'] ?? '') ?>"
                    class="input input-bordered w-full <?= getFieldError('date_cours') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("date_cours") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Heure début</label>
                <input type="time" name="heure_debut"
                    value="<?= htmlspecialchars($coursToEdit['heure_debut'] ?? $_POST['heure_debut'] ?? '') ?>"
                    class="input input-bordered w-full <?= getFieldError('heure_debut') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("heure_debut") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Heure fin</label>
                <input type="time" name="heure_fin"
                    value="<?= htmlspecialchars($coursToEdit['heure_fin'] ?? $_POST['heure_fin'] ?? '') ?>"
                    class="input input-bordered w-full <?= getFieldError('heure_fin') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("heure_fin") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Nombre d'heures</label>
                <input type="number" name="nombre_heures"
                    value="<?= htmlspecialchars($coursToEdit['nombre_heures'] ?? $_POST['nombre_heures'] ?? '') ?>"
                    class="input input-bordered w-full <?= getFieldError('nombre_heures') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("nombre_heures") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Salle</label>
                <input type="text" name="salle"
                    value="<?= htmlspecialchars($coursToEdit['salle'] ?? $_POST['salle'] ?? '') ?>"
                    placeholder="Ex: A1, B2, etc."
                    class="input input-bordered w-full <?= getFieldError('salle') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("salle") ?? "" ?></p>
            </div>

            <div class="form-control md:col-span-2">
                <label class="label font-medium">Classes concernées</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <?php foreach ($classes as $classe): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="classes[]" value="<?= $classe['id_classe'] ?>"
                                <?= isset($coursClassesIds) && in_array($classe['id_classe'], $coursClassesIds) ? 'checked' : '' ?>
                                class="checkbox checkbox-primary">
                            <span><?= $classe['libelle'] ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <p class="text-red-500"><?= getFieldError("classes") ?? "" ?></p>
            </div>

            <div class="modal-action md:col-span-2">
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=cours" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal de détails -->
<dialog id="detailsCoursModal" class="modal <?= isset($details) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Détails du cours</h3>
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=cours" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>

        <?php if (isset($details)): ?>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-500">Module</h4>
                        <p class="mt-1"><?= $details['info']['module_libelle'] ?></p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-500">Date et heure</h4>
                        <p class="mt-1">
                            <?= date('d/m/Y', strtotime($details["info"]['date_cours'])) ?> -
                            <?= substr($details["info"]['heure_debut'], 0, 5) ?> à <?= substr($details["info"]['heure_fin'], 0, 5) ?>
                        </p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-500">Salle</h4>
                        <p class="mt-1"><?= $details["info"]['salle'] ?></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-500">Statut</h4>
                        <p class="mt-1">
                            <?php if ($details["info"]['statut'] == 'planifié'): ?>
                                <span class="badge badge-info">Planifié</span>
                            <?php elseif ($details["info"]['statut'] == 'effectué'): ?>
                                <span class="badge badge-success">Effectué</span>
                            <?php else: ?>
                                <span class="badge badge-error">Annulé</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-500">Nombre d'heures</h4>
                        <p class="mt-1"><?= $details["info"]['nombre_heures'] ?>h</p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h4 class="font-medium text-gray-500">Classes concernées</h4>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                        <?php foreach ($details['classes'] as $classe): ?>
                            <div class="badge badge-outline p-3">
                                <?= $classe['libelle'] ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h4 class="font-medium text-gray-500">Étudiants inscrits</h4>
                    <div class="mt-2 overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom complet</th>
                                    <th>Classe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($details['etudiants'] as $etudiant): ?>
                                    <tr>
                                        <td><?= $etudiant['matricule'] ?></td>
                                        <td><?= $etudiant['prenom'] ?> <?= $etudiant['nom'] ?></td>
                                        <td><?= $etudiant['libelle_classe'] ?? 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=cours" class="btn">Fermer</a>
        </div>
    </div>
</dialog>

<!-- Modal de confirmation annulation -->
<dialog id="confirmCancelModal" class="modal <?= isset($_GET['confirm_cancel']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Annuler le cours</h3>
        <p class="py-4">Êtes-vous sûr de vouloir annuler ce cours ?</p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=cours" class="btn">Non</a>
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=cours&cancel_cours_id=<?= $_GET['confirm_cancel'] ?? '' ?>" class="btn btn-error">Oui, annuler</a>
        </div>
    </div>
</dialog>