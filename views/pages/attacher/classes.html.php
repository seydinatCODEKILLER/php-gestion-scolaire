<div class="px-3 flex justify-between items-center mt-4">
    <div class="hidden lg:flex items-center gap-2">
        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center">
            <img src="assets/cours.png" alt="Icône classes" class="object-cover">
        </div>
        <div class="flex flex-col gap-1">
            <h1 class="font-medium text-2xl">Gestion des classes</h1>
            <p class="text-gray-400 w-96 text-sm font-medium">
                Liste des classes sous votre responsabilité
            </p>
        </div>
    </div>

    <!-- Filtre -->
    <form method="GET" class="flex items-center gap-2">
        <input type="hidden" name="controllers" value="attache">
        <input type="hidden" name="page" value="classes">

        <div class="form-control">
            <input type="text" name="search" placeholder="Rechercher..."
                class="input input-bordered" value="<?= $filtered['search'] ?? '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="ri-search-line"></i>
        </button>
        <a href="?controllers=attache&page=classes" class="btn btn-ghost">
            Reinitialiser <i class="ri-close-line"></i>
        </a>
    </form>
</div>

<!-- Liste des classes -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $classe): ?>
        <div class="card bg-white shadow hover:shadow-md transition-shadow ">
            <div class="card-body relative">
                <h2 class="card-title">
                    <?= $classe['libelle'] ?>
                    <span class="badge badge-soft badge-neutral"><?= $classe['annee_scolaire'] ?></span>
                </h2>
                <p class="text-gray-600"><?= $classe['filiere'] ?></p>
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex gap-2">
                        <span class="badge badge-soft badge-primary">
                            <i class="ri-user-line"></i> <?= $classe['nb_etudiants'] ?>
                        </span>
                        <span class="badge badge-soft <?= $classe['nb_absences'] > 0 ? 'badge-warning' : 'badge-success' ?>">
                            <i class="ri-calendar-close-line"></i> <?= $classe['nb_absences'] ?>
                        </span>
                    </div>
                    <a href="?controllers=attacher&page=classes&details_classe_id=<?= $classe['id_classe'] ?>"
                        class="btn btn-soft btn-primary">
                        Détails
                    </a>
                </div>
                <div class="flex justify-center items-center w-10 h-10 rounded-full border border-gray-200 shadow-md absolute top-2 right-2"><i class="ri-arrow-right-up-box-line text-lg"></i></div>
            </div>
        </div>

        <!-- Modal Détails -->
        <dialog id="modal_<?= $classe['id_classe'] ?>" class="modal <?= (isset($details) && $details['info']['id_classe'] == $classe['id_classe'] ? 'modal-open' : '') ?>">
            <div class="modal-box max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-lg">Détails de la classe <?= $classe['libelle'] ?></h3>
                    <a href="<?= ROOT_URL ?>?controllers=attacher&page=classes" class="btn btn-sm btn-circle btn-ghost">✕</a>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold">Informations</h4>
                        <p>Filière: <?= $classe['filiere'] ?></p>
                        <p>Année scolaire: <?= $classe['annee_scolaire'] ?></p>
                        <p>Effectif: <?= $classe['nb_etudiants'] ?> étudiants</p>
                    </div>
                    <div>
                        <h4 class="font-semibold">Statistiques</h4>
                        <p>Absences totales: <?= $classe['nb_absences'] ?></p>
                        <p>Taux d'absentéisme:
                            <?= $classe['nb_etudiants'] > 0
                                ? round(($classe['nb_absences'] / $classe['nb_etudiants']) * 100, 2)
                                : 0
                            ?>%
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Liste des étudiants</h4>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($details) && $details['info']['id_classe'] == $classe['id_classe']): ?>
                                    <?php foreach ($details['etudiants'] as $etudiant): ?>
                                        <tr>
                                            <td><?= $etudiant['matricule'] ?></td>
                                            <td><?= $etudiant['prenom'] ?> <?= $etudiant['nom'] ?></td>
                                            <td><?= $etudiant['email'] ?></td>
                                            <td><?= $etudiant['telephone'] ?? 'N/A' ?></td>
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
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Fermer</button>
                    </form>
                </div>
            </div>
        </dialog>
    <?php endforeach; ?>
</div>