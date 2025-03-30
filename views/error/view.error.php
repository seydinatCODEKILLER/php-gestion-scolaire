<div class="flex h-full flex-col justify-center items-center gap-3">
    <?php if (getDataFromSession("user")): ?>
        <div class="flex justify-center">
            <a href="<?= ROOT_URL ?>?<?= redirectAfterError(getDataFromSession("user", "libelle")) ?>" class="md:font-medium underline">Retourner a votre dashboard <i class="ri-arrow-right-up-box-line"></i></a>
        </div>
    <?php else:  ?>
        <div class="flex justify-center">
            <a href="<?= ROOT_URL ?>" class="md:font-medium underline">Retourner a la page de connexion <i class="ri-arrow-right-up-box-line"></i></a>
        </div>
    <?php endif; ?>
    <img src="assets/error.jpg" class="h-full lg:h-96 object-cover" alt="">
</div>