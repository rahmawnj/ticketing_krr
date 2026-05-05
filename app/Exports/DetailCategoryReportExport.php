<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DetailCategoryReportExport implements FromView
{
    public function __construct(
        private string $from,
        private string $to,
        private array $columns,
        private array $rows,
        private array $footer,
        private bool $showAdminFee,
        private array $scopeMeta,
        private string $reportHeading
    ) {
    }

    public function view(): View
    {
        return view('report.detail-summary-export', [
            'from' => Carbon::parse($this->from),
            'to' => Carbon::parse($this->to),
            'columns' => $this->columns,
            'rows' => $this->rows,
            'footer' => $this->footer,
            'showAdminFee' => $this->showAdminFee,
            'scopeMeta' => $this->scopeMeta,
            'reportHeading' => $this->reportHeading,
        ]);
    }
}
