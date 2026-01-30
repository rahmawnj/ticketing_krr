<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sewa;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\Penyewaan;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\PenyewaanExport;
use App\Models\DetailTransaction;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportPenyewaanExport;
use App\Exports\ReportTransactionExport;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');

        $title = 'Report Transaction ' . $date;
        $breadcrumbs = ['Master', 'Report Transaction'];
        $users = User::get();

        return view('report.transaction', compact('title', 'breadcrumbs', 'users'));
    }

   public function transactionList(Request $request)
{
    if ($request->ajax()) {
        $now = Carbon::now()->format('Y-m-d');
        $transactionType = $request->transaction_type;

        // Tambahkan with('user') untuk mengambil nama kasir
        $query = Transaction::with('user')->where('is_active', 1);

        if ($request->from && $request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
            $query->whereBetween('created_at', [$request->from, $to]);

            if ($request->kasir != 'all') {
                $query->where('user_id', $request->kasir);
            }
        } else {
            $query->whereDate('created_at', $now);
        }

        if (!empty($transactionType)) {
            $query->where('transaction_type', $transactionType);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('kasir', function ($row) {
                // Menampilkan nama kasir dari relasi user
                return $row->user->name ?? '-';
            })
            ->editColumn('harga', function ($row) {
                return 'Rp. ' . number_format($row->bayar, 0, ',', '.') ?? 0;
            })
            ->editColumn('jumlah', function ($row) {
                return 'Rp. ' . number_format($row->detail()->sum('total') + $row->detail()->sum('ppn'), 0, ',', '.') ?? 0;
            })
            ->editColumn('discount', function ($row) {
                return 'Rp. ' . number_format($row->detail()->sum('total') * $row->discount / 100, 0, ',', '.') ?? 0;
            })
            ->addColumn('harga_ticket', function ($row) {
                $disc = $row->bayar * $row->discount / 100;
                return 'Rp. ' . number_format($row->bayar - $disc + $row->ppn, 0, ',', '.') ?? 0;
            })
            ->editColumn('ppn', function ($row) {
                return 'Rp. ' .  number_format($row->ppn, 0, ',', '.') ?? 0;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

    function export_transaction(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');

        if ($request->from && $request->to && $request->kasir == 'all') {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

            $data = Transaction::with('ticket')->where('is_active', 1)->whereBetween('created_at', [$request->from, $to])->get();
        } elseif ($request->from && $request->to && $request->kasir != 'all') {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
            $data = Transaction::with('ticket')->where(['is_active' => 1, 'user_id' => $request->kasir])->whereBetween('created_at', [$request->from, $to])->get();
        } else {
            $data = Transaction::with('ticket')->where('is_active', 1)->whereDate('created_at', $now)->get();
        }

        return Excel::download(new ReportTransactionExport($data), "Laporan Transaksi.xlsx");
    }

    public function penyewaan(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');

        $title = 'Report Penyewaan ' . $date;
        $breadcrumbs = ['Master', 'Report Penyewaan'];
        $users = User::get();

        return view('report.penyewaan', compact('title', 'breadcrumbs', 'users'));
    }

    public function penyewaanList(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now()->format('Y-m-d');

            if ($request->from && $request->to && $request->kasir == 'all') {
                $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

                $data = Penyewaan::whereBetween('created_at', [$request->from, $to]);
            } elseif ($request->from && $request->to && $request->kasir != 'all') {
                $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

                $data = Penyewaan::where('user_id', $request->kasir)->whereBetween('created_at', [$request->from, $to]);
            } else {
                $data = Penyewaan::whereDate('created_at', $now);
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
                })
                ->editColumn('sewa', function ($row) {
                    return $row->sewa->name;
                })
                ->addColumn('kasir', function ($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->editColumn('harga', function ($row) {
                    return 'Rp. ' . number_format($row->sewa->harga, 0, ',', '.');
                })
                ->editColumn('total', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function export_penyewaan(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');

        if ($request->from && $request->to && $request->kasir == 'all') {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

            $data = Penyewaan::whereBetween('created_at', [$request->from, $to])->get();
        } elseif ($request->from && $request->to && $request->kasir != 'all') {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

            $data = Penyewaan::where('user_id', $request->kasir)->whereBetween('created_at', [$request->from, $to])->get();
        } else {
            $data = Penyewaan::whereDate('created_at', $now)->get();
        }

        return Excel::download(new ReportPenyewaanExport($data), "Laporan Penyewaan.xlsx");
    }

    public function rekapTransaction(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');
        $from = $request->from ? Carbon::parse($request->from)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
        } else {
            $to = Carbon::now()->addDay(1)->format('Y-m-d');
        }
        $title = 'Rekap Transaction ' . $date;
        $breadcrumbs = ['Master', 'Rekap Transaction'];
        $tickets = Ticket::get();
        $users = User::get();

        $memberships = Membership::where('is_active', 1)->get();
        $sewa = Sewa::get();
        return view('report.rekap-transaction', compact('title', 'breadcrumbs', 'from', 'to', 'tickets', 'users', 'memberships', 'sewa'));
    }

 public function exportTransaction(Request $request)
{
    $from = Carbon::parse($request->from)->format('Y-m-d');
    $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

    $memberships = Membership::where('is_active', 1)->get();
    $sewa = Sewa::get();

    return Excel::download(new TransactionExport($from, $to, $request->kasir, $memberships, $sewa), 'Rekap Transaction.xlsx');
}

public function printTransaction(Request $request)
{
    $from = Carbon::parse($request->from)->format('Y-m-d');
    $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
    $kasir = $request->kasir;

    // Ambil semua data master untuk looping di view
    $tickets = Ticket::get();
    $memberships = Membership::where('is_active', 1)->get();
    $sewa = Sewa::get();
    $users = User::all();

    return view('report.download-transaction', compact('tickets', 'memberships', 'sewa', 'from', 'to', 'kasir', 'users'));
}
    public function rekapPenyewaan(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');
        $from = $request->from ? Carbon::parse($request->from)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $to = $request->to ? Carbon::parse($request->to)->addDay(1)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $title = 'Rekap Penyewaan ' . $date;
        $breadcrumbs = ['Master', 'Rekap Penyewaan'];
        $sewa = Sewa::get();
        $users = User::get();

        return view('report.rekap-penyewaan', compact('title', 'breadcrumbs', 'from', 'to', 'sewa', 'users'));
    }

    public function exportPenyewaan(Request $request)
    {
        $from = Carbon::parse(request('from'))->format('Y-m-d');
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d');

        return Excel::download(new PenyewaanExport($from, $to, $request->kasir), 'Rekap Penyewaan.xlsx');
    }

    public function printPenyewaan(Request $request)
    {
        $from = Carbon::parse(request('from'))->format('Y-m-d');
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d');
        $kasir = $request->kasir;
        $penyewaanId = Penyewaan::whereBetween('created_at', [$from, $to])->pluck('id');

        $sewa = Sewa::whereHas('penyewaans', function ($query) use ($penyewaanId) {
            $query->whereIn('id', $penyewaanId);
        })->get();

        return view('report.download-penyewaan', compact('from', 'to', 'kasir', 'sewa'));
    }
}
