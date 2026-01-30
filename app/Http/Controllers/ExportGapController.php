<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ExportGapController extends Controller
{
    public function exportRekomendasiGap(Request $request)
    {
        try {
            // Set memory limit untuk handle data besar
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            // Ambil data rekomendasi gap per jenjang jabatan dengan detail PTK
            $rekomendasiGapData = $this->getRekomendasiGapWithDetail($request);

            if (empty($rekomendasiGapData)) {
                return redirect()->back()->with('error', 'Tidak ada data rekomendasi gap untuk diexport.');
            }

            // Buat spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);

            // ======================
            // SHEET 1: SUMMARY GAP PER JENJANG
            // ======================
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('SUMMARY GAP');

            // Set page setup
            $sheet->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4)
                ->setFitToWidth(1)
                ->setFitToHeight(0);

            $currentRow = 1;

            // JUDUL UTAMA
            $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'LAPORAN ANALISIS GAP KOMPETENSI PTK PER JENJANG JABATAN');
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'Detail Gap Per Sub Indikator dan Level');
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['size' => 12, 'color' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow++;
            $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'Dicetak: ' . now()->format('d F Y H:i:s'));
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['color' => ['rgb' => '666666'], 'italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $currentRow += 2;

            // ======================
            // FILTER INFO
            // ======================
            $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'FILTER YANG DIGUNAKAN');
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $currentRow++;

            // Ambil nama filter
            $kegiatanName = '';
            if ($request->filled('kegiatan_id')) {
                $kegiatan = DB::table('kegiatan')->where('kegiatan_id', $request->kegiatan_id)->first();
                $kegiatanName = $kegiatan->kegiatan_name ?? '';
            }

            $jenjangName = '';
            if ($request->filled('pangkat_jabatan_id')) {
                $jenjang = DB::table('pangkat_jabatan')->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)->first();
                $jenjangName = $jenjang->jenjang_jabatan ?? '';
            }

            $jenisPtkName = '';
            if ($request->filled('jenis_ptk_id')) {
                $jenisPtk = DB::table('jenis_ptk')->where('jenis_ptk_id', $request->jenis_ptk_id)->first();
                $jenisPtkName = $jenisPtk->jenis_ptk ?? '';
            }

            $kotaName = '';
            if ($request->filled('kota_id')) {
                $kota = DB::table('kota')->where('kota_id', $request->kota_id)->first();
                $kotaName = $kota->nama_kota ?? '';
            }

            // Tampilkan filter
            $filters = [
                ['Kegiatan:', $kegiatanName ?: 'Semua Kegiatan'],
                ['Jenjang Jabatan:', $jenjangName ?: 'Semua Jenjang'],
                ['Jenis PTK:', $jenisPtkName ?: 'Semua Jenis'],
                ['Kota:', $kotaName ?: 'Semua Kota'],
                ['Jenis Kelamin:', $request->jenis_kelamin ? ($request->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : 'Semua'],
                ['Tanggal Export:', now()->format('d-m-Y H:i:s')]
            ];

            foreach ($filters as $index => $filter) {
                $sheet->setCellValue("A{$currentRow}", $filter[0]);
                $sheet->setCellValue("B{$currentRow}", $filter[1]);
                $sheet->mergeCells("B{$currentRow}:K{$currentRow}");

                $bgColor = $index % 2 == 0 ? 'F8FAFC' : 'F1F5F9';
                $sheet->getStyle("A{$currentRow}:K{$currentRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                ]);
                $sheet->getStyle("A{$currentRow}")->applyFromArray(['font' => ['bold' => true]]);

                $currentRow++;
            }

            $currentRow += 2;

            // ======================
            // SUMMARY STATISTIK
            // ======================
            $totalJenjang = count($rekomendasiGapData);
            $totalSubIndikator = 0;
            $totalPTK = 0;
            $totalGap = 0;

            foreach ($rekomendasiGapData as $jenjang) {
                $totalPTK += $jenjang['total_ptk'] ?? 0;
                foreach ($jenjang['sub_indikator_gap'] as $sub) {
                    $totalSubIndikator++;
                    $totalGap += count($sub['detail_gap'] ?? []);
                }
            }

            $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'SUMMARY ANALISIS GAP');
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $currentRow++;

            $stats = [
                ['Total Jenjang Jabatan', $totalJenjang, '1a5bb8'],
                ['Total PTK yang Dianalisis', $totalPTK, '28a745'],
                ['Total Sub Indikator dengan Gap', $totalSubIndikator, 'dc3545'],
                ['Total Kebutuhan Belajar', $totalGap, 'ffc107']
            ];

            $col = 0;
            foreach ($stats as $stat) {
                $startCol = chr(65 + ($col * 3));
                $endCol = chr(65 + ($col * 3) + 2);

                $sheet->mergeCells("{$startCol}{$currentRow}:{$endCol}{$currentRow}");
                $sheet->setCellValue("{$startCol}{$currentRow}", $stat[0]);
                $sheet->getStyle("{$startCol}{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $stat[2]]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow++;
                $sheet->mergeCells("{$startCol}{$currentRow}:{$endCol}{$currentRow}");
                $sheet->setCellValue("{$startCol}{$currentRow}", $stat[1]);
                $sheet->getStyle("{$startCol}{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $col++;
            }

            $currentRow += 2;

            // ======================
            // DATA DETAIL PER JENJANG
            // ======================
            foreach ($rekomendasiGapData as $jenjangIndex => $jenjang) {
                $targetLevel = $jenjang['target_level'];

                // HEADER JENJANG
                $sheet->mergeCells("A{$currentRow}:K{$currentRow}");
                $sheet->setCellValue("A{$currentRow}", 'JENJANG: ' . strtoupper($jenjang['jenjang_jabatan']) . ' - Target Level ' . $targetLevel);
                $sheet->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $currentRow++;

                // INFO JENJANG
                $infoData = [
                    ['Total PTK pada jenjang ini:', $jenjang['total_ptk'] . ' PTK'],
                    ['PTK dengan kebutuhan belajar:', $jenjang['ptk_dengan_gap'] . ' PTK (' . ($jenjang['total_ptk'] > 0 ? round(($jenjang['ptk_dengan_gap'] / $jenjang['total_ptk']) * 100, 1) : 0) . '%)'],
                    ['Total sub indikator dengan kebutuhan:', count($jenjang['sub_indikator_gap']) . ' sub indikator'],
                    ['Total kebutuhan belajar ditemukan:', $jenjang['total_gap_jumlah'] . ' kebutuhan']
                ];

                foreach ($infoData as $info) {
                    $sheet->setCellValue("A{$currentRow}", $info[0]);
                    $sheet->setCellValue("B{$currentRow}", $info[1]);
                    $sheet->mergeCells("B{$currentRow}:K{$currentRow}");

                    $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                    $sheet->getStyle("A{$currentRow}:K{$currentRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                    ]);
                    $sheet->getStyle("A{$currentRow}")->applyFromArray(['font' => ['bold' => true]]);

                    $currentRow++;
                }

                $currentRow++;

                // HEADER TABEL DETAIL GAP
                $headers = [
                    'NO',
                    'KODE SUB INDIKATOR',
                    'NAMA SUB INDIKATOR',
                    'LEVEL DICAPAI',
                    'JUMLAH PTK (LEVEL INI)',
                    'TARGET LEVEL',
                    'GAP LEVEL',
                    'REKOMENDASI BELAJAR',
                    'JUMLAH PTK (BUTUH)',
                    '% DARI TOTAL PTK',
                    'PRIORITAS'
                ];

                foreach ($headers as $col => $header) {
                    $columnLetter = chr(65 + $col);
                    $sheet->setCellValue($columnLetter . $currentRow, $header);
                    $sheet->getStyle($columnLetter . $currentRow)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                }

                $currentRow++;

                // DATA DETAIL GAP
                $no = 1;
                $jenjangTotalPtk = $jenjang['total_ptk'];

                foreach ($jenjang['sub_indikator_gap'] as $subIndikator) {
                    foreach ($subIndikator['detail_gap'] as $gap) {
                        // Hitung persentase
                        $percentage = $jenjangTotalPtk > 0
                            ? round(($gap['jumlah_ptk_gap'] / $jenjangTotalPtk) * 100, 1)
                            : 0;

                        // Tentukan prioritas
                        if ($percentage >= 30) {
                            $prioritas = 'TINGGI';
                            $prioritasColor = 'FF0000';
                        } elseif ($percentage >= 15) {
                            $prioritas = 'SEDANG';
                            $prioritasColor = 'FFA500';
                        } else {
                            $prioritas = 'RENDAH';
                            $prioritasColor = '008000';
                        }

                        $sheet->setCellValue("A{$currentRow}", $no);
                        $sheet->setCellValue("B{$currentRow}", $subIndikator['sub_indikator_code']);
                        $sheet->setCellValue("C{$currentRow}", $subIndikator['sub_indikator_name']);
                        $sheet->setCellValue("D{$currentRow}", 'Level ' . $gap['level_dicapai']);
                        $sheet->setCellValue("E{$currentRow}", $gap['jumlah_ptk_level']);
                        $sheet->setCellValue("F{$currentRow}", 'Level ' . $gap['target_level']);
                        $sheet->setCellValue("G{$currentRow}", '+' . ($gap['target_level'] - $gap['level_dicapai']));
                        $sheet->setCellValue("H{$currentRow}", $gap['rekomendasi']);
                        $sheet->setCellValue("I{$currentRow}", $gap['jumlah_ptk_gap']);
                        $sheet->setCellValue("J{$currentRow}", $percentage . '%');
                        $sheet->setCellValue("K{$currentRow}", $prioritas);

                        // STYLING
                        $bgColor = $currentRow % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                        $sheet->getStyle("A{$currentRow}:K{$currentRow}")->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                        ]);

                        // Warna untuk gap level
                        $gapLevel = $gap['target_level'] - $gap['level_dicapai'];
                        if ($gapLevel >= 3) {
                            $sheet->getStyle("G{$currentRow}")->applyFromArray([
                                'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']]
                            ]);
                        } elseif ($gapLevel >= 2) {
                            $sheet->getStyle("G{$currentRow}")->applyFromArray([
                                'font' => ['bold' => true, 'color' => ['rgb' => 'FFA500']]
                            ]);
                        }

                        // Warna untuk prioritas
                        $sheet->getStyle("K{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => $prioritasColor]]
                        ]);

                        // Alignment
                        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("K{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $currentRow++;
                        $no++;
                    }
                }

                $currentRow += 2;
            }

            // ======================
            // SHEET 2: DAFTAR PTK DENGAN GAP DETAIL
            // ======================
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('DETAIL PTK GAP');
            $sheet2->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PageSetup::PAPERSIZE_A4);

            $row2 = 1;

            // JUDUL SHEET 2
            $sheet2->mergeCells("A{$row2}:L{$row2}");
            $sheet2->setCellValue("A{$row2}", 'DETAIL PTK DENGAN KEBUTUHAN BELAJAR');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a5bb8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2++;
            $sheet2->mergeCells("A{$row2}:L{$row2}");
            $sheet2->setCellValue("A{$row2}", 'Per Jenjang Jabatan dan Detail Sub Indikator');
            $sheet2->getStyle("A{$row2}")->applyFromArray([
                'font' => ['size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row2 += 2;

            // Loop untuk setiap PTK dengan gap
            foreach ($rekomendasiGapData as $jenjang) {
                $targetLevel = $jenjang['target_level'];

                $sheet2->mergeCells("A{$row2}:L{$row2}");
                $sheet2->setCellValue("A{$row2}", 'JENJANG: ' . strtoupper($jenjang['jenjang_jabatan']) . ' (Target Level: ' . $targetLevel . ')');
                $sheet2->getStyle("A{$row2}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $row2++;

                foreach ($jenjang['ptk_detail'] as $ptkIndex => $ptk) {
                    $startRow = $row2;

                    // INFO PTK
                    $sheet2->setCellValue("A{$row2}", 'NIP:');
                    $sheet2->setCellValue("B{$row2}", $ptk['nip'] ?? '-');
                    $sheet2->mergeCells("B{$row2}:D{$row2}");

                    $sheet2->setCellValue("E{$row2}", 'Nama:');
                    $sheet2->setCellValue("F{$row2}", $ptk['nama'] ?? '-');
                    $sheet2->mergeCells("F{$row2}:H{$row2}");

                    $sheet2->setCellValue("I{$row2}", 'Sekolah:');
                    $sheet2->setCellValue("J{$row2}", $ptk['nama_sekolah'] ?? '-');
                    $sheet2->mergeCells("J{$row2}:L{$row2}");

                    $row2++;

                    $sheet2->setCellValue("A{$row2}", 'Jenjang:');
                    $sheet2->setCellValue("B{$row2}", $ptk['jenjang_jabatan'] ?? '-');
                    $sheet2->mergeCells("B{$row2}:D{$row2}");

                    $sheet2->setCellValue("E{$row2}", 'Kota:');
                    $sheet2->setCellValue("F{$row2}", $ptk['nama_kota'] ?? '-');
                    $sheet2->mergeCells("F{$row2}:H{$row2}");

                    $sheet2->setCellValue("I{$row2}", 'Total Kebutuhan:');
                    $sheet2->setCellValue("J{$row2}", $ptk['total_gap'] ?? 0);
                    $sheet2->mergeCells("J{$row2}:L{$row2}");

                    // Styling info PTK
                    $sheet2->getStyle("A{$startRow}:L{$row2}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']]
                    ]);

                    $row2++;

                    // HEADER DETAIL GAP
                    $headersPtk = ['NO', 'SUB INDIKATOR', 'LEVEL DICAPAI', 'TARGET LEVEL', 'GAP', 'REKOMENDASI'];
                    foreach ($headersPtk as $col => $header) {
                        $cell = chr(65 + ($col * 2)) . $row2;
                        $sheet2->setCellValue($cell, $header);
                        $sheet2->mergeCells($cell . ':' . chr(65 + ($col * 2) + 1) . $row2);
                        $sheet2->getStyle($cell)->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    }

                    $row2++;

                    // DATA DETAIL GAP PER PTK
                    $noGap = 1;
                    foreach ($ptk['detail_gap'] as $gap) {
                        $sheet2->setCellValue("A{$row2}", $noGap);
                        $sheet2->mergeCells("A{$row2}:B{$row2}");

                        $sheet2->setCellValue("C{$row2}", $gap['sub_indikator_code'] . ' - ' . $gap['sub_indikator_name']);
                        $sheet2->mergeCells("C{$row2}:D{$row2}");

                        $sheet2->setCellValue("E{$row2}", 'Level ' . $gap['level_dicapai']);
                        $sheet2->mergeCells("E{$row2}:F{$row2}");

                        $sheet2->setCellValue("G{$row2}", 'Level ' . $gap['target_level']);
                        $sheet2->mergeCells("G{$row2}:H{$row2}");

                        $sheet2->setCellValue("I{$row2}", '+' . ($gap['target_level'] - $gap['level_dicapai']));
                        $sheet2->mergeCells("I{$row2}:J{$row2}");

                        $sheet2->setCellValue("K{$row2}", $gap['rekomendasi']);
                        $sheet2->mergeCells("K{$row2}:L{$row2}");

                        // Styling
                        $bgColor = $row2 % 2 == 0 ? 'FFFFFF' : 'F9FAFB';
                        $sheet2->getStyle("A{$row2}:L{$row2}")->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                        ]);

                        // Alignment
                        $sheet2->getStyle("A{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("E{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("G{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet2->getStyle("I{$row2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $row2++;
                        $noGap++;
                    }

                    $row2 += 2;
                }
            }

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(12);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(10);
            $sheet->getColumnDimension('H')->setWidth(50);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);

            $sheet2->getColumnDimension('A')->setWidth(4);
            $sheet2->getColumnDimension('B')->setWidth(4);
            $sheet2->getColumnDimension('C')->setWidth(20);
            $sheet2->getColumnDimension('D')->setWidth(20);
            $sheet2->getColumnDimension('E')->setWidth(10);
            $sheet2->getColumnDimension('F')->setWidth(10);
            $sheet2->getColumnDimension('G')->setWidth(10);
            $sheet2->getColumnDimension('H')->setWidth(10);
            $sheet2->getColumnDimension('I')->setWidth(8);
            $sheet2->getColumnDimension('J')->setWidth(8);
            $sheet2->getColumnDimension('K')->setWidth(30);
            $sheet2->getColumnDimension('L')->setWidth(30);

            // Set active sheet kembali ke sheet 1
            $spreadsheet->setActiveSheetIndex(0);

            // Output file menggunakan Laravel Response
            $filename = 'analisis-gap-ptk-' . date('Ymd-His') . '.xlsx';

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                },
                $filename,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Export Rekomendasi Gap Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Method baru untuk mengambil data gap dengan detail PTK
     */
    private function getRekomendasiGapWithDetail(Request $request)
    {
        $targetLevels = [
            'Pertama' => 2,  // Target level 2
            'Muda'    => 3,  // Target level 3
            'Madya'   => 4,  // Target level 4
            'Utama'   => 5   // Target level 5
        ];

        // Ambil semua jenjang yang ada
        $jenjangQuery = DB::table('pangkat_jabatan')
            ->select('jenjang_jabatan')
            ->whereNotNull('jenjang_jabatan')
            ->whereIn('jenjang_jabatan', ['Pertama', 'Muda', 'Madya', 'Utama'])
            ->distinct();

        if ($request->filled('pangkat_jabatan_id')) {
            $pangkat = DB::table('pangkat_jabatan')
                ->where('pangkat_jabatan_id', $request->pangkat_jabatan_id)
                ->first();
            if ($pangkat && $pangkat->jenjang_jabatan) {
                $jenjangQuery->where('jenjang_jabatan', $pangkat->jenjang_jabatan);
            }
        }

        $jenjangList = $jenjangQuery->pluck('jenjang_jabatan')->toArray();

        $result = [];

        foreach ($jenjangList as $jenjang) {
            if (!isset($targetLevels[$jenjang])) continue;

            $targetLevel = $targetLevels[$jenjang];

            // 1. Hitung total PTK per jenjang (HANYA PTK yang memiliki jawaban di kegiatan yang dipilih)
            $totalPtkQuery = DB::table('ptk')
                ->select(DB::raw('COUNT(DISTINCT ptk.ptk_id) as total_ptk'))
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->join('ptk_jawaban', 'ptk.ptk_id', '=', 'ptk_jawaban.ptk_id') // JOIN dengan ptk_jawaban
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->where('ptk_jawaban.level', '>=', 1);

            // Terapkan filter untuk totalPtkQuery
            if ($request->filled('kegiatan_id')) {
                $totalPtkQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }
            if ($request->filled('kota_id')) {
                $totalPtkQuery->where('ptk.kota_id', $request->kota_id);
            }
            if ($request->filled('jenis_ptk_id')) {
                $totalPtkQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            }
            if ($request->filled('jenjang_pendidikan_id')) {
                $totalPtkQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            }
            if ($request->filled('bentuk_pendidikan')) {
                $totalPtkQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            }
            if ($request->filled('jenis_kelamin')) {
                $totalPtkQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            }

            $totalPtkResult = $totalPtkQuery->first();
            $totalPtk = $totalPtkResult->total_ptk ?? 0;

            if ($totalPtk == 0) continue;

            // 2. Ambil data jawaban PTK untuk analisis gap
            $jawabanQuery = DB::table('ptk_jawaban')
                ->select(
                    'ptk.ptk_id',
                    'ptk.nip',
                    'ptk.nama',
                    'pangkat_jabatan.jenjang_jabatan',
                    'sekolah.nama_sekolah',
                    'kota.nama_kota',
                    'ptk_jawaban.sub_indikator_id',
                    'ptk_jawaban.sub_indikator_code',
                    'sub_indikator.sub_indikator_name',
                    'ptk_jawaban.level as level_dicapai',
                    'ptk_jawaban.tahap',
                    'kegiatan.entity'
                )
                ->join('ptk', 'ptk_jawaban.ptk_id', '=', 'ptk.ptk_id')
                ->join('kegiatan', 'ptk_jawaban.kegiatan_id', '=', 'kegiatan.kegiatan_id')
                ->join('pangkat_jabatan', 'ptk.pangkat_jabatan_id', '=', 'pangkat_jabatan.pangkat_jabatan_id')
                ->leftJoin('sub_indikator', 'ptk_jawaban.sub_indikator_id', '=', 'sub_indikator.sub_indikator_id')
                ->leftJoin('sekolah', 'ptk.sekolah_id', '=', 'sekolah.sekolah_id')
                ->leftJoin('kota', 'ptk.kota_id', '=', 'kota.kota_id')
                ->leftJoin('jenjang_pendidikan', 'ptk.jenjang_pendidikan_id', '=', 'jenjang_pendidikan.jenjang_pendidikan_id')
                ->where('pangkat_jabatan.jenjang_jabatan', $jenjang)
                ->where('ptk_jawaban.level', '>=', 1);

            // Terapkan filter ke jawabanQuery
            if ($request->filled('kegiatan_id')) {
                $jawabanQuery->where('ptk_jawaban.kegiatan_id', $request->kegiatan_id);
            }
            if ($request->filled('kota_id')) {
                $jawabanQuery->where('ptk.kota_id', $request->kota_id);
            }
            if ($request->filled('jenis_ptk_id')) {
                $jawabanQuery->where('ptk.jenis_ptk_id', $request->jenis_ptk_id);
            }
            if ($request->filled('jenjang_pendidikan_id')) {
                $jawabanQuery->where('ptk.jenjang_pendidikan_id', $request->jenjang_pendidikan_id);
            }
            if ($request->filled('bentuk_pendidikan')) {
                $jawabanQuery->where('sekolah.bentuk_pendidikan', $request->bentuk_pendidikan);
            }
            if ($request->filled('jenis_kelamin')) {
                $jawabanQuery->where('ptk.jenis_kelamin', $request->jenis_kelamin);
            }

            $jawabanData = $jawabanQuery->get();

            if ($jawabanData->isEmpty()) continue;

            // 3. Kelompokkan data
            $groupedBySubIndikator = $jawabanData->groupBy('sub_indikator_id');
            $groupedByPtk = $jawabanData->groupBy('ptk_id');

            $subIndikatorGap = [];
            $ptkDetail = [];
            $totalGapJumlah = 0;
            $ptkDenganGap = 0;

            // 4. Analisis per sub indikator
            foreach ($groupedBySubIndikator as $subIndikatorId => $subData) {
                $firstData = $subData->first();
                $ptkPerLevel = $subData->groupBy('level_dicapai');

                $detailGap = [];

                foreach ($ptkPerLevel as $levelDicapai => $ptkLevelData) {
                    $jumlahPtkLevel = $ptkLevelData->count();

                    // Jika level dicapai TIDAK SAMA dengan target level
                    if ($levelDicapai < $targetLevel) {
                        // Buat gap untuk SETIAP level dari level dicapai + 1 sampai target level
                        for ($target = $levelDicapai + 1; $target <= $targetLevel; $target++) {
                            $gapLevel = $target - $levelDicapai;

                            $rekomendasi = $this->getRekomendasiText(
                                $subIndikatorId,
                                $firstData->sub_indikator_code,
                                $firstData->tahap ?? '',
                                $firstData->entity ?? '',
                                $levelDicapai,
                                $target
                            );

                            $detailGap[] = [
                                'level_dicapai' => $levelDicapai,
                                'target_level' => $target,
                                'gap_level' => $gapLevel,
                                'rekomendasi' => $rekomendasi,
                                'jumlah_ptk_level' => $jumlahPtkLevel,
                                'jumlah_ptk_gap' => $jumlahPtkLevel
                            ];

                            $totalGapJumlah++;
                        }
                    }
                }

                if (!empty($detailGap)) {
                    $subIndikatorGap[] = [
                        'sub_indikator_id' => $subIndikatorId,
                        'sub_indikator_code' => $firstData->sub_indikator_code,
                        'sub_indikator_name' => $firstData->sub_indikator_name,
                        'detail_gap' => $detailGap,
                        'total_gap' => count($detailGap)
                    ];
                }
            }

            // 5. Analisis per PTK
            foreach ($groupedByPtk as $ptkId => $ptkData) {
                $firstPtkData = $ptkData->first();
                $ptkGap = [];

                foreach ($ptkData as $jawaban) {
                    $levelDicapai = $jawaban->level_dicapai;

                    // Jika level dicapai TIDAK SAMA dengan target level
                    if ($levelDicapai < $targetLevel) {
                        // Buat gap untuk SETIAP level dari level dicapai + 1 sampai target level
                        for ($target = $levelDicapai + 1; $target <= $targetLevel; $target++) {
                            $rekomendasi = $this->getRekomendasiText(
                                $jawaban->sub_indikator_id,
                                $jawaban->sub_indikator_code,
                                $jawaban->tahap ?? '',
                                $jawaban->entity ?? '',
                                $levelDicapai,
                                $target
                            );

                            $ptkGap[] = [
                                'sub_indikator_id' => $jawaban->sub_indikator_id,
                                'sub_indikator_code' => $jawaban->sub_indikator_code,
                                'sub_indikator_name' => $jawaban->sub_indikator_name,
                                'level_dicapai' => $levelDicapai,
                                'target_level' => $target,
                                'rekomendasi' => $rekomendasi
                            ];
                        }
                    }
                }

                if (!empty($ptkGap)) {
                    $ptkDenganGap++;
                    $ptkDetail[] = [
                        'ptk_id' => $ptkId,
                        'nip' => $firstPtkData->nip,
                        'nama' => $firstPtkData->nama,
                        'jenjang_jabatan' => $firstPtkData->jenjang_jabatan,
                        'nama_sekolah' => $firstPtkData->nama_sekolah,
                        'nama_kota' => $firstPtkData->nama_kota,
                        'detail_gap' => $ptkGap,
                        'total_gap' => count($ptkGap)
                    ];
                }
            }

            if (!empty($subIndikatorGap)) {
                $result[] = [
                    'jenjang_jabatan' => $jenjang,
                    'target_level' => $targetLevel,
                    'total_ptk' => $totalPtk,
                    'ptk_dengan_gap' => $ptkDenganGap,
                    'total_gap_jumlah' => $totalGapJumlah,
                    'sub_indikator_gap' => $subIndikatorGap,
                    'ptk_detail' => $ptkDetail
                ];
            }
        }

        return $result;
    }

    /**
     * Get rekomendasi text
     */
    private function getRekomendasiText($subIndikatorId, $subIndikatorCode, $tahap, $entity, $levelDicapai, $levelTarget)
    {
        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('tahap', $tahap)
            ->where('entity', $entity)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        $rekomendasi = DB::table('ptk_rekomendasi')
            ->where('sub_indikator_id', $subIndikatorId)
            ->where('sub_indikator_code', $subIndikatorCode)
            ->where('level', $levelTarget)
            ->first();

        if ($rekomendasi) {
            return $rekomendasi->rekomendasi;
        }

        $gap = $levelTarget - $levelDicapai;
        $levelNames = [
            1 => 'Gagal',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan'
        ];

        $levelDicapaiName = $levelNames[$levelDicapai] ?? "Level $levelDicapai";
        $levelTargetName = $levelNames[$levelTarget] ?? "Level $levelTarget";

        if ($gap == 1) {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik 1 level)";
        } else {
            return "Perlu meningkatkan dari $levelDicapaiName ke $levelTargetName (naik $gap level)";
        }
    }
}
