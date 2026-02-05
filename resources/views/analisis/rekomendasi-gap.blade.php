@extends('layouts.main')
@section('mycontent')
    @php
        $title = 'Detail PTK dengan Kebutuhan Belajar';

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

        // Helper function untuk mendapatkan rekomendasi pelatihan
        function getRekomendasiPelatihan(
            $subIndikatorId,
            $tahap,
            $entity,
            $subIndikatorCode,
            $levelDicapai,
            $levelTarget,
        ) {
            // Coba ambil dari database terlebih dahulu
            $rekomendasi = DB::table('ptk_rekomendasi')
                ->where('sub_indikator_id', $subIndikatorId)
                ->where('sub_indikator_code', $subIndikatorCode)
                ->where('level', $levelTarget)
                ->first();

            if ($rekomendasi) {
                return $rekomendasi->rekomendasi;
            }

            // Jika tidak ada di database, buat rekomendasi generik
            $levelNames = [
                1 => 'Gagal',
                2 => 'Penerapan',
                3 => 'Analisis',
                4 => 'Evaluasi',
                5 => 'Pembimbingan',
            ];

            $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
            $levelTargetName = $levelNames[$levelTarget] ?? "Level $levelTarget";
            $gap = $levelTarget - $levelDicapai;

            if ($gap == 1) {
                return "Pelatihan untuk meningkatkan dari {$levelDicapaiName} ke {$levelTargetName}";
            } else {
                return "Pelatihan intensif untuk meningkatkan dari {$levelDicapaiName} ke {$levelTargetName} (naik {$gap} level)";
            }
        }
    @endphp

    <style>
        
/* =====================================================
   DETAIL PTK — CLEAN STYLE (PUTIH + BIRU)
   (tanpa ubah DB/BE/HTML)
===================================================== */
:root{
  --p:#1a5bb8;
  --p2:#2d6bc8;
  --blue:#1a4d8e;
  --ink:#1a2937;
  --muted:#64748b;
  --line:#e2e8f0;
  --soft:#f8fafc;
  --soft2:#eef2f7;
}

/* ====== Base wrap ====== */
.analisis-wrap{
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius:20px;
  padding:25px;
  margin-bottom:30px;
  position:relative;
  overflow:hidden;
}
.analisis-wrap::before{
  content:'';
  position:absolute;
  top:0; right:0;
  width:300px; height:300px;
  background: linear-gradient(135deg, rgba(26,91,184,.05) 0%, rgba(26,91,184,0) 70%);
  border-radius:0 20px 0 0;
  z-index:0;
}
.analisis-wrap > *{ position:relative; z-index:1; }

/* ====== Header baduy ====== */
.analisis-head{
  position:relative;
  border-radius:18px;
  overflow:hidden;
  padding:18px 18px !important;
  min-height:92px;
  background: var(--blue) !important;
  border-bottom:1px solid rgba(255,255,255,.14);
  color:#fff !important;
}
.analisis-head::before{
  content:"";
  position:absolute; inset:0;
  background-image:url("{{ asset('build/images/baduy.jpg') }}");
  background-repeat:repeat;
  background-size:140px auto;
  background-position:center;
  opacity:.55;
  filter:grayscale(100%) contrast(1.15);
  z-index:0;
}
.analisis-head::after{
  content:"";
  position:absolute; inset:0;
  background: rgba(26,91,184,.50);
  z-index:1;
  pointer-events:none;
}
.analisis-head > *{ position:relative; z-index:2; }

.analisis-head h5{
  margin:0 !important;
  font-weight:900 !important;
  font-size:20px !important;
  letter-spacing:.2px;
  color:#fff !important;
  line-height:1.15;
  text-shadow:0 2px 12px rgba(0,0,0,.35);
}
.analisis-head h5 i{
  width:44px;
  height:44px;
  border-radius:14px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  background:rgba(255,255,255,.14);
  border:1px solid rgba(255,255,255,.18);
  color:#fff;
  backdrop-filter:blur(6px);
  margin-right:10px;
}
.analisis-head .meta{
  margin-top:8px !important;
  font-weight:500;
  font-size:13px;
  color:rgba(255,255,255,.92) !important;
  text-shadow:0 2px 12px rgba(0,0,0,.35);
}
.analisis-head .meta small,
.analisis-head .text-muted{ color:rgba(255,255,255,.90) !important; }

.analisis-head .alert{
  margin-top:12px !important;
  margin-bottom:0 !important;
  background:rgba(255,255,255,.14) !important;
  border:1px solid rgba(255,255,255,.22) !important;
  color:#fff !important;
  border-radius:14px;
  backdrop-filter:blur(6px);
}
.analisis-head .alert strong,
.analisis-head .alert i{ color:#fff !important; }

/* ====== Filter info card ====== */
.filter-info-card{
  background:#fff;
  border-radius:16px;
  padding:20px;
  margin-bottom:25px;
  border:1px solid var(--line);
}
.filter-badge{
  background:#e6f7ff;
  color:#0056b3;
  padding:5px 12px;
  border-radius:8px;
  font-size:.85rem;
  font-weight:600;
  border:1px solid #b3d9ff;
}

/* ====== Sub indikator card ====== */
.sub-indikator-card{
  border-left: none !important;
  padding-left: 20px !important;   /* biar gak kerasa mepet */
}
.sub-indikator-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:16px;
  padding-bottom:14px;
  border-bottom:1px solid var(--line);
}

/* ====== CARD PUTIH BARU UNTUK 1 JENJANG (Jenjang Madya -> sampai PTK) ====== */
.jenjang-white-card{
  background:#fff;
  border:1px solid rgba(15,23,42,.10);
  border-radius:18px;
  padding:18px;
  box-shadow:0 10px 26px rgba(2,6,23,.06);
}
.jenjang-white-card .sub-indikator-header{
  margin-bottom:14px;
  padding-bottom:12px;
}
@media (max-width:768px){
  .jenjang-white-card{ padding:14px; }
}

