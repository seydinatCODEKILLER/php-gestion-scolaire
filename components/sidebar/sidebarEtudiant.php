<div
    id="sidebar"
    class="flex flex-col justify-between p-3 fixed left-0 shadow-md h-full bg-white text-gray-900 w-64 lg:w-52 md:flex transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 z-50">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between">
            <div class="flex items-center gap-2 text-md">
                <i class="ri-funds-fill text-xl"></i>
                <span class="font-medium">Academica.co</span>
            </div>
            <div class="lg:hidden" id="sidebar-close">
                <i class="ri-layout-right-line text-lg font-semibold"></i>
            </div>
        </div>
        <nav>
            <ul class="flex flex-col gap-1">
                <li class="py-2 px-4 <?= $page === 'dashboard' ? ' bg-gray-50 rounded border border-purple-300' : 'hover:bg-gray-50' ?> ">
                    <a
                        href="<?= ROOT_URL ?>?controllers=etudiant&page=dashboard"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-home-3-line text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'cours' ? 'bg-gray-50 rounded border border-purple-300' : 'hover:bg-gray-50' ?> ">
                    <a
                        href="<?= ROOT_URL ?>?controllers=etudiant&page=cours"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-archive-line text-lg"></i>
                        <span>Mes cours</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'justifications' ? 'bg-gray-50 rounded border border-purple-300' : 'hover:bg-gray-50' ?>">
                    <a
                        href="<?= ROOT_URL ?>?controllers=etudiant&page=justifications"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-questionnaire-line text-lg"></i>
                        <span>Justifications</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'etudiants' ? 'bg-gray-50 rounded border border-purple-300' : 'hover:bg-gray-50' ?>">
                    <a
                        href="<?= ROOT_URL ?>?controllers=etudiant&page=absences"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-file-marked-line text-lg"></i>
                        <span>Absences</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="flex items-center justify-between">
        <div class="flex gap-1">
            <img src="uploads/etudiant/<?= getDataFromSession("user", "avatar") ?>" alt="" class="w-10 h-10 rounded object-cover">
            <div class="flex flex-col">
                <span class="text-sm text-purple-500 font-medium"><?= getDataFromSession("user", "libelle") ?></span>
                <p class="font-medium text-gray-800 text-sm"><?= getDataFromSession("user", "prenom") ?> <?= getDataFromSession("user", "nom") ?></p>
            </div>
        </div>
        <div class="dropdown dropdown-top w-10 h-10 flex justify-center items-center hover:bg-gray-50 hover:rounded">
            <i class="ri-expand-up-down-line " tabindex="0" role="button"></i>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <li>
                    <a href="<?= ROOT_URL ?>?controllers=etudiant&page=profils" class="text-sm font-semibold border-b border-gray-100">
                        <i class="ri-settings-2-line font-medium"></i>
                        <span>Mon compte</span>
                    </a>
                </li>
                <li>
                    <a href="<?= ROOT_URL ?>?controllers=security&page=deconnexion" class="text-sm font-semibold">
                        <i class="ri-logout-box-r-line font-medium"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>