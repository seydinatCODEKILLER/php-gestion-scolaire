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
            <img src="assets/justification.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des justifications</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Planifiez et gérez les justifications de votre établissement
            </p>
        </div>
    </div>

</div>

<!-- Filtres -->
<div class="bg-white p-4 rounded-lg shadow-sm mt-2">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="controllers" value="attacher">
        <input type="hidden" name="page" value="justifications">

        <div class="form-control">
            <label class="label">Statut</label>
            <select name="statut" class="select select-bordered">
                <option value="">Tous</option>
                <option value="en attente" <?= ($filtered['statut'] ?? '') == 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="acceptée" <?= ($filtered['statut'] ?? '') == 'acceptée' ? 'selected' : '' ?>>Acceptée</option>
                <option value="refusée" <?= ($filtered['statut'] ?? '') == 'refusée' ? 'selected' : '' ?>>Refusée</option>
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

        <div class="form-control md:col-span-4">
            <button type="submit" class="btn btn-success text-white">
                <i class="ri-filter-line"></i> Filtrer
            </button>
            <a href="?controllers=attacher&page=justifications" class="btn btn-ghost ml-2">
                <i class="ri-close-line"></i> Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Liste des justifications -->
<div class="px-3 mt-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prenom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date justification</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Absences</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($justifications)):  ?>
                    <?php foreach ($justifications as $j): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2 whitespace-nowrap"><?= $j["nom"] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $j["prenom"] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $j["classe"] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $j["libelle"] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($j['date_justification'])) ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($j['date_absence'])) ?></td>
                            <td class="px-6 py-2 whitespace-nowrap">
                                <?php if ($j['statut'] == 'en attente'): ?>
                                    <span class="badge badge-soft badge-neutral"><?= $j['statut'] ?></span>
                                <?php elseif ($j['statut'] == 'acceptée'): ?>
                                    <span class="badge badge-soft badge-success"><?= $j['statut'] ?></span>
                                <?php else: ?>
                                    <span class="badge badge-soft badge-warning"><?= $j['statut'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-right">
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-52">
                                        <?php if ($j['statut'] == 'en attente'): ?>
                                            <li>
                                                <a href="?controllers=attacher&page=justifications&justification_accepted=<?= $j['id_justification'] ?>">
                                                    <i class="ri-checkbox-circle-line"></i> Acceptée
                                                </a>
                                            </li>
                                            <li>
                                                <a href="?controllers=attacher&page=justifications&justification_denied=<?= $j['id_justification'] ?>">
                                                    <i class="ri-close-line"></i> Refusée
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a href="?controllers=attacher&page=justifications&confirm_cancel=<?= $j['id_justification'] ?>">
                                                    <i class="ri-close-circle-line"></i> Annuler
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <img src="assets/recherche.png" alt="Aucun étudiant" class="">
                                <div class="text-gray-500 text-sm font-medium">
                                    Aucune justifications pour le moment
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?= renderPagination($paginations, array_merge(['controllers' => 'attacher', 'page' => 'justifications'])) ?>
    </div>
</div>

<dialog id="confirmModal" class="modal <?= isset($_GET['justification_accepted']) || isset($_GET['justification_denied']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">
            <?= isset($_GET['justification_accepted']) ? 'Accepter la justification' : 'Refuser la justification' ?>
        </h3>
        <p class="py-4">
            Êtes-vous sûr de vouloir <?= isset($_GET['justification_accepted']) ? 'accepter' : 'refuser' ?> cette justications ?
        </p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=attacher&page=justifications" class="btn">Annuler</a>
            <?php if (isset($_GET['justification_accepted'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=attacher&page=justifications&justification_accepted_id=<?= $_GET['justification_accepted'] ?>" class="btn btn-soft btn-primary">Confirmer</a>
            <?php elseif (isset($_GET['justification_denied'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=attacher&page=justifications&justification_denied_id=<?= $_GET['justification_denied'] ?>" class="btn btn-soft btn-primary">Confirmer</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Fermer la modal en cliquant à l'extérieur -->
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal de confirmation annulation -->
<dialog id="confirmCancelModal" class="modal <?= isset($_GET['confirm_cancel']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Annuler la demande</h3>
        <p class="py-4">Êtes-vous sûr de vouloir annuler cette demande ?</p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=attacher&page=justifications" class="btn">Non</a>
            <a href="<?= ROOT_URL ?>?controllers=attacher&page=justifications&cancel_justification_id=<?= $_GET['confirm_cancel'] ?? '' ?>" class="btn btn-error">Oui, annuler</a>
        </div>
    </div>
</dialog>