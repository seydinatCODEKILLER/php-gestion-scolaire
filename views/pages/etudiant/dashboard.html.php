<div class="px-3">
    <div class="fixed top-5 right-5 space-y-4 transition transform duration-300 opacity-0 translate-y-2" id="alerter">
        <?php if ($message): ?>
            <div role="alert" class="alert alert-success w-96 text-white">
                <i class="ri-checkbox-circle-fill"></i>
                <span><?= $message ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 py-5">
        <div class="p-3 rounded bg-white border border-blue-100 shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-blue-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-book-line text-blue-500 font-medium"></i>
                </div>
                <p class="text-blue-500 font-medium text-lg">Absences</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $data["absences"] ?></p>
                <span class="text-gray-400 font-medium text-sm">Total de vos absences</span>
            </div>
        </div>
        <div class="p-3 rounded bg-white border border-orange-100 shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-orange-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-book-line text-orange-500 font-medium"></i>
                </div>
                <p class="text-orange-500 font-medium text-lg">Cours</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $data["cours_suivis"] ?></p>
                <span class="text-gray-400 font-medium text-sm">Total vos cours suivit</span>
            </div>
        </div>
        <div class="p-3 rounded bg-white border border-purple-100 shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-purple-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-book-line text-purple-500 font-medium"></i>
                </div>
                <p class="text-purple-500 font-medium text-lg">Justifications</p>
            </div>
            <div>
                <p class="font-medium text-5xl"><?= $data["justifications_soumises"] ?></p>
                <span class="text-gray-400 font-medium text-sm">Total vos justifications en attente</span>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-white shadow-sm flex flex-col gap-2 p-2">
            <?php if (!empty($data["emploi_du_temps"])): ?>
                <p class="text-gray-500 font-medium mb-3"> <i class="ri-calendar-todo-line"></i> Vos cours de la journée</p>
                <div class="space-y-4">
                    <?php foreach ($data["emploi_du_temps"] as $cours): ?>
                        <!-- Carte de cours -->
                        <div class="group relative p-3 border rounded-lg border-indigo-100 bg-indigo-50/30 transition-all duration-300">
                            <!-- Fond décoratif au hover -->
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-50/50 to-white group-opacity-100 rounded-lg transition-opacity duration-300"></div>
                            <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-4">
                                <!-- Plage horaire -->
                                <div class="md:col-span-2 flex items-center">
                                    <div class="bg-white p-1 rounded-lg shadow-xs border border-gray-100 text-center min-w-[80px]">
                                        <p class="font-bold text-gray-800 text-lg">
                                            <?= substr($cours['heure_debut'], 0, 5) ?>
                                        </p>
                                        <div class="h-4 flex items-center justify-center">
                                            <div class="w-3 h-px bg-gray-300"></div>
                                        </div>
                                        <p class="font-bold text-gray-800 text-lg">
                                            <?= substr($cours['heure_fin'], 0, 5) ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Module et professeur -->
                                <div class="md:col-span-7">
                                    <h3 class="font-semibold text-gray-800 text-lg mb-1 group-hover:text-indigo-600 transition-colors">
                                        <?= htmlspecialchars($cours['module']) ?>
                                    </h3>
                                    <p class="flex items-center text-gray-500 text-sm">
                                        <span class="badge badge-soft badge-primary"> <i class="ri-user-3-line mr-1.5"></i> Professeur : </span>
                                        <?= htmlspecialchars($cours['professeur']) ?>
                                    </p>
                                </div>

                                <!-- Salle et badge -->
                                <div class="md:col-span-3 flex flex-col items-end justify-between">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-medium">
                                        <i class="ri-building-2-line mr-1.5"></i>
                                        <?= htmlspecialchars($cours['salle']) ?>
                                    </span>
                                    <span class="text-xs text-gray-400 mt-2">
                                        <?= date('H:i', strtotime($cours['heure_fin'])) === '12:00' ? 'Midi' : '' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="flex flex-col gap-3 items-center justify-center">
                    <img src="assets/recherche.png" class="object-cover" alt="">
                    <p class="text-sm text-gray-500">Aucun cours prevue pour cette semaine</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="w-full">
            <calendar-date class="cally bg-base-100 border border-base-300 shadow-lg rounded-box w-full">
                <svg aria-label="Previous" class="fill-current size-4" slot="previous" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                </svg>
                <svg aria-label="Next" class="fill-current size-4" slot="next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                </svg>
                <calendar-month></calendar-month>
            </calendar-date>
        </div>
    </div>