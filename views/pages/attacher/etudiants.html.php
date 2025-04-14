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
            <img src="assets/student.png" alt="" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des absences</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Planifiez et gérez les absences de vos etudiants
            </p>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white p-4 rounded-lg shadow-sm mt-2">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="hidden" name="controllers" value="attacher">
        <input type="hidden" name="page" value="etudiants">

        <div class="form-control">
            <label class="label">Statut</label>
            <select name="statut" class="select select-bordered">
                <option value="">Tous</option>
                <option value="disponible" <?= ($filtered['statut'] ?? '') == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="archiver" <?= ($filtered['statut'] ?? '') == 'archiver' ? 'selected' : '' ?>>Archiver</option>
            </select>
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
            <a href="?controllers=attacher&page=etudiants" class="btn btn-ghost ml-2">
                <i class="ri-close-line"></i> Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Liste des etudiants -->
<div class="px-3 mt-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matricule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prenom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Inscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Filiere</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Niveaux</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
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
                            <td class="px-6 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($e['date_inscription'])) ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['classe'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['filiere'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap"><?= $e['niveau'] ?></td>
                            <td class="px-6 py-2 whitespace-nowrap">
                                <?php if ($e['statut'] == 'disponible'): ?>
                                    <span class="badge badge-soft badge-info"><?= $e['statut'] ?></span>
                                <?php else: ?>
                                    <span class="badge badge-soft badge-error"><?= $e['statut'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap text-right">
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-ghost btn-sm">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-52">
                                        <li>
                                            <a href="?controllers=attacher&page=etudiants&details_student=<?= $e['id_etudiant'] ?>">
                                                <i class="ri-eye-line"></i> Voir absences
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <img src="assets/recherche.png" alt="Aucun étudiant" class="">
                                <div class="text-gray-500 text-sm font-medium">
                                    Aucun etudiants disponibles
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?= renderPagination($pagination, array_merge(['controllers' => 'attacher', 'page' => 'etudiants'])) ?>
    </div>
</div>

<!-- Modal de détails -->
<dialog id="detailsCoursModal" class="modal <?= isset($_GET["details_student"]) ? 'modal-open' : '' ?>">
    <div class="modal-box w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-lg">Liste des absences</h3>
            <a href="<?= ROOT_URL ?>?controllers=attacher&page=etudiants" class="btn btn-sm btn-circle btn-ghost">✕</a>
        </div>

        <?php if (isset($_GET["details_student"])): ?>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                <div class="space-y-4 w-full">
                    <div>
                        <h4 class="font-medium text-gray-500">Module</h4>
                    </div>
                    <div class="w-full">
                        <table class="table">
                            <thead>
                                <tr class="hover:bg-gray-50">
                                    <th class="px-6 py-2 whitespace-nowrap">Module</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Debut cours</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Fin cours</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Date absences</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Justifications</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Professeur</th>
                                    <th class="px-6 py-2 whitespace-nowrap">Salle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data["details"]["data"])): ?>
                                    <?php foreach ($data["details"]["data"] as $etudiant): ?>
                                        <tr>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['module'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['heure_debut'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['heure_fin'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['date_absence'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['statut_justification'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['professeur'] ?></td>
                                            <td class=" py-2 whitespace-nowrap"><?= $etudiant['salle'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            Chargement en cours...
                                        </td>
                                    </tr>
                                <?php endif; ?>
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