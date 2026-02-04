@extends('layouts.main')
@section('mycontent')
    @php
        $tittle = 'Analisis Hasil Instrumen';

        $levelNames = [
            1 => 'Gagal',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan',
        ];

        $levelColors = [
            1 => '#17a212',
            2 => '#17a2b8',
            3 => '#007bff',
            4 => '#ffc107',
            5 => '#28a745',
        ];

        $jenjangColors = [
            'Pertama' => '#ff6b6b',
            'Muda' => '#4ecdc4',
            'Madya' => '#45b7d1',
            'Utama' => '#96ceb4',
        ];
    @endphp

    <style>
/* =====================================================
   ANALISIS LAYOUT STYLES - CLEAN + RESPONSIVE (MOBILE SAFE)
   (Tidak ubah desain desktop, fokus rapihin + mobile)
   ===================================================== */

/* =========================
   EMPTY STATE BUTTON CENTER
========================= */
#analisisContent .row .col-12.d-flex .text-center {
  display: flex;
  flex-direction: column;
  align-items: center;
}
#analisisContent .row .col-12.d-flex .text-center .btn {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin-left: auto !important;
  margin-right: auto !important;
}

/* =========================
   BASE WRAP + HEADER
========================= */
.analisis-wrap {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 20px;
  padding: 25px;
  margin-bottom: 30px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}
.analisis-wrap::before {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 300px;
  height: 300px;
  background: linear-gradient(135deg, rgba(26, 91, 184, 0.05) 0%, rgba(26, 91, 184, 0) 70%);
  border-radius: 0 20px 0 0;
  z-index: 0;
}
.analisis-wrap > * {
  position: relative;
  z-index: 1;
}

.analisis-head {
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  padding: 18px 18px;
  min-height: 92px;
  background: #1a4d8e;
  border-bottom: 1px solid rgba(255, 255, 255, 0.14);
  color: #fff !important;
  box-shadow: 0 8px 25px rgba(26, 91, 184, 0.2);
}
.analisis-head::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: url("{{ asset('build/images/baduy.jpg') }}");
  background-repeat: repeat;
  background-size: 140px auto;
  background-position: center;
  opacity: 0.55;
  filter: grayscale(100%) contrast(1.15);
  z-index: 0;
}
.analisis-head::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(26, 91, 184, 0.5);
  z-index: 1;
  pointer-events: none;
}
.analisis-head h5,
.analisis-head .meta {
  position: relative;
  z-index: 2;
  text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
}
.analisis-head h5 {
  font-weight: 800 !important;
  color: #fff !important;
}
.analisis-head .text-muted {
  color: rgba(255, 255, 255, 0.9) !important;
}

/* =========================
   FILTER SECTION
========================= */
.analisis-filter {
  background: white;
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 25px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
  position: relative;
  z-index: 1;
}

.filter-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 15px;
  justify-content: center; /* tetap center */
}

.filter-col {
  flex: 1 1 calc(25% - 20px);
  min-width: 250px;
}

.filter-col label {
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.filter-col .form-select,
.filter-col .form-control {
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  padding: 10px 15px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  height: 46px;
}

.filter-col .form-select:focus,
.filter-col .form-control:focus {
  border-color: #1a5bb8;
  box-shadow: 0 0 0 3px rgba(26, 91, 184, 0.1);
}

/* Kolom tombol */
.filter-col.d-flex.gap-2.align-items-end {
  align-self: flex-end;
}
.filter-col .d-flex.gap-2.w-100 {
  flex-wrap: nowrap; /* desktop tetap rapih */
}

/* Buttons */
.btn-primary {
  background: linear-gradient(135deg, #1a5bb8 0%, #2d6bc8 100%);
  border: none;
  border-radius: 10px;
  padding: 12px 25px;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(26, 91, 184, 0.2);
  transition: all 0.3s ease;
  height: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(26, 91, 184, 0.3);
  background: linear-gradient(135deg, #2d6bc8 0%, #3b82f6 100%);
}

.btn-outline-secondary {
  border: 2px solid #cbd5e1;
  border-radius: 10px;
  padding: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
  height: 46px;
  width: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-outline-secondary:hover {
  border-color: #94a3b8;
  background-color: #f8fafc;
}

/* =========================
   STAT CARDS
========================= */
.stat-card {
  background: white;
  border-radius: 16px;
  padding: 25px;
  margin-bottom: 20px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  height: 100%;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}
.stat-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background: linear-gradient(to bottom, #1a5bb8, #2d6bc8);
  border-radius: 16px 0 0 16px;
}

.stat-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 15px;
  position: relative;
  z-index: 1;
}
.stat-icon i { font-size: 1.5rem; }

.stat-number {
  font-size: 2rem;
  font-weight: 800;
  color: #1a2937;
  line-height: 1;
  margin-bottom: 5px;
  font-family: "Inter", sans-serif;
}
.stat-label {
  font-size: 0.9rem;
  color: #64748b;
  margin-top: 0;
  font-weight: 500;
}
.stat-label small {
  font-size: 0.8rem;
  color: #94a3b8;
}

/* =========================
   CHART CONTAINER (SAFE)
========================= */
.chart-container {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  margin-bottom: 25px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
  height: 460px;
  display: flex;
  flex-direction: column;
  overflow: visible; /* biar label x-axis ga kepotong */
}
.chart-container-large { height: 560px; }

.chart-title {
  flex: 0 0 auto;
  margin-bottom: 18px;
}
.chart-canvas-wrap {
  flex: 1 1 auto;
  min-height: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px 6px 30px;
}
.chart-canvas-wrap.is-doughnut {
  aspect-ratio: 1 / 1;
  width: 100%;
  max-width: 420px;
  margin: 0 auto;
  padding: 0;
}

/* canvas behavior */
.chart-container canvas {
  width: 100% !important;
  height: 100% !important;
  display: block;
}
/* canvas,
.chartjs-render-monitor {
  width: auto !important;
  height: auto !important;
  max-width: 100% !important;
  max-height: 100% !important;
} */

/* =========================
   JENJANG CHART LIST (SCROLL)
========================= */
.jenjang-charts-scroll-container {
  position: relative;
  width: 100%;
}
#jenjangChartsContainer {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
  max-height: 600px;
  overflow-y: auto;
  padding: 10px;
  margin: 0;
  scroll-behavior: smooth;
}
#jenjangChartsContainer .col-md-6,
#jenjangChartsContainer .col-lg-4 {
  grid-column: auto;
  width: 100%;
  margin: 0;
  padding: 0;
}

.jenjang-charts-scroll-container::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 30px;
  background: linear-gradient(to top, rgba(255, 255, 255, 0.9), transparent);
  pointer-events: none;
  z-index: 2;
}

/* =========================
   TABLE CARD + TABLE
========================= */
.table-card {
  background: white;
  border-radius: 16px;
  padding: 25px;
  margin-bottom: 25px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
  position: relative;
  overflow: hidden;
}
.table-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(to right, #1a5bb8, #3b82f6);
}

.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

/* Table styling */
.modus-table { font-size: 0.9rem; }
.modus-table th {
  background: linear-gradient(to bottom, #f1f5f9, #e2e8f0);
  color: #334155;
  font-weight: 700;
  padding: 12px 15px;
  text-align: left;
  white-space: nowrap;
}
.modus-table td {
  padding: 12px 15px;
  vertical-align: middle;
}

/* Badge level fix */
.badge-level {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  max-width: 220px;
  overflow: hidden;
  text-overflow: ellipsis;
  padding: 8px 14px;
  border-radius: 12px;
  line-height: 1;
  font-weight: 800;
}

/* Progress Bars */
.progress {
  background-color: #e2e8f0;
  border-radius: 10px;
  height: 10px;
  overflow: hidden;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}
.progress-bar {
  border-radius: 10px;
  transition: width 0.6s ease;
  box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}
.progress-bar.bg-warning {
  box-shadow: 0 2px 4px rgba(245, 158, 11, 0.25);
}
.progress-bar.bg-danger {
  box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
}

/* =========================
   EMPTY STATE + LOADING + ERROR
========================= */
.empty-state {
  background: white;
  border-radius: 16px;
  padding: 80px 20px;
  text-align: center;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
  position: relative;
  overflow: hidden;
}
.empty-state::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg, rgba(26, 91, 184, 0.05) 0%, rgba(26, 91, 184, 0) 70%);
  border-radius: 50%;
}
.empty-state i {
  font-size: 5rem;
  color: #cbd5e1;
  margin-bottom: 20px;
  position: relative;
  z-index: 1;
}
.empty-state p {
  font-size: 1.1rem;
  color: #64748b;
  margin-bottom: 25px;
  position: relative;
  z-index: 1;
}
.empty-state .btn {
  position: relative;
  z-index: 1;
  padding: 12px 30px;
  border-radius: 10px;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(26, 91, 184, 0.2);
}

#loadingSpinner {
  background: white;
  border-radius: 16px;
  padding: 60px 20px;
  margin: 20px 0;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
}
.spinner-border { width: 3rem; height: 3rem; border-width: 0.25em; }

#errorAlert {
  border-radius: 12px;
  border: none;
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  color: #7f1d1d;
  padding: 20px;
  margin: 20px 0;
  box-shadow: 0 8px 25px rgba(239, 68, 68, 0.1);
  border-left: 5px solid #ef4444;
}

/* =========================
   TABS (MOBILE SCROLL)
========================= */
.nav-tabs {
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: 20px;
}
.nav-tabs .nav-link {
  border: 2px solid transparent;
  border-radius: 10px 10px 0 0;
  padding: 12px 24px;
  font-weight: 600;
  color: #64748b;
  background-color: #f8fafc;
  margin-right: 5px;
  transition: all 0.3s ease;
  position: relative;
  white-space: nowrap;
}
.nav-tabs .nav-link:hover {
  border-color: #e2e8f0;
  background-color: white;
  color: #1a5bb8;
}
.nav-tabs .nav-link.active {
  border-color: #1a5bb8;
  border-bottom-color: white;
  background-color: white;
  color: #1a5bb8;
  box-shadow: 0 -2px 10px rgba(26, 91, 184, 0.1);
}
.nav-tabs .nav-link.active::after {
  content: "";
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background-color: white;
}
.tab-content {
  background: white;
  border-radius: 0 0 12px 12px;
  padding: 20px;
  border: 1px solid #e2e8f0;
  border-top: none;
}

/* =========================
   RESPONSIVE BREAKPOINTS
========================= */

/* <= 1200px: kolom filter 3 */
@media (max-width: 1200px) {
  .filter-col { flex: 1 1 calc(33.333% - 20px); }
}

/* <= 992px: tombol mulai aman, chart lebih pendek */
@media (max-width: 992px) {
  .chart-container { height: 430px; }
  .chart-container-large { height: 520px; }
}

/* <= 768px: MOBILE MAIN */
@media (max-width: 768px) {
  .analisis-wrap {
    padding: 15px;
    margin: 0 -15px;
    border-radius: 0;
  }

  .analisis-head {
    padding: 16px;
    border-radius: 15px;
    margin-bottom: 20px;
  }
  .analisis-head h5 { font-size: 1.15rem; }

  .analisis-filter { padding: 15px; }

  .filter-col {
    flex: 1 1 100%;
    min-width: 100%;
  }

  /* tombol jadi wrap (biar export ga kepotong) */
  .filter-col .d-flex.gap-2.w-100 {
    flex-wrap: wrap;
  }

  /* Filter / Reset / Export mobile: rapi */
  #btnFilter {
    flex: 1 1 calc(60% - 8px);
    min-width: 180px;
  }
  #btnReset {
    flex: 0 0 46px;
  }

  /* Export tombol full width di bawah */
  #btnExportLengkap,
  #btnExportRekomendasi {
    flex: 1 1 100%;
    width: 100%;
  }

  /* Chart area */
  .chart-container { height: 420px; padding: 20px; }
  .chart-container-large { height: 520px; }
  .chart-canvas-wrap.is-doughnut { max-width: 360px; }
  .chart-canvas-wrap { padding-bottom: 34px; }

  .table-card { padding: 18px; }
  .stat-card { padding: 20px; }
  .stat-number { font-size: 1.75rem; }

  /* Tabs bisa geser */
  .nav-tabs {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    gap: 6px;
    padding-bottom: 6px;
  }
  .nav-tabs .nav-link { flex: 0 0 auto; }

  /* Jenjang grid jadi 1 kolom biar ga mepet */
  #jenjangChartsContainer {
    grid-template-columns: 1fr;
    max-height: 560px;
  }
}

/* <= 576px: HP kecil */
@media (max-width: 576px) {
  .analisis-head { padding: 14px; }

  .filter-col .form-select,
  .filter-col .form-control {
    padding: 8px 12px;
    font-size: 0.9rem;
    height: 42px;
  }

  .btn-primary { padding: 10px 16px; font-size: 0.95rem; }
  .btn-outline-secondary { height: 42px; width: 42px; }

  .chart-container { height: 400px; padding: 15px; }
  .chart-container-large { height: 500px; }
  .chart-canvas-wrap.is-doughnut { max-width: 320px; }
  .chart-canvas-wrap { padding-bottom: 36px; }

  .empty-state { padding: 60px 15px; }
  .empty-state i { font-size: 4rem; }

  /* Table padding lebih rapat (tetap bisa scroll) */
  .modus-table th,
  .modus-table td {
    padding: 10px 12px;
    font-size: 0.85rem;
  }
}

    /* =========================
   TABLE HORIZONTAL SCROLL (MOBILE FRIENDLY)
   ========================= */

/* pastikan wrapper bisa geser ke samping */
.table-responsive{
  overflow-x: auto !important;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
}

/* paksa tabelnya punya lebar minimum biar muncul scroll */
.table-responsive .table{
  min-width: 980px;          /* bisa kamu naikin kalau kolom banyak */
  width: max-content;
}

/* biar teks di sel gak turun ke bawah (nggak bikin tabel "tinggi") */
.table-responsive .table th,
.table-responsive .table td{
  white-space: nowrap;
}

/* opsi: bikin scrollbar lebih enak dilihat */
.table-responsive::-webkit-scrollbar{ height: 8px; }
.table-responsive::-webkit-scrollbar-track{ background: #f1f5f9; border-radius: 6px; }
.table-responsive::-webkit-scrollbar-thumb{ background: #cbd5e1; border-radius: 6px; }
.table-responsive::-webkit-scrollbar-thumb:hover{ background: #94a3b8; }

/* kalau di HP, bikin min-width sedikit lebih besar biar kolom kebaca */
@media (max-width: 768px){
  .table-responsive .table{ min-width: 1100px; }
}
@media (max-width: 576px){
  .table-responsive .table{ min-width: 1200px; }
}

/* =========================
   PRINT (tetap)
========================= */
@media print {
  .analisis-wrap { box-shadow: none; background: white; }
  .btn-primary,
  .btn-outline-secondary,
  .analisis-filter {
    display: none !important;
  }
  .chart-container {
    break-inside: avoid;
    page-break-inside: avoid;
  }
  .table-responsive { overflow: visible !important; }
}
/* =========================
   STAT CARDS: MOBILE 2x2
========================= */
@media (max-width: 768px) {
  /* bikin 2 kolom */
  #analisisContent .row > .col-md-3 {
    flex: 0 0 50%;
    max-width: 50%;
  }

  /* rapihin jarak biar ga kegedean */
  #analisisContent .stat-card {
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 14px;
  }

  #analisisContent .stat-number {
    font-size: 1.5rem;
  }

  #analisisContent .stat-label {
    font-size: 0.85rem;
    line-height: 1.2;
  }

  #analisisContent .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    margin-bottom: 10px;
  }
}
/* =========================
   TOP 4 CHART: LEGEND RAPIH DI DALAM CARD
========================= */
@media (max-width: 768px) {
  /* kasih ruang bawah buat legend biar ga mentok */
  #analisisContent > .row:nth-of-type(2) .chart-container,
  #analisisContent > .row:nth-of-type(3) .chart-container{
    height: 310px !important;
  }

  /* area doughnut jangan kegedean, biar legend muat */
  #analisisContent > .row:nth-of-type(2) .chart-canvas-wrap.is-doughnut,
  #analisisContent > .row:nth-of-type(3) .chart-canvas-wrap.is-doughnut{
    max-width: 170px !important;
  }
}
/* HP kecil banget (opsional): tetap 2 kolom tapi lebih rapat */
@media (max-width: 420px) {
  #analisisContent .stat-card { padding: 14px; }
  #analisisContent .stat-number { font-size: 1.35rem; }
  #analisisContent .stat-icon { width: 42px; height: 42px; }
}

/* =========================
   FORCE CHART 2-UP ON MOBILE
========================= */
@media (max-width: 768px) {
  /* paksa row jadi 2 kolom rapet */
  #analisisContent .row {
    display: flex !important;
    flex-wrap: wrap !important;
    margin-left: -6px !important;
    margin-right: -6px !important;
  }

  /* semua col-md-6 jadi 1/2 lebar */
  #analisisContent .row > .col-md-6 {
    flex: 0 0 50% !important;
    max-width: 50% !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }

  /* kecilin card chart biar muat 2 sebelahan */
  #analisisContent .chart-container {
    height: 260px !important;
    padding: 12px !important;
    margin-bottom: 12px !important;
    border-radius: 14px !important;
  }

  /* judul lebih kecil */
  #analisisContent .chart-title {
    font-size: 0.85rem !important;
    margin-bottom: 8px !important;
    line-height: 1.15 !important;
  }

  /* doughnut area lebih kecil */
  #analisisContent .chart-canvas-wrap.is-doughnut {
    max-width: 180px !important;
  }

  /* canvas jangan maksa tinggi berlebihan */
  #analisisContent .chart-container canvas {
    height: 100% !important;
  }
}
/* =========================
   HARD FIX: JENJANG CANVAS HEIGHT
   (khusus chart per jenjang)
========================= */
#jenjangChartsContainer .chart-card{
  height: 380px;
  display: flex;
  flex-direction: column;
}

#jenjangChartsContainer .chart-card canvas{
  flex: 1 1 auto;
  height: 260px !important;   /* INI KUNCI: bikin chart kebaca */
  max-height: 260px !important;
  width: 100% !important;
}

/* mobile */
@media (max-width: 768px){
  #jenjangChartsContainer .chart-card canvas{
    height: 240px !important;
    max-height: 240px !important;
  }
}
/* =========================
   HARD OVERRIDE: JENJANG CHART JANGAN IKUT 2-UP MOBILE
   (TARUH PALING BAWAH CSS)
========================= */
@media (max-width: 768px){

  /* balikin row jenjang ke normal */
  #analisisContent #jenjangChartsContainer.row{
    display: block !important;   /* matiin flex 2-up */
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  /* ini yang penting: lawan rule #analisisContent .row > .col-md-6 { 50% !important } */
  #analisisContent #jenjangChartsContainer > .col-md-6,
  #analisisContent #jenjangChartsContainer > .col-lg-4{
    flex: 0 0 100% !important;
    max-width: 100% !important;
    width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  /* bikin card nya gede + rapi */
  #analisisContent #jenjangChartsContainer .chart-card{
    background: #fff;
    border-radius: 16px;
    padding: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,.06);
    height: 360px !important;  /* gedein */
  }

  /* wrapper wajib punya tinggi biar chart ga gepeng */
  #analisisContent #jenjangChartsContainer .chart-wrapper{
    position: relative;
    height: 280px !important;  /* gedein chart */
  }

  /* ini ngelawan rule canvas auto yang bikin shrink */
  #analisisContent #jenjangChartsContainer canvas{
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;
    max-width: none !important;
    max-height: none !important;
    display: block !important;
  }
}

/* Judul aman, legend ga nabrak */
#jenjangChartsContainer .chart-card h6{
  margin-bottom: 8px !important;
}

#jenjangChartsContainer .chart-card .chart-wrapper{
  position: relative;
  height: 260px;              /* sesuaikan sama kebutuhan */
  padding-top: 18px;          /* INI kunci: ruang buat legend */
}

#jenjangChartsContainer .chart-card .chart-wrapper canvas{
  width: 100% !important;
  height: 100% !important;
}
#jenjangChartsContainer .chart-wrapper{
  padding-top: 26px; /* ruang buat legend di atas */
}
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Analisis</a></li>
                            <li class="breadcrumb-item active">{{ $tittle }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="analisis-wrap">
            <!-- Header -->
            <div class="analisis-head">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ri-pie-chart-2-line"></i> {{ $tittle }}
                </h5>
                <div class="meta mt-2">Analisis komprehensif hasil instrumen berdasarkan berbagai kriteria</div>
            </div>

            <!-- Filter -->
            <div class="analisis-filter">
                <form action="{{ route('analisis.index') }}" method="GET" id="analisisForm">
                    <div class="filter-row">
                        <div class="filter-col">
                            <label class="form-label">Kegiatan</label>
                            <select class="form-select" name="kegiatan_id" id="kegiatanSelect">
                                <option value="">Semua Kegiatan</option>
                                @foreach ($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->kegiatan_id }}"
                                        {{ request('kegiatan_id') == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                        {{ $kegiatan->kegiatan_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-col">
                            <label class="form-label">Jenjang Jabatan</label>
                            <select class="form-select" name="pangkat_jabatan_id" id="pangkatSelect">
                                <option value="">Semua Jenjang</option>
                                @foreach ($pangkatJabatans as $pangkat)
                                    <option value="{{ $pangkat->pangkat_jabatan_id }}"
                                        {{ request('pangkat_jabatan_id') == $pangkat->pangkat_jabatan_id ? 'selected' : '' }}>
                                        {{ $pangkat->jenjang_jabatan ?? $pangkat->pangkat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-col">
                            <label class="form-label">Jenis PTK</label>
                            <select class="form-select" name="jenis_ptk_id" id="jenisPtkSelect">

                                @foreach ($jenisPtkList as $jenis)
                                    <option value="{{ $jenis->jenis_ptk_id }}"
                                        {{ request('jenis_ptk_id') == $jenis->jenis_ptk_id ? 'selected' : '' }}>
                                        {{ $jenis->jenis_ptk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-col">
                            <label class="form-label">Kota</label>
                            <select class="form-select" name="kota_id" id="kotaSelect">
                                <option value="">Semua Kota</option>
                                @foreach ($kotas as $kota)
                                    <option value="{{ $kota->kota_id }}"
                                        {{ request('kota_id') == $kota->kota_id ? 'selected' : '' }}>
                                        {{ $kota->nama_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tambahkan di bagian filter setelah kota_id -->
                        <div class="filter-col">
                            <label class="form-label">Jenjang Pendidikan</label>
                            <select class="form-select" name="jenjang_pendidikan_id" id="jenjangPendidikanSelect">
                                <option value="">Semua Jenjang</option>
                                @foreach ($jenjangPendidikanList as $jenjang)
                                    <option value="{{ $jenjang->jenjang_pendidikan_id }}"
                                        {{ request('jenjang_pendidikan_id') == $jenjang->jenjang_pendidikan_id ? 'selected' : '' }}>
                                        {{ $jenjang->jenjang_pendidikan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-col">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" id="jenisKelaminSelect">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="filter-col d-flex gap-2 align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1" id="btnFilter">
                                    <i class="ri-filter-line align-bottom me-1"></i> Filter
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btnReset">
                                    <i class="ri-refresh-line align-bottom"></i>
                                </button>

                                <!-- TOMBOL EXPORT LENGKAP (HANYA 1 TOMBOL) -->
                                <button type="button" class="btn btn-success" id="btnExportLengkap">
                                    <i class="ri-file-excel-2-line align-bottom me-1"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-success" id="btnExportRekomendasi">
                                    <i class="ri-file-excel-2-line align-bottom me-1"></i> Export Rekomendasi KB
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data analisis...</p>
            </div>

            <!-- Error Alert -->
            <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

            <!-- Analisis Content -->
            <div id="analisisContent">
                @if (isset($analisisData) && !isset($analisisData['error']))
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(26,91,184,.12); color: #1a5bb8;">
                                    <i class="ri-user-3-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['total_ptk'] ?? 0 }}</div>
                                <div class="stat-label">Total PTK (Semua Filter)</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(40,167,69,.12); color: #28a745;">
                                    <i class="ri-checkbox-circle-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['ptk_menjawab'] ?? 0 }}</div>
                                <div class="stat-label">PTK Menjawab (Kegiatan)</div>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(255,193,7,.12); color: #ffc107;">
                                <i class="ri-bar-chart-line fs-4"></i>
                            </div>
                            <div class="stat-number">{{ number_format($analisisData['statistik']['rata_level'] ?? 0, 2) }}</div>
                            <div class="stat-label">Rata-rata Level</div>
                        </div>
                    </div> --}}
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(220,53,69,.12); color: #dc3545;">
                                    <i class="ri-progress-4-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['statistik']['persentase_isi'] ?? 0 }}%</div>
                                <div class="stat-label">Progress Pengisian</div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 1: Distribusi Berdasarkan Jawaban -->
                    <div class="row">
                        <!-- Chart 1: Distribusi Level Kompetensi -->
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-bar-chart-2-line"></i> Distribusi Level Kompetensi
                                    <small class="text-muted ms-2">(Berdasarkan jumlah jawaban)</small>
                                </div>
                                <canvas id="levelDistributionChart" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Chart 2: Distribusi Jenjang Jabatan -->
                        <div class="col-md-6">
                            <div class="chart-container">
                            <div class="chart-title">
                                <i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan
                                <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                            </div>

                            <div class="chart-canvas-wrap is-doughnut">
                                <canvas id="jenjangDistributionChart"></canvas>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 2: Distribusi Berdasarkan PTK -->
                    <div class="row">
                        <!-- Chart 3: Distribusi Jenjang Pendidikan -->
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-school-line"></i> Distribusi Jenjang Pendidikan
                                    <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                                </div>
                                <canvas id="jenjangPendidikanChart" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Chart 4: Distribusi Jenis Kelamin -->
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-title">
                                    <i class="ri-user-line"></i> Distribusi Jenis Kelamin <br>
                                    <small class="text-muted ms-2">(Berdasarkan PTK yang menjawab)</small>
                                </div>
                                <canvas id="jenisKelaminChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 5: Semua Sub Indikator -->
                    @if (!empty($analisisData['all_sub_indikators_chart']['labels']))
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-container chart-container-large">
                                    <div class="chart-title">
                                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                                        <span class="badge bg-info ms-2">Jumlah PTK (Bukan Jawaban)</span>
                                    </div>
                                    <canvas id="allSubIndikatorsChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tabel Modus per Kota -->
                    @if (!empty($analisisData['modus_per_kota']))
                        <div class="row">
                            <div class="col-12">
                                <div class="table-card">
                                    <div class="chart-title">
                                        <i class="ri-map-pin-line"></i>
                                        @if (request('kota_id'))
                                            Modus Level per Kota
                                        @else
                                            Modus Level Provinsi Banten
                                        @endif
                                        <small class="text-muted ms-2">(Berdasarkan jumlah PTK)</small>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered modus-table">
                                            <thead>
                                                <tr>
                                                    @if (request('kota_id'))
                                                        <th>Kota</th>
                                                        <th>Total Jawab Sub Indikator</th>
                                                    @else
                                                        <th>Provinsi</th>
                                                        <th>Total Jawab Sub Indikator</th>
                                                    @endif
                                                    <th>Sub Indikator</th>
                                                    <th>Modus Level</th>
                                                    <th>Jumlah PTK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($analisisData['modus_per_kota'] as $kota)
                                                    @if (!empty($kota['sub_indikator_modus']))
                                                        @foreach ($kota['sub_indikator_modus'] as $index => $sub)
                                                            <tr>
                                                                @if ($index === 0)
                                                                    @if (request('kota_id'))
                                                                        <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                            style="vertical-align: middle; font-weight: 600;">
                                                                            {{ $kota['nama_kota'] }}
                                                                        </td>
                                                                    @else
                                                                        <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                            style="vertical-align: middle; font-weight: 600;">
                                                                            Banten
                                                                        </td>
                                                                    @endif
                                                                    <td rowspan="{{ count($kota['sub_indikator_modus']) }}"
                                                                        style="vertical-align: middle; text-align: center;">
                                                                        {{ $kota['total_jawaban'] }}
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <small
                                                                        class="text-muted">{{ $sub['sub_indikator_code'] }}</small><br>
                                                                    <span
                                                                        class="fw-medium">{{ Str::limit($sub['sub_indikator_name'], 40) }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $levelColor =
                                                                            $levelColors[$sub['modus_level'] ?? 2] ??
                                                                            '#17a2b8';
                                                                        $levelName =
                                                                            $levelNames[$sub['modus_level'] ?? 2] ??
                                                                            'Penerapan';
                                                                    @endphp
                                                                    <span class="badge-level"
                                                                        style="background-color: {{ $levelColor }}; color: white;">
                                                                        Level {{ $sub['modus_level'] }}
                                                                        ({{ $levelName }})
                                                                    </span>
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ $sub['jumlah_jawaban'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Ganti bagian Charts 6: Sub Indikator per Jenjang Jabatan dengan ini -->
                    @if (!empty($analisisData['sub_indikator_per_jenjang']))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="chart-container">
                                    <div class="chart-title">
                                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per
                                        Jenjang Jabatan
                                        <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>

                                    </div>

                                    <!-- Container dengan scroll -->
                                    <div class="jenjang-charts-scroll-container">
                                        <div class="row" id="jenjangChartsContainer">
                                            @foreach ($analisisData['sub_indikator_per_jenjang'] as $jenjangChart)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="chart-card">
                                                        <h6 class="mb-3 text-center"
                                                            style="color: #1a5bb8; font-weight: 600; font-size: 16px;">
                                                            <i
                                                                class="ri-user-star-line me-2"></i>{{ $jenjangChart['jenjang_jabatan'] }}
                                                        </h6>



                                                        <div class="chart-wrapper">
                                                            <canvas id="jenjangChart_{{ $loop->index }}"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Info jumlah chart -->
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="ri-information-line"></i>
                                            Menampilkan {{ count($analisisData['sub_indikator_per_jenjang']) }} jenjang
                                            jabatan
                                            @if (count($analisisData['sub_indikator_per_jenjang']) > 6)
                                                (Gunakan scroll untuk melihat lebih banyak)
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif




                    <!-- Tabel Rekomendasi Gap per Jenjang Jabatan -->
                    @if (!empty($analisisData['rekomendasi_gap_per_jenjang']))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-card">
                                    <div class="chart-title">
                                        <i class="ri-list-check-2"></i> Rekomendasi Kebutuhan Belajar per Jenjang Jabatan
                                        <small class="text-muted ms-2">(Hanya menampilkan sub indikator yang belum mencapai
                                            level kebutuhan belajar)</small>
                                    </div>


                                    <!-- Tab Navigation -->
                                    <div class="mb-4">
                                        <ul class="nav nav-tabs" id="jenjangTab" role="tablist">
                                            @foreach ($analisisData['rekomendasi_gap_per_jenjang'] as $jenjangIndex => $jenjang)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $jenjangIndex == 0 ? 'active' : '' }}"
                                                        id="tab-{{ $jenjang['jenjang_jabatan'] }}" data-bs-toggle="tab"
                                                        data-bs-target="#content-{{ $jenjang['jenjang_jabatan'] }}"
                                                        type="button" role="tab">
                                                        {{ $jenjang['jenjang_jabatan'] }}
                                                        <span
                                                            class="badge bg-danger ms-1">{{ count($jenjang['rekomendasi'] ?? []) }}</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- Tab Content -->
                                    <div class="tab-content" id="jenjangTabContent">
                                        @foreach ($analisisData['rekomendasi_gap_per_jenjang'] as $jenjangIndex => $jenjang)
                                            <div class="tab-pane fade {{ $jenjangIndex == 0 ? 'show active' : '' }}"
                                                id="content-{{ $jenjang['jenjang_jabatan'] }}" role="tabpanel">

                                                @if (!empty($jenjang['rekomendasi']))
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered modus-table">
                                                            <thead>
                                                                <tr>
                                                                    <th width="3%">#</th>
                                                                    <th>Sub Indikator</th>
                                                                    <th width="8%" class="text-center">Level Dicapai
                                                                    </th>
                                                                    <th width="8%" class="text-center">Level kebutuhan Belajar</th>
                                                                    <th width="6%" class="text-center">Gap</th>
                                                                    <th>Rekomendasi Kebutuhan Belajar</th>
                                                                    <th width="10%" class="text-center">Jumlah PTK</th>
                                                                    <th width="10%" class="text-center">Detail PTK</th>
                                                                    <th width="12%" class="text-center">% dari Total
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $counter = 1;
                                                                    $jenjangTotalPtk = $jenjang['total_ptk'];
                                                                @endphp

                                                                @foreach ($jenjang['rekomendasi'] as $rek)
                                                                    @if (!empty($rek['detail_gap']))
                                                                        @php
                                                                            // **PERUBAHAN: Hitung jumlah PTK untuk sub indikator ini**
                                                                            $jumlahPtKSubIndikator =
                                                                                $rek['total_ptk_sub_indikator'] ?? 0;
                                                                            $percentage =
                                                                                $jenjangTotalPtk > 0
                                                                                    ? round(
                                                                                        ($jumlahPtKSubIndikator /
                                                                                            $jenjangTotalPtk) *
                                                                                            100,
                                                                                        1,
                                                                                    )
                                                                                    : 0;
                                                                            $progressClass =
                                                                                $percentage >= 30
                                                                                    ? 'bg-danger'
                                                                                    : ($percentage >= 15
                                                                                        ? 'bg-warning'
                                                                                        : 'bg-info');
                                                                        @endphp

                                                                        @foreach ($rek['detail_gap'] as $gapIndex => $gap)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{ $counter++ }}</td>
                                                                                <td>
                                                                                    @if ($gapIndex === 0)
                                                                                        <small
                                                                                            class="text-muted">{{ $rek['sub_indikator_code'] }}</small><br>
                                                                                        <span
                                                                                            class="fw-medium">{{ Str::limit($rek['sub_indikator_name'], 40) }}</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <span class="badge-level"
                                                                                        style="background-color: {{ getLevelColor($gap['level_dicapai']) }}; color: white;">
                                                                                        Level {{ $gap['level_dicapai'] }}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <span class="badge-level"
                                                                                        style="background-color: {{ getLevelColor($gap['level_harus']) }}; color: white;">
                                                                                        Level {{ $gap['level_harus'] }}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @php
                                                                                        $gapLevel =
                                                                                            $gap['level_gap'] ?? 0;
                                                                                    @endphp
                                                                                    @if ($gapLevel > 0)
                                                                                        <span
                                                                                            class="badge bg-danger">+{{ $gapLevel }}</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge bg-success">0</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <small>{{ $gap['rekomendasi'] }}</small>
                                                                                </td>
                                                                                <!-- **PERUBAHAN: Kolom Jumlah PTK hanya muncul sekali per sub indikator** -->
                                                                                @if ($gapIndex === 0)
                                                                                    <td rowspan="{{ count($rek['detail_gap']) }}"
                                                                                        class="text-center"
                                                                                        style="vertical-align: middle;">
                                                                                        {{ $jumlahPtKSubIndikator }}
                                                                                    </td>

                                                                                    <td class="text-center">
                                                                                        <a href="{{ route('analisis.rekomendasi-gap.index') }}?kegiatan_id={{ request('kegiatan_id', '') }}&pangkat_jabatan_id={{ request('pangkat_jabatan_id', '') }}&jenis_ptk_id={{ request('jenis_ptk_id', '') }}&kota_id={{ request('kota_id', '') }}&jenjang_pendidikan_id={{ request('jenjang_pendidikan_id', '') }}&jenis_kelamin={{ request('jenis_kelamin', '') }}&sub_indikator_id=${rek.sub_indikator_id || ''}&jenjang_jabatan=${jenjang.jenjang_jabatan || ''}"
                                                                                            class="btn btn-sm btn-info"
                                                                                            target="_blank">
                                                                                            <i class="ri-eye-line"></i>
                                                                                            Lihat Detail PTK
                                                                                        </a>
                                                                                    </td>

                                                                                    <td rowspan="{{ count($rek['detail_gap']) }}"
                                                                                        class="text-center"
                                                                                        style="vertical-align: middle;">
                                                                                        <div
                                                                                            class="d-flex align-items-center gap-2">
                                                                                            <span
                                                                                                class="fw-bold">{{ $percentage }}%</span>
                                                                                            <div class="progress flex-grow-1"
                                                                                                style="height: 6px;">
                                                                                                <div class="progress-bar {{ $progressClass }}"
                                                                                                    role="progressbar"
                                                                                                    style="width: {{ min($percentage, 100) }}%;">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="table-light">
                                                                    <td colspan="6" class="text-end fw-bold">Total PTK
                                                                        pada jenjang ini:</td>
                                                                    <td class="text-center fw-bold">{{ $jenjangTotalPtk }}
                                                                    </td>
                                                                    <td class="text-center fw-bold">100%</td>
                                                                </tr>
                                                                <tr class="table-info">
                                                                    <td colspan="6" class="text-end fw-bold">Total sub
                                                                        indikator dengan gap:</td>
                                                                    <td colspan="2" class="text-center fw-bold">
                                                                        {{ count($jenjang['rekomendasi'] ?? []) }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-success text-center py-4">
                                                        <i class="ri-checkbox-circle-fill fs-4 text-success"></i>
                                                        <h5 class="mt-2 mb-0">Semua PTK sudah mencapai level kebutuhan belajar!</h5>
                                                        <p class="text-muted mb-0">Tidak ada gap untuk jenjang
                                                            {{ $jenjang['jenjang_jabatan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tabel Progress Kota -->
                    @if (!empty($analisisData['progress_kota']))
                        <div class="row">
                            <div class="col-12">
                                <div class="table-card">

                                    <div class="chart-title">
                                        <i class="ri-progress-3-line"></i> Progress Pengisian per Kota
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered modus-table">
                                            <thead>
                                                <tr>
                                                    <th>Kota</th>
                                                    <th>Total PTK</th>
                                                    <th>Sudah Isi</th>
                                                    <th>Persentase</th>
                                                    <th>Progress Bar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($analisisData['progress_kota'] as $kota)
                                                    <tr>
                                                        <td>{{ $kota->nama_kota }}</td>
                                                        <td style="text-align: center;">{{ $kota->total_ptk }}</td>
                                                        <td style="text-align: center;">{{ $kota->sudah_isi }}</td>
                                                        <td style="text-align: center; font-weight: 600;">
                                                            {{ $kota->persentase }}%</td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar
                                                            @if ($kota->persentase >= 80) bg-success
                                                            @elseif($kota->persentase >= 50) bg-warning
                                                            @else bg-danger @endif"
                                                                    role="progressbar"
                                                                    style="width: {{ $kota->persentase }}%;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 500px;">
                            <div class="text-center" style="max-width: 500px;">
                                <div class="mb-4">
                                    <i class="ri-bar-chart-box-line" style="font-size: 5rem; color: #dee2e6;"></i>
                                </div>
                                <h4 class="mb-3" style="color: #495057;">Belum Ada Data Analisis</h4>
                                <p class="text-muted mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                                    Silakan pilih filter yang diinginkan untuk melihat analisis data instrumen
                                </p>
                                <button class="btn btn-primary btn-lg mt-3"
                                    onclick="document.getElementById('analisisForm').submit()"
                                    style="padding: 12px 30px; font-size: 1.1rem;">
                                    <i class="ri-filter-line align-bottom me-2"></i> Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('sipproja-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        /* =====================================================
                                                                                                                                                               GLOBAL CHART INSTANCE
                                                                                                                                                            ===================================================== */
        let levelDistributionChart = null;
        let jenjangDistributionChart = null;
        let jenjangPendidikanChart = null;
        let jenisKelaminChart = null;
        let allSubIndikatorsChart = null;
        let pelatihanChart = null; // TAMBAHKAN INI

        /* =====================================================
           RESET FORM
        ===================================================== */
        document.getElementById('btnReset')?.addEventListener('click', function() {
            document.getElementById('kegiatanSelect').value = '';
            document.getElementById('pangkatSelect').value = '';
            document.getElementById('jenisPtkSelect').value = '';
            document.getElementById('kotaSelect').value = '';
            document.getElementById('bentukPendidikanSelect').value = '';
            document.getElementById('jenisKelaminSelect').value = '';
            document.getElementById('analisisForm').submit();
        });

        /* =====================================================
           FORM SUBMIT AJAX
        ===================================================== */
        document.getElementById('analisisForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            loadAnalisis();
        });

        function loadAnalisis() {
            const form = document.getElementById('analisisForm');
            const params = new URLSearchParams(new FormData(form));

            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('analisisContent').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';

            fetch(`{{ route('analisis.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal memuat data');
                    return res.json();
                })
                .then(data => {
                    document.getElementById('loadingSpinner').style.display = 'none';

                    if (data.error) {
                        document.getElementById('errorAlert').innerText = data.error;
                        document.getElementById('errorAlert').style.display = 'block';
                        return;
                    }

                    updateAnalisisContent(data);
                })
                .catch(err => {
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('errorAlert').innerText = err.message;
                    document.getElementById('errorAlert').style.display = 'block';
                });
        }

        /* =====================================================
           UPDATE HTML CONTENT
        ===================================================== */
        function updateAnalisisContent(data) {
            let html = `
    <div class="row">
        ${statCard('ri-user-3-line','Total PTK<br><small>Semua Filter</small>',data.statistik?.total_ptk ?? 0,'#1a5bb8')}
        ${statCard('ri-checkbox-circle-line','PTK Menjawab<br><small>Kegiatan</small>',data.statistik?.ptk_menjawab ?? 0,'#28a745')}
 ${statCard('ri-user-forbid-line','PTK Belum<br>Menjawab',data.statistik?.ptk_belum_menjawab ?? 0,'#dc3545')}
        ${statCard('ri-progress-4-line','Progress<br>Pengisian',(data.statistik?.persentase_isi ?? 0)+'%','#dc3545')}
    </div>





    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-bar-chart-2-line"></i> Distribusi Level Kompetensi <small class="text-muted">(Jumlah jawaban)</small></div>
                <canvas id="levelDistributionChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-pie-chart-line"></i> Distribusi Jenjang Jabatan <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="jenjangDistributionChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-school-line"></i> Distribusi Jenjang Pendidikan <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="jenjangPendidikanChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="ri-user-line"></i> Distribusi Jenis Kelamin <small class="text-muted">(PTK yang menjawab)</small></div>
                <canvas id="jenisKelaminChart" height="300"></canvas>
            </div>
        </div>
    </div>`;


            /* =====================================================
               TABEL PTK BELUM MENJAWAB - YANG SUDAH DIPERBAIKI
            ===================================================== */
            if (data.ptk_belum_menjawab && Array.isArray(data.ptk_belum_menjawab) && data.ptk_belum_menjawab.length > 0) {
                let ptkRows = '';

                // Loop melalui setiap PTK yang belum menjawab
                data.ptk_belum_menjawab.forEach((ptk, index) => {
                    // Format sekolah/instansi
                    let sekolahInstansi = '-';
                    if (ptk.nama_sekolah) {
                        sekolahInstansi = `<small>${ptk.nama_sekolah}</small>`;
                    } else if (ptk.instansi) {
                        sekolahInstansi = `<small>${ptk.instansi}</small>`;
                    }

                    // Format jenis PTK (pastikan tidak undefined)
                    const jenisPtk = ptk.jenis_ptk || '-';

                    // Format jenjang pendidikan
                    const jenjangPendidikan = ptk.jenjang_pendidikan || '-';

                    // Format no HP
                    const noHp = ptk.no_hp || '-';

                    ptkRows += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${ptk.nip || '-'}</td>
                    <td><strong>${ptk.nama || '-'}</strong></td>
                    <td>${ptk.jenjang_jabatan || '-'}</td>
                    <td>${jenisPtk}</td>
                    <td>${ptk.nama_kota || '-'}</td>
                    <td>${jenjangPendidikan}</td>
                    <td>${noHp}</td>
                    <td>${sekolahInstansi}</td>
                    <td class="text-center">
                        <span class="badge bg-danger">Belum Isi</span>
                    </td>
                </tr>`;
                });

                // HTML untuk tabel
                html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-user-forbid-line"></i> PTK yang Belum Menjawab Instrumen
                        <span class="badge bg-danger ms-2">${data.ptk_belum_menjawab.length} PTK</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">NIP</th>
                                    <th>Nama</th>
                                    <th width="12%">Jenjang Jabatan</th>
                                    <th width="10%">Jenis PTK</th>
                                    <th width="12%">Kota</th>
                                    <th width="10%">Jenjang Pendidikan</th>
                                    <th width="8%">No. HP</th>
                                    <th width="15%">Sekolah/Instansi</th>
                                    <th width="8%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${ptkRows}
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="ri-information-line"></i>
                            Menampilkan ${data.ptk_belum_menjawab.length} PTK yang belum menjawab instrumen
                        </small>
                    </div>
                </div>
            </div>
        </div>`;
            } else if (data.ptk_belum_menjawab && data.ptk_belum_menjawab.length === 0) {
                // Jika tidak ada PTK yang belum menjawab
                html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-user-forbid-line"></i> PTK yang Belum Menjawab Instrumen
                        <span class="badge bg-success ms-2">0 PTK</span>
                    </div>
                    <div class="alert alert-success text-center py-4">
                        <i class="ri-checkbox-circle-fill fs-4 text-success"></i>
                        <h5 class="mt-2 mb-0">Semua PTK sudah menjawab instrumen!</h5>
                        <p class="text-muted mb-0">Tidak ada PTK yang belum mengisi instrumen</p>
                    </div>
                </div>
            </div>
        </div>`;
            }

            // Tambahkan tabel modus per kota jika ada
            if (data.modus_per_kota?.length > 0) {
                let modusRows = '';
                data.modus_per_kota.forEach((kota, kotaIndex) => {
                    if (kota.sub_indikator_modus && kota.sub_indikator_modus.length > 0) {
                        kota.sub_indikator_modus.forEach((sub, subIndex) => {
                            modusRows += `
                    <tr>
                        ${subIndex === 0 ? `
                                                                                                                                                                                        <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align: middle; font-weight: 600;">
                                                                                                                                                                                            ${kota.nama_kota}
                                                                                                                                                                                        </td>
                                                                                                                                                                                        <td rowspan="${kota.sub_indikator_modus.length}" style="vertical-align: middle; text-align: center;">
                                                                                                                                                                                            ${kota.total_jawaban}
                                                                                                                                                                                        </td>
                                                                                                                                                                                    ` : ''}
                        <td>
                            <small class="text-muted">${sub.sub_indikator_code}</small><br>
                            <span class="fw-medium">${sub.sub_indikator_name ? sub.sub_indikator_name.substring(0, 40) + (sub.sub_indikator_name.length > 40 ? '...' : '') : '-'}</span>
                        </td>
                        <td>
                            <span class="badge-level" style="background-color: ${getLevelColor(sub.modus_level)}; color: white;">
                                Level ${sub.modus_level} (${getLevelName(sub.modus_level)})
                            </span>
                        </td>
                        <td style="text-align: center;">${sub.jumlah_jawaban}</td>
                    </tr>`;
                        });
                    }
                });

                html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-map-pin-line"></i> Modus Level per Kota <small class="text-muted">(Jumlah PTK)</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th>Kota</th>
                                    <th>Total Jawaban Sub Indikator</th>
                                    <th>Sub Indikator</th>
                                    <th>Modus Level</th>
                                    <th>Jumlah PTK</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${modusRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`;
            }

            // Tambahkan chart sub indikator jika ada data
            if (data.all_sub_indikators_chart?.labels?.length > 0) {
                html += `
        <div class="row">
            <div class="col-12">
                <div class="chart-container chart-container-large">
                    <div class="chart-title">
                        <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator
                        <span class="badge bg-info ms-2">Jumlah PTK</span>
                    </div>
                    <canvas id="allSubIndikatorsChart" height="400"></canvas>
                </div>
            </div>
        </div>`;
            }


            // Tambahkan charts per jenjang jabatan jika ada
            if (data.sub_indikator_per_jenjang?.length > 0) {
                html += `
    <div class="row mt-4">
        <div class="col-12">
            <div class="chart-container chart-container-large">
                <div class="chart-title">
                    <i class="ri-bar-chart-grouped-line"></i> Distribusi PTK per Sub Indikator per Jenjang Jabatan
                    <span class="badge bg-info ms-2">Jumlah PTK per Jenjang</span>
                </div>

                <div class="row" id="jenjangChartsContainer">`;

                data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
                    // Hitung rata-rata level jika belum ada di data
                    const rataLevel = jenjangData.rata_rata_level ?
                        jenjangData.rata_rata_level.toFixed(2) :
                        hitungRataRataLevel(jenjangData);

                    html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="chart-card" style="background: white; border-radius: 12px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,.05); height: 370px;">
                            <h6 class="mb-3 text-center" style="color: #1a5bb8; font-weight: 600; font-size: 15px;">
                                <i class="ri-user-star-line me-2"></i>${jenjangData.jenjang_jabatan}
                            </h6>


                            <canvas
                                id="jenjangChart_${index}"
                                height="250"
                                style="margin-top: 10px;">
                            </canvas>
                        </div>
                    </div>`;
                });

                html += `
                </div>
            </div>
        </div>
    </div>`;
            }








            // Di dalam updateAnalisisContent() setelah bagian modus_per_kota
            if (data.rekomendasi_gap_per_jenjang?.length > 0) {
                let gapHtml = '';

                data.rekomendasi_gap_per_jenjang.forEach((jenjang, jenjangIndex) => {
                    if (jenjang.rekomendasi && jenjang.rekomendasi.length > 0) {
                        // Buat tab untuk setiap jenjang
                        gapHtml += `
                <div class="tab-pane fade ${jenjangIndex === 0 ? 'show active' : ''}"
                     id="content-${jenjang.jenjang_jabatan.replace(/\s+/g, '-')}"
                     role="tabpanel">
            `;

                        // Cek jika ada gap
                        let hasGap = false;
                        jenjang.rekomendasi.forEach(rek => {
                            if (rek.detail_gap && rek.detail_gap.length > 0) {
                                hasGap = true;
                            }
                        });

                        if (hasGap) {
                            gapHtml += `
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th width="3%">#</th>
                                    <th>Sub Indikator</th>
                                    <th width="10%" class="text-center">Level Dicapai</th>
                                    <th width="10%" class="text-center">Level Kebutuhan Belajar</th>
                                    <th width="10%" class="text-center">Gap</th>
                                    <th>Rekomendasi Kebutuhan Belajar</th>
                                    <th width="10%" class="text-center">Jumlah PTK</th>
                                    <th width="10%" class="text-center">Detail PTK</th>
                                    <th width="12%" class="text-center">% dari Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                            let counter = 1;
                            jenjang.rekomendasi.forEach((rek, rekIndex) => {
                                if (rek.detail_gap && rek.detail_gap.length > 0) {
                                    rek.detail_gap.forEach((gap, gapIndex) => {
                                        const percentage = jenjang.total_ptk > 0 ?
                                            Math.round((gap.jumlah_ptk / jenjang.total_ptk) * 100 *
                                                10) / 10 :
                                            0;
                                        const progressClass = percentage >= 30 ? 'bg-danger' :
                                            (percentage >= 15 ? 'bg-warning' : 'bg-info');

                                        gapHtml += `
                                <tr>
                                    <td class="text-center">${counter++}</td>
                                    <td>
                                        ${gapIndex === 0 ? `
                                                                                                                                                                                        <small class="text-muted">${rek.sub_indikator_code || '-'}</small><br>
                                                                                                                                                                                        <span class="fw-medium">${rek.sub_indikator_name ?
                                                                                                                                                                                            (rek.sub_indikator_name.length > 40 ?
                                                                                                                                                                                                rek.sub_indikator_name.substring(0, 40) + '...' :
                                                                                                                                                                                                rek.sub_indikator_name
                                                                                                                                                                                            ) : '-'}</span>
                                                                                                                                                                                    ` : ''}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-level" style="background-color: ${getLevelColor(gap.level_dicapai)}; color: white;">
                                            Level ${gap.level_dicapai}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-level" style="background-color: ${getLevelColor(gap.level_harus)}; color: white;">
                                            Level ${gap.level_harus}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        ${gap.level_gap > 0 ?
                                            `<span class="badge bg-danger">+${gap.level_gap}</span>` :
                                            `<span class="badge bg-success">0</span>`
                                        }
                                    </td>
                                    <td>
                                        <small>${gap.rekomendasi || '-'}</small>
                                    </td>
                                    <td class="text-center">${gap.jumlah_ptk || 0}</td>

<td class="text-center">
    <a href="{{ route('analisis.rekomendasi-gap.index') }}?kegiatan_id={{ request('kegiatan_id', '') }}&pangkat_jabatan_id={{ request('pangkat_jabatan_id', '') }}&jenis_ptk_id={{ request('jenis_ptk_id', '') }}&kota_id={{ request('kota_id', '') }}&jenjang_pendidikan_id={{ request('jenjang_pendidikan_id', '') }}&jenis_kelamin={{ request('jenis_kelamin', '') }}&sub_indikator_id=${rek.sub_indikator_id || ''}&jenjang_jabatan=${jenjang.jenjang_jabatan || ''}"
       class="btn btn-sm btn-info" target="_blank">
        <i class="ri-eye-line"></i> Lihat Detail PTK
    </a>
</td>

                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold">${percentage}%</span>
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar ${progressClass}"
                                                     role="progressbar"
                                                     style="width: ${Math.min(percentage, 100)}%;">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                                    });
                                }
                            });

                            gapHtml += `
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="7" class="text-end fw-bold">Total PTK pada jenjang ini:</td>
                                    <td class="text-center fw-bold">${jenjang.total_ptk}</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="7" class="text-end fw-bold">Total sub indikator dengan gap:</td>
                                    <td class="text-center fw-bold">${jenjang.rekomendasi.length}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                        } else {
                            // Tidak ada gap
                            gapHtml += `
                    <div class="alert alert-success text-center py-4">
                        <i class="ri-checkbox-circle-fill fs-4 text-success"></i>
                        <h5 class="mt-2 mb-0">Semua PTK sudah mencapai level kegiatan belajar!</h5>
                        <p class="text-muted mb-0">Tidak ada gap untuk jenjang ${jenjang.jenjang_jabatan}</p>
                    </div>
                `;
                        }

                        gapHtml += `</div>`; // Close tab-pane
                    }
                });

                // Buat HTML untuk tab navigation
                let tabNavs = '';
                data.rekomendasi_gap_per_jenjang.forEach((jenjang, jenjangIndex) => {
                    if (jenjang.rekomendasi && jenjang.rekomendasi.length > 0) {
                        const gapCount = jenjang.rekomendasi.length;
                        const safeId = jenjang.jenjang_jabatan.replace(/\s+/g, '-');

                        tabNavs += `
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${jenjangIndex === 0 ? 'active' : ''}"
                            id="tab-${safeId}"
                            data-bs-toggle="tab"
                            data-bs-target="#content-${safeId}"
                            type="button"
                            role="tab">
                        ${jenjang.jenjang_jabatan}
                        <span class="badge bg-danger ms-1">${gapCount}</span>
                    </button>
                </li>
            `;
                    }
                });

                // Gabungkan semua menjadi satu HTML
                html += `
        <div class="row mt-4">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-list-check-2"></i> Rekomendasi Kebutuhan Belajar per Jenjang Jabatan
                        <small class="text-muted ms-2">(Hanya menampilkan sub indikator yang belum mencapai level kebutuhan belajar)</small>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="mb-4">
                        <ul class="nav nav-tabs" id="jenjangTab" role="tablist">
                            ${tabNavs}
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="jenjangTabContent">
                        ${gapHtml}
                    </div>
                </div>
            </div>
        </div>
    `;
            }







            // Tambahkan tabel progress kota jika ada
            if (data.progress_kota?.length > 0) {
                let progressRows = '';
                data.progress_kota.forEach(kota => {
                    const progressClass = kota.persentase >= 80 ? 'bg-success' :
                        kota.persentase >= 50 ? 'bg-warning' : 'bg-danger';

                    progressRows += `
            <tr>
                <td>${kota.nama_kota}</td>
                <td style="text-align: center;">${kota.total_ptk}</td>
                <td style="text-align: center;">${kota.sudah_isi}</td>
                <td style="text-align: center; font-weight: 600;">${kota.persentase}%</td>
                <td>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar ${progressClass}"
                             role="progressbar"
                             style="width: ${kota.persentase}%;">
                        </div>
                    </div>
                </td>
            </tr>`;
                });

                html += `
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="chart-title">
                        <i class="ri-progress-3-line"></i> Progress Pengisian per Kota
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered modus-table">
                            <thead>
                                <tr>
                                    <th>Kota</th>
                                    <th>Total PTK</th>
                                    <th>Sudah Isi</th>
                                    <th>Persentase</th>
                                    <th>Progress Bar</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${progressRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`;
            }
            /* Di bagian updateAnalisisContent(), perbaiki bagian pelatihan */
            if (data.pelatihan_data?.length > 0) {
                const totalPelatihan = data.pelatihan_data.reduce((sum, item) => sum + (item.jumlah_ptk || 0), 0);

                html += `
    <div class="row mt-4">
        <div class="col-12">
            <div class="chart-container chart-container-large">
                <div class="chart-title">
                    <i class="ri-book-mark-line"></i> Distribusi Pelatihan yang diperlukan PTK
                    <span class="badge bg-info ms-2">Berdasarkan Kegiatan</span>
                </div>
                <canvas id="pelatihanChart" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="table-card">
                <div class="chart-title">
                    <i class="ri-list-check-2"></i> Detail Pelatihan yang Diikuti
                    <span class="badge bg-info ms-2">Total ${totalPelatihan} PTK</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered modus-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama Pelatihan</th>
                                <th width="10%" class="text-center">Tipe</th>
                                <th width="15%" class="text-center">Jumlah PTK</th>
                                <th width="15%" class="text-center">Persentase</th>
                                <th width="20%" class="text-center">Progress</th>
                            </tr>
                        </thead>
                        <tbody>`;

                data.pelatihan_data.forEach((pelatihan, index) => {
                    const persentase = totalPelatihan > 0 ?
                        ((pelatihan.jumlah_ptk / totalPelatihan) * 100).toFixed(1) : 0;
                    const progressClass = persentase >= 30 ? 'bg-success' :
                        persentase >= 15 ? 'bg-warning' : 'bg-danger';

                    // Tentukan tipe pelatihan
                    let tipeBadge = '';
                    if (pelatihan.tipe === 'master') {
                        tipeBadge = '<span class="badge bg-primary">Master</span>';
                    } else if (pelatihan.tipe === 'manual') {
                        tipeBadge = '<span class="badge bg-secondary">Manual</span>';
                    }

                    html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${pelatihan.nama_pelatihan || 'Pelatihan Lainnya'}</td>
                            <td class="text-center">${tipeBadge}</td>
                            <td class="text-center">${pelatihan.jumlah_ptk}</td>
                            <td class="text-center fw-bold">${persentase}%</td>
                            <td>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar ${progressClass}"
                                         role="progressbar"
                                         style="width: ${persentase}%;">
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                });

                html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>`;
            }


            document.getElementById('analisisContent').innerHTML = html;
            document.getElementById('analisisContent').style.display = 'block';

            setTimeout(() => renderCharts(data), 100);
        }

        /* =====================================================
           STAT CARD HELPER
        ===================================================== */
        function statCard(icon, label, value, color) {
            return `
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:${color}22;color:${color}">
                <i class="${icon} fs-4"></i>
            </div>
            <div class="stat-number">${value}</div>
            <div class="stat-label">${label}</div>
        </div>
    </div>`;
        }

        /* =====================================================
           HELPER FUNCTIONS - TAMBAHKAN FUNGSI INI
        ===================================================== */
        function hitungRataRataLevel(jenjangData) {
            try {
                // Debug: Lihat struktur data
                console.log('Data untuk perhitungan rata-rata:', jenjangData);

                // Cek jika rata_rata_level sudah ada dari server
                if (jenjangData.rata_rata_level !== undefined && jenjangData.rata_rata_level !== null) {
                    return parseFloat(jenjangData.rata_rata_level).toFixed(2);
                }

                // Jika tidak ada, hitung dari datasets
                let totalLevel = 0;
                let totalCount = 0;

                if (jenjangData.datasets && Array.isArray(jenjangData.datasets)) {
                    jenjangData.datasets.forEach(dataset => {
                        const label = dataset.label || '';
                        const levelMatch = label.match(/Level (\d+)/);

                        if (levelMatch && dataset.data && Array.isArray(dataset.data)) {
                            const level = parseInt(levelMatch[1]);
                            const jumlah = dataset.data.reduce((sum, val) => sum + (parseInt(val) || 0), 0);

                            totalLevel += level * jumlah;
                            totalCount += jumlah;
                        }
                    });
                }

                if (totalCount > 0) {
                    const rataRata = totalLevel / totalCount;
                    return rataRata.toFixed(2);
                }

                return '0.00';
            } catch (error) {
                console.error('Error calculating average level:', error, jenjangData);
                return 'N/A';
            }
        }

        function getLevelColor(level) {
            const colors = {
                1: '#17a212',
                2: '#17a2b8',
                3: '#007bff',
                4: '#ffc107',
                5: '#28a745'
            };
            return colors[level] || '#17a2b8';
        }

        function getLevelName(level) {
            const names = {
                1: 'Gagal',
                2: 'Penerapan',
                3: 'Analisis',
                4: 'Evaluasi',
                5: 'Pembimbingan'
            };
            return names[level] || 'Penerapan';
        }

        /* =====================================================
           RENDER ALL CHARTS
        ===================================================== */
        function renderCharts(data) {
            console.log('Rendering charts with data:', data);

            // Destroy existing charts
            [levelDistributionChart, jenjangDistributionChart, jenjangPendidikanChart, jenisKelaminChart,
                allSubIndikatorsChart, pelatihanChart
            ].forEach(chart => {
                if (chart) {
                    try {
                        chart.destroy();
                    } catch (e) {
                        console.log('Error destroying chart:', e);
                    }
                }
            });

            /* ================= LEVEL DISTRIBUTION (INCLUDE LEVEL 1) ================= */
            const levelCtx = document.getElementById('levelDistributionChart')?.getContext('2d');
            if (levelCtx) {
                const src = Array.isArray(data.level_distribution) ? data.level_distribution : [];
                const get = l => src.find(x => x.level === l)?.count ?? 0;

                levelDistributionChart = new Chart(levelCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'],
                        datasets: [{
                            label: 'Jumlah Jawaban',
                            data: [get(1), get(2), get(3), get(4), get(5)],
                            backgroundColor: ['#17a212', '#17a2b8', '#007bff', '#ffc107', '#28a745'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }


            /* ================= JENJANG DISTRIBUTION (BERDASARKAN PTK) ================= */
            const jenjangCtx = document.getElementById('jenjangDistributionChart')?.getContext('2d');
            if (jenjangCtx) {
                const src = data.jenjang_distribution?.length ?
                    data.jenjang_distribution : [{
                        jenjang_jabatan: 'Tidak Ada Data',
                        count: 0
                    }];

                jenjangDistributionChart = new Chart(jenjangCtx, {
                    type: 'doughnut',
                    data: {
                        labels: src.map(x => x.jenjang_jabatan),
                        datasets: [{
                            data: src.map(x => x.count),
                            backgroundColor: ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57',
                                '#5f27cd'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: { padding: { bottom: 26 } },
plugins: {
  legend: {
    position: 'bottom',
    align: 'center',
    labels: {
      usePointStyle: true,
      pointStyle: 'rectRounded',
      boxWidth: 10,
      boxHeight: 10,
      padding: 8,
      font: { size: 10 }
    }
  }
}
                    }
                });
            }

            /* ================= JENJANG PENDIDIKAN DISTRIBUTION ================= */
            const jenjangPendidikanCtx = document.getElementById('jenjangPendidikanChart')?.getContext('2d');
            if (jenjangPendidikanCtx) {
                const src = data.jenjang_pendidikan_distribution?.length ?
                    data.jenjang_pendidikan_distribution : [{
                        jenjang_pendidikan: 'Tidak Ada Data',
                        count: 0
                    }];

                jenjangPendidikanChart = new Chart(jenjangPendidikanCtx, {
                type: 'doughnut', // ✅ dari pie -> doughnut
                data: {
                    labels: src.map(x => x.jenjang_pendidikan),
                    datasets: [{
                    data: src.map(x => x.count),
                    backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40']
                    }]
                },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: { padding: { bottom: 26 } },
plugins: {
  legend: {
    position: 'bottom',
    align: 'center',
    labels: {
      usePointStyle: true,
      pointStyle: 'rectRounded',
      boxWidth: 10,
      boxHeight: 10,
      padding: 8,
      font: { size: 10 }
    }
  }
}
                    }
                });
            }
            /* ================= JENIS KELAMIN DISTRIBUTION (BERDASARKAN PTK) ================= */
            const jenisKelaminCtx = document.getElementById('jenisKelaminChart')?.getContext('2d');
            if (jenisKelaminCtx) {
                const src = data.jenis_kelamin_distribution?.length ?
                    data.jenis_kelamin_distribution : [{
                        jenis_kelamin: 'Tidak Ada Data',
                        count: 0
                    }];

                jenisKelaminChart = new Chart(jenisKelaminCtx, {
                    type: 'doughnut',
                    data: {
                        labels: src.map(x => x.jenis_kelamin),
                        datasets: [{
                            data: src.map(x => x.count),
                            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
                                '#166a8f'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        radius: '70%',
                        cutout: '65%',
                        layout: { padding: { bottom: 26 } },
plugins: {
  legend: {
    position: 'bottom',
    align: 'center',
    labels: {
      usePointStyle: true,
      pointStyle: 'rectRounded',
      boxWidth: 10,
      boxHeight: 10,
      padding: 8,
      font: { size: 10 }
    }
  }
}
                    }
                });
            }

            /* ================= ALL SUB INDIKATOR (BERDASARKAN PTK) ================= */
            const allSubCtx = document.getElementById('allSubIndikatorsChart')?.getContext('2d');
            if (allSubCtx && data.all_sub_indikators_chart) {
                const chartData = data.all_sub_indikators_chart;
                console.log('All sub indicators chart data:', chartData);

                if (chartData.labels?.length > 0 && chartData.datasets?.length > 0) {
                    allSubIndikatorsChart = new Chart(allSubCtx, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: chartData.datasets
                        },
options: {
  responsive: true,
  maintainAspectRatio: false,

  // ✅ kasih ruang buat legend bawah (biar gak niban chart)
  layout: { padding: { top: 8, bottom: 24 } },

  plugins: {
    legend: {
      position: 'bottom',     // ✅ ini yang kamu mau: bawah
      align: 'center',
      labels: {
        usePointStyle: true,
        pointStyle: 'circle',
        boxWidth: 8,
        boxHeight: 8,
        padding: 8,
        font: { size: 10 }
      }
    },
    tooltip: {
      mode: 'index',
      intersect: false,
      callbacks: {
        label: function(context) {
          const label = context.dataset.label || '';
          const value = context.raw;
          return `${label}: ${value} PTK`;
        }
      }
    }
  },

  scales: {
    x: {
      stacked: false,
      ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
    },
    y: {
      beginAtZero: true,
      ticks: { stepSize: 1 }
    }
  }
}
                    });
                } else {
                    console.log('Chart data kosong, tidak membuat chart');
                    const container = allSubCtx.canvas.parentElement;
                    if (container) {
                        container.innerHTML += `
                    <div class="alert alert-info mt-3">
                        <i class="ri-information-line"></i>
                        Tidak ada data untuk grafik sub indikator
                    </div>
                `;
                    }
                }
            }


            /* ================= PELATIHAN CHART ================= */
            const pelatihanCtx = document.getElementById('pelatihanChart')?.getContext('2d');
            if (pelatihanCtx && data.pelatihan_data?.length > 0) {
                const pelatihanData = data.pelatihan_data;
                const labels = pelatihanData.map(item => {
                    // Potong label jika terlalu panjang
                    const name = item.nama_pelatihan || 'Pelatihan Lainnya';
                    return name.length > 30 ? name.substring(0, 30) + '...' : name;
                });
                const values = pelatihanData.map(item => item.jumlah_ptk);

                // Generate warna berbeda untuk setiap bar
                const backgroundColors = labels.map((_, index) => {
                    const colors = [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                        '#4DC9F6', '#F67019', '#F53794', '#537BC4',
                        '#ACC236', '#166A8F', '#00A950', '#58595B'
                    ];
                    return colors[index % colors.length];
                });

                pelatihanChart = new Chart(pelatihanCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah PTK',
                            data: values,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                            borderWidth: 1
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
                                        const label = labels[context.dataIndex] || '';
                                        const value = context.raw;
                                        const total = values.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} PTK (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                title: {
                                    display: true,
                                    text: 'Nama Pelatihan',
                                    font: {
                                        size: 13
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah PTK',
                                    font: {
                                        size: 13
                                    }
                                }
                            }
                        }
                    }
                });
            }


            /* ================= RENDER JENJANG CHARTS ================= */
            renderJenjangCharts(data);
        }

        /* =====================================================
           RENDER JENJANG CHARTS
        ===================================================== */
        function renderJenjangCharts(data) {
            console.log('Rendering jenjang charts:', data?.sub_indikator_per_jenjang);

            // Hapus container sebelumnya
            const container = document.getElementById('jenjangChartsContainer');
            if (!container || !data?.sub_indikator_per_jenjang) return;

            // Hancurkan chart sebelumnya
            document.querySelectorAll('[id^="jenjangChart_"]').forEach(canvas => {
                const chartId = canvas.id;
                if (window[chartId]) {
                    try {
                        window[chartId].destroy();
                    } catch (e) {
                        console.log('Error destroying chart:', e);
                    }
                }
            });

            // Render chart untuk setiap jenjang
            data.sub_indikator_per_jenjang.forEach((jenjangData, index) => {
                const canvasId = `jenjangChart_${index}`;
                const canvas = document.getElementById(canvasId);

                if (!canvas) {
                    console.log(`Canvas ${canvasId} tidak ditemukan`);
                    return;
                }

                // Dapatkan context canvas
                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    console.log(`Context tidak ditemukan untuk ${canvasId}`);
                    return;
                }

                console.log(`Membuat chart untuk ${jenjangData.jenjang_jabatan}`, jenjangData);

                // Pastikan datasets ada dan valid
                if (!jenjangData.datasets || !Array.isArray(jenjangData.datasets)) {
                    console.log(`Datasets tidak valid untuk ${jenjangData.jenjang_jabatan}`);
                    return;
                }

// Buat chart
window[canvasId] = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: jenjangData.labels || [],
    datasets: (jenjangData.datasets || []).map(dataset => ({
      label: dataset.label || 'Unknown',
      data: dataset.data || [],
      backgroundColor: dataset.backgroundColor || '#17a2b8',
      borderColor: dataset.borderColor || '#17a2b8',
      borderWidth: dataset.borderWidth || 1
    }))
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    animation: false,
layout: { padding: { top: 2, bottom: 4, left: 6, right: 6 } },
plugins: {
  legend: {
    display: true,
    position: 'bottom',   // ✅ pindah ke bawah biar ga nabrak judul
    align: 'center',
    labels: {
      font: { size: 8 },
      boxWidth: 8,
      boxHeight: 8,
      padding: 6,
      usePointStyle: true,
      pointStyle: 'circle'
    }
  },
  tooltip: {
    mode: 'index',
    intersect: false,
    callbacks: { label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} PTK` }
  }
},
    scales: {
      x: {
        ticks: {
          font: { size: 9 },
          maxRotation: 45,
          minRotation: 45
        }
      },
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1, font: { size: 10 } },
        title: {
          display: true,
          text: 'Jumlah PTK',
          font: { size: 10 }
        }
      }
    }
  }
});

window[canvasId].resize();
setTimeout(() => window[canvasId]?.resize(), 50);
console.log(`Chart ${canvasId} berhasil dibuat`);

                console.log(`Chart ${canvasId} berhasil dibuat`);
            });
        }

        /* =====================================================
           INITIAL LOAD (SSR DATA)
        ===================================================== */
        @if (isset($analisisData) && !isset($analisisData['error']))
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Initial load dengan data:', @json($analisisData));
                renderCharts(@json($analisisData));
            });
        @endif
    </script>













    {{-- Tambahkan di bagian akhir script --}}
    <script>
        // Export Excel Lengkap (4 Sheet dalam 1 file)
        document.getElementById('btnExportLengkap')?.addEventListener('click', function() {
            const form = document.getElementById('analisisForm');
            const formData = new FormData(form);

            // Tambahkan parameter tambahan
            formData.append('_token', '{{ csrf_token() }}');

            // Buat URL dengan parameter
            let params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Tampilkan loading
            const btn = this;
            const originalText = btn.innerHTML;
            const originalWidth = btn.offsetWidth;

            // Set fixed width agar tidak berubah
            btn.style.minWidth = originalWidth + 'px';
            btn.innerHTML = '<i class="ri-loader-4-line align-bottom me-1 spin-icon"></i> Exporting...';
            btn.disabled = true;

            // Tambahkan class untuk animasi spin
            const style = document.createElement('style');
            style.innerHTML = `
        .spin-icon {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
            document.head.appendChild(style);

            // Redirect ke export URL
            setTimeout(() => {
                window.location.href = '{{ route('analisis.export-excel') }}?' + params.toString();

                // Reset tombol setelah 5 detik
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.style.minWidth = '';
                    document.head.removeChild(style);
                }, 5000);
            }, 500);
        });

        // Reset Form
        document.getElementById('btnReset')?.addEventListener('click', function() {
            document.getElementById('kegiatanSelect').value = '';
            document.getElementById('pangkatSelect').value = '';
            document.getElementById('jenisPtkSelect').value = '';
            document.getElementById('kotaSelect').value = '';
            document.getElementById('jenjangPendidikanSelect').value = '';
            document.getElementById('jenisKelaminSelect').value = '';
            document.getElementById('analisisForm').submit();
        });
    </script>



    <SCRIPT>
        // Event handler untuk tombol lihat detail (TANPA level_dicapai)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-view-gap-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const subIndikatorId = this.getAttribute('data-sub-indikator-id');
                    const jenjang = this.getAttribute('data-jenjang');

                    // Buat URL TANPA level_dicapai
                    const params = new URLSearchParams({
                        kegiatan_id: '{{ request('kegiatan_id') }}',
                        pangkat_jabatan_id: '{{ request('pangkat_jabatan_id') }}',
                        jenis_ptk_id: '{{ request('jenis_ptk_id') }}',
                        kota_id: '{{ request('kota_id') }}',
                        jenjang_pendidikan_id: '{{ request('jenjang_pendidikan_id') }}',
                        jenis_kelamin: '{{ request('jenis_kelamin') }}',
                        sub_indikator_id: subIndikatorId,
                        jenjang_jabatan: jenjang
                        // TIDAK ADA level_dicapai di sini!
                    });

                    window.location.href = '{{ route('analisis.rekomendasi-gap.index') }}?' +
                        params.toString();
                });
            });
        });
    </SCRIPT>

    <script>
        // Tambahkan di bagian akhir script JavaScript Anda
        document.getElementById('btnExportRekomendasi')?.addEventListener('click', function() {
            const form = document.getElementById('analisisForm');
            const formData = new FormData(form);

            // Tambahkan parameter tambahan
            formData.append('_token', '{{ csrf_token() }}');

            // Buat URL dengan parameter
            let params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Tampilkan loading
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line align-bottom me-1 spin-icon"></i> Exporting...';
            btn.disabled = true;

            // Redirect ke export URL
            setTimeout(() => {
                window.location.href = '{{ route('export.rekomendasi-gap') }}?' + params.toString();

                // Reset tombol setelah 5 detik
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 5000);
            }, 500);
        });
    </script>
@endsection
