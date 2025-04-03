<div class="px-3 flex justify-between items-center mt-4">
    <!-- Un seul conteneur pour tous les messages -->
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
            <img src="assets/filieres.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des filières</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Créez et gérez les différentes filières de votre établissement
            </p>
        </div>
    </div>
    <button onclick="addFiliereModal.showModal()" class="px-4 py-2 shadow-xl font-medium rounded-md bg-purple-500 hover:bg-purple-600 text-white">
        <span>Nouvelle filière</span>
        <i class="ri-function-add-line"></i>
    </button>
</div>

<!-- Modal d'ajout/modification -->
<dialog id="addFiliereModal" class="modal <?= !empty(getFieldErrors()) || isset($filiereToEdit) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full md:max-w-xl">
        <div class="flex md:flex-col lg:flex-row items-center">
            <span class="py-1 px-2 rounded-3xl bg-purple-200 text-purple-500">
                <i class="ri-bookmark-line"></i>
                <?= isset($filiereToEdit) ? 'Modifier la filière' : 'Ajouter une filière' ?>
            </span>
            <?php if (getFieldError('general')): ?>
                <div class="alert alert-error mt-4">
                    <i class="ri-error-warning-line"></i>
                    <span><?= htmlspecialchars(getFieldError('general')) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?= ROOT_URL ?>?controllers=responsable&page=filieres" class="mt-4 space-y-4">
            <input type="hidden" name="id_filiere" value="<?= $filiereToEdit['id_filiere'] ?? '' ?>">

            <div class="form-control">
                <label class="label font-medium">Libellé</label>
                <input type="text" name="libelle"
                    value="<?= htmlspecialchars($filiereToEdit['libelle'] ?? $_POST['libelle'] ?? '') ?>"
                    placeholder="Ex: Informatique"
                    class="input input-bordered w-full <?= getFieldError('libelle') ? 'input-error' : '' ?>">
                <p class="text-red-500"><?= getFieldError("libelle") ?? "" ?></p>
            </div>

            <div class="form-control">
                <label class="label font-medium">Description(optionelle)</label>
                <textarea name="description" class="textarea textarea-bordered w-full"
                    placeholder="Description de la filière"><?= htmlspecialchars($filiereToEdit['description'] ?? $_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="modal-action">
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=filieres" class="btn btn-ghost">Annuler</a>
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

<!-- Liste des filières -->
<div class="px-3 mt-3">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libellé</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date création</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($filieres['data'] as $filiere): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($filiere['libelle']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($filiere["description"] == ""): ?>
                                <span class="text-gray-300">Aucune description</span>
                            <?php else:  ?>
                                <?= $filiere["description"] ?>
                            <?php endif; ?>
                        <td class="px-6 py-4">
                            <?php if ($filiere["state"] == "disponible"): ?>
                                <span class="badge badge-soft badge-success"><?= $filiere["state"] ?></span>
                            <?php else:  ?>
                                <span class="badge badge-soft badge-error"><?= $filiere["state"] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y', strtotime($filiere['date_creation'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if ($filiere['state'] === 'disponible'): ?>
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-40">
                                        <li>
                                            <a href="?controllers=responsable&page=filieres&edit_filiere_id=<?= $filiere['id_filiere'] ?>">
                                                <i class="ri-pencil-line"></i> Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a href="?controllers=responsable&page=filieres&confirm_archive=<?= $filiere['id_filiere'] ?>">
                                                <i class="ri-delete-bin-line"></i> Archiver
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="?controllers=responsable&page=filieres&confirm_unarchive=<?= $filiere['id_filiere'] ?>"
                                    class="btn btn-sm btn-outline">
                                    <i class="ri-archive-line"></i> Désarchiver
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= renderPagination($filieres['pagination'], array_merge(['controllers' => 'responsable', 'page' => 'filieres'])) ?>
    </div>
</div>

<!-- Modal de confirmation suppression -->
<dialog id="confirmDeleteModal" class="modal <?= isset($_GET['confirm_archive']) || isset($_GET['confirm_unarchive']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">
            <?= isset($_GET['confirm_archive']) ? 'Archiver la filiere' : 'Désarchiver la filiere' ?>
        </h3>
        <p class="py-4">
            Êtes-vous sûr de vouloir <?= isset($_GET['confirm_archive']) ? 'archiver' : 'désarchiver' ?> cette filiere ?
        </p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=filieres" class="btn">Annuler</a>
            <?php if (isset($_GET['confirm_archive'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=filieres&archived_filiere_id=<?= $_GET['confirm_archive'] ?>" class="btn btn-primary">Confirmer</a>
            <?php elseif (isset($_GET['confirm_unarchive'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=filieres&unarchive_filiere_id=<?= $_GET['confirm_unarchive'] ?>" class="btn btn-primary">Confirmer</a>
            <?php endif; ?>
        </div>
    </div>
</dialog>