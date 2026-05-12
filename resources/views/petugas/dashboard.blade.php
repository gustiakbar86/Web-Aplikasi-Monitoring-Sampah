@extends('petugas.layout')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="content-area p-4">
    <div class="bg-primary text-white p-4 rounded mb-4">
        <h4 class="mb-0">Selamat Datang Petugas {{ $instansi->nama_instansi ?? 'Instansi' }}</h4>
    </div>
    
    <!-- Filter Periode -->
    <div class="bg-white p-4 rounded shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0">Statistik Sampah</h5>
            <div class="d-flex gap-2">
                <!-- Filter Tipe Sampah -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle border" type="button" id="typeFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-2"></i>
                        <span id="selectedType">Semua Sampah</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="typeFilter">
                        <li><a class="dropdown-item active" href="#" data-type="both">Semua Sampah</a></li>
                        <li><a class="dropdown-item" href="#" data-type="terkelola">Sampah Terkelola</a></li>
                        <li><a class="dropdown-item" href="#" data-type="diserahkan">Sampah Diserahkan</a></li>
                    </ul>
                </div>
                <!-- Filter Periode -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle border" type="button" id="periodFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span id="selectedPeriod">Minggu Ini</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="periodFilter">
                        <li><a class="dropdown-item" href="#" data-period="daily">Hari Ini</a></li>
                        <li><a class="dropdown-item active" href="#" data-period="weekly">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#" data-period="monthly">Bulan Ini</a></li>
                        <li><a class="dropdown-item" href="#" data-period="yearly">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <h6 class="fw-bold mb-4">Distribusi Jenis Sampah</h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <h6 class="fw-bold mb-4">Trend Berat Sampah</h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-area {
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
    }

    .bg-primary {
        background-color: #1e3f8c !important;
    }

    .rounded {
        border-radius: 8px !important;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    h6.fw-bold {
        color: #1e3f8c;
    }

    .dropdown-item.active {
        background-color: #1e3f8c;
        color: white;
    }

    .btn-light {
        background-color: white;
        border-color: #dee2e6;
    }

    .btn-light:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    canvas {
        max-width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup default charts
        let pieChart = null;
        let barChart = null;

        // Colors for different waste types
        const colors = {
            'Organik': '#28a745',
            'Anorganik': '#dc3545',
            'Residu': '#ffc107'
        };

        // Chart.js default settings
        Chart.defaults.font.family = "'Segoe UI', 'Arial', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.position = 'bottom';

        // Function to transform labels for display
        function transformLabel(label) {
            const labelMap = {
                'organik': 'Organik Terkelola',
                'anorganik': 'Anorganik Terkelola',
                'residu': 'Residu Diserahkan',
                'Organik': 'Organik Terkelola',
                'Anorganik': 'Anorganik Terkelola',
                'Residu': 'Residu Diserahkan'
            };
            return labelMap[label] || label;
        }

        function initializeCharts(data) {
            // Validar data struktur
            if (!data || !data.distribution || !data.trend) {
                console.error('Invalid data structure:', data);
                return;
            }

            // Set default data if empty
            if (Object.keys(data.distribution).length === 0) {
                data.distribution = {
                    'organik': 0,
                    'anorganik': 0,
                    'residu': 0
                };
            }

            // Validasi trend data
            if (!Array.isArray(data.trend.labels) || !Array.isArray(data.trend.values)) {
                console.error('Invalid trend data:', data.trend);
                return;
            }

            // Destroy existing charts if they exist
            if (pieChart) pieChart.destroy();
            if (barChart) barChart.destroy();

            // Setup Pie Chart
            const pieCtx = document.getElementById('pieChart');
            if (!pieCtx) {
                console.error('Pie chart canvas not found');
                return;
            }

            // Transform labels for display
            const transformedLabels = Object.keys(data.distribution).map(transformLabel);

            pieChart = new Chart(pieCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: transformedLabels,
                    datasets: [{
                        data: Object.values(data.distribution),
                        backgroundColor: Object.keys(data.distribution).map(key => colors[key.charAt(0).toUpperCase() + key.slice(1)] || colors[key] || '#cccccc'),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => (a || 0) + (b || 0), 0) || 1;
                                    const percentage = total ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: ${value}kg (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Setup Bar Chart
            const barCtx = document.getElementById('barChart');
            if (!barCtx) {
                console.error('Bar chart canvas not found');
                return;
            }

            barChart = new Chart(barCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.trend.labels || [],
                    datasets: [{
                        label: 'Berat (Kg)',
                        data: data.trend.values || [],
                        backgroundColor: '#1e3f8c',
                        borderColor: '#1e3f8c',
                        borderWidth: 1,
                        borderRadius: 4,
                        maxBarThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Berat: ${context.raw || 0} kg`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Berat (Kg)',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: '#e9ecef'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Function to load data based on period and type
        let currentType = 'both';
        let currentPeriod = 'weekly';

        async function loadData(period = currentPeriod, type = currentType) {
            try {
                const url = `{{ route('petugas.statistik') }}?period=${encodeURIComponent(period)}&type=${encodeURIComponent(type)}`;
                console.log('Loading data from:', url);
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Received data:', data);
                
                initializeCharts(data);
                currentPeriod = period;
                currentType = type;
            } catch (error) {
                console.error('Error loading data:', error);
                // Tampilkan chart dengan data default
                initializeCharts({
                    distribution: {
                        'Organik': 0,
                        'Anorganik': 0,
                        'Residu': 0
                    },
                    trend: {
                        labels: [],
                        values: []
                    }
                });
            }
        }

        // Handle type filter changes
        document.querySelectorAll('[data-type]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Update active state
                document.querySelectorAll('[data-type]').forEach(el => {
                    el.classList.remove('active');
                });
                e.target.classList.add('active');

                // Update button text
                const typeText = e.target.textContent;
                document.getElementById('selectedType').textContent = typeText;

                // Load new data
                const type = e.target.getAttribute('data-type');
                loadData(currentPeriod, type);
            });
        });

        // Handle period filter changes
        document.querySelectorAll('[data-period]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();

                // Update active state
                document.querySelectorAll('[data-period]').forEach(el => {
                    el.classList.remove('active');
                });
                e.target.classList.add('active');

                // Update button text
                const periodText = e.target.textContent;
                document.getElementById('selectedPeriod').textContent = periodText;

                // Load new data
                const period = e.target.getAttribute('data-period');
                loadData(period, currentType);
            });
        });

        // Initial load
        loadData('weekly', 'both');
    });
</script>
@endpush
