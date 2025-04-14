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

<button onclick="addStudentModal.showModal()"
    class="btn btn-success btn-wide <?= $periode_inscription ? '' : 'btn-disabled' ?> fixed right-4 bottom-4 w-11 h-11 rounded-full flex items-center justify-center">
    <i class="ri-user-add-line"></i>
</button>

<!-- Filtres -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-white rounded shadow-md p-4">
        <input type="hidden" name="controllers" value="attacher">
        <input type="hidden" name="page" value="inscriptions">

        <div class="form-control flex flex-col">
            <label class="label">Année scolaire</label>
            <select name="annee_scolaire" class="select select-bordered">
                <?php foreach ($annees_scolaires as $annee): ?>
                    <option value="<?= $annee ?>" <?= $annee === $filtered['annee_scolaire'] ? 'selected' : '' ?>>
                        <?= $annee ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-control flex flex-col">
            <label class="label">Classe</label>
            <select name="id_classe" class="select select-bordered">
                <option value="">Toutes les classes</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id_classe'] ?>"
                        <?= $classe['id_classe'] == $filtered['id_classe'] ? 'selected' : '' ?>>
                        <?= $classe['libelle'] ?> (<?= $classe['filiere'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-control flex flex-col">
            <button type="submit" class="btn btn-primary">
                <i class="ri-filter-line"></i> Filtrer
            </button>
        </div>
        <a href="?controllers=attacher&page=inscriptions" class="btn btn-ghost">
            <i class="ri-loop-left-line"></i> Actualiser
        </a>
    </form>
    <div class="bg-white rounded px-4 py-2 shadow-md">
        <div class="flex flex-col items-star justify-between gap-6">
            <!-- Périodes Container -->
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Carte Inscription -->
                <div class="bg-white p-4 rounded shadow-sm border-l-4 <?= $periode_inscription ? 'border-green-500' : 'border-warning-300' ?>">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-full <?= $periode_inscription ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' ?>">
                            <i class="ri-user-add-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Nouvelles inscriptions</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full <?= $periode_inscription ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?>">
                                    <?= $periode_inscription ? 'Ouvert' : 'Fermé' ?>
                                </span>
                                <?php if ($periode_inscription): ?>
                                    <span class="text-xs text-green-600 animate-pulse">
                                        <i class="ri-flashlight-fill"></i> En cours
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="ri-calendar-line"></i> Du
                        <span class="font-medium text-gray-800"><?= formatDate($periodes['inscription']['debut']) ?></span>
                        au
                        <span class="font-medium text-gray-800"><?= formatDate($periodes['inscription']['fin']) ?></span>
                    </p>
                </div>

                <!-- Carte Réinscription -->
                <div class="bg-white p-4 rounded shadow-sm border-l-4 <?= $periode_reinscription ? 'border-blue-500' : 'border-orange-300' ?>">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-full <?= $periode_reinscription ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-500' ?>">
                            <i class="ri-user-follow-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Réinscriptions</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full <?= $periode_reinscription ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-600' ?>">
                                    <?= $periode_reinscription ? 'Ouvert' : 'Fermé' ?>
                                </span>
                                <?php if ($periode_reinscription): ?>
                                    <span class="text-xs text-blue-600 animate-pulse">
                                        <i class="ri-flashlight-fill"></i> En cours
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="ri-calendar-line"></i> Du
                        <span class="font-medium text-gray-800"><?= formatDate($periodes['reinscription']['debut']) ?></span>
                        au
                        <span class="font-medium text-gray-800"><?= formatDate($periodes['reinscription']['fin']) ?></span>
                    </p>
                </div>
            </div>

            <!-- Bouton d'action -->
            <div class="flex flex-col items-center gap-2">
                <?php if (!$periode_inscription && !$periode_reinscription): ?>
                    <p class="text-xs text-gray-500 text-center max-w-[200px]">
                        <i class="ri-information-line"></i> Les inscriptions sont actuellement fermées
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Inscription -->
<dialog id="addStudentModal" class="modal <?= !empty(getFieldErrors()) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <h3 class="font-medium text-gray-500 text-lg">Nouvelle inscription</h3>
        <form method="POST" action="?controllers=attacher&page=inscriptions" class="mt-4" enctype="multipart/form-data">
            <input type="hidden" name="inscrire" value="1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nom -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nom</span>
                    </label>
                    <input value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                        type="text"
                        placeholder="Entrez le nom"
                        name="nom"
                        class="input input-bordered <?= getFieldError('nom') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('nom')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("nom") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Prénom -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Prénom</span>
                    </label>
                    <input value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                        placeholder="Entrez le prénom"
                        type="text"
                        name="prenom"
                        class="input input-bordered <?= getFieldError('prenom') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('prenom')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("prenom") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        placeholder="Adresse email"
                        type="email"
                        name="email"
                        class="input input-bordered <?= getFieldError('email') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('email')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("email") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Téléphone -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Téléphone</span>
                    </label>
                    <input placeholder="Numéro de téléphone"
                        value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"
                        type="tel"
                        name="telephone"
                        class="input input-bordered <?= getFieldError('telephone') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('telephone')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("telephone") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Adresse -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Adresse</span>
                    </label>
                    <input placeholder="Adresse complète"
                        value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>"
                        name="adresse"
                        class="input input-bordered <?= getFieldError('adresse') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('adresse')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("adresse") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Matricule (généré automatiquement) -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Matricule</span>
                    </label>
                    <input type="text"
                        name="matricule"
                        class="input input-bordered"
                        value="<?= $_POST['matricule'] ?? generateMatricule() ?>"
                        readonly>
                </div>

                <!-- Classe -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Classe</span>
                    </label>
                    <select name="id_classe" class="select select-bordered <?= getFieldError('id_classe') ? 'border-red-500' : 'border-gray-300' ?>">
                        <option value="">Choisir une classe</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= $classe['id_classe'] ?>"
                                <?= $classe["id_classe"] == ($_POST['id_classe'] ?? '') ? 'selected' : '' ?>>
                                <?= $classe['libelle'] ?> (<?= $classe['filiere'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (getFieldError('id_classe')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("id_classe") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Année scolaire -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Année scolaire</span>
                    </label>
                    <select name="annee_scolaire" class="select select-bordered <?= getFieldError('annee_scolaire') ? 'border-red-500' : 'border-gray-300' ?>">
                        <option value="">Choisir une année</option>
                        <?php foreach ($annees_scolaires as $annee): ?>
                            <option value="<?= $annee ?>" <?= $annee == ($_POST['annee_scolaire'] ?? '') ? 'selected' : '' ?>>
                                <?= $annee ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (getFieldError('annee_scolaire')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("annee_scolaire") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Mot de passe -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Mot de passe</span>
                    </label>
                    <input value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
                        placeholder="Entrez le mot de passe"
                        type="password"
                        name="password"
                        class="input input-bordered <?= getFieldError('password') ? 'border-red-500' : 'border-gray-300' ?>">
                    <?php if (getFieldError('password')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("password") ?></p>
                    <?php endif; ?>
                </div>

                <!-- Avatar -->
                <div class="form-control">
                    <label class="label font-medium">Photo de profil</label>
                    <input type="file"
                        name="avatar"
                        class="file-input file-input-bordered w-full">
                    <?php if (getFieldError('avatar')): ?>
                        <p class="text-red-500 text-sm mt-1"><?= getFieldError("avatar") ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions du modal -->
            <div class="modal-action mt-6">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Enregistrer
                </button>
                <button type="button" onclick="addStudentModal.close()" class="btn btn-ghost">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- Liste des étudiants -->
<div class="px-3 mt-6">
    <div class="overflow-x-auto bg-white p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matricule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Année scolaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date inscription</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($etudiants)): ?>
                    <?php foreach ($etudiants as $e): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['matricule'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['nom'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['prenom'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['classe'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['annee_scolaire'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($e['date_inscription'])) ?></td>
                            <td class="px-6 py-2 whitespace-nowrap text-right">
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-52">
                                        <li>
                                            <label for="modal-reinscription-<?= $e['id_etudiant'] ?>"
                                                class="<?= !$periode_inscription ? 'disabled' : '' ?>">
                                                <i class="ri-user-follow-line"></i> Réinscrire
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <img src="assets/recherche.png" alt="Aucun étudiant" class="">
                                <div class="text-gray-500 text-sm font-medium">
                                    Aucun étudiant trouvé
                                </div>
                                <?php if ($periode_inscription): ?>
                                    <label for="modal-nouvelle-inscription" class="btn btn-primary mt-4">
                                        <i class="ri-user-add-line"></i> Ajouter un étudiant
                                    </label>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?= renderPagination($pagination, array_merge(['controllers' => 'attacher', 'page' => 'inscriptions'])) ?>
    </div>
</div>

<!-- Modals de Réinscription (un par étudiant) -->
<?php foreach ($etudiants as $e): ?>
    <input type="checkbox" id="modal-reinscription-<?= $e['id_etudiant'] ?>" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box w-full max-w-3xl">
            <h3 class="font-bold text-lg">Réinscription de <?= $e['prenom'] ?> <?= $e['nom'] ?></h3>

            <?php if (!$periode_inscription): ?>
                <div class="alert alert-warning mt-4">
                    <i class="ri-alert-line"></i>
                    <span>Les réinscriptions sont actuellement fermées</span>
                </div>
            <?php else: ?>
                <form method="POST" action="?controllers=attacher&page=inscriptions" class="mt-6 space-y-4">
                    <input type="hidden" name="reinscrire" value="1">
                    <input type="hidden" name="id_etudiant" value="<?= $e['id_etudiant'] ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Matricule</span>
                            </label>
                            <input type="text" class="input input-bordered" value="<?= $e['matricule'] ?>" readonly>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Classe actuelle</span>
                            </label>
                            <input type="text" class="input input-bordered"
                                value="<?= $e['classe'] ?>" readonly>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nouvelle classe</span>
                        </label>
                        <select name="id_classe" class="select select-bordered" required>
                            <?php foreach ($classes as $classe): ?>
                                <option value="<?= $classe['id_classe'] ?>">
                                    <?= $classe['libelle'] ?> (<?= $classe['filiere'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Année scolaire</span>
                        </label>
                        <select name="annee_scolaire" class="select select-bordered" required>
                            <?php foreach ($annees_scolaires as $annee): ?>
                                <option value="<?= $annee ?>" <?= $annee === $filtered['annee_scolaire'] ? 'selected' : '' ?>>
                                    <?= $annee ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control flex flex-col">
                        <label class="label">
                            <span class="label-text">Redoublement</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <label class="label cursor-pointer gap-2">
                                <input type="radio" name="redoublement" value="1" class="radio radio-primary">
                                <span class="label-text">Oui</span>
                            </label>
                            <label class="label cursor-pointer gap-2">
                                <input type="radio" name="redoublement" value="0" class="radio radio-primary" checked>
                                <span class="label-text">Non</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Réinscrire
                        </button>
                        <label for="modal-reinscription-<?= $e['id_etudiant'] ?>" class="btn">Annuler</label>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>