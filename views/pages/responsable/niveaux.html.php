<div class="px-3 flex justify-between items-center mt-4">
    <!-- En-tête similaire aux autres pages -->
    <div class="hidden lg:flex items-center gap-2">
        <div class="w-24 h-24 rounded-full bg-purple-100 flex items-center justify-center">
            <img src="assets/niveau.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl"><?= $contenue ?></h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Créez et gérez les différents niveaux de votre établissement
            </p>
        </div>
    </div>
    <button onclick="addNiveauModal.showModal()" class="btn btn-primary">
        <span>Nouveau niveau</span>
        <i class="ri-function-add-line"></i>
    </button>
</div>

<!-- Modal d'ajout/modification -->
<dialog id="addNiveauModal" class="modal <?= !empty($errors) || isset($toEdit) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg"><?= isset($toEdit) ? 'Modifier le niveau' : 'Ajouter un niveau' ?></h3>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error mt-4">
                <i class="ri-error-warning-line"></i>
                <span><?= htmlspecialchars($errors['general']) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4 space-y-4">
            <input type="hidden" name="id_niveau" value="<?= $toEdit['id_niveau'] ?? '' ?>">

            <div class="form-control">
                <label class="label font-medium">Libellé</label>
                <input type="text" name="libelle"
                    value="<?= htmlspecialchars($toEdit['libelle'] ?? $_POST['libelle'] ?? '') ?>"
                    placeholder="Ex: Licence 1"
                    class="input input-bordered w-full <?= !empty($errors['libelle']) ? 'input-error' : '' ?>">
                <?php if (!empty($errors['libelle'])): ?>
                    <p class="text-red-500"><?= $errors['libelle'] ?></p>
                <?php endif; ?>
            </div>

            <div class="modal-action">
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=niveaux" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Liste des niveaux -->
<div class="px-3 mt-3">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libellé</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($niveaux['data'] as $niveau): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($niveau['libelle']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge <?= $niveau['state'] === 'disponible' ? 'badge-success' : 'badge-warning' ?>">
                                <?= ucfirst($niveau['state']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="dropdown dropdown-end">
                                <button tabindex="0" class="btn btn-ghost btn-sm">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-40">
                                    <li>
                                        <a href="?controllers=responsable&page=niveaux&edit_niveau_id=<?= $niveau['id_niveau'] ?>">
                                            <i class="ri-pencil-line"></i> Modifier
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?controllers=responsable&page=niveaux&confirm_archive=<?= $niveau['id_niveau'] ?>">
                                            <i class="ri-archive-line"></i>
                                            <?= $niveau['state'] === 'disponible' ? 'Archiver' : 'Désarchiver' ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= renderPagination($niveaux['pagination'], array_merge(['controllers' => 'responsable', 'page' => 'niveaux'])) ?>
    </div>
</div>

<!-- Modal de confirmation -->
<dialog id="confirmModal" class="modal <?= isset($_GET['confirm_archive']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">
            <?= isset($_GET['confirm_archive']) ? 'Confirmation' : '' ?>
        </h3>
        <p class="py-4">
            Êtes-vous sûr de vouloir <?= isset($_GET['confirm_archive']) ?
                                            (getNiveauById($_GET['confirm_archive'])['state'] === 'disponible' ? 'archiver' : 'désarchiver')
                                            : '' ?> ce niveau ?
        </p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=niveaux" class="btn">Annuler</a>
            <?php if (isset($_GET['confirm_archive'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=niveaux&archived_niveau_id=<?= $_GET['confirm_archive'] ?>"
                    class="btn btn-primary">
                    Confirmer
                </a>
            <?php endif; ?>
        </div>
    </div>
</dialog>