/* ====== Info keterangan (gap-info) ====== */
.gap-info{
  background:#fff;
  border:1px solid #dbeafe;
  border-radius:14px;
  padding:14px 16px;
  margin:12px 0 16px;
  color: var(--ink);
}
.gap-info i{ color:var(--p) !important; margin-right:8px; }

/* ====== Stat cards (angka hitam, icon beda warna) ====== */
.stat-card-gap{
  background:#fff;
  border-radius:16px;
  padding:22px;
  border:1px solid var(--line);
  position:relative;
  overflow:hidden;
  height:100%;
}
.stat-card-gap::before{
  content:'';
  position:absolute;
  top:0; left:0;
  width:5px; height:100%;
  background: linear-gradient(to bottom, var(--p), var(--p2));
}
.stat-icon-gap{
  width:56px;
  height:56px;
  border-radius:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:12px;
}
.stat-icon-gap i{ font-size:1.5rem; color:inherit !important; }

.stat-number{
  font-size:2rem;
  font-weight:900;
  color: var(--ink);
  line-height:1;
  margin-bottom:6px;
}

/* label jangan item pekat + kecilin dikit */
.stat-label{
  font-size:.92rem;
  color:#475569;
  margin:0;
  font-weight:700;
}

/* beda warna icon box per kartu */
#analisisContent .row.mb-4 > .col-md-3:nth-child(1) .stat-icon-gap{ background:#eaf2ff; color:#2563eb; }
#analisisContent .row.mb-4 > .col-md-3:nth-child(2) .stat-icon-gap{ background:#e9f9ef; color:#16a34a; }
#analisisContent .row.mb-4 > .col-md-3:nth-child(3) .stat-icon-gap{ background:#fdecec; color:#ef4444; }
#analisisContent .row.mb-4 > .col-md-3:nth-child(4) .stat-icon-gap{ background:#eef2ff; color:#4f46e5; }

/* ====== Badges ====== */
.badge-target{
  background: linear-gradient(135deg, var(--p), var(--p2));
  color:#fff;
  padding:6px 10px;
  border-radius:10px;
  font-size:.75rem;
  font-weight:800;
}
.badge-gap{
  background: linear-gradient(135deg, var(--p), var(--p2)) !important;
  color:#fff !important;
  padding:6px 10px !important;
  border-radius:10px !important;
  font-size:.75rem !important;
  font-weight:800 !important;
}
.analisis-wrap .badge.bg-danger{
  background: linear-gradient(135deg, var(--p), var(--p2)) !important;
  border:none !important;
  color:#fff !important;
}
.analisis-wrap .badge.bg-warning{
  border:none !important;
  font-weight:800;
}

/* ====== Level section ====== */
.level-section{ margin-top:16px; margin-bottom:22px; }

/* LEVEL HEADER jadi card utama (gabung rekomendasi) */
.level-header-card{
  background:#fff !important;
  border:1px solid var(--line) !important;
  border-radius:16px !important;
  padding:16px 16px 12px !important;
  margin-bottom:14px !important;
}

/* Badge level di kiri */
.badge-level{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  white-space:nowrap;
  padding:8px 14px;
  border-radius:12px;
  line-height:1;
  font-weight:900;
  font-size:.85rem;
}

/* Rekomendasi jadi section di DALAM card (bukan card baru) */
.level-header-card .rekomendasi-pelatihan{
  margin-top:14px !important;
  border:none !important;
  box-shadow:none !important;
  background:transparent !important;
  padding:14px 0 0 !important;
  border-top:1px dashed #dbeafe !important;
  border-radius:0 !important;
}
.level-header-card .rekomendasi-pelatihan h6{
  color:var(--p) !important;
  font-weight:900;
  margin-bottom:10px !important;
}
.level-header-card .rekomendasi-pelatihan ul{
  margin:0 !important;
  padding-left:20px !important;
}
.level-header-card .rekomendasi-pelatihan li{
  line-height:1.7 !important;
  margin-bottom:10px !important;
}
.level-header-card .rekomendasi-pelatihan li:last-child{ margin-bottom:0 !important; }
.level-header-card .rekomendasi-pelatihan strong{ color:var(--p) !important; }

/* ====== Level tanpa PTK: gabung jadi 1 card ====== */
.level-header-card:has(+ .empty-level){
  border-bottom-left-radius:0 !important;
  border-bottom-right-radius:0 !important;
  margin-bottom:0 !important;
  border-bottom:none !important;
}
.level-header-card + .empty-level{
  background:#fff !important;
  border:1px solid var(--line) !important;
  border-top:none !important;
  border-radius:0 0 16px 16px !important;
  margin:0 0 14px !important;
  padding:18px 16px !important;
}
.level-header-card + .empty-level i{
  font-size:2.2rem !important;
  opacity:.55 !important;
}
.level-header-card + .empty-level small{ color:var(--muted) !important; }

/* ====== Table card (INI AJA yang "card tabel") ====== */
.level-table-card{
  background:#fff;
  border-radius:16px;
  padding:18px;
  border:1px solid var(--line);
}

/* =====================================================
   TABLE LINES — FIX (GARIS LEBIH HALUS + RAPI)
===================================================== */

/* container tabel jadi card halus + rounded */
.ptk-table-container{
  max-height:400px;
  overflow-y:auto;
  margin-top:10px;
}
.ptk-table{
  font-size:.85rem;
  width:100%;
  border-collapse:collapse;
}
.ptk-table th{
  background:#eef2f7;
  font-weight:900;
  padding:12px 10px;
  position:sticky;
  top:0;
  z-index:1;
  white-space:nowrap;

  border-bottom:1px solid #e2e8f0 !important;
  border-right:1px solid #eef2f7 !important;
}
.ptk-table thead th:last-child{ border-right:none !important; }

.ptk-table tbody td{
  padding:10px !important;
  vertical-align:middle !important;
  border-bottom:1px solid #eef2f7 !important;
  border-right:1px solid #f1f5f9 !important;
}
.ptk-table tbody tr:hover{ background:#f8fafc !important; }

/* ====== Pagination ====== */
.pagination-sm .page-link{ font-size:.75rem; padding:.25rem .5rem; }
.page-item.active .page-link{ background-color:var(--p); border-color:var(--p); }
.page-link{ color:var(--p); border:1px solid #dee2e6; }
.page-link:hover{ color:#0d47a1; background:#e9ecef; border-color:#dee2e6; }

/* ====== Responsive ====== */
@media (max-width:768px){
  .ptk-table{ font-size:.8rem; }
  .ptk-table th, .ptk-table td{ padding:8px 6px; }
}

/* ====== Center tombol empty state (kalau ada) ====== */
#analisisContent .row .col-12.d-flex .text-center .btn{
  display:inline-flex;
  justify-content:center;
  align-items:center;
  margin-left:auto !important;
  margin-right:auto !important;
}
#analisisContent .row .col-12.d-flex .text-center{
  display:flex;
  flex-direction:column;
  align-items:center;
}

/* ====== MATIIN SEMUA HOVER GERAK / TRANSISI ====== */
.stat-card-gap,
.sub-indikator-card,
.level-header-card,
.level-table-card,
.gap-info,
.filter-info-card,
.jenjang-white-card{
  transition:none !important;
}
.sub-indikator-card:hover,
.level-header-card:hover,
.level-table-card:hover,
.jenjang-white-card:hover{
  transform:none !important;
}

/* =====================================================
   FIX TOTAL: 1 CARD (LEVEL + REKOMENDASI + TABLE)
   + GARIS BIRU TIDAK NUTUP TABLE
===================================================== */
.level-section{
  background:#fff !important;
  border:1px solid #dbeafe !important;
  border-left:6px solid #1a5bb8 !important;
  border-radius:16px !important;
  padding:16px 16px 14px 16px !important;
  overflow:hidden !important;
}

/* Header level: jadi bagian dalam (bukan card sendiri) */
.level-section .level-header-card{
  background:transparent !important;
  border:none !important;
  border-radius:0 !important;
  padding:0 0 12px 0 !important;
  margin:0 !important;
  box-shadow:none !important;
}

/* Rekomendasi: tetap rapi tapi bukan card baru */
.level-section .rekomendasi-pelatihan{
  background:transparent !important;
  border:none !important;
  border-left:none !important;
  border-radius:0 !important;
  box-shadow:none !important;
  padding:12px 0 0 0 !important;
  margin:10px 0 0 0 !important;
  border-top:1px dashed #dbeafe !important;
}

/* Table card: jadi bagian dalam wrapper */
.level-section .level-table-card{
  background:transparent !important;
  border:none !important;
  border-radius:0 !important;
  padding:12px 0 0 0 !important;
  margin:0 !important;
  box-shadow:none !important;
}

/* Biar table gak nempel kiri & gak “kegigit” garis */
.level-section .ptk-table-container{
  padding-left:6px !important;
  padding-right:2px !important;
  margin-top:10px !important;
}

/* Pastikan table full dan gak overflow ke kiri */
.level-section .ptk-table{
  width:100% !important;
  margin:0 !important;
}

/* HAPUS pseudo garis biru lama kalau ada yang masih nyangkut */
.level-header-card::before,
.level-table-card::before,
.rekomendasi-pelatihan::before{
  content:none !important;
}

/* PAKSA badge Level jadi sama panjang */
.level-header-card span.badge-level{
  display: inline-flex !important;
  flex: 0 0 165px !important;
  width: 165px !important;
  max-width: 165px !important;
  justify-content: center !important;
  text-align: center !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}
/* ===== SAMAIN LEBAR CARD JENJANG KAYAK CARD ATAS ===== */

/* jangan dorong ke kanan */
.sub-indikator-card{
  padding-left: 0 !important;
}

/* card jenjang full lebar */
.jenjang-white-card{
  width: 100% !important;
  display: block !important;
  box-sizing: border-box !important;
}

/* opsional: biar jaraknya rapi sama statistik */
.sub-indikator-card.mb-4{
  margin-top: 6px;
}
/* ===== RINGKASAN: JADI CARD PUTIH + HILANGIN GARIS/BORDER ANEH ===== */

/* bungkus ringkasan jadi card putih */
#analisisContent .sub-indikator-card.mt-4{
  background:#fff !important;
  border:1px solid rgba(15,23,42,.10) !important;
  border-radius:18px !important;
  padding:18px !important;
  box-shadow:0 10px 26px rgba(2,6,23,.06) !important;
}

/* header ringkasan jangan ada garis bawah */
#analisisContent .sub-indikator-card.mt-4 .sub-indikator-header{
  border-bottom:1px solid #e2e8f0 !important;
  padding-bottom:12px !important;
  margin-bottom:14px !important;
}

/* container tabel ringkasan: hilangin garis putih / border */
#analisisContent .sub-indikator-card.mt-4 .ptk-table-container{
  background:transparent !important;
  border:none !important;
  box-shadow:none !important;
  margin-top:0 !important;
  padding:0 !important;
}

/* tabel bootstrap yang ringkasan: jangan bordered, jangan ada garis putih */
#analisisContent .sub-indikator-card.mt-4 table.table{
  background:transparent !important;
  border:none !important;
  margin:0 !important;
}
#analisisContent .sub-indikator-card.mt-4 table.table-bordered > :not(caption) > *{
  border-width:0 !important; /* matiin semua border bawaan */
}
#analisisContent .sub-indikator-card.mt-4 table.table thead th{
  background:#eef2f7 !important;
  border-bottom:1px solid #e2e8f0 !important;
}
#analisisContent .sub-indikator-card.mt-4 table.table tbody td{
  border-bottom:1px solid #edf2f7 !important;
}
/* ================================
   PAKSA: GARIS TABLE JANGAN ITEM
   (taruh paling bawah)
================================ */

/* Bootstrap 5 table vars (kalau kepake) */
.table{
  --bs-table-border-color: #e2e8f0 !important;
}

/* Card container */
.ptk-table-container{
  border:1px solid #e2e8f0 !important;
  border-radius:14px !important;
  overflow:hidden !important;
  background:#fff !important;
}

/* ===== 1) TABEL CUSTOM (.ptk-table) ===== */
.ptk-table{
  width:100% !important;
  border-collapse:separate !important;
  border-spacing:0 !important;
}

/* HEADER */
.ptk-table thead th{
  background:#f1f5f9 !important;
  color:#0f172a !important;
  border-bottom:1px solid #e2e8f0 !important;

  /* MATIIN garis vertikal item */
  border-left:none !important;
  border-right:none !important;
}

/* BODY */
.ptk-table tbody td{
  border-bottom:1px solid #eef2f7 !important;

  /* MATIIN garis vertikal item */
  border-left:none !important;
  border-right:none !important;
}

/* terakhir: jangan ada garis bawah */
.ptk-table tbody tr:last-child td{ border-bottom:none !important; }

/* ===== 2) TABEL RINGKASAN (bootstrap table-bordered) ===== */
#analisisContent .sub-indikator-card.mt-4 table.table{
  width:100% !important;
  border-collapse:separate !important;
  border-spacing:0 !important;
  background:#fff !important;
}

/* PAKSA override semua border bawaan bootstrap */
#analisisContent .sub-indikator-card.mt-4 table.table.table-bordered,
#analisisContent .sub-indikator-card.mt-4 table.table.table-bordered *{
  border-color:#e2e8f0 !important;
}

/* MATIIN border kotak/vertikal bawaan */
#analisisContent .sub-indikator-card.mt-4 table.table.table-bordered > :not(caption) > * > *{
  border-left:none !important;
  border-right:none !important;
  border-top:none !important;
  border-bottom:1px solid #eef2f7 !important; /* cuma garis bawah halus */
}

/* header garis bawah lebih tegas dikit */
#analisisContent .sub-indikator-card.mt-4 table.table thead th{
  background:#f1f5f9 !important;
  border-bottom:1px solid #e2e8f0 !important;
}

/* baris terakhir tanpa garis bawah */
#analisisContent .sub-indikator-card.mt-4 table.table tbody tr:last-child td{
  border-bottom:none !important;
}

/* =====================================================
   RESPONSIVE MOBILE FIX (taruh paling bawah)
===================================================== */

/* Global spacing mobile */
@media (max-width: 576px) {
  .container-fluid { padding-left: 12px !important; padding-right: 12px !important; }

  .analisis-wrap{
    padding:14px !important;
    border-radius:16px !important;
  }

  .analisis-head{
    padding:14px !important;
    border-radius:16px !important;
    min-height:auto !important;
  }
  .analisis-head h5{
    font-size:16px !important;
    gap:10px !important;
  }
  .analisis-head h5 i{
    width:38px !important;
    height:38px !important;
    border-radius:12px !important;
    margin-right:8px !important;
  }
  .analisis-head .meta{
    font-size:12px !important;
    line-height:1.45 !important;
  }
  .analisis-head .alert{
    padding:10px 12px !important;
    border-radius:12px !important;
  }

  /* Filter card */
  .filter-info-card{
    padding:14px !important;
    border-radius:14px !important;
  }
  .filter-badge{
    font-size:.78rem !important;
    padding:5px 10px !important;
    border-radius:10px !important;
  }

  /* Stat cards jadi 2 kolom */
  #analisisContent .row.mb-4 > .col-md-3{
    width:50% !important;
    flex:0 0 50% !important;
    margin-bottom:12px !important;
  }
  .stat-card-gap{ padding:14px !important; border-radius:14px !important; }
  .stat-number{ font-size:1.5rem !important; }
  .stat-label{ font-size:.85rem !important; }

  /* Jenjang card */
  .jenjang-white-card{
    padding:14px !important;
    border-radius:16px !important;
  }

  .sub-indikator-header{
    flex-direction:column !important;
    align-items:flex-start !important;
    gap:10px !important;
  }
  .sub-indikator-header h5,
  .sub-indikator-header h6{
    font-size:15px !important;
    line-height:1.3 !important;
  }

  /* Badge wrap rapi */
  .sub-indikator-header .d-flex.gap-2,
  .jenjang-white-card .d-flex.gap-2.mt-2{
    gap:6px !important;
  }
  .badge-target,
  .badge-gap,
  .badge.bg-danger,
  .badge.bg-secondary,
  .badge.bg-info,
  .badge.bg-warning{
    font-size:.72rem !important;
    padding:6px 10px !important;
    border-radius:10px !important;
  }

  /* Level section */
  .level-section{
    padding:14px !important;
    border-radius:14px !important;
  }

  /* Header level jadi stack */
  .level-header-card .d-flex.justify-content-between{
    flex-direction:column !important;
    align-items:flex-start !important;
    gap:10px !important;
  }

  /* Badge level jangan maksa lebar 165 di HP */
  .level-header-card span.badge-level{
    width:auto !important;
    max-width:100% !important;
    flex:unset !important;
    padding:8px 12px !important;
    border-radius:12px !important;
    font-size:.8rem !important;
  }

  .level-header-card .ms-3.fw-bold,
  .level-header-card .ms-3{
    margin-left:0 !important;
    display:block !important;
    margin-top:6px !important;
  }

  /* Gap info */
  .gap-info{
    padding:12px 12px !important;
    border-radius:12px !important;
    font-size:.9rem !important;
    line-height:1.45 !important;
  }

  /* EMPTY LEVEL */
  .level-header-card + .empty-level{
    padding:14px 12px !important;
  }
  .level-header-card + .empty-level i{
    font-size:1.8rem !important;
  }

  /* ===========================
     TABLE: bikin aman di HP
     =========================== */
  .ptk-table-container{
    overflow-x:auto !important;
    -webkit-overflow-scrolling:touch;
  }

  /* kasih min-width biar bisa scroll, ga mepet */
  .ptk-table{
    min-width:740px !important;
    font-size:.82rem !important;
  }
  .ptk-table thead th,
  .ptk-table tbody td{
    padding:8px 8px !important;
  }

  /* ringkasan table bootstrap juga scroll */
  #analisisContent .sub-indikator-card.mt-4 .ptk-table-container{
    overflow-x:auto !important;
    -webkit-overflow-scrolling:touch;
  }
  #analisisContent .sub-indikator-card.mt-4 table.table{
    min-width:980px !important;
    font-size:.82rem !important;
  }
  #analisisContent .sub-indikator-card.mt-4 table.table th,
  #analisisContent .sub-indikator-card.mt-4 table.table td{
    padding:8px 8px !important;
    white-space:nowrap !important;
  }

  /* link tel jangan nge-break */
  a[href^="tel:"]{ white-space:nowrap !important; }
}

