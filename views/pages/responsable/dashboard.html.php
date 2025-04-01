<div class="px-3 mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <div
            class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded bg-blue-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-group-3-line text-blue-500 font-medium"></i>
                </div>
                <p class="text-blue-500 font-medium text-lg">Classes</p>
            </div>
            <div>
                <p class="font-medium text-5xl" id="totalPatients"><?= $data["classes"]["nb_classes"] ?></p>
                <span class="text-gray-400 font-medium">Total de vos classes disponibles</span>
            </div>
            <div
                class="w-12 h-12 rounded-full bg-blue-50 flex justify-center items-center cursor-pointer absolute top-2 right-2">
                <i class="ri-arrow-right-up-box-line text-xl"></i>
            </div>
        </div>
        <div
            class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded bg-orange-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-calendar-line text-orange-500 font-medium"></i>
                </div>
                <p class="text-orange-500 font-medium text-lg">Professeurs</p>
            </div>
            <div>
                <p class="font-medium text-5xl" id="totalAppointments"><?= $data["professeurs"]["nb_professeurs"] ?></p>
                <span class="text-gray-400 font-medium">Total de vos professeurs disponibles</span>
            </div>
            <div
                class="w-12 h-12 rounded-full bg-orange-50 flex justify-center items-center cursor-pointer absolute top-2 right-2">
                <i class="ri-arrow-right-up-box-line text-xl"></i>
            </div>
        </div>
        <div
            class="p-3 rounded bg-white shadow-sm flex flex-col gap-2 relative transition-transform duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded bg-green-50 flex justify-center items-center cursor-pointer">
                    <i class="ri-history-line text-green-500 font-medium"></i>
                </div>
                <p class="text-green-500 font-medium text-lg">
                    Cours
                </p>
            </div>
            <div>
                <p class="font-medium text-5xl" id="pendingAppointments"><?= $data["cours"]["nb_cours"] ?></p>
                <span class="text-gray-400 font-medium">Total de vos cours</span>
            </div>
            <div
                class="w-12 h-12 rounded-full bg-green-50 flex justify-center items-center cursor-pointer absolute top-2 right-2">
                <i class="ri-arrow-right-up-box-line text-xl"></i>
            </div>
        </div>
    </div>
</div>
<div class="px-3 mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="p-3 rounded bg-white shadow-sm flex flex-col gap-3 cursor-pointer">
            <p class="text-gray-600">Evolution des cours par filieres</p>
            <canvas id="filiereChart"></canvas>
        </div>
    </div>
</div>
<script>
    const ctx = document.getElementById('filiereChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($coursFiliere, 'libelle')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($coursFiliere, 'nb_cours')) ?>,
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#8B5CF6',
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>