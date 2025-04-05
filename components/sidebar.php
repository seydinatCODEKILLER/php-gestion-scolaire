<div
    id="sidebar"
    class="flex flex-col justify-between p-3 fixed left-0 shadow-md h-full text-gray-900 w-64 lg:w-52 md:flex transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 z-50">
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
        <?php if ($role === "RP"): ?>
            <nav>
                <ul>
                    <li class="py-2 px-4 <?= $page === 'dashboard' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=dashboard"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-home-3-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'classes' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=classes"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-group-line text-lg"></i>
                            <span>Classes</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'professeurs' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=professeurs"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-user-line text-lg"></i>
                            <span>Professeurs</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'cours' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=cours"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-book-open-line text-lg"></i>
                            <span>Cours</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'filieres' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=filieres"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-stack-line text-lg"></i>
                            <span>Filieres</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'niveaus' ? 'bg-purple-600 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=responsable&page=niveaus"
                            class="font-medium gap-3 flex items-center text-sm">
                            <i class="ri-voice-ai-line text-lg"></i>
                            <span>Niveaux</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php elseif ($role === "Professeur"): ?>
            <nav>
                <ul>
                    <li class="py-2 px-4 <?= $page === 'dashboard' ? 'bg-orange-500 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=dashboard"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-home-3-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'cours' ? 'bg-orange-500 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=cours"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-honour-line text-lg"></i>
                            <span>Mes cours</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 <?= $page === 'absences' ? 'bg-orange-500 text-white shadow-xl' : 'hover:bg-gray-200' ?> rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=absences"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-line-chart-fill text-lg"></i>
                            <span>Absences</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php elseif ($role === "Etudiant"): ?>
            <nav>
                <ul>
                    <li class="py-2 px-4 bg-purple-50 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=dashboard"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-home-3-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=catalogues"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-nurse-line text-lg"></i>
                            <span>Catalogues</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=reservations"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-user-line text-lg"></i>
                            <span>Reservations</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php elseif ($role === "Attache"): ?>
            <nav>
                <ul>
                    <li class="py-2 px-4 bg-purple-50 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=dashboard"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-home-3-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=catalogues"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-nurse-line text-lg"></i>
                            <span>Catalogues</span>
                        </a>
                    </li>
                    <li class="py-2 px-4 hover:bg-purple-100 rounded-3xl">
                        <a
                            href="<?= ROOT_URL ?>?controllers=client&page=reservations"
                            class="font-medium gap-3 flex items-center">
                            <i class="ri-user-line text-lg"></i>
                            <span>Reservations</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    <a href="<?= ROOT_URL ?>?controllers=security&page=deconnexion">
        <button
            class="px-2 py-2 rounded-3xl border boder-gray-200 font-medium shadow-xl w-full">
            <i class="ri-logout-box-r-line font-medium"></i>
            <span>Deconnexion</span>
        </button>
    </a>
</div>