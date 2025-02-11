<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportReportMonthly implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithTitle, WithStyles, WithEvents, WithCustomStartCell
{
    private $transactions;
    private $period;

    public function __construct($transactions, $period)
    {
        $this->transactions = $transactions;
        $this->period = $period;
    }

    public function collection()
    {
        $collection = collect($this->transactions);
        $totalQuantity = $collection->sum(fn ($t) => $t->details->sum('quantity'));
        $totalPrice = $collection->sum(fn ($t) => $t->details->sum('total_price'));

        $collection->push((object)[
            'code' => 'Total',
            'created_at' => null,
            'details' => collect([
                (object)[
                    'product' => (object)['catalog' => null, 'name' => ''],
                    'unit_price' => null,
                    'quantity' => $totalQuantity,
                    'total_price' => $totalPrice
                ]
            ])
        ]);

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Katalog',
            'Produk',
            'Harga',
            'Qty',
            'Jumlah',
        ];
    }

    public function map($transaction): array
    {
        $rows = [];
        foreach ($transaction->details as $detail) {
            $rows[] = [
                $transaction->code === 'Total' ? 'Total' : $transaction->code,
                $transaction->code === 'Total' ? '' : \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('l, d F Y'),
                $transaction->code === 'Total' ? '' : ($detail->product->catalog->name ?? '-'),
                $transaction->code === 'Total' ? '' : $detail->product->name,
                $transaction->code === 'Total' ? '' : 'Rp ' . number_format($detail->unit_price, 0, ',', '.'),
                $detail->quantity,
                'Rp ' . number_format($detail->total_price, 0, ',', '.')
            ];
        }
        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 30,
            'D' => 30,
            'E' => 15,
            'F' => 10,
            'G' => 15,
        ];
    }

    public function title(): string
    {
        return 'LAPORAN PENJUALAN BULANAN';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->setCellValue('A1', 'LAPORAN PENJUALAN BULANAN');
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A2:G2');
                $event->sheet->setCellValue('A2', 'Periode: ' . $this->period);
                $event->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $event->sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getStyle('A3:G3')->getFont()->setBold(true);
                $lastRow = $event->sheet->getHighestRow();

                $event->sheet->mergeCells("A{$lastRow}:E{$lastRow}");
                $event->sheet->setCellValue("A{$lastRow}", 'Total');
                $event->sheet->getStyle("A{$lastRow}:G{$lastRow}")->getFont()->setBold(true);

                $event->sheet->getStyle("A{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $tableRange = 'A3:G' . $lastRow;
                $event->sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('A3:G3')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
