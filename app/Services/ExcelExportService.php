<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Illuminate\Support\Str;

class ExcelExportService
{
    /**
     * Sanitize cell string to prevent Formula Injection (CSV / Excel formula injection).
     * Strings beginning with =, +, -, @, \t, \r are prepended with a single quote.
     */
    public static function sanitizeCellValue($value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $trimmed = ltrim($value);
        if ($trimmed !== '' && in_array($trimmed[0], ['=', '+', '-', '@'], true)) {
            return "'" . $value;
        }

        return $value;
    }

    /**
     * Create and download a professionally styled Excel file.
     *
     * @param string $filename Base filename (without .xlsx extension)
     * @param string $title Report Title (e.g. "LAPORAN KEUANGAN & PEMBUKUAN")
     * @param string $subtitle Period or Subtitle (e.g. "Periode: 01 Okt 2026 s/d 31 Okt 2026")
     * @param array $headers List of column header names: ['No', 'Kode', 'Pelanggan', ...]
     * @param array $rows List of row arrays: [[1, 'SPA-01', 'John', 150000], ...]
     * @param array $columnTypes Type formatting for columns: [0 => 'int', 3 => 'currency', ...]
     * @param array $totalColumns Column indices that should have a SUM total row, e.g. [5, 6]
     * @param string $currencyCode Currency prefix/format, default 'AED'
     * @param array $extraMetadata Additional metadata pairs to display in top banner
     * @return StreamedResponse
     */
    public function download(
        string $filename,
        string $title,
        string $subtitle,
        array $headers,
        array $rows,
        array $columnTypes = [],
        array $totalColumns = [],
        string $currencyCode = 'AED',
        array $extraMetadata = []
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr(preg_replace('/[^A-Za-z0-9 _-]/', '', $title), 0, 31) ?: 'Data');

        // RTL Support for Arabic
        if (app()->getLocale() === 'ar') {
            $sheet->setRightToLeft(true);
        }

        // Page Setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $colCount = max(count($headers), 1);
        $lastColLetter = Coordinate::stringFromColumnIndex($colCount);

        // 1. Spa / Brand Header (Row 1)
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->setCellValue('A1', __('F55 MEN RELAXATION & MASSAGE CENTER (DUBAI)'));
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'D4AF37']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '141414'],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // 2. Report Title (Row 2)
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->setCellValue('A2', strtoupper(__($title)));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E1E1E'],
            ],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(26);

        // 3. Subtitle / Period (Row 3)
        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->setCellValue('A3', __($subtitle));
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => 'D1D5DB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E1E1E'],
            ],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // 4. Generation Metadata (Row 4)
        $generatedAt = __('Waktu Cetak:') . ' ' . now()->format('d/m/Y H:i:s');
        if (!empty($extraMetadata)) {
            $extraParts = [];
            foreach ($extraMetadata as $k => $v) {
                $extraParts[] = __($k) . ': ' . (is_string($v) ? __($v) : $v);
            }
            $generatedAt .= ' | ' . implode(' | ', $extraParts);
        }
        $sheet->mergeCells("A4:{$lastColLetter}4");
        $sheet->setCellValue('A4', $generatedAt);
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['size' => 9, 'color' => ['rgb' => '9CA3AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '141414'],
            ],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);

        // Row 5: Empty Spacer
        $sheet->getRowDimension(5)->setRowHeight(10);

        // Row 6: Table Headers
        $headerRow = 6;
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        foreach ($headers as $idx => $headerText) {
            $colLetter = Coordinate::stringFromColumnIndex($idx + 1);
            $sheet->setCellValue("{$colLetter}{$headerRow}", self::sanitizeCellValue(__($headerText)));
        }

        // Style Table Headers (Gold / Dark Charcoal Theme)
        $sheet->getStyle("A{$headerRow}:{$lastColLetter}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '111111']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D4AF37'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '997A15'],
                ],
            ],
        ]);

        // Freeze Panes below table header so it stays visible during scroll
        $dataStartRow = 7;
        $sheet->freezePane("A{$dataStartRow}");

        // AutoFilter on headers
        $sheet->setAutoFilter("A{$headerRow}:{$lastColLetter}{$headerRow}");

        // Currency Format Definition
        $currencyFormat = ($currencyCode === 'Rp' || $currencyCode === 'IDR')
            ? '"Rp" #,##0'
            : '"AED" #,##0.00';

        // 5. Populate Data Rows
        $currentRow = $dataStartRow;
        foreach ($rows as $row) {
            $sheet->getRowDimension($currentRow)->setRowHeight(22);
            $isEvenRow = ($currentRow % 2 === 0);
            $bgColor = $isEvenRow ? 'FBFBFB' : 'FFFFFF';

            foreach ($row as $colIdx => $cellValue) {
                $colLetter = Coordinate::stringFromColumnIndex($colIdx + 1);
                $cellCoordinate = "{$colLetter}{$currentRow}";
                $type = $columnTypes[$colIdx] ?? 'string';

                if ($type === 'currency' || $type === 'number' || $type === 'float') {
                    $numericVal = is_numeric($cellValue) ? (float) $cellValue : 0.0;
                    $sheet->setCellValueExplicit($cellCoordinate, $numericVal, DataType::TYPE_NUMERIC);
                    if ($type === 'currency') {
                        $sheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode($currencyFormat);
                    } else {
                        $sheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode('#,##0.00');
                    }
                    $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                } elseif ($type === 'int' || $type === 'integer') {
                    $numericVal = is_numeric($cellValue) ? (int) $cellValue : 0;
                    $sheet->setCellValueExplicit($cellCoordinate, $numericVal, DataType::TYPE_NUMERIC);
                    $sheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } elseif ($type === 'date') {
                    $sheet->setCellValueExplicit($cellCoordinate, (string) $cellValue, DataType::TYPE_STRING);
                    $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } elseif ($type === 'center') {
                    $sheet->setCellValueExplicit($cellCoordinate, self::sanitizeCellValue((string) $cellValue), DataType::TYPE_STRING);
                    $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->setCellValueExplicit($cellCoordinate, self::sanitizeCellValue((string) $cellValue), DataType::TYPE_STRING);
                    $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                $sheet->getStyle($cellCoordinate)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            // Row zebra striping & borders
            $sheet->getStyle("A{$currentRow}:{$lastColLetter}{$currentRow}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bgColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ]);

            $currentRow++;
        }

        $lastDataRow = max($currentRow - 1, $headerRow);

        // 6. Total Row (if specified)
        if (!empty($totalColumns) && count($rows) > 0) {
            $totalRow = $currentRow;
            $sheet->getRowDimension($totalRow)->setRowHeight(26);

            // Label "TOTAL" on first column or before first total column
            $sheet->setCellValue("A{$totalRow}", __('TOTAL KESELURUHAN'));
            $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Compute totals
            foreach ($totalColumns as $colIdx) {
                $colLetter = Coordinate::stringFromColumnIndex($colIdx + 1);
                $formula = "=SUM({$colLetter}{$dataStartRow}:{$colLetter}{$lastDataRow})";
                $sheet->setCellValue("{$colLetter}{$totalRow}", $formula);

                $type = $columnTypes[$colIdx] ?? 'currency';
                if ($type === 'currency') {
                    $sheet->getStyle("{$colLetter}{$totalRow}")->getNumberFormat()->setFormatCode($currencyFormat);
                } else {
                    $sheet->getStyle("{$colLetter}{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                }
                $sheet->getStyle("{$colLetter}{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            // Style Total Row: Bold, distinct highlight, double bottom border
            $sheet->getStyle("A{$totalRow}:{$lastColLetter}{$totalRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '111111']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FEF08A'], // Soft Gold / Amber highlight
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => 'B8960C'],
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DOUBLE,
                        'color' => ['rgb' => '997A15'],
                    ],
                    'left' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                    'right' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ]);

            $currentRow++;
        }

        // 7. Auto-size columns with minimum padding
        for ($i = 1; $i <= $colCount; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // 8. Output as StreamedResponse with RFC 5987/6266 UTF-8 disposition
        $utf8Filename = $filename . '.xlsx';
        $asciiFallback = Str::ascii($filename);
        $asciiFallback = preg_replace('/[^A-Za-z0-9_-]/', '_', $asciiFallback);
        if (trim($asciiFallback, '_') === '') {
            $asciiFallback = 'Export_' . now()->format('Ymd_His');
        }
        $asciiFallback .= '.xlsx';

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $utf8Filename,
            $asciiFallback
        );

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => $disposition,
            'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

