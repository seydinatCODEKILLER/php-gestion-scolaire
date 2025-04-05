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
            <img src="assets/professeur.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl"><?= $contenue ?></h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Gérez les professeurs de votre établissement
            </p>
        </div>
    </div>

    <button onclick="addProfesseurModal.showModal()" class="btn bg-purple-500 shadow-lg text-white">
        <span>Nouveau professeur</span>
        <i class="ri-function-add-line"></i>
    </button>
</div>

<!-- Modal d'ajout/modification -->
<dialog id="addProfesseurModal" class="modal <?= !empty(getFieldErrors()) || isset($toEdit) ? 'modal-open' : '' ?>">
    <div class="modal-box w-11/12 max-w-5xl">
        <h3 class="font-bold text-lg"><?= isset($toEdit) ? 'Modifier le professeur' : 'Ajouter un professeur' ?></h3>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error mt-4">
                <i class="ri-error-warning-line"></i>
                <span><?= htmlspecialchars($errors['general']) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= ROOT_URL ?>?controllers=responsable&page=professeurs" class="mt-4 space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="id_professeur" value="<?= $toEdit['id_professeur'] ?? '' ?>">
            <input type="hidden" name="id_utilisateur" value="<?= $toEdit['id_utilisateur'] ?? '' ?>">
            <input type="hidden" name="current_avatar" value="<?= $toEdit['avatar'] ?? '' ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Informations personnelles -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label font-medium">Nom</label>
                        <input type="text" name="nom"
                            value="<?= htmlspecialchars($toEdit['nom'] ?? $_POST['nom'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('nom') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('nom') ?? "" ?></p>
                    </div>

                    <div class="form-control">
                        <label class="label font-medium">Prénom</label>
                        <input type="text" name="prenom"
                            value="<?= htmlspecialchars($toEdit['prenom'] ?? $_POST['prenom'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('prenom') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('prenom') ?? "" ?></p>
                    </div>

                    <div class="form-control">
                        <label class="label font-medium">Email</label>
                        <input type="email" name="email"
                            value="<?= htmlspecialchars($toEdit['email'] ?? $_POST['email'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('email') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('email') ?? "" ?></p>
                    </div>

                    <div class="form-control">
                        <label class="label font-medium">Téléphone</label>
                        <input type="text" name="telephone"
                            value="<?= htmlspecialchars($toEdit['telephone'] ?? $_POST['telephone'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('telephone') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('telephone') ?? "" ?></p>
                    </div>

                    <?php if (!isset($toEdit)): ?>
                        <div class="form-control">
                            <label class="label font-medium">Mot de passe</label>
                            <input type="password" name="password"
                                class="input input-bordered w-full <?= getFieldError('password') ? 'input-error' : '' ?>"
                                value="<?= htmlspecialchars($toEdit['password'] ?? $_POST['password'] ?? '') ?>">
                            <p class="text-red-500"><?= getFieldError('password') ?? "" ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Informations professionnelles -->
                <div class="space-y-2">
                    <div class="form-control">
                        <label class="label font-medium">Spécialité</label>
                        <input type="text" name="specialite"
                            value="<?= htmlspecialchars($toEdit['specialite'] ?? $_POST['specialite'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('specialite') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('specialite') ?? "" ?></p>
                    </div>

                    <div class="form-control">
                        <label class="label font-medium">Grade</label>
                        <input type="text" name="grade"
                            value="<?= htmlspecialchars($toEdit['grade'] ?? $_POST['grade'] ?? '') ?>"
                            class="input input-bordered w-full <?= getFieldError('grade') ? 'input-error' : '' ?>">
                        <p class="text-red-500"><?= getFieldError('grade') ?? "" ?></p>
                    </div>

                    <div class="form-control">
                        <label class="label font-medium">Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="file-input file-input-bordered w-full">
                        <?php if (!empty($toEdit['avatar'])): ?>
                            <div class="mt-2">
                                <img src="<?= htmlspecialchars($toEdit['avatar']) ?>"
                                    alt="Avatar actuel" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        <?php endif; ?>
                        <p class="text-red-500"><?= getFieldError('avatar') ?? "" ?></p>

                    </div>

                    <!-- Remplacer le select multiple par des checkboxes -->
                    <div class="form-control">
                        <label class="label font-medium">Classes affectées</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-2 border rounded">
                            <?php foreach ($classes as $classe): ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="classes[]" value="<?= $classe['id_classe'] ?>"
                                        <?= isset($toEdit) && in_array($classe['id_classe'], $profClassesIds ?? []) ? 'checked' : '' ?>
                                        class="checkbox checkbox-primary">
                                    <span>
                                        <?= htmlspecialchars($classe['libelle']) ?>
                                        (<?= htmlspecialchars($classe['annee_scolaire']) ?>)
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-red-500"><?= getFieldError('classes') ?? "" ?></p>

                    </div>
                </div>
            </div>

            <div class="modal-action">
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Liste des professeurs -->
<div class="px-3 mt-3">
    <!-- Formulaire de recherche -->
    <div class="flex justify-end mb-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="hidden" name="controllers" value="responsable">
            <input type="hidden" name="page" value="professeurs">
            <input type="text" name="search" placeholder="Rechercher..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                class="input ">
            <button type="submit" class="btn btn-primary">
                <i class="ri-search-line"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avatar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom & Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spécialité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($professeurs['data'] as $prof): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-2 whitespace-nowrap">
                            <div class="w-10 h-10 rounded-full overflow-hidden">
                                <?php if (!empty($prof['avatar'])): ?>
                                    <img src="uploads/professeur/<?= $prof["avatar"] ?>"
                                        alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="ri-user-line text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?= htmlspecialchars($prof['nom']) ?> <?= htmlspecialchars($prof['prenom']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= htmlspecialchars($prof['email']) ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <?= htmlspecialchars($prof['specialite']) ?>
                        </td>
                        <td class="px-6 py-2">
                            <?php if ($prof["state"] == "disponible"): ?>
                                <span class="badge badge-soft badge-success"><?= $prof["state"] ?></span>
                            <?php else:  ?>
                                <span class="badge badge-soft badge-error"><?= $prof["state"] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium">
                            <?php if ($prof['state'] === 'disponible'): ?>
                                <div class="dropdown dropdown-end">
                                    <button type="button" tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-40 mt-1">
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs&edit_professeur_id=<?= $prof['id_professeur'] ?>" class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-pencil-line text-gray-400"></i>
                                                Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs&confirm_archive=<?= $prof['id_professeur'] ?>"
                                                class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-archive-line text-gray-400"></i>
                                                Archiver
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs&details_professeur_id=<?= $prof['id_professeur'] ?>" class="text-gray-700 hover:bg-gray-100">
                                                <i class="ri-eye-line text-gray-400"></i>
                                                Détails
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a href="?controllers=responsable&page=professeurs&confirm_unarchive=<?= $prof['id_professeur'] ?>"
                                    class="btn btn-sm btn-outline">
                                    <i class="ri-archive-line"></i> Désarchiver
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= renderPagination($professeurs['pagination'], array_merge(['controllers' => 'responsable', 'page' => 'professeurs'])) ?>
    </div>
