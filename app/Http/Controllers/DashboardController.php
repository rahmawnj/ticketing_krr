<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Setting;
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
    $selectedDateInput = request('date');
    $selectedDate = Carbon::today();

    if (!empty($selectedDateInput)) {
        try {
            $selectedDate = Carbon::createFromFormat('Y-m-d', $selectedDateInput);
        } catch (\Throwable $th) {
            $selectedDate = Carbon::today();
        }
    }

    $selectedDateYmd = $selectedDate->format('Y-m-d');
    $todayLabel = $selectedDate->copy()->locale('id')->translatedFormat('l, d M Y');
    $setting = Setting::query()->first();
    $dashboardMetricMode = $setting->dashboard_metric_mode ?? 'amount';
    $isToday = $selectedDateYmd === Carbon::now('Asia/Jakarta')->toDateString();
    $isCountMode = $dashboardMetricMode === 'count';
    $periodBadge = $isToday ? 'Hari Ini' : $selectedDate->copy()->locale('id')->translatedFormat('d M Y');
    $chartTitle = $isToday ? 'Rekap Transaksi Hari Ini (24 Jam)' : 'Rekap Transaksi ' . $todayLabel . ' (24 Jam)';
    $chartSubtitle = $isCountMode
        ? ($isToday
            ? 'Perbandingan jumlah transaksi per tipe setiap jam'
            : 'Perbandingan jumlah transaksi per tipe setiap jam pada tanggal terpilih')
        : ($isToday
            ? 'Perbandingan nominal transaksi per tipe setiap jam'
            : 'Perbandingan nominal transaksi per tipe setiap jam pada tanggal terpilih');

    // Data summary harian berdasarkan 4 tipe transaksi.
    $renewal = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'renewal')->sum('bayar');
    $renewalCount = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'renewal')->count();
    $newMember = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'registration')->sum('bayar');
    $newMemberCount = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'registration')->count();
    $rental = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'rental')->sum('bayar');
    $rentalCount = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'rental')->count();
    $ticket = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'ticket')->sum('bayar');
    $ticketCount = Transaction::whereDate('created_at', $selectedDateYmd)->where('transaction_type', 'ticket')->count();

    // --- Persiapan Data Chart Hari Ini (24 Jam) ---
    $startDate = $selectedDate->copy()->startOfDay();
    $endDate = $selectedDate->copy()->endOfDay();

    // 1. Ambil data per jam untuk 4 kategori: renewal, registration, rental, ticket.
    $chartDataRaw = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('transaction_type', ['rental', 'ticket', 'registration', 'renewal'])
        ->selectRaw(
            $isCountMode
                ? 'HOUR(created_at) as hour, transaction_type, COUNT(*) as total_value'
                : 'HOUR(created_at) as hour, transaction_type, SUM(bayar) as total_value'
        )
        ->groupBy('hour', 'transaction_type')
        ->orderBy('hour', 'asc')
        ->get();

    $chartLabels = [];
    $data_renewal = [];
    $data_new_member = [];
    $data_rental = [];
    $data_ticket = [];
    $formattedHours = [];

    for ($hour = 0; $hour < 24; $hour++) {
        $formattedHourKey = (string) $hour;
        $chartLabels[] = sprintf('%02d:00', $hour);

        // 2. Inisialisasi data untuk 4 kategori utama per jam.
        $formattedHours[$formattedHourKey] = [
            'renewal' => 0,
            'new_member' => 0,
            'rental' => 0,
            'ticket' => 0,
        ];
    }

    // 3. Isi array data dari hasil database berdasarkan tipe transaksi per jam.
    foreach ($chartDataRaw as $item) {
        $hourKey = (string) $item->hour;
        $type = $item->transaction_type;
        $value = (int) $item->total_value;

        if (isset($formattedHours[$hourKey])) {
            if ($type === 'renewal') {
                $formattedHours[$hourKey]['renewal'] = $value;
            } elseif ($type === 'registration') {
                $formattedHours[$hourKey]['new_member'] = $value;
            } elseif ($type === 'rental') {
                $formattedHours[$hourKey]['rental'] = $value;
            } elseif ($type === 'ticket') {
                $formattedHours[$hourKey]['ticket'] = $value;
            }
        }
    }

    // 4. Ekstrak data ke array yang siap untuk Chart.js
    foreach ($formattedHours as $data) {
        $data_renewal[] = $data['renewal'];
        $data_new_member[] = $data['new_member'];
        $data_rental[] = $data['rental'];
        $data_ticket[] = $data['ticket'];
    }

    // 5. Susun array chartData baru
    $chartData = [
        'labels' => $chartLabels,
        'renewal' => $data_renewal,
        'new_member' => $data_new_member,
        'rental' => $data_rental,
        'ticket' => $data_ticket,
    ];

    return view('dashboard.index', compact(
        'title',
        'breadcrumbs',
        'renewal',
        'renewalCount',
        'newMember',
        'newMemberCount',
        'rental',
        'rentalCount',
        'ticket',
        'ticketCount',
        'todayLabel',
        'periodBadge',
        'chartTitle',
        'chartSubtitle',
        'dashboardMetricMode',
        'selectedDateYmd',
        'chartData'
    ));
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
