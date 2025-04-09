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
</div>

<!-- Filtres -->
<div class="bg-white p-4 rounded-lg shadow-sm mt-2">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="controllers" value="professeur">
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
            <a href="?controllers=professeur&page=cours" class="btn btn-ghost ml-2">
                <i class="ri-close-line"></i> Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Liste des cours -->
<div class="px-3 mt-6 bg-white p-2">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classes</th>
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
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?= $c['classes_list'] ?>
                        </td>
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
                                        <a href="?controllers=professeur&page=cours&details_cours_id=<?= $c['id_cours'] ?>">
                                            <i class="ri-eye-line"></i> Détails
                                        </a>
                                    </li>
                                    <?php if ($c['statut'] == 'effectué'): ?>
                                        <li>
                                            <a href="?controllers=professeur&page=cours&marquer_absences=<?= $c['id_cours'] ?>">
                                                <i class="ri-user-unfollow-line"></i> Marquer absences
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
        <?= renderPagination($pagination, array_merge(['controllers' => 'professeur', 'page' => 'cours'])) ?>
    </div>
</div>

<!-- Modal de détails -->
<dialog id="detailsCoursModal" class="modal <?= isset($details) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Détails du cours</h3>
            <a href="<?= ROOT_URL ?>?controllers=professeur&page=cours" class="btn btn-sm btn-circle btn-ghost">✕</a>
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
                                <span class="badge bade-soft badge-info">Planifié</span>
                            <?php elseif ($details["info"]['statut'] == 'effectué'): ?>
                                <span class="badge badge-soft badge-success">Effectué</span>
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
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($details['etudiants'] as $etudiant): ?>
                                    <tr>
                                        <td><?= $etudiant['matricule'] ?></td>
                                        <td><?= $etudiant['prenom'] ?> <?= $etudiant['nom'] ?></td>
                                        <td><?= $etudiant['libelle_classe'] ?? 'N/A' ?></td>
                                        <td>
                                            <?php if ($etudiant['absent']): ?>
                                                <span class="badge badge-soft badge-error">Absent</span>
                                            <?php else: ?>
                                                <span class="badge badge-soft badge-success">Présent</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=professeur&page=cours" class="btn">Fermer</a>
        </div>
    </div>
</dialog>