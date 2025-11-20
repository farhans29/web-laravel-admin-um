<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard Banner -->
        <div class="relative bg-cover bg-center p-4 sm:p-6 rounded-xl overflow-hidden mb-8 shadow-lg"
            style="background-image: url('{{ asset('images/0fd3416c.jpeg') }}')">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gray-900/70"></div>

            <!-- Content -->
            <div class="relative">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl md:text-3xl text-white font-bold mb-1">
                            DASHBOARD FRONT DESK
                        </h1>
                        <p class="text-blue-100 font-medium">Selamat datang kembali, {{ Auth::user()->first_name }}
                            {{ Auth::user()->last_name }}</p>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center bg-white/10 backdrop-blur-sm rounded-lg p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-white text-sm font-medium">Terakhir diperbarui:
                            {{ now()->format('d M, Y H:i') }}</span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Confirm Booking (Upcoming) -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-blue-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Konfirmasi Booking (Mendatang)</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['upcoming'] }}</h3>
                                <p class="text-blue-200 text-xs mt-1">Segera check-in</p>
                            </div>
                            <div class="bg-blue-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Booking (Today) -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-purple-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Konfirmasi Booking (Hari Ini)</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['today'] }}</h3>
                                <p class="text-purple-200 text-xs mt-1">Kedatangan hari ini</p>
                            </div>
                            <div class="bg-purple-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-In -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-green-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Check-In</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['checkin'] }}</h3>
                                <p class="text-green-200 text-xs mt-1">Sedang menginap</p>
                            </div>
                            <div class="bg-green-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-Out -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-yellow-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Check-Out</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['checkout'] }}</h3>
                                <p class="text-yellow-200 text-xs mt-1">Jadwal Check-Out Hari Ini</p>
                            </div>
                            <div class="bg-yellow-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Check-out Section -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all hover:shadow-lg">
                    <div
                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                            <h2 class="font-semibold text-gray-800 text-lg">Check-out Hari Ini</h2>
                        </div>
                        <a href="{{ route('checkin.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        @include('pages.bookings.checkin.partials.checkin_table', [
                            'checkOuts' => $checkOuts,
                            'per_page' => request('per_page', 4),
                            'type' => 'check-out',
                            'showStatus' => false, 
                            'showActions' => false,
                        ])
                    </div>
                    <div class="px-6 py-3 bg-gray-50 text-sm text-gray-500 border-t border-gray-100">
                        Menampilkan {{ min(4, count($checkOuts)) }} dari {{ count($checkOuts) }} check-out mendatang
                    </div>
                </div>

                <!-- Check-in Section -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all hover:shadow-lg">
                    <div
                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-green-50 to-teal-50">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <h2 class="font-semibold text-gray-800 text-lg">Check-in Hari Ini</h2>
                        </div>
                        <a href="{{ route('newReserv.index') }}"
                            class="text-sm font-medium text-green-600 hover:text-green-800 flex items-center">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        @include('pages.bookings.newreservations.partials.newreserve_table', [
                            'checkIns' => $checkIns,
                            'per_page' => request('per_page', 4),
                            'type' => 'check-in',
                            'showStatus' => false, 
                            'showActions' => false,
                        ])
                    </div>
                    <div class="px-6 py-3 bg-gray-50 text-sm text-gray-500 border-t border-gray-100">
                        Menampilkan {{ min(4, count($checkIns)) }} dari {{ count($checkIns) }} check-in mendatang
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Report Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sales Summary & Charts -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Sales Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Transaksi</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    {{ number_format($salesReport['summary']['total_transactions']) }}
                                </h3>
                            </div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-blue-100 text-xs mt-2">30 hari terakhir</p>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Pendapatan Kotor</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    Rp {{ number_format($salesReport['summary']['gross_amount'] / 1000000, 1) }}JT
                                </h3>
                            </div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-green-100 text-xs mt-2">Bruto</p>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Pendapatan Bersih</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    Rp {{ number_format($salesReport['summary']['net_amount'] / 1000000, 1) }}JT
                                </h3>
                            </div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-purple-100 text-xs mt-2">Setelah admin fee</p>
                    </div>

                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Rata-rata</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    Rp {{ number_format($salesReport['summary']['average_transaction'] / 1000, 0) }}K
                                </h3>
                            </div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-orange-100 text-xs mt-2">Per transaksi</p>
                    </div>
                </div>

                <!-- Daily Revenue Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Trend Pendapatan Harian (30 Hari)</h3>
                        <p class="text-sm text-gray-500">Perkembangan pendapatan harian</p>
                    </div>
                    <div class="p-6">
                        <canvas id="dailyRevenueChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Room Type Revenue Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Pendapatan per Tipe Kamar</h3>
                        <p class="text-sm text-gray-500">Distribusi revenue berdasarkan jenis kamar</p>
                    </div>
                    <div class="p-6">
                        <canvas id="roomRevenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Booking Type Distribution -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Distribusi Tipe Booking</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingTypeChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Trend 6 Bulan</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="monthlyTrendChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
                    </div>
                    <div class="p-4">
                        @php
                        $recentTransactions = \App\Models\Transaction::with(['room', 'user'])
                            ->where('transaction_status', 'paid')
                            ->orderBy('transaction_date', 'desc')
                            ->limit(6)
                            ->get();
                        @endphp

                        <div class="space-y-3">
                            @foreach($recentTransactions as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 truncate max-w-[120px]">
                                            {{ $transaction->room_name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $transaction->user_name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format($transaction->grandtotal_price / 1000, 0) }}K
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->transaction_date->format('d M') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Revenue Chart
            const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: @json($salesReport['chart_data']['daily_labels']),
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: @json($salesReport['chart_data']['daily_revenue']),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'K';
                                }
                            }
                        }
                    }
                }
            });

            // Room Revenue Chart
            const roomCtx = document.getElementById('roomRevenueChart').getContext('2d');
            new Chart(roomCtx, {
                type: 'bar',
                data: {
                    labels: @json($salesReport['chart_data']['room_types']),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($salesReport['chart_data']['room_revenues']),
                        backgroundColor: [
                            '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'JT';
                                }
                            }
                        }
                    }
                }
            });

            // Booking Type Chart
            const bookingCtx = document.getElementById('bookingTypeChart').getContext('2d');
            new Chart(bookingCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($salesReport['chart_data']['booking_types']),
                    datasets: [{
                        data: @json($salesReport['chart_data']['booking_counts']),
                        backgroundColor: [
                            '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($salesReport['chart_data']['monthly_labels']),
                    datasets: [{
                        label: 'Pendapatan Bulanan',
                        data: @json($salesReport['chart_data']['monthly_revenue']),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'JT';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>