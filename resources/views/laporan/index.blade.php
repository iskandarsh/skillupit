<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Analytics Dashboard</h1>
                    <p class="text-gray-500 font-medium">Pantau pertumbuhan dan aktivitas komunitas SkillUpIT.</p>
                </div>

            </div>

            <!-- Main Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>

                    </div>
                    <p class="text-sm font-medium text-gray-500">Total Mentor</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $totalMentor }}
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-full">New</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Total Kelas</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $totalKelas }}
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">85% Rate</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Total Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">156</p>
                </div>

                <!-- Card 4 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Active</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Referral</p>
                    <p class="text-2xl font-bold text-gray-900">89</p>
                </div>
            </div>

            <!-- Visualization Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Bar Chart (Span 2) -->
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-gray-800">Popularitas Kelas</h3>
                        <select class="text-sm border-none bg-gray-50 rounded-lg focus:ring-0">
                            <option>7 Hari Terakhir</option>
                            <option>30 Hari Terakhir</option>
                        </select>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="kelasChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart (Span 1) -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-8 text-center">Efikasi Belajar</h3>
                    <div class="relative h-[250px]">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span class="flex items-center"><span class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></span>Lulus</span>
                            <span class="font-bold text-gray-900">65%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span class="flex items-center"><span class="w-3 h-3 bg-amber-400 rounded-full mr-2"></span>On Progress</span>
                            <span class="font-bold text-gray-900">25%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global Style Overrides
        Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Bar Chart: Kelas
        const ctxKelas = document.getElementById('kelasChart').getContext('2d');
        new Chart(ctxKelas, {
            type: 'bar',
            data: {
                labels: ['Laravel', 'Flutter', 'UI/UX', 'NodeJS', 'Python'],
                datasets: [{
                    label: 'Peminat',
                    data: [88, 62, 45, 76, 32],
                    backgroundColor: '#6366f1',
                    borderRadius: 12,
                    barThickness: 40,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        border: {
                            display: false
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Doughnut Chart: Status
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Lulus', 'On Progress', 'Lainnya'],
                datasets: [{
                    data: [156, 84, 25],
                    backgroundColor: ['#6366f1', '#fbbf24', '#f1f5f9'],
                    hoverOffset: 10,
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '82%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-app-layout>