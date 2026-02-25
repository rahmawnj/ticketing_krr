<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\Setting;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MemberExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private string $filter;
    private int $membershipId;
    private int $suspendDays;

    public function __construct(string $filter = 'all', int $membershipId = 0)
    {
        $this->filter = $filter;
        $this->membershipId = $membershipId;
        $this->suspendDays = max((int) Setting::valueOf('member_suspend_after_days', 0), 0);
    }

    public function collection()
    {
        $query = Member::with('membership')->orderBy('nama', 'asc');

        if ($this->filter === 'member') {
            $query->where('parent_id', 0);
        } elseif ($this->filter === 'submember') {
            $query->where('parent_id', '!=', 0);
        }

        if ($this->membershipId > 0) {
            $query->where('membership_id', $this->membershipId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Member Code',
            'Nama',
            'No HP',
            'No Identitas',
            'RFID',
            'Tipe',
            'Membership',
            'Tanggal Register',
            'Tanggal Expired',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->display_member_code ?? '-',
            $row->nama ?? '-',
            $row->no_hp ?? '-',
            $row->no_ktp ?? '-',
            $row->rfid ?? '-',
            ((int) $row->parent_id === 0) ? 'Member' : 'Submember',
            optional($row->membership)->name ?? '-',
            $row->tgl_register ? Carbon::parse($row->tgl_register)->format('d/m/Y') : '-',
            $row->tgl_expired ? Carbon::parse($row->tgl_expired)->format('d/m/Y') : '-',
            $this->toStatusLabel((string) $row->lifecycle_status),
        ];
    }

    private function toStatusLabel(string $lifecycleStatus): string
    {
        if ($lifecycleStatus === 'active') {
            return 'Active';
        }

        if ($lifecycleStatus === 'inactive') {
            return 'Inactive';
        }

        if ($lifecycleStatus === 'suspend') {
            return 'Suspend';
        }

        if ($lifecycleStatus === 'expired') {
            return 'Expired > ' . $this->suspendDays . ' Hari';
        }

        return '-';
    }
}
