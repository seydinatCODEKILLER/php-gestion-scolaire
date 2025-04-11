<div
    id="sidebar"
    class="flex flex-col justify-between p-3 fixed left-0 shadow-md h-full bg-gray-800 text-white w-64 lg:w-52 md:flex transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 z-50">
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
                <li class="py-2 px-4 <?= $page === 'dashboard' ? 'border-l-4 border-green-500 bg-gray-700 rounded' : 'hover:bg-gray-700 hover:rounded-3xl' ?> ">
                    <a
                        href="<?= ROOT_URL ?>?controllers=attacher&page=dashboard"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-home-3-line text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'classes' ? 'border-l-4 border-green-500 bg-gray-700 rounded' : 'hover:bg-gray-700 hover:rounded-3xl' ?> ">
                    <a
                        href="<?= ROOT_URL ?>?controllers=attacher&page=classes"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-archive-line text-lg"></i>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'justifications' ? 'border-l-4 border-green-500 bg-gray-700 rounded' : 'hover:bg-gray-700 hover:rounded-3xl' ?>">
                    <a
                        href="<?= ROOT_URL ?>?controllers=attacher&page=justifications"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-questionnaire-line text-lg"></i>
                        <span>Justifications</span>
                    </a>
                </li>
                <li class="py-2 px-4 <?= $page === 'etudiants' ? 'border-l-4 border-green-500 bg-gray-700 rounded' : 'hover:bg-gray-700 hover:rounded-3xl' ?>">
                    <a
                        href="<?= ROOT_URL ?>?controllers=attacher&page=etudiants"
                        class="font-medium gap-3 flex items-center text-sm">
                        <i class="ri-file-marked-line text-lg"></i>
                        <span>Absences</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <a href="<?= ROOT_URL ?>?controllers=security&page=deconnexion">
        <button
            class="px-2 py-2 bg-gray-700 text-white hover:bg-green-500 rounded-3xl font-medium shadow-xl w-full">
            <i class="ri-logout-box-r-line font-medium"></i>
            <span>Deconnexion</span>
        </button>
    </a>
</div>