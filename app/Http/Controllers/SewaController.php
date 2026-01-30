<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use App\Models\Setting; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SewaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sewa-access');
    }

    public function index()
    {
        $title = 'Data Sewa';
        $breadcrumbs = ['Master', 'Data Sewa'];
        $setting = Setting::first(); // Ambil data setting

        return view('sewa.index', compact('title', 'breadcrumbs', 'setting')); // Kirim setting ke view
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Sewa::orderBy('device', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // Tambahkan data PPN ke tombol edit untuk digunakan di JS
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('sewa.update', $row->id) . '" data-bs-toggle="modal" data-use-ppn="' . $row->use_ppn . '" data-ppn-value="' . $row->ppn . '">Edit</a> <button type="button" data-route="' . route('sewa.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->editColumn('harga', function ($row) {
                    // Tampilkan total harga (Harga Pokok + PPN) di tabel
                    return 'Rp. ' . number_format($row->harga + $row->ppn, 0, ',', '.');
                })
                ->editColumn('ppn_status', function ($row) {
                    // Kolom baru untuk menampilkan status PPN
                    if ($row->use_ppn == 1) {
                        return '<span class="badge bg-success">Diterapkan (Rp. ' . number_format($row->ppn, 0, ',', '.') . ')</span>';
                    }
                    return '<span class="badge bg-danger">Tidak Diterapkan</span>';
                })
                ->rawColumns(['action', 'ppn_status']) // Tambahkan 'ppn_status' ke rawColumns
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'harga' => 'required|numeric',
                'device' => 'required|numeric'
            ]);

            DB::beginTransaction();

            $harga = $request->harga;
            $setting = Setting::first();

            // Hitung PPN jika checkbox "ppn" dicentang ("on")
            $usePpn = $request->ppn == "on" ? 1 : 0;
            $calculatedPpn = $usePpn ? ($harga * $setting->ppn / 100) : 0;

            $data = $request->all();
            $data['use_ppn'] = $usePpn;
            $data['ppn'] = $calculatedPpn;

            $sewa = Sewa::create($data);

            DB::commit();

            return redirect()->route('sewa.index')->with('success', "{$sewa->name} berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Sewa $sewa)
    {
        return response()->json([
            'status' => 'success',
            'sewa' => $sewa
        ], 200);
    }

    public function update(Request $request, Sewa $sewa)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'harga' => 'required|numeric',
                'device' => 'required|numeric'
            ]);

            DB::beginTransaction();

            $harga = $request->harga;
            $setting = Setting::first();

            // Hitung PPN jika checkbox "ppn" dicentang ("on")
            $usePpn = $request->ppn == "on" ? 1 : 0;
            $calculatedPpn = $usePpn ? ($harga * $setting->ppn / 100) : 0;

            $data = $request->all();
            $data['use_ppn'] = $usePpn;
            $data['ppn'] = $calculatedPpn;

            $sewa->update($data);

            DB::commit();

            return redirect()->route('sewa.index')->with('success', "{$sewa->name} berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Sewa $sewa)
    {
        try {
            DB::beginTransaction();

            $sewa->delete();

            DB::commit();

            return redirect()->route('sewa.index')->with('success', "{$sewa->name} berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
