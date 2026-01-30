<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    $title = 'Dashboard';
    $breadcrumbs = ['Dashboard'];
    $now  = Carbon::now()->format('Y-m-d');

    // Data Summary Harian (Sesuai kebutuhan baru)
    $transaction = Transaction::whereDate('created_at', $now)->where('is_active', 1)->count();
    $rental = Transaction::whereDate('created_at', $now)->where('transaction_type', 'rental')->sum('bayar');
    $membership = Transaction::whereDate('created_at', $now)->whereIn('transaction_type', ['registration', 'renewal'])->sum('bayar');
    $ticket = Transaction::whereDate('created_at', $now)->where('transaction_type', 'ticket')->sum('bayar');

    // --- Persiapan Data Chart 7 Hari Terakhir ---

    $startDate = Carbon::today()->subDays(6)->startOfDay();
    $endDate = Carbon::today()->endOfDay();

    // 1. Ambil data untuk 3 kategori: 'rental', 'ticket', 'registration', 'renewal'
    $chartDataRaw = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('transaction_type', ['rental', 'ticket', 'registration', 'renewal'])
        ->selectRaw('DATE(created_at) as date, transaction_type, SUM(amount) as total_amount')
        ->groupBy('date', 'transaction_type')
        ->orderBy('date', 'asc')
        ->get();

    $chartLabels = [];
    $data_ticket = []; // Data untuk Ticket
    $data_rental = []; // Data untuk Rental
    $data_membership = []; // Data gabungan untuk Registration & Renewal
    $formattedDates = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $formattedDateKey = $date->format('Y-m-d');
        $chartLabels[] = $date->format('j M');

        // 2. Inisialisasi data untuk 3 kategori utama (Ticket, Rental, Membership)
        $formattedDates[$formattedDateKey] = [
            'ticket' => 0,
            'rental' => 0,
            'membership' => 0, // Kunci baru untuk gabungan
        ];
    }

    // 3. Isi array data dari hasil database. Gabungkan registration dan renewal ke 'membership'.
    foreach ($chartDataRaw as $item) {
        $dateKey = $item->date;
        $type = $item->transaction_type;
        $amount = (int) $item->total_amount;

        if (isset($formattedDates[$dateKey])) {
            if ($type === 'ticket') {
                $formattedDates[$dateKey]['ticket'] = $amount;
            } elseif ($type === 'rental') {
                $formattedDates[$dateKey]['rental'] = $amount;
            } elseif (in_array($type, ['registration', 'renewal'])) {
                // Gabungkan jumlah untuk Registration dan Renewal ke kategori Membership
                $formattedDates[$dateKey]['membership'] += $amount;
            }
        }
    }

    // 4. Ekstrak data ke array yang siap untuk Chart.js
    foreach ($formattedDates as $data) {
        $data_ticket[] = $data['ticket'];
        $data_rental[] = $data['rental'];
        $data_membership[] = $data['membership'];
    }

    // 5. Susun array chartData baru
    $chartData = [
        'labels' => $chartLabels,
        'ticket' => $data_ticket,
        'rental' => $data_rental,
        'membership' => $data_membership,
    ];

    return view('dashboard.index', compact('title', 'breadcrumbs', 'transaction', 'rental', 'ticket', 'membership', 'chartData'));
}
    public function profile()
    {
        $title = 'Edit Profile';
        $breadcrumbs = ['Edit Profile'];
        $user = User::find(auth()->user()->id);

        return view('dashboard.profile', compact('title', 'breadcrumbs', 'user'));
    }

    public function update(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);

            $request->validate([
                'username' => 'required|string|unique:users,username,' . $user->id,
                'name' => 'required|string',
            ]);

            DB::beginTransaction();

            if ($request->password) {
                $password = bcrypt($request->password);
            } else {
                $password = $user->password;
            }

            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'password' => $password,
            ]);

            DB::commit();

            return back()->with('success', "Profile berhasil diupdate");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
