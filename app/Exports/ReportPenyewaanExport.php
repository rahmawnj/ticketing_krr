<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ReportPenyewaanExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $groupedRows;
    protected $grandQty;
    protected $grandPpn;
    protected $grandTotal;

    public function __construct($groupedRows, $grandQty, $grandPpn, $grandTotal)
    {
        $this->groupedRows = $groupedRows;
        $this->grandQty = $grandQty;
        $this->grandPpn = $grandPpn;
        $this->grandTotal = $grandTotal;
    }

    public function collection()
    {
        $rows = new Collection();

        foreach ($this->groupedRows as $group) {
            foreach ($group['details'] as $detail) {
                $rows->push([
                    $detail['no'],
                    $detail['no_bukti'],
                    $detail['tanggal'],
                    $detail['kasir'],
                    $detail['kode_trx'],
                    $group['sewa_name'],
                    $detail['qty'],
                    $detail['metode'],
                    $detail['ppn'],
                    $detail['total_bayar'],
                ]);
            }

            $rows->push([
                'TOTAL',
                '',
                '',
                '',
                '',
                $group['sewa_name'],
                $group['subtotal_qty'],
                '',
                $group['subtotal_ppn'],
                $group['subtotal_total'],
            ]);
        }

        $rows->push([
            'GRAND TOTAL',
            '',
            '',
            '',
            '',
            '',
            $this->grandQty,
            '',
            $this->grandPpn,
            $this->grandTotal,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Bukti',
            'Tanggal',
            'FO/Kasir',
            'Kode Transaksi',
            'Tiket',
            'Qty',
            'Metode',
            'PBJT',
            'Total Bayar',
        ];
    }
}
