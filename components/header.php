<div class="flex justify-between items-center px-4 py-2">
    <h1 class="font-medium text-xl">Bienvenue <?= getDataFromSession("user", "prenom") ?> 👋</h1>
    <div class="flex items-center gap-2">
        <div class="flex flex-col">
            <span class="text-sm text-end text-purple-500 font-medium"><?= getDataFromSession("user", "libelle") ?></span>
            <span class="text-sm font-medium"><?= getDataFromSession("user", "prenom") ?> <?= getDataFromSession("user", "nom") ?></span>
        </div>
        <img src="<?= getDataFromSession("user", "avatar") ?>" alt="" class="w-9 h-9 rounded object-cover cursor-pointer hover:border hover:border-gray-300 hover:p-1">
    </div>
</div>