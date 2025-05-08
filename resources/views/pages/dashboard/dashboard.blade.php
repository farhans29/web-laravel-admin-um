<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Business Operations Banner -->
        <div class="relative bg-gray-800 p-4 sm:p-6 rounded-sm overflow-hidden mb-8 border-l-4 border-blue-500 sticky top-0 z-10">
            {{-- relative bg-gray-800 p-4 sm:p-6 rounded-sm overflow-hidden mb-8 border-l-4 border-blue-500 --}}
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center opacity-20"
                style="background-image: url('{{ asset('images/dashboard.avif') }}')">
            </div>

            <!-- Content -->
            <div class="relative ">
                <h1 class="text-2xl md:text-3xl text-white font-bold mb-1">
                    BUSINESS OPERATIONS DASHBOARD
                </h1>
                <p class="text-blue-300 font-medium">Welcome to {{ $globalTitle }}, {{ Auth::user()->name }}</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    @can('purchasing')
                        <span class="px-3 py-1 bg-blue-600/80 text-white text-sm rounded-full">Purchasing</span>
                    @endcan
                    @can('sales')
                        <span class="px-3 py-1 bg-green-600/80 text-white text-sm rounded-full">Sales</span>
                    @endcan
                    @can('warehouse')
                        <span class="px-3 py-1 bg-yellow-600/80 text-white text-sm rounded-full">Warehouse</span>
                    @endcan
                    @can('accounting')
                        <span class="px-3 py-1 bg-purple-600/80 text-white text-sm rounded-full">Accounting</span>
                    @endcan
                    @can('finance')
                        <span class="px-3 py-1 bg-red-600/80 text-white text-sm rounded-full">Finance</span>
                    @endcan
                    @can('tax')
                        <span class="px-3 py-1 bg-indigo-600/80 text-white text-sm rounded-full">Tax</span>
                    @endcan
                    @can('human_resource')
                        <span class="px-3 py-1 bg-pink-600/80 text-white text-sm rounded-full">HR</span>
                    @endcan
                    @can('general_affairs')
                        <span class="px-3 py-1 bg-teal-600/80 text-white text-sm rounded-full">General Affairs</span>
                    @endcan
                    @can('logistics')
                        <span class="px-3 py-1 bg-orange-600/80 text-white text-sm rounded-full">Logistic</span>
                    @endcan
                    @can('aju')
                        <span class="px-3 py-1 bg-gray-600/80 text-white text-sm rounded-full">Archive</span>
                    @endcan
                </div>

                <div class="mt-6 pt-4 border-t border-gray-700/50">
                    <div class="flex items-center text-gray-300 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 animate-pulse text-green-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Live Data: Monthly revenue $1.2M | 84 pending purchase orders | 32 shipments today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Stats Grid - First Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @can('purchasing')
                <!-- Purchasing -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Pending POs</h3>
                            <p class="text-2xl font-bold">84</p>
                            <p class="text-red-500 text-sm">↑ 12% from last week</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Purchasing</span>
                    </div>
                </div>
            @endcan

            @can('sales')
                <!-- Sales -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Monthly Sales</h3>
                            <p class="text-2xl font-bold">$1.2M</p>
                            <p class="text-green-500 text-sm">↑ 8% from last month</p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Sales</span>
                    </div>
                </div>
            @endcan

            @can('warehouse')
                <!-- Warehouse -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Inventory Value</h3>
                            <p class="text-2xl font-bold">$850K</p>
                            <p class="text-yellow-500 text-sm">↓ 5% from target</p>
                        </div>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Warehouse</span>
                    </div>
                </div>
            @endcan

            @can('accounting')
                <!-- Accounting -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">AR Aging</h3>
                            <p class="text-2xl font-bold">$320K</p>
                            <p class="text-purple-500 text-sm">15% > 30 days</p>
                        </div>
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Accounting</span>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Department Stats Grid - Second Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @can('finance')
                <!-- Finance -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-red-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Cash Position</h3>
                            <p class="text-2xl font-bold">$2.4M</p>
                            <p class="text-green-500 text-sm">↑ 12% YoY</p>
                        </div>
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Finance</span>
                    </div>
                </div>
            @endcan

            @can('tax')
                <!-- Tax -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-indigo-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Tax Liability</h3>
                            <p class="text-2xl font-bold">$185K</p>
                            <p class="text-indigo-500 text-sm">Due in 23 days</p>
                        </div>
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded">Tax</span>
                    </div>
                </div>
            @endcan

            @can('human_resource')
                <!-- HR -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-pink-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Employees</h3>
                            <p class="text-2xl font-bold">142</p>
                            <p class="text-pink-500 text-sm">3 open positions</p>
                        </div>
                        <span class="px-2 py-1 bg-pink-100 text-pink-800 text-xs rounded">HR</span>
                    </div>
                </div>
            @endcan

            @can('general_affairs')
                <!-- General Affairs -->
                <div class="bg-white p-4 rounded shadow border-t-4 border-teal-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Facility Issues</h3>
                            <p class="text-2xl font-bold">7</p>
                            <p class="text-teal-500 text-sm">2 high priority</p>
                        </div>
                        <span class="px-2 py-1 bg-teal-100 text-teal-800 text-xs rounded">GA</span>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Combined Department Sections -->
        @if (auth()->user()->canAny(['purchasing', 'warehouse', 'sales', 'finance']))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                @can('purchasing', 'warehouse')
                    <!-- Purchasing & Warehouse -->
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Procurement & Inventory</h2>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Purchasing &
                                Warehouse</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-3 bg-blue-50 rounded">
                                <p class="text-sm text-gray-600">Open POs</p>
                                <p class="text-xl font-bold">84</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded">
                                <p class="text-sm text-gray-600">Avg. Lead Time</p>
                                <p class="text-xl font-bold">5.2 days</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded">
                                <p class="text-sm text-gray-600">Stock Alerts</p>
                                <p class="text-xl font-bold">12 items</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded">
                                <p class="text-sm text-gray-600">Inventory Turns</p>
                                <p class="text-xl font-bold">4.2x</p>
                            </div>
                        </div>

                        <div class="h-64 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                @endcan

                @can('sales', 'finance')
                    <!-- Sales & Finance -->
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Revenue & Finance</h2>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Sales & Finance</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-3 bg-green-50 rounded">
                                <p class="text-sm text-gray-600">MTD Sales</p>
                                <p class="text-xl font-bold">$420K</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded">
                                <p class="text-sm text-gray-600">Open Quotes</p>
                                <p class="text-xl font-bold">$1.1M</p>
                            </div>
                            <div class="p-3 bg-red-50 rounded">
                                <p class="text-sm text-gray-600">Cash Flow</p>
                                <p class="text-xl font-bold">$240K</p>
                            </div>
                            <div class="p-3 bg-red-50 rounded">
                                <p class="text-sm text-gray-600">Outstanding AR</p>
                                <p class="text-xl font-bold">$320K</p>
                            </div>
                        </div>

                        <div class="h-64 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                @endcan
            </div>
        @endif

        <!-- Additional Department Sections -->
        @if (auth()->user()->canAny(['human_resource', 'general_affairs', 'logistics', 'tax', 'accounting']))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                @can('human_resource', 'general_affairs')
                    <!-- HR & General Affairs -->
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">People & Facilities</h2>
                            <span class="px-3 py-1 bg-pink-100 text-pink-800 text-sm rounded-full">HR & GA</span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Employees</span>
                                <span class="font-medium">142</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Open Positions</span>
                                <span class="font-medium">3</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">On Leave</span>
                                <span class="font-medium">5</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Facilities Tickets</span>
                                <span class="font-medium">7</span>
                            </div>
                        </div>

                        <div class="mt-4 h-40 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                            [Headcount Chart]
                        </div>
                    </div>
                @endcan

                @can('logistics')
                    <!-- Logistics -->
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Logistics</h2>
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full">Shipping &
                                Receiving</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-3 bg-orange-50 rounded">
                                <p class="text-sm text-gray-600">Today's Shipments</p>
                                <p class="text-xl font-bold">32</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded">
                                <p class="text-sm text-gray-600">On-Time Rate</p>
                                <p class="text-xl font-bold">94%</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded">
                                <p class="text-sm text-gray-600">Inbound Today</p>
                                <p class="text-xl font-bold">18</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded">
                                <p class="text-sm text-gray-600">Freight Costs</p>
                                <p class="text-xl font-bold">$8.2K</p>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('tax', 'accounting')
                    <!-- Tax & Accounting -->
                    <div class="bg-white p-6 rounded shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Finance & Compliance</h2>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm rounded-full">Tax &
                                Accounting</span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Current Tax Liability</span>
                                <span class="font-medium">$185K</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">AP Due This Week</span>
                                <span class="font-medium">$42K</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">AR Over 60 Days</span>
                                <span class="font-medium">$48K</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Month-End Status</span>
                                <span class="font-medium text-green-600">On Track</span>
                            </div>
                        </div>

                        <div class="mt-4 h-40 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                            [Financial Compliance Status]
                        </div>
                    </div>
                @endcan
            </div>
        @endif

        @can('aju')
            <!-- Archive Section -->
            <div class="bg-white p-6 rounded shadow mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Document Archive</h2>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">Records Management</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-3 bg-gray-50 rounded text-center">
                        <p class="text-sm text-gray-600">Total Documents</p>
                        <p class="text-xl font-bold">12,842</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded text-center">
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-xl font-bold">147</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded text-center">
                        <p class="text-sm text-gray-600">Pending Review</p>
                        <p class="text-xl font-bold">23</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded text-center">
                        <p class="text-sm text-gray-600">Storage Used</p>
                        <p class="text-xl font-bold">78%</p>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    @section('js-page')
        <script>
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            const inventoryChart = new Chart(inventoryCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Inventory Value',
                        data: [125, 132, 118, 145, 152, 138, 142],
                        borderColor: 'rgba(99, 102, 241, 1)',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Stock Alerts',
                        data: [8, 10, 12, 15, 11, 9, 12],
                        borderColor: 'rgba(245, 158, 11, 1)',
                        backgroundColor: 'rgba(245, 158, 11, 0.08)',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            bodyFont: {
                                family: 'Inter, sans-serif'
                            },
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.dataset.label === 'Inventory Value') {
                                        label += '$' + context.raw + 'K';
                                    } else {
                                        label += context.raw + ' items';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(226, 232, 240, 0.5)'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif'
                                },
                                callback: function(value) {
                                    return '$' + value + 'K';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animations: {
                        tension: {
                            duration: 1000,
                            easing: 'linear',
                            from: 0.5,
                            to: 0.4,
                            loop: false
                        }
                    }
                }
            });

            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Closed Sales',
                        data: [380, 420, 395, 450, 480, 410, 440],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                        borderRadius: 6,
                        borderSkipped: false
                    }, {
                        label: 'Quotes',
                        data: [520, 580, 540, 600, 620, 590, 610],
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        hoverBackgroundColor: 'rgba(99, 102, 241, 1)',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            bodyFont: {
                                family: 'Inter, sans-serif'
                            },
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.raw + 'K';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(226, 232, 240, 0.5)'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif'
                                },
                                callback: function(value) {
                                    return '$' + value + 'K';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        delay: function(context) {
                            return context.dataIndex * 100;
                        }
                    }
                }
            });
        </script>
    @endsection
</x-app-layout>