/* Tablet: masih rapihin dikit */
@media (max-width: 768px) {
  .analisis-wrap{ padding:18px !important; }
  .ptk-table-container{ overflow-x:auto !important; -webkit-overflow-scrolling:touch; }
  .ptk-table{ min-width:720px !important; }
}
/* =====================================================
   MOBILE: TABLE HORIZONTAL SCROLL (GESER KANAN-KIRI)
   taruh paling bawah
===================================================== */
@media (max-width: 576px){

  /* semua container tabel boleh geser */
  .ptk-table-container{
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch;
    width: 100% !important;
    max-width: 100% !important;
  }

  /* paksa tabel custom melebar biar bisa di-scroll */
  .ptk-table{
    width: max-content !important;   /* penting */
    min-width: 760px !important;     /* atur sesuai kolom */
  }

  /* paksa tabel ringkasan (bootstrap) juga melebar */
  #analisisContent .sub-indikator-card.mt-4 table.table{
    width: max-content !important;   /* penting */
    min-width: 980px !important;     /* ringkasan kolom lebih banyak */
  }

  /* biar isi kolom gak patah random, jadi enak digeser */
  .ptk-table th, .ptk-table td,
  #analisisContent .sub-indikator-card.mt-4 table.table th,
  #analisisContent .sub-indikator-card.mt-4 table.table td{
    white-space: nowrap !important;
  }

  /* khusus kolom Nama & Sekolah boleh panjang tapi tetep satu baris */
  .ptk-table td:nth-child(3),
  .ptk-table td:nth-child(5),
  #analisisContent .sub-indikator-card.mt-4 table.table td:nth-child(3),
  #analisisContent .sub-indikator-card.mt-4 table.table td:nth-child(6){
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}

/* =========================================
   SAMAIN UKURAN BADGE (GAP / STATUS / TARGET)
   taruh paling bawah
========================================= */
#analisisContent .badge,
#analisisContent .badge-gap,
#analisisContent .badge-target,
#analisisContent .badge-level{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;

  height:32px !important;          /* <= ukuran seragam */
  padding:0 12px !important;       /* <= padding seragam */
  font-size:.78rem !important;     /* <= font seragam */
  font-weight:800 !important;
  line-height:1 !important;
  border-radius:12px !important;

  white-space:nowrap !important;
}

/* khusus badge-level biar gak terlalu panjang */
#analisisContent .badge-level{
  height:34px !important;
  padding:0 14px !important;
}

/* mobile: sedikit kecil biar muat */
@media (max-width:576px){
  #analisisContent .badge,
  #analisisContent .badge-gap,
  #analisisContent .badge-target,
  #analisisContent .badge-level{
    height:30px !important;
    padding:0 10px !important;
    font-size:.74rem !important;
    border-radius:11px !important;
  }
}

/* OPTIONAL: samain jarak antar badge biar rapi */
#analisisContent .d-flex.gap-2.flex-wrap{
  gap:8px !important;
}
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('analisis.index') }}">Analisis</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="analisis-wrap">
            <!-- Header -->
            <div class="analisis-head">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ri-alert-line"></i> {{ $title }}
                </h5>

                <div class="meta mt-2">
                    Detail PTK yang memiliki kebutuhan belajar (gap) berdasarkan filter yang diterapkan
                    @if ($request->filled('sub_indikator_id') || $request->filled('jenjang_jabatan'))
                        <br>
                        <small>
                            Filter spesifik diterapkan:
                            @if ($request->filled('sub_indikator_id'))
                                Sub Indikator ID: {{ $request->sub_indikator_id }}
                            @endif
                            @if ($request->filled('jenjang_jabatan'))
                                | Jenjang: {{ $request->jenjang_jabatan }}
                            @endif
                        </small>
                    @endif
                </div>

                @if ($request->filled('sub_indikator_id'))
                    <div class="alert alert-info">
                        <i class="ri-information-line"></i>
                        <strong>Menampilkan semua level untuk sub indikator ini:</strong>
                        {{ $subIndikatorName ?? 'Sub Indikator Terpilih' }}
                        @if ($request->filled('jenjang_jabatan'))
                            | Jenjang: {{ $request->jenjang_jabatan }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- INFO FILTER YANG DITERAPKAN -->
            @if (
                $request->hasAny([
                    'kegiatan_id',
                    'pangkat_jabatan_id',
                    'jenis_ptk_id',
                    'kota_id',
                    'jenjang_pendidikan_id',
                    'jenis_kelamin',
                ]))
                <div class="filter-info-card">
                    <h6 class="mb-3 d-flex align-items-center gap-2">
                        <i class="ri-filter-line"></i> Filter yang Diterapkan
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($request->filled('kegiatan_id'))
                            <span class="filter-badge">
                                Kegiatan:
                                {{ $kegiatans->where('kegiatan_id', $request->kegiatan_id)->first()->kegiatan_name ?? '-' }}
                            </span>
                        @endif

                        @if ($request->filled('pangkat_jabatan_id'))
                            <span class="filter-badge">
                                Jenjang:
                                {{ $pangkatJabatans->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)->first()->jenjang_jabatan ?? '-' }}
                            </span>
                        @endif

                        @if ($request->filled('jenis_ptk_id'))
                            <span class="filter-badge">
                                Jenis PTK:
                                {{ $jenisPtkList->where('jenis_ptk_id', $request->jenis_ptk_id)->first()->jenis_ptk ?? '-' }}
                            </span>
                        @endif

                        @if ($request->filled('kota_id'))
                            <span class="filter-badge">
                                Kota:
                                {{ $kotas->where('kota_id', $request->kota_id)->first()->nama_kota ?? '-' }}
                            </span>
                        @endif

                        @if ($request->filled('jenjang_pendidikan_id'))
                            <span class="filter-badge">
                                Jenjang Pendidikan:
                                {{ $jenjangPendidikanList->where('jenjang_pendidikan_id', $request->jenjang_pendidikan_id)->first()->jenjang_pendidikan ?? '-' }}
                            </span>
                        @endif

                        @if ($request->filled('jenis_kelamin'))
                            <span class="filter-badge">
                                Jenis Kelamin:
                                {{ $request->jenis_kelamin == 'L' ? 'Laki-laki' : ($request->jenis_kelamin == 'P' ? 'Perempuan' : $request->jenis_kelamin) }}
                            </span>
                        @endif

                        @if ($request->filled('bentuk_pendidikan'))
                            <span class="filter-badge">Bentuk Pendidikan: {{ $request->bentuk_pendidikan }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Konten Data -->
            <div id="analisisContent">
                @if (isset($analisisData) && !empty($analisisData['detail_per_jenjang']))
                    <!-- Statistik Gap -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-group-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_unique_ptk'] ?? 0 }}</div>
                                <div class="stat-label">PTK dengan Gap</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-list-check fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_unique_sub_indikator'] ?? 0 }}</div>
                                <div class="stat-label">Sub Indikator Bermasalah</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-file-list-3-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ $analisisData['total_data_ptk'] ?? 0 }}</div>
                                <div class="stat-label">Total Data Gap</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card-gap">
                                <div class="stat-icon-gap">
                                    <i class="ri-stack-line fs-4"></i>
                                </div>
                                <div class="stat-number">{{ count($analisisData['detail_per_jenjang']) ?? 0 }}</div>
                                <div class="stat-label">Jenjang dengan Gap</div>
                            </div>
                        </div>
                    </div>

                    @foreach ($analisisData['detail_per_jenjang'] as $jenjangIndex => $jenjang)
                        @if (!empty($jenjang['detail_per_sub_indikator']))
                            <div class="sub-indikator-card mb-4">

                                {{-- ✅ BUNGKUS DARI "JENJANG MADYA" SAMPE PTK DI CARD PUTIH --}}
                                <div class="jenjang-white-card">

                                    <div class="sub-indikator-header">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="ri-user-star-line text-primary"></i>
                                                Jenjang {{ $jenjang['jenjang_jabatan'] }}
                                            </h5>
                                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                                <span class="badge-target">Target Level: {{ $jenjang['target_level'] }}</span>
                                                <span class="badge bg-danger">{{ $jenjang['total_ptk'] }} PTK dengan Gap</span>
                                                <span class="badge bg-secondary">{{ $jenjang['total_sub_indikator'] }} Sub Indikator Bermasalah</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="gap-info">
                                        <i class="ri-information-line"></i>
                                        <strong>Keterangan:</strong> PTK di jenjang {{ $jenjang['jenjang_jabatan'] }} harus
                                        mencapai level {{ $jenjang['target_level'] }}.
                                        Hanya menampilkan sub indikator yang memiliki PTK dengan level di bawah target.
                                    </div>

                                    @foreach ($jenjang['detail_per_sub_indikator'] as $subIndex => $sub)
                                        @if ($sub['total_ptk'] > 0)
                                            <div class="mb-4">
                                                <div class="sub-indikator-header">
                                                    <div>
                                                        <h6 class="mb-2">
                                                            <i class="ri-list-check text-danger"></i>
                                                            {{ $sub['sub_indikator_code'] }} - {{ $sub['sub_indikator_name'] }}
                                                        </h6>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <span class="badge bg-secondary">{{ $sub['total_ptk'] }} PTK dengan Gap</span>
                                                            <span class="badge bg-info">{{ $sub['total_levels'] }} Level Bermasalah</span>
                                                            <span class="badge-target">Target: Level {{ $sub['target_level'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tampilkan tabel per level -->
                                                @for ($level = 1; $level <= 5; $level++)
                                                    @php
                                                        // Cari data PTK untuk level ini
                                                        $levelData = null;
                                                        foreach ($sub['ptk_per_level'] as $item) {
                                                            if ($item['level'] == $level) {
                                                                $levelData = $item;
                                                                break;
                                                            }
                                                        }

                                                        // Tentukan jika level ini ada gap
                                                        $hasGap = $levelData && $level < $sub['target_level'];
                                                        $hasPtk = $levelData && !empty($levelData['ptk_list']);

                                                        // Ambil rekomendasi pelatihan jika ada gap
                                                        $rekomendasiPelatihan = [];
                                                        if ($hasGap && isset($levelData['rekomendasi_pelatihan'])) {
                                                            $rekomendasiPelatihan = $levelData['rekomendasi_pelatihan'];
                                                        } elseif ($hasGap && $level < $sub['target_level']) {
                                                            for ($targetLevel = $level + 1; $targetLevel <= $sub['target_level']; $targetLevel++) {
                                                                $rekomendasi = getRekomendasiPelatihan(
                                                                    $sub['sub_indikator_id'],
                                                                    'Tahap 1',
                                                                    'Guru',
                                                                    $sub['sub_indikator_code'],
                                                                    $level,
                                                                    $targetLevel,
                                                                );
                                                                $rekomendasiPelatihan[] = [
                                                                    'level_target' => $targetLevel,
                                                                    'rekomendasi' => $rekomendasi,
                                                                ];
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($hasPtk)
                                                        <div class="level-section">
                                                            <!-- Header Level -->
                                                            <div class="level-header-card">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <span class="badge-level"
                                                                            style="background-color: {{ $levelColors[$level] ?? '#17a2b8' }}; color: white;">
                                                                            Level {{ $level }} ({{ $levelNames[$level] ?? 'Penerapan' }})
                                                                        </span>
                                                                        <span class="ms-3 fw-bold">
                                                                            <i class="ri-user-line"></i> {{ $levelData['ptk_count'] }} PTK
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        @if ($hasGap)
                                                                            <span class="badge-gap me-2">
                                                                                Gap: +{{ $sub['target_level'] - $level }} level
                                                                            </span>
                                                                        @endif
                                                                        <span class="badge bg-warning">{{ $levelData['status'] }}</span>
                                                                    </div>
                                                                </div>

                                                                @if (!empty($rekomendasiPelatihan))
                                                                    <div class="rekomendasi-pelatihan mt-3">
                                                                        <h6 class="mb-2">
                                                                            <i class="ri-lightbulb-line text-warning"></i>
                                                                            Rekomendasi Kebutuhan Belajar:
                                                                        </h6>
                                                                        <div class="rekomendasi-container">
                                                                            <ul>
                                                                                @foreach ($rekomendasiPelatihan as $rek)
                                                                                    <li>
                                                                                        <strong>Level {{ $rek['level_target'] }}:</strong>
                                                                                        <span style="white-space: normal !important;">
                                                                                            {{ $rek['rekomendasi'] }}
                                                                                        </span>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Tabel PTK per Level -->
                                                            <div class="level-table-card">
                                                                <div class="ptk-table-container">
                                                                    <table class="ptk-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="3%">No</th>
                                                                                <th width="12%">NIP</th>
                                                                                <th width="18%">Nama</th>
                                                                                <th width="12%">No. HP</th>
                                                                                <th width="20%">Sekolah/Instansi</th>
                                                                                <th width="10%">Kota</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $startNumber =
                                                                                    ($levelData['ptk_list']->currentPage() - 1) *
                                                                                        $levelData['ptk_list']->perPage() +
                                                                                    1;
                                                                            @endphp

                                                                            @foreach ($levelData['ptk_list'] as $ptkIndex => $ptk)
                                                                                <tr>
                                                                                    <td class="text-center">{{ $startNumber + $ptkIndex }}</td>
                                                                                    <td>{{ $ptk['nip'] ?? '-' }}</td>
                                                                                    <td><strong>{{ $ptk['nama'] ?? '-' }}</strong></td>
                                                                                    <td>
                                                                                        @if (!empty($ptk['no_hp']))
                                                                                            <a href="tel:{{ $ptk['no_hp'] }}">{{ $ptk['no_hp'] }}</a>
                                                                                        @else
                                                                                            -
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($ptk['sekolah'])
                                                                                            {{ $ptk['sekolah'] }}
                                                                                        @elseif($ptk['instansi'])
                                                                                            {{ $ptk['instansi'] }}
                                                                                        @else
                                                                                            -
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ $ptk['kota'] ?? '-' }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                @if ($levelData['ptk_list']->hasPages())
                                                                    <div class="mt-3">
                                                                        <nav aria-label="Pagination">
                                                                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                                                                {{-- Previous --}}
                                                                                @if ($levelData['ptk_list']->onFirstPage())
                                                                                    <li class="page-item disabled">
                                                                                        <span class="page-link">&laquo;</span>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="page-item">
                                                                                        <a class="page-link" href="{{ $levelData['ptk_list']->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                                                    </li>
                                                                                @endif

                                                                                @php
                                                                                    $currentPage = $levelData['ptk_list']->currentPage();
                                                                                    $lastPage = $levelData['ptk_list']->lastPage();
                                                                                    $start = max(1, $currentPage - 2);
                                                                                    $end = min($lastPage, $currentPage + 2);
                                                                                @endphp

                                                                                @for ($i = $start; $i <= $end; $i++)
                                                                                    @if ($i == $currentPage)
                                                                                        <li class="page-item active">
                                                                                            <span class="page-link">{{ $i }}</span>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item">
                                                                                            <a class="page-link" href="{{ $levelData['ptk_list']->url($i) }}">{{ $i }}</a>
                                                                                        </li>
                                                                                    @endif
                                                                                @endfor

                                                                                {{-- Next --}}
                                                                                @if ($levelData['ptk_list']->hasMorePages())
                                                                                    <li class="page-item">
                                                                                        <a class="page-link" href="{{ $levelData['ptk_list']->nextPageUrl() }}" rel="next">&raquo;</a>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="page-item disabled">
                                                                                        <span class="page-link">&raquo;</span>
                                                                                    </li>
                                                                                @endif
                                                                            </ul>

                                                                            <div class="text-center">
                                                                                <small class="text-muted">
                                                                                    Menampilkan {{ $levelData['ptk_list']->firstItem() ?? 0 }} -
                                                                                    {{ $levelData['ptk_list']->lastItem() ?? 0 }}
                                                                                    dari {{ $levelData['ptk_list']->total() }} PTK
                                                                                </small>
                                                                            </div>
                                                                        </nav>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif($level < $sub['target_level'])
                                                        <!-- Level tanpa PTK tapi ada gap -->
                                                        <div class="level-section">
                                                            <div class="level-header-card">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <span class="badge-level"
                                                                            style="background-color: {{ $levelColors[$level] ?? '#17a2b8' }}; color: white;">
                                                                            Level {{ $level }} ({{ $levelNames[$level] ?? 'Penerapan' }})
                                                                        </span>
                                                                        <span class="ms-3">
                                                                            <span class="badge-gap">
                                                                                Gap: +{{ $sub['target_level'] - $level }} level
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="badge bg-secondary">TIDAK ADA PTK</span>
                                                                    </div>
                                                                </div>

                                                                @php
                                                                    $rekomendasiPelatihan = [];
                                                                    for ($targetLevel = $level + 1; $targetLevel <= $sub['target_level']; $targetLevel++) {
                                                                        $rekomendasi = getRekomendasiPelatihan(
                                                                            $sub['sub_indikator_id'],
                                                                            'Tahap 1',
                                                                            'Guru',
                                                                            $sub['sub_indikator_code'],
                                                                            $level,
                                                                            $targetLevel,
                                                                        );
                                                                        $rekomendasiPelatihan[] = [
                                                                            'level_target' => $targetLevel,
                                                                            'rekomendasi' => $rekomendasi,
                                                                        ];
                                                                    }
                                                                @endphp

                                                                @if (!empty($rekomendasiPelatihan))
                                                                    <div class="rekomendasi-pelatihan mt-3">
                                                                        <h6 class="mb-2">
                                                                            <i class="ri-lightbulb-line text-warning"></i>
                                                                            Rekomendasi Kebutuhan Belajar:
                                                                        </h6>
                                                                        <div class="rekomendasi-container">
                                                                            <ul>
                                                                                @foreach ($rekomendasiPelatihan as $rek)
                                                                                    <li>
                                                                                        <strong>Level {{ $rek['level_target'] }}:</strong>
                                                                                        <span style="white-space: normal !important;">
                                                                                            {{ $rek['rekomendasi'] }}
                                                                                        </span>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="empty-level">
                                                                <i class="ri-user-unfollow-line"></i>
                                                                <p class="mt-2 mb-0">Tidak ada PTK di level ini</p>
                                                                <small class="text-muted">
                                                                    Level {{ $level }} membutuhkan peningkatan ke level {{ $sub['target_level'] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endfor
                                            </div>
                                        @endif
                                    @endforeach

                                </div>{{-- /jenjang-white-card --}}
                            </div>
                        @endif
                    @endforeach

                    <!-- Ringkasan Semua PTK -->
                    <div class="sub-indikator-card mt-4">
                        <div class="sub-indikator-header">
                            <h5 class="mb-0">
                                <i class="ri-group-line"></i> Ringkasan Semua PTK dengan Kebutuhan Belajar
                            </h5>
                            <div>
                                <span class="badge bg-primary">{{ $analisisData['total_unique_ptk'] }} PTK</span>
                                <span class="badge bg-info ms-2">20 data per halaman</span>
                            </div>
                        </div>

                        <div class="ptk-table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="3%">No</th>
                                        <th width="12%">NIP</th>
                                        <th width="18%">Nama</th>
                                        <th width="12%">No. HP</th>
                                        <th width="15%">Jenjang</th>
                                        <th width="15%">Sekolah/Instansi</th>
                                        <th width="10%">Kota</th>
                                        <th width="10%">Jumlah Gap</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($analisisData['all_ptks_paginated'] as $ptk)
                                        <tr>
                                            <td class="text-center">{{ $ptk['no'] }}</td>
                                            <td>{{ $ptk['nip'] ?? '-' }}</td>
                                            <td><strong>{{ $ptk['nama'] }}</strong></td>
                                            <td>
                                                @if ($ptk['no_hp'] != '-')
                                                    <a href="tel:{{ $ptk['no_hp'] }}">{{ $ptk['no_hp'] }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $ptk['jenjang'] }}</td>
                                            <td>{{ $ptk['sekolah'] }}</td>
                                            <td>{{ $ptk['kota'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $ptk['gap_count'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($analisisData['all_ptks_paginated']->hasPages())
                            <div class="mt-3">
                                <nav aria-label="Pagination Ringkasan">
                                    <ul class="pagination pagination-sm justify-content-center mb-0">
                                        @if ($analisisData['all_ptks_paginated']->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">&laquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $analisisData['all_ptks_paginated']->previousPageUrl() }}" rel="prev">&laquo;</a>
                                            </li>
                                        @endif

                                        @php
                                            $currentPage = $analisisData['all_ptks_paginated']->currentPage();
                                            $lastPage = $analisisData['all_ptks_paginated']->lastPage();
                                            $start = max(1, $currentPage - 2);
                                            $end = min($lastPage, $currentPage + 2);
                                        @endphp

                                        @for ($i = $start; $i <= $end; $i++)
                                            @if ($i == $currentPage)
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $analisisData['all_ptks_paginated']->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        @if ($analisisData['all_ptks_paginated']->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $analisisData['all_ptks_paginated']->nextPageUrl() }}" rel="next">&raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">&raquo;</span>
                                            </li>
                                        @endif
                                    </ul>

                                    <div class="text-center">
                                        <small class="text-muted">
                                            Menampilkan {{ $analisisData['all_ptks_paginated']->firstItem() ?? 0 }} -
                                            {{ $analisisData['all_ptks_paginated']->lastItem() ?? 0 }} dari
                                            {{ $analisisData['all_ptks_paginated']->total() }} PTK
                                        </small>
                                    </div>
                                </nav>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Tidak ada gap -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="ri-checkbox-circle-line" style="font-size: 5rem; color: #28a745;"></i>
                        </div>
                        <h4 class="mb-3" style="color: #28a745;">Tidak Ada Kebutuhan Belajar</h4>
                        <p class="text-muted mb-4">
                            Semua PTK sudah mencapai target level berdasarkan filter yang diterapkan
                        </p>
                        <a href="{{ route('analisis.index') }}" class="btn btn-primary">
                            <i class="ri-arrow-left-line"></i> Kembali ke Analisis
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('sipproja-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight level dengan gap besar
            document.querySelectorAll('.badge-gap').forEach(badge => {
                const gapText = badge.textContent;
                const gapNumber = parseInt(gapText.match(/\d+/)[0]);

                if (gapNumber >= 3) {
                    badge.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
                    badge.style.boxShadow = '0 2px 4px rgba(220, 53, 69, 0.3)';
                } else if (gapNumber == 2) {
                    badge.style.background = 'linear-gradient(135deg, #fd7e14, #e8590c)';
                    badge.style.boxShadow = '0 2px 4px rgba(253, 126, 20, 0.3)';
                } else {
                    badge.style.background = 'linear-gradient(135deg, #ffc107, #e0a800)';
                    badge.style.boxShadow = '0 2px 4px rgba(255, 193, 7, 0.3)';
                }
            });

            // Add hover effect to table rows
            document.querySelectorAll('.ptk-table tbody tr').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f0f8ff';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
@endsection