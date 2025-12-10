@extends('layouts.main-user')

@section('mycontent')

<div class="content-wrapper profil-page">
<div class="container-fluid">

<link rel="stylesheet" href="{{ asset('build/css/profil.min.css?v=' . time()) }}">

<!-- ========================= PAGE TITLE ========================= -->
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box bg-galaxy-transparent">
            <h4 class="fw-bold mb-0">Grafik Persentase Kebutuhan Pelatihan</h4>
        </div>
    </div>
</div>

<!-- ========================= SEARCH ========================= -->
<div class="row mb-4">
    <div class="col-lg-6">
        <form method="GET" action="{{ route('ui.grafik') }}" class="d-flex gap-2 align-items-end">

            <div class="flex-grow-1">
                <label class="form-label fw-semibold">Cari Kota/Kabupaten</label>
                <input list="daerahList"
                       name="daerah"
                       value="{{ $selectedDaerah }}"
                       class="form-control"
                       placeholder="Contoh: Serang">

                <datalist id="daerahList">
                    @foreach($daerahList as $item)
                        <option value="{{ $item }}">
                    @endforeach
                </datalist>
            </div>

            <button class="btn btn-primary mt-4">
                <i class="ri-search-line"></i> Cari
            </button>
        </form>
    </div>

    <div class="col-lg-6 d-flex align-items-end justify-content-end mt-3 mt-lg-0">
        <span class="badge bg-primary-subtle text-primary p-2 px-3">
            Menampilkan: <strong>{{ $selectedDaerah ?? 'Semua Daerah' }}</strong>
        </span>
    </div>
</div>

<!-- ========================= MAIN CARD ========================= -->
<div class="card border-0 shadow-sm">
    <div class="card-header baduy-bg">
        <h5 class="fw-bold text-white mb-0">
            <i class="ri-bar-chart-grouped-line me-2"></i>
            Grafik Guru, Kepala Sekolah, dan Pengawas
        </h5>
    </div>

    <div class="card-body">

        <!-- ====== GURU ====== -->
        <div class="big-box mb-5">
            <h5 class="fw-bold">Instrumen GURU</h5>

            <p class="text-muted">
                Instrumen tertinggi:
                <span class="text-primary fw-bold">{{ $maxGuru }}</span>
            </p>

            <div class="chart-container">
                <canvas id="chartGuru"></canvas>
            </div>

            <p class="text-center mt-2 fw-semibold">
                Kota: {{ $selectedDaerah ?? '-' }}
            </p>
        </div>

        <!-- ====== KEPSEK ====== -->
        <div class="big-box mb-5">
            <h5 class="fw-bold">Instrumen KEPALA SEKOLAH</h5>

            <p class="text-muted">
                Instrumen tertinggi:
                <span class="text-primary fw-bold">{{ $maxKepsek }}</span>
            </p>

            <div class="chart-container">
                <canvas id="chartKepsek"></canvas>
            </div>

            <p class="text-center mt-2 fw-semibold">
                Kota: {{ $selectedDaerah ?? '-' }}
            </p>
        </div>

        <!-- ====== PENGAWAS ====== -->
        <div class="big-box mb-3">
            <h5 class="fw-bold">Instrumen PENGAWAS</h5>

            <p class="text-muted">
                Instrumen tertinggi:
                <span class="text-primary fw-bold">{{ $maxPengawas }}</span>
            </p>

            <div class="chart-container">
                <canvas id="chartPengawas"></canvas>
            </div>

            <p class="text-center mt-2 fw-semibold">
                Kota: {{ $selectedDaerah ?? '-' }}
            </p>
        </div>

    </div>
</div>

</div>
</div>

<!-- ========================= CHART JS ========================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const daerah = @json($labels);

// =============== BUILD CHART (13 instrumen, warna berbeda) ===============
function buildChart(id, dataset) {

    const labels = Object.keys(dataset);
    const values = Object.values(dataset).map(v => v[0]);

    const colors = [
        '#6A5ACD', '#FF6B6B', '#4ECDC4', '#FFA500', '#1E90FF',
        '#FF1493', '#32CD32', '#FFD700', '#00CED1', '#FF4500',
        '#8A2BE2', '#20B2AA', '#DC143C'
    ];

    new Chart(document.getElementById(id), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Nilai Instrumen",
                data: values,
                backgroundColor: colors,
                borderRadius: 10
            }]
        },

        options: {
            plugins: {
                legend: { display: false }
            },
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => v + '%' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { autoSkip: false }
                }
            }
        }
    });
}

// Build all charts
buildChart("chartGuru", @json($guruData));
buildChart("chartKepsek", @json($kepsekData));
buildChart("chartPengawas", @json($pengawasData));
</script>

<!-- ========================= CSS ========================= -->
<style>
.big-box {
    background:#f6f8ff;
    border:1px solid #d7e2ff;
    border-radius:12px;
    padding:20px;
}

.chart-container {
    max-width:600px;
    height:260px;
    margin:0 auto;
}

@media(max-width:768px){
    .chart-container{ height:220px; }
}

@media(max-width:480px){
    .chart-container{ height:180px; }
}
</style>

@endsection
