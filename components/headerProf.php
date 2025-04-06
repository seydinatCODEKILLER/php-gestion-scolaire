<div class="flex justify-between items-center px-4 py-2">
    <h1 class="font-medium text-xl hidden lg:block">Bienvenue <?= getDataFromSession("user", "prenom") ?></h1>
    <div class="w-10 h-10 bg-gray-50 flex justify-center items-center rounded-full lg:hidden" id="sidebar-device"><i class="ri-apps-2-line"></i></div>
    <p class="px-3 py-1 rounded-3xl bg-purple-100 text-purple-500 font-medium hidden lg:block"><?= $contenue ?></p>
    <div class="flex items-center gap-4">
        <div class="flex flex-col">
            <span class="text-sm text-end text-purple-500 font-medium"><?= getDataFromSession("user", "libelle") ?></span>
            <span class="text-sm font-medium"><?= getDataFromSession("user", "prenom") ?> <?= getDataFromSession("user", "nom") ?></span>
        </div>
        <img src="uploads/professeur/<?= getDataFromSession("user", "avatar") ?>" alt="" class="w-10 h-10 rounded-full object-cover cursor-pointer hover:border hover:border-gray-300">
    </div>
</div>