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
            <img src="assets/main.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des classes</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                vous pouvez ici créer, modifier et supprimer des classes. Utilisez les filtres pour rechercher rapidement
            </p>
        </div>
    </div>
    <button onclick="addClassModal.showModal()" class="px-4 py-2 shadow-xl font-medium rounded-md bg-purple-500 hover:bg-purple-600 text-white">
        <span>Nouvelle classe</span>
        <i class="ri-function-add-line"></i>
    </button>
    <!-- Modal d'ajout et de modifications d'une classe -->
    <dialog id="addClassModal" class="modal <?= !empty(getFieldErrors()) || isset($classeToEdit) ? 'modal-open' : '' ?>">
        <div class="modal-box w-full md:max-w-xl lg:max-w-2xl">
            <div class="flex md:flex-col lg:flex-row items-center">
                <span class="py-1 px-2 rounded-3xl bg-purple-200 text-purple-500"><i class="ri-graduation-cap-line"></i> <?= isset($classeToEdit) ? 'Modifier la classe' : 'Ajouter une nouvelle classe' ?></span>
                <?php if (getFieldError('general')): ?>
                    <div class="alert alert-error mt-4">
                        <i class="ri-error-warning-line"></i>
                        <span><?= htmlspecialchars(getFieldError('general')) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Formulaire -->
            <form method="POST" action="<?= ROOT_URL ?>?controllers=responsable&page=classes" class="mt-4 space-y-4">
                <input type="hidden" name="id_classe" value="<?= $classeToEdit['id_classe'] ?? '' ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Libellé -->
                    <div class="form-control">
                        <label class="label font-medium">Libellé de la classe</label>
                        <input type="text" value="<?= htmlspecialchars($classeToEdit['libelle'] ?? $_POST['libelle'] ?? '') ?>" name="libelle" placeholder="Ex: L1 Développement" class="px-2 py-2 rounded border <?= getFieldError('libelle') ? 'border-red-500' : 'border-gray-300' ?> w-full">
                        <p class="text-red-500"><?= getFieldError("libelle") ?? "" ?></p>
                    </div>
                    <!-- Filière -->
                    <div class="form-control">
                        <label class="label font-medium">Filière</label>
                        <select name="filiere" class="px-2 py-2 rounded border <?= getFieldError('filiere') ? 'border-red-500' : 'border-gray-300' ?> w-full">
                            <option value="">Sélectionnez une filière</option>
                            <?php foreach ($filieres as $filiere): ?>
                                <option value="<?= $filiere['id_filiere'] ?>" <?= ($classeToEdit['id_filiere'] ?? $_POST['filiere'] ?? '') == $filiere['id_filiere'] ? 'selected' : '' ?>><?= htmlspecialchars($filiere['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-red-500"><?= getFieldError("filiere") ?? "" ?></p>
                    </div>
                    <!-- Niveau -->
                    <div class="form-control">
                        <label class="label font-medium">Niveaux</label>
                        <select name="niveau" class="px-2 py-2 rounded border <?= getFieldError('niveau') ? 'border-red-500' : 'border-gray-300' ?> w-full">
                            <option value="">Sélectionnez un niveau</option>
                            <?php foreach ($niveaux as $niveau): ?>
                                <option value="<?= $niveau['id_niveau'] ?>" <?= ($classeToEdit['id_niveau'] ?? $_POST['niveau'] ?? '') == $niveau['id_niveau'] ? 'selected' : '' ?>><?= htmlspecialchars($niveau['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-red-500"><?= getFieldError("niveau") ?? "" ?></p>
                    </div>
                    <!-- Capacité max (optionnel) -->
                    <div class="form-control">
                        <label class="label font-medium">Capacite Maximal</label>
                        <input type="text" value="<?= htmlspecialchars($classeToEdit['capacite_max'] ?? $_POST['capacite'] ?? '') ?>" name="capacite" placeholder="100 elelves" class="px-2 py-2 rounded border <?= getFieldError('capacite') ? 'border-red-500' : 'border-gray-300' ?> w-full">
                        <p class="text-red-500"><?= getFieldError("capacite") ?? "" ?></p>
                    </div>
                </div>
                <!-- Année scolaire -->
                <div class="form-control">
                    <label class="label font-medium">Année scolaire</label>
                    <input type="text" value="<?= htmlspecialchars($classeToEdit['annee_scolaire'] ?? $_POST['annee_scolaire'] ?? '') ?>" name="annee_scolaire" placeholder="Ex: 2023-2024" class="px-2 py-2 rounded border <?= getFieldError('annee_scolaire') ? 'border-red-500' : 'border-gray-300' ?> w-full">
                    <p class="text-red-500"><?= getFieldError("annee_scolaire") ?? "" ?></p>
                </div>
                <!-- Boutons de soumission -->
                <div class="modal-action">
                    <!-- Bouton pour fermer la modal -->
                    <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes" type="button" class="btn btn-ghost">
                        Annuler
                    </a>
                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Fermer la modal en cliquant à l'extérieur -->
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>
<!-- Liste des classes -->
<div class="px-3 mt-3">
    <div class="flex md:justify-end">
        <form action="" class="flex items-center gap-2">
            <input type="hidden" name="controllers" value="responsable">
            <input type="hidden" name="page" value="classes">
            <div class="flex items-center gap-2">
                <select name="niveau" class="py-1 px-3 border-b rounded text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">--Niveaux--</option>
                    <?php foreach ($niveaux as $niveau): ?>
                        <option value="<?= $niveau["id_niveau"] ?>" <?= ($filtered['niveau'] ?? '') == $niveau['id_niveau'] ? 'selected' : '' ?>><?= $niveau["libelle"] ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="filiere" class="py-1 px-3 border-b rounded text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">--Filieres--</option>
                    <?php foreach ($filieres as $filiere): ?>
                        <option value="<?= $filiere["id_filiere"] ?>" <?= ($filtered['filiere'] ?? '') == $filiere['id_filiere'] ? 'selected' : '' ?>><?= $filiere["libelle"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white rounded flex items-center">
                <span class="hidden md:flex">filtrez</span>
                <i class="ri-filter-3-line"></i>
            </button>
        </form>
    </div>
    <div class="overflow-x-auto mt-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effectif</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Année</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($classes as $classe): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-2 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="ri-team-line text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($classe['libelle']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($classe['filiere']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($classe['niveau']) ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= $classe['effectif'] ?></div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($classe['annee_scolaire']) ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium">
                            <?php if ($classe['state'] === 'disponible'): ?>
                                <div class="dropdown dropdown-end">
                                    <button type="button" tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-40 mt-1">
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes&edit_classe_id=<?= $classe['id_classe'] ?>" class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-pencil-line text-gray-400"></i>
                                                Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes&confirm_archive=<?= $classe['id_classe'] ?>"
                                                class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-archive-line text-gray-400"></i>
                                                Archiver
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes&details_classe_id=<?= $classe['id_classe'] ?>" class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-eye-line text-gray-400"></i>
                                                Détails
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="?controllers=responsable&page=classes&confirm_unarchive=<?= $classe['id_classe'] ?>"
                                    class="btn btn-sm btn-outline">
                                    <i class="ri-archive-line"></i> Désarchiver
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= renderPagination($pagination, array_merge(['controllers' => 'responsable', 'page' => 'classes'])) ?>
    </div>
    <!-- Modal de détails -->
    <dialog id="detailsModal" class="modal <?= isset($_GET['details_classe_id']) ? 'modal-open' : '' ?>">
        <div class="modal-box w-full md:max-w-xl max-w-3xl">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg">Détails de la classe</h3>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes" class="btn btn-sm btn-circle btn-ghost">✕</a>
            </div>
            <?php if (isset($classeDetails)): ?>
                <div class="py-4 w-full">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="ri-team-line text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold"><?= htmlspecialchars($classeDetails['libelle']) ?></h4>
                            <div class="flex gap-2 mt-1">
                                <span class="badge badge-primary"><?= htmlspecialchars($classeDetails['filiere']) ?></span>
                                <span class="badge badge-secondary"><?= htmlspecialchars($classeDetails['niveau']) ?></span>
                                <span class="badge <?= $classeDetails['state'] === 'disponible' ? 'badge-success' : 'badge-warning' ?>">
                                    <?= ucfirst($classeDetails['state']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <p><strong>Année scolaire:</strong> <?= htmlspecialchars($classeDetails['annee_scolaire']) ?></p>
                            <p><strong>Capacité:</strong> <?= $classeDetails['capacite_max'] ?> places</p>
                            <p><strong>Effectif:</strong> <?= $classeDetails['effectif'] ?> étudiants</p>
                        </div>
                        <div>
                            <h5 class="font-bold mb-2">Professeurs</h5>
                            <?php if (!empty($classeDetails['professeurs'])): ?>
                                <ul class="space-y-1">
                                    <?php foreach (explode(',', $classeDetails['professeurs']) as $prof): ?>
                                        <li><?= trim(htmlspecialchars($prof)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>Aucun professeur attitré</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="w-full">
                        <h5 class="font-bold mb-2">Liste des eleves</h5>
                        <?php if (!empty($classeDetails['etudiants'])): ?>

                            <ul class="space-y-1">
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                                    <?php foreach (explode(',', $classeDetails['etudiants']) as $etudiant): ?>
                                        <li><?= trim(htmlspecialchars($etudiant)) ?></li>
                                    <?php endforeach; ?>
                                </div>
                            </ul>
                        <?php else: ?>
                            <p>Aucun etudiant dans cette classe</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </dialog>
    <!-- Modal de confirmation -->
    <dialog id="confirmModal" class="modal <?= isset($_GET['confirm_archive']) || isset($_GET['confirm_unarchive']) ? 'modal-open' : '' ?>">
        <div class="modal-box">
            <h3 class="font-bold text-lg">
                <?= isset($_GET['confirm_archive']) ? 'Archiver la classe' : 'Désarchiver la classe' ?>
            </h3>
            <p class="py-4">
                Êtes-vous sûr de vouloir <?= isset($_GET['confirm_archive']) ? 'archiver' : 'désarchiver' ?> cette classe ?
            </p>
            <div class="modal-action">
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes" class="btn">Annuler</a>
                <?php if (isset($_GET['confirm_archive'])): ?>
                    <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes&archived_classe_id=<?= $_GET['confirm_archive'] ?>" class="btn btn-primary">Confirmer</a>
                <?php elseif (isset($_GET['confirm_unarchive'])): ?>
                    <a href="<?= ROOT_URL ?>?controllers=responsable&page=classes&unarchive_classe_id=<?= $_GET['confirm_unarchive'] ?>" class="btn btn-primary">Confirmer</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fermer la modal en cliquant à l'extérieur -->
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>