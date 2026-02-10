<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportTransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    // Gunakan variabel static untuk counter nomor urut agar tidak reset ke 1 terus
    private $rowNumber = 1;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {
        // Hitung diskon (sesuai logika di Controller kamu)
        $totalDetail = $data->detail()->sum('total');
        $disc = $totalDetail * $data->discount / 100;

        return [
            $this->rowNumber++, // Nomor urut
            Carbon::parse($data->created_at)->format('d/m/Y H:i:s'),
            $data->ticket_code,
            $data->user->name ?? '-', // <--- MENAMPILKAN NAMA KASIR
            $data->amount,
            $data->bayar,
            $data->bayar - $disc + $data->ppn,
            $data->ppn,
            $disc,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Tanggal',
            'Ticket Code',
            'Nama Kasir', // <--- HEADER BARU
            'Amount',
            'Jumlah',
            'Total',
            'PPN',
            'Discount',
        ];
    }
}
