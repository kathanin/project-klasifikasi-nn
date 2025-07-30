@extends('layouts.bootstrap')
@section('judul-content','Dashboard')
@section('content')
<div class="row g-4">
    <!-- Chart 1 -->
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <h5 class="card-title">Jumlah Pengajuan Nasabah</h5>
            <canvas id="barChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
    <div class="card p-3 h-100">
        <h5 class="card-title">Tingkat Penjualan Produk Bulan {{$bulan}}</h5>
        <canvas id="produkChart"></canvas>
    </div>
</div>
</div>
<div class="row mt-4">
    <!-- Chart 2 -->
    <div class="col-md-8">
        <div class="card p-3 mb-4">
            <h5 class="card-title">Tingkat Pengajuan Bulan {{$bulan}}</h5>
            <canvas id="lineChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5 class="card-title">Presentase Klasifikasi</h5>
            <canvas id="doughnutChart"></canvas>
            <div class="mt-2 small">
            <span class="badge bg-success">Low</span>
            <span class="badge bg-warning text-dark">Medium</span>
            <span class="badge bg-danger">High</span>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js-plugin')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
  @section('js-ready')
    // Bar Chart
    new Chart(document.getElementById("barChart"), {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                label: "Pengajuan",
                data: [{{ implode(',', $nasabah) }}],
                backgroundColor: "#007f3e"
            }]
        },
        options: {
            responsive: true,

            scales: { 
                y: {
                    beginAtZero: true, 
                    grid: {
                        drawOnChartArea: false
                    }
                }, 
                x: {
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
        }
    });

    // Bar Chart
    new Chart(document.getElementById("produkChart"), {
        type: "bar",
        data: {
            labels: @json($produkLabels), // Ambil label dari controller
            datasets: [{
                label: "Jumlah",
                data: @json($produkTotals), // Ambil data total dari controller
                backgroundColor: "#007f3e",
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            scales: { 
                y: {
                    beginAtZero: true, 
                    max: 18,
                    ticks: {
                        stepSize: 3
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }, 
                x: {
                    ticks: {
                        maxRotation: 30,
                        minRotation: 30,
                        autoSkip: false
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Line Chart
    new Chart(document.getElementById("lineChart"), {
        type: "line",
        data: {
            labels: ["W1", "W2", "W3", "W4"],
            datasets: [{
                label: "Pengajuan",
                data: [{{ implode(',', $mingguan) }}],
                fill: true,
                borderColor: "#007f3e",
                backgroundColor: "rgba(0,127,62,0.2)",
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById("doughnutChart"), {
        type: "doughnut",
        data: {
            labels: ["Low", "Medium", "High"],
            datasets: [{
                data: [{{ implode(',', $hasilApi) }}],
                backgroundColor: ["#28a745", "#ffc107", "#dc3545"]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
@endsection