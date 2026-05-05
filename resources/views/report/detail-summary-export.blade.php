@php
    $itemColumnCount = max(count($columns), 1);
@endphp
<table>
    <tr>
        <th colspan="{{ 1 + $itemColumnCount + 3 + ($showAdminFee ? 1 : 0) }}" style="font-weight: bold; text-align: center;">{{ $reportHeading }}</th>
    </tr>
    <tr>
        <th colspan="{{ 1 + $itemColumnCount + 3 + ($showAdminFee ? 1 : 0) }}" style="font-weight: bold; text-align: center;">{{ strtoupper($scopeMeta['title']) }}</th>
    </tr>
    <tr>
        <th colspan="{{ 1 + $itemColumnCount + 3 + ($showAdminFee ? 1 : 0) }}" style="font-weight: bold; text-align: center;">
            PERIODE {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}
        </th>
    </tr>
    <tr><td colspan="{{ 1 + $itemColumnCount + 3 + ($showAdminFee ? 1 : 0) }}"></td></tr>

    <tr>
        <th rowspan="2" style="border: 1px solid #000; font-weight: bold;">Tanggal</th>
        <th colspan="{{ $itemColumnCount }}" style="border: 1px solid #000; font-weight: bold; text-align: center;">{{ strtoupper($scopeMeta['group_label']) }}</th>
        <th rowspan="2" style="border: 1px solid #000; font-weight: bold;">Total</th>
        <th rowspan="2" style="border: 1px solid #000; font-weight: bold;">DPP</th>
        <th rowspan="2" style="border: 1px solid #000; font-weight: bold;">PBJT</th>
        @if($showAdminFee)
            <th rowspan="2" style="border: 1px solid #000; font-weight: bold;">Biaya Admin</th>
        @endif
    </tr>
    <tr>
        @forelse($columns as $column)
            <th style="border: 1px solid #000; font-weight: bold;">{{ $column['label'] }}</th>
        @empty
            <th style="border: 1px solid #000; font-weight: bold;">-</th>
        @endforelse
    </tr>

    @forelse($rows as $row)
        <tr>
            <td style="border: 1px solid #000;">{{ $row['tanggal'] }}</td>
            @forelse($columns as $column)
                <td style="border: 1px solid #000;">{{ number_format($row['items'][$column['key']] ?? 0, 0, ',', '.') }}</td>
            @empty
                <td style="border: 1px solid #000;">-</td>
            @endforelse
            <td style="border: 1px solid #000;">{{ number_format($row['total'], 0, ',', '.') }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row['dpp'], 0, ',', '.') }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row['ppn'], 0, ',', '.') }}</td>
            @if($showAdminFee)
                <td style="border: 1px solid #000;">{{ number_format($row['admin_fee'], 0, ',', '.') }}</td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="{{ 1 + $itemColumnCount + 3 + ($showAdminFee ? 1 : 0) }}" style="border: 1px solid #000;">Tidak ada data transaksi pada rentang tanggal ini.</td>
        </tr>
    @endforelse

    <tr style="font-weight: bold; background: #f2f2f2;">
        <td style="border: 1px solid #000;">TOTAL</td>
        @forelse($columns as $column)
            <td style="border: 1px solid #000;">{{ number_format($footer['items'][$column['key']] ?? 0, 0, ',', '.') }}</td>
        @empty
            <td style="border: 1px solid #000;">-</td>
        @endforelse
        <td style="border: 1px solid #000;">{{ number_format($footer['total'], 0, ',', '.') }}</td>
        <td style="border: 1px solid #000;">{{ number_format($footer['dpp'], 0, ',', '.') }}</td>
        <td style="border: 1px solid #000;">{{ number_format($footer['ppn'], 0, ',', '.') }}</td>
        @if($showAdminFee)
            <td style="border: 1px solid #000;">{{ number_format($footer['admin_fee'], 0, ',', '.') }}</td>
        @endif
    </tr>
</table>