</div>

<!-- Modal de confirmation d'archivage -->
<dialog id="confirmArchiveModal" class="modal <?= isset($_GET['confirm_archive']) || isset($_GET['confirm_unarchive']) ? 'modal-open' : '' ?>">
    <div class="modal-box">
        <h3 class="font-bold text-lg">
            <?= isset($_GET['confirm_archive']) ? 'Archiver la professeur' : 'Désarchiver la professeur' ?>
        </h3>
        <p class="py-4">
            Êtes-vous sûr de vouloir <?= isset($_GET['confirm_archive']) ? 'archiver' : 'désarchiver' ?> cette professeur ?
        </p>
        <div class="modal-action">
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs" class="btn">Annuler</a>
            <?php if (isset($_GET['confirm_archive'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs&archived_professeur_id=<?= $_GET['confirm_archive'] ?>" class="btn btn-primary">Confirmer</a>
            <?php elseif (isset($_GET['confirm_unarchive'])): ?>
                <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs&unarchive_professeur_id=<?= $_GET['confirm_unarchive'] ?>" class="btn btn-primary">Confirmer</a>
            <?php endif; ?>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Modal des classes du professeur -->
<dialog id="classesModal" class="modal <?= isset($_GET['details_professeur_id']) ? 'modal-open' : '' ?>">
    <div class="modal-box w-11/12 max-w-3xl">
        <div class="flex justify-between items-center">
            <h3 class="font-bold text-lg">Classes du professeur</h3>
            <a href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>

        <?php if (isset($profClasses)): ?>
            <div class="mt-4">
                <form method="GET" class="mb-4">
                    <input type="hidden" name="controllers" value="responsable">
                    <input type="hidden" name="page" value="professeurs">
                    <input type="hidden" name="details_professeur_id" value="<?= $_GET['details_professeur_id'] ?>">
                    <select name="annee" onchange="this.form.submit()" class="select select-bordered">
                        <option value="">Toutes les années</option>
                        <option value="2023-2024" <?= ($_GET['annee'] ?? '') === '2023-2024' ? 'selected' : '' ?>>2023-2024</option>
                        <option value="2024-2025" <?= ($_GET['annee'] ?? '') === '2024-2025' ? 'selected' : '' ?>>2024-2025</option>
                    </select>
                </form>

                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Année scolaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($profClasses as $classe): ?>
                                <tr>
                                    <td><?= htmlspecialchars($classe['libelle']) ?></td>
                                    <td><?= htmlspecialchars($classe['filiere']) ?></td>
                                    <td><?= htmlspecialchars($classe['niveau']) ?></td>
                                    <td><?= htmlspecialchars($classe['annee_scolaire']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</dialog>