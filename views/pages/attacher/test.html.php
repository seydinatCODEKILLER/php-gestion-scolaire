<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sombre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            100: '#1E1E2D',
                            200: '#2D2D3D',
                            300: '#3A3A4A',
                            400: '#4A4A5A',
                        },
                        primary: {
                            500: '#6366F1',
                            600: '#4F46E5',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-item:hover .sidebar-icon {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .data-table th {
            position: sticky;
            top: 0;
            background-color: #2D2D3D;
            z-index: 10;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 bg-dark-100 h-full flex-shrink-0 border-r border-dark-300 flex flex-col">
        <!-- Logo -->
        <div class="p-4 border-b border-dark-300 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-chart-line text-primary-500 text-2xl"></i>
                <span class="text-xl font-bold">AnalyticsPro</span>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 overflow-y-auto py-4 px-2">
            <div class="space-y-1">
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg bg-dark-200 text-white group">
                    <i class="sidebar-icon fas fa-tachometer-alt mr-3 text-primary-500 group-hover:text-white"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                    <i class="sidebar-icon fas fa-users mr-3 text-gray-400 group-hover:text-white"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                    <i class="sidebar-icon fas fa-shopping-cart mr-3 text-gray-400 group-hover:text-white"></i>
                    <span>Produits</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                    <i class="sidebar-icon fas fa-chart-pie mr-3 text-gray-400 group-hover:text-white"></i>
                    <span>Rapports</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                    <i class="sidebar-icon fas fa-cog mr-3 text-gray-400 group-hover:text-white"></i>
                    <span>Paramètres</span>
                </a>
            </div>

            <div class="mt-8 px-4">
                <h3 class="text-xs uppercase font-semibold text-gray-500 mb-2">Analytics</h3>
                <div class="space-y-1">
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                        <i class="sidebar-icon fas fa-chart-bar mr-3 text-gray-400 group-hover:text-white"></i>
                        <span>Performances</span>
                    </a>
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-lg hover:bg-dark-200 group">
                        <i class="sidebar-icon fas fa-bell mr-3 text-gray-400 group-hover:text-white"></i>
                        <span>Alertes</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-dark-300 flex items-center">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Profile" class="w-10 h-10 rounded-full mr-3">
            <div>
                <div class="font-medium">Sophie Martin</div>
                <div class="text-xs text-gray-500">Admin</div>
            </div>
            <button class="ml-auto text-gray-400 hover:text-white">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-dark-100 border-b border-dark-300 p-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Tableau de bord</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="bg-dark-200 border border-dark-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
                </div>
                <button class="text-gray-400 hover:text-white">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="text-gray-400 hover:text-white">
                    <i class="fas fa-envelope"></i>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-dark-200 rounded-xl p-6 border-l-4 border-primary-500 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Ventes totales</p>
                            <h3 class="text-2xl font-bold mt-1">24,532 €</h3>
                            <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 12.5%</p>
                        </div>
                        <div class="bg-primary-500 bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-shopping-bag text-primary-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-dark-200 rounded-xl p-6 border-l-4 border-blue-400 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Nouveaux clients</p>
                            <h3 class="text-2xl font-bold mt-1">1,245</h3>
                            <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 8.3%</p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-users text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-dark-200 rounded-xl p-6 border-l-4 border-purple-400 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Commandes</p>
                            <h3 class="text-2xl font-bold mt-1">856</h3>
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-arrow-down mr-1"></i> 3.2%</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-boxes text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-dark-200 rounded-xl p-6 border-l-4 border-green-400 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Taux de conversion</p>
                            <h3 class="text-2xl font-bold mt-1">3.6%</h3>
                            <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 1.8%</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-percentage text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graph and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Graph -->
                <div class="lg:col-span-2 bg-dark-200 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold">Performances des ventes</h2>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-dark-300 rounded-lg text-sm">Mois</button>
                            <button class="px-3 py-1 bg-dark-300 rounded-lg text-sm">Semaine</button>
                            <button class="px-3 py-1 bg-primary-500 rounded-lg text-sm">Jour</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-dark-200 rounded-xl p-6 shadow-lg">
                    <h2 class="text-lg font-semibold mb-6">Activité récente</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="bg-primary-500 bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-user-plus text-primary-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Nouveau client</p>
                                <p class="text-sm text-gray-400">Alex Dupont a créé un compte</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 12 minutes</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-green-500 bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-shopping-cart text-green-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Nouvelle commande</p>
                                <p class="text-sm text-gray-400">Commande #4582 pour 120€</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 34 minutes</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-purple-500 bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-truck text-purple-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Livraison</p>
                                <p class="text-sm text-gray-400">Commande #4579 livrée</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 1 heure</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-yellow-500 bg-opacity-20 p-2 rounded-full mr-3">
                                <i class="fas fa-comment text-yellow-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Nouveau commentaire</p>
                                <p class="text-sm text-gray-400">Marie a commenté le produit #245</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 2 heures</p>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-4 py-2 bg-dark-300 rounded-lg text-sm hover:bg-dark-300">Voir tout</button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-dark-200 rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold">Dernières commandes</h2>
                    <button class="px-4 py-2 bg-primary-500 rounded-lg text-sm hover:bg-primary-600">Exporter</button>
                </div>
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="data-table w-full">
                        <thead>
                            <tr class="text-left text-gray-400 text-sm">
                                <th class="pb-4 pl-4">ID</th>
                                <th class="pb-4">Client</th>
                                <th class="pb-4">Date</th>
                                <th class="pb-4">Montant</th>
                                <th class="pb-4">Statut</th>
                                <th class="pb-4 pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-dark-300 hover:bg-dark-300">
                                <td class="py-3 pl-4">#4582</td>
                                <td class="py-3">Jean Dubois</td>
                                <td class="py-3">12/06/2023</td>
                                <td class="py-3">120€</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-green-500 bg-opacity-20 text-green-500 rounded-full text-xs">Payé</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <button class="text-gray-400 hover:text-white mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-dark-300 hover:bg-dark-300">
                                <td class="py-3 pl-4">#4581</td>
                                <td class="py-3">Marie Lambert</td>
                                <td class="py-3">11/06/2023</td>
                                <td class="py-3">85€</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-yellow-500 bg-opacity-20 text-yellow-500 rounded-full text-xs">En attente</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <button class="text-gray-400 hover:text-white mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-dark-300 hover:bg-dark-300">
                                <td class="py-3 pl-4">#4580</td>
                                <td class="py-3">Thomas Martin</td>
                                <td class="py-3">11/06/2023</td>
                                <td class="py-3">210€</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-blue-500 bg-opacity-20 text-blue-500 rounded-full text-xs">Expédié</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <button class="text-gray-400 hover:text-white mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-dark-300 hover:bg-dark-300">
                                <td class="py-3 pl-4">#4579</td>
                                <td class="py-3">Sophie Leroy</td>
                                <td class="py-3">10/06/2023</td>
                                <td class="py-3">150€</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-purple-500 bg-opacity-20 text-purple-500 rounded-full text-xs">Livré</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <button class="text-gray-400 hover:text-white mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-dark-300">
                                <td class="py-3 pl-4">#4578</td>
                                <td class="py-3">Pierre Bernard</td>
                                <td class="py-3">09/06/2023</td>
                                <td class="py-3">95€</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-red-500 bg-opacity-20 text-red-500 rounded-full text-xs">Annulé</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <button class="text-gray-400 hover:text-white mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Chart.js Configuration
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                        label: '2022',
                        data: [6500, 5900, 8000, 8100, 5600, 5500, 4000, 6300, 7200, 7800, 9000, 9500],
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '2023',
                        data: [7000, 6200, 8500, 9000, 6000, 7500, 6500, 8000, 8500, 9000, 9500, 10000],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#E5E7EB'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1F2937',
                        titleColor: '#F3F4F6',
                        bodyColor: '#E5E7EB',
                        borderColor: '#4B5563',
                        borderWidth: 1,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#374151',
                            borderColor: '#4B5563'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    },
                    y: {
                        grid: {
                            color: '#374151',
                            borderColor: '#4B5563'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            callback: function(value) {
                                return value + '€';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
</body>

</html>