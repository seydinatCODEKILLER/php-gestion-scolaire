<div class="w-1/2 hidden lg:block">
    <img
        src="https://img.freepik.com/vecteurs-libre/illustration-concept-fraicheur_114360-3768.jpg?uid=R105310362&ga=GA1.1.1591275311.1702855758&semt=ais_hybrid&w=740"
        alt=""
        class="w-full h-full object-cover" />
</div>
<div class="flex-1 flex flex-col justify-center items-center gap-5 relative">
    <div class="fixed top-5 right-5 space-y-4 transition transform duration-300 opacity-0 translate-y-2" id="alerter">
        <?php if (getFieldError('credentials')): ?>
            <div role="alert" class="alert alert-error w-96">
                <i class="ri-error-warning-line"></i>
                <span><?= getFieldError('credentials') ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="flex justify-start">
        <div class="flex items-center gap-2 text-xl animate-bounce">
            <i class="ri-book-open-fill text-xl"></i>
            <span class="font-medium text-gray-800">Academica.co</span>
        </div>
    </div>
    <p class="text-gray-500 w-full lg:w-96 text-center">
        Bienvenue sur la plateforme de gestion scolaire ! Connectez-vous pour accéder à votre espace personnel
    </p>
    <form method="post" class="w-full md:w-[500px] mx-auto p-3 mt-4">
        <div class="mb-4">
            <label class="block text-gray-500 font-semibold mb-2">Email </label>
            <div class="relative">
                <input
                    type="email"
                    name="email"
                    value="<?= $_POST['email'] ?? ''; ?>"
                    placeholder="Entrez votre email"
                    class="w-full px-4 shadow rounded-3xl py-3 border-b border-gray-200 focus:outline-none focus:border-blue-500" />
                <i class="ri-mail-ai-line absolute right-3 top-3"></i>
            </div>
            <span class="text-red-500"><?= getFieldError('email'); ?></span>
        </div>
        <div class="mb-4">
            <label class="block text-gray-500 font-semibold mb-2">Mot de passe</label>
            <div class="relative">
                <input
                    type="password"
                    name="password"
                    value="<?= $_POST['password'] ?? ''; ?>"
                    placeholder="Entrez votre mot de passe"
                    class="w-full px-4 py-3 shadow rounded-3xl border-b border-gray-200 focus:outline-none focus:border-blue-500" />
                <i class="ri-lock-password-line absolute right-3 top-3"></i>
            </div>
            <span class="text-red-500"><?= getFieldError('password'); ?></span>
        </div>
        <button
            type="submit"
            class="w-full bg-purple-500 text-white py-3 rounded-3xl hover:bg-purple-600 font-semibold">
            Se connecter
        </button>
    </form>
    <div id="notifications" class="fixed top-4 right-4 space-y-2"></div>
</div>