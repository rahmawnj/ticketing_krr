<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\History;
use App\Models\Setting;
use App\Models\Membership;
use App\Models\LimitMember;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\MemberImport;
use App\Models\DetailTransaction;
use App\Models\HistoryMembership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:member-access')->except(['findOne']);
    }

    public function index()
    {
        $title = 'Data Member';
        $breadcrumbs = ['Master', 'Data Member'];
        $limit = LimitMember::first() ?? new LimitMember();
        $memberships = Membership::whereIsActive(1)->get();

        return view('member.index', compact('title', 'breadcrumbs', 'limit', 'memberships'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
           $filter = $request->get('filter', 'all');

            // 2. Tentukan basis query
            $data = Member::with('membership')->orderBy('nama', 'asc');

            // 3. Terapkan filter berdasarkan nilai $filter
            if ($filter === 'member') {
                // Filter untuk Anggota Utama (parent_id = 0 atau NULL, asumsikan 0)
                $data->where('parent_id', 0);
            } elseif ($filter === 'submember') {
                // Filter untuk Anggota Grup (parent_id != 0 atau NULL)
                $data->where('parent_id', '!=', 0);
            }
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    // <a href="#modal-dialog-membership" id="' . $row->id . '" class="btn btn-xs btn-outline-secondary btn-membership" data-route="' . route('members.show', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-user-circle"></i></a>
                    $actionBtn .= '<a href="#modal-dialog-info" id="' . $row->id . '" class="btn btn-xs btn-outline-info btn-show" data-route="' . route('members.show', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-eye"></i></a> ';

                    if ($row->membership_id != 0 || $row->membership != null) {
                        $actionBtn .= '<a href="' . route('members.invoice', $row->id) . '" target="_blank" id="" class="btn btn-xs btn-outline-secondary btn-invoice"><i class="fas fa-print"></i></a> ';
                    }

                    $actionBtn .= '<a href="' . route('members.edit', $row->id) . '" id="' . $row->id . '" class="btn btn-xs btn-outline-success"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('members.destroy', $row->id) . '" class="delete btn btn-outline-danger btn-delete btn-xs"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->editColumn('harga', function ($row) {
                    return 'Rp. ' . number_format($row->harga, 0, ',', '.');
                })
                ->editColumn('no_ktp', function($row) {
                return  $row->no_ktp ;
            })
            // Tambahkan no_hp jika kolomnya ada di database
            ->editColumn('no_hp', function($row) {
                return $row->no_hp ?? '-';
            })
               ->editColumn('expired', function ($row) {
                    if (Carbon::now('Asia/Jakarta')->format('Y-m-d') >= $row->tgl_expired) {

                        return '<span class="badge bg-danger fs-12px">Expired</span>';

                    } elseif ($row->is_active == 0) {
                        return '<span class="badge fs-12px bg-secondary">Inactive</span>';
                    } else {
                        return '<span class="badge fs-12px bg-success">Active</span>';
                    }
                })
                ->editColumn('masa_berlaku', function ($row) {
                    return Carbon::parse($row->tgl_register)->format('d/m/Y') . ' - ' . Carbon::parse($row->tgl_expired)->format('d/m/Y');
                })
                ->editColumn('sisa_hari', content: function ($row) {
                    $today = Carbon::now('Asia/Jakarta')->startOfDay();
                    $expiredAt = Carbon::parse($row->tgl_expired)->startOfDay();
                    $sisa = $today->diffInDays($expiredAt, false);

                    if ($sisa <= 0) {
                        return '<span class="badge bg-danger rounded-0 fs-11px">Expired</span>';
                    }

                    return $sisa . ' Hari';
                })
                ->editColumn('qr_code', function ($row) {
                    return QrCode::size(50)->generate($row->qr_code);
                })
                ->addColumn('membership_name', function ($row) {
                    if ($row->membership_id != 0) {
                        return '<span class="badge bg-primary rounded-0 fs-11px">' . $row->membership->name . '</span>';
                    } else {
                        return '<span class="badge bg-default text-black rounded-0 fs-11px">Pilih Membership</span>';
                    }
                })
                ->addColumn('image_profile', function ($row) {
                    $imageUrl = $row->image_profile != null ? asset('/storage/' . $row->image_profile) : config('app.url') . '/img/user/user-10.jpg';
                    $membershipName = $row->membership ? $row->membership->name : '-';
                    $memberType = $row->parent_id == 0 ? 'Member' : 'Submember';
                    return '<img src="' . $imageUrl . '" data-full="' . $imageUrl . '" data-name="' . e($row->nama) . '" data-phone="' . e($row->no_hp ?? '-') . '" data-ktp="' . e($row->no_ktp ?? '-') . '" data-rfid="' . e($row->rfid ?? '-') . '" data-membership="' . e($membershipName) . '" data-type="' . e($memberType) . '" alt="" class="rounded h-40px js-photo-thumb">';
                })
                ->addColumn('member_type', function ($row) {
                    if ($row->parent_id == 0) {
                        return '<span class="badge bg-info rounded-0 fs-11px">Member</span>';
                    } else {
                        return '<span class="badge bg-warning text-black rounded-0 fs-11px">Submember</span>';
                    }
                })
                ->rawColumns(['action', 'expired', 'qr_code', 'membership_name', 'sisa_hari', 'member_type', 'image_profile'])
                ->make(true);
        }
    }

    function create()
    {
        $title = 'Create Member';
        $breadcrumbs = ['Master', 'Data Member', 'Create Member'];
        $memberships = Membership::whereIsActive(1)->get();
        $member = new Member();
        $action = route('members.store');
        $method = 'POST';

        return view('member.form', compact('title', 'breadcrumbs', 'memberships', 'member', 'action', 'method'));
    }

public function store(CreateMemberRequest $request)
    {
        DB::beginTransaction();
        try {
            $attr = $request->validated();
            $membership = Membership::find($request->membership);

            // Jika membership tidak ditemukan
            if (!$membership) {
                 throw new \Exception("Jenis Member tidak valid.");
            }

            $attr['tgl_lahir'] = $request->tanggal_lahir;
            $attr['tgl_register'] = now('Asia/Jakarta')->format('Y-m-d');
            $attr['tgl_expired'] = now('Asia/Jakarta')->addDay($membership->duration_days)->format('Y-m-d');
            $attr['qr_code'] = "MBR" . strtoupper(Str::random(13));
            $attr['membership_id'] = $request->membership;
            $attr['is_active'] = 1;
            $attr['parent_id'] = 0;
            $attr['access_used'] = 0;

            if ($request->image_profile) {
                $image = $request->file('image_profile');
                // Gunakan RFID dari request atau buat random jika tidak ada
                $rfid = $request->rfid ?? Str::random(10);
                $attr['image_profile'] =
                    $image->storeAs('members', $rfid . now('Asia/Jakarta')->format('YmdHis') . '.' . $image->extension());
            }

            $member = Member::create($attr);

            HistoryMembership::create([
                'member_id' => $member->id,
                'membership_id' => $membership->id,
                'start_date' => $member->tgl_register,
                'end_date' => $member->tgl_expired,
                'status' => 'active',
            ]);

            $totalMembersRegistered = 1;

            if ($request->rfid_group) {
                foreach ($request->rfid_group as $key => $rfid_group) {
                    $name_group = $request->name_group[$key];
                    $image_group = null;

                    if (isset($request->file('image_group')[$key])) {
                        $image = $request->file('image_group')[$key];
                        $image_group =
                            $image->storeAs('members', ($rfid_group ?? Str::random(10)) . now('Asia/Jakarta')->format('YmdHis') . '.' . $image->extension());
                    }

                    $child = Member::create([
                        'rfid' => $rfid_group,
                        'nama' => $name_group,
                        'alamat' => $member->alamat,
                        'no_ktp' => $member->no_ktp,
                        'no_hp' => $member->no_hp,
                        'tgl_lahir' => $member->tgl_lahir,
                        'jenis_kelamin' => $member->jenis_kelamin,

                        'membership_id' => $member->membership_id,
                        'tgl_register' => $member->tgl_register,
                        'tgl_expired' => $member->tgl_expired,
                        'qr_code' => "MBR" . strtoupper(Str::random(13)),
                        'is_active' => 1,
                        'image_profile' => $image_group,
                        'parent_id' => $member->id,
                        'access_used' => 0,
                    ]);

                    HistoryMembership::create([
                        'member_id' => $child->id,
                        'membership_id' => $membership->id,
                        'start_date' => $member->tgl_register,
                        'end_date' => $member->tgl_expired,
                        'status' => 'active',
                    ]);

                    $totalMembersRegistered++;
                }
            }

            $basePricePerMember = (float) $membership->price;
            $ppnAmount = $membership->use_ppn ? ((float) $membership->ppn) : 0;

            $grossTotal = $basePricePerMember + $ppnAmount;
            $now = Carbon::now('Asia/Jakarta');
            $lastTrx = Transaction::whereDate('created_at', $now->format('Y-m-d'))->latest()->first();
            $notrx = $lastTrx ? $lastTrx->no_trx + 1 : 1;

            $transaction = Transaction::create([
                'user_id' => auth()->id() ?? 1,
                'no_trx' => $notrx,
                'ticket_code' => 'REG/' . $now->format('dmY') . rand(1000, 9999),
                'tipe' => ($totalMembersRegistered > 1) ? 'group' : 'individual',
                'amount' => $totalMembersRegistered,
                'discount' => 0,
                'transaction_type' => 'registration',
                'ppn' => $ppnAmount,
                'bayar' => $basePricePerMember,
                'status' => 'open',
                'is_active' => 1,
                'metode' => 'cash',
                'ticket_id'=> $membership->id,
                'member_id' => $member->id,
                'member_info' => $member->nama . ' - ' . $member->no_hp
            ]);

            $invoiceUrl = route('transactions.invoice', $transaction->id);

            // DetailTransaction::create([
            //     'transaction_id' => $transaction->id,
            //     'ticket_id' => $membership->id,
            //     'qty' => $totalMembersRegistered,
            //     'total' => $basePricePerMember,
            //     'ppn' => $ppnAmount,
            // ]);

            DB::commit();
            return redirect()->route('members.index')
                ->with('success', "Member {$member->nama} berhasil ditambahkan")
                ->with('invoice_url', $invoiceUrl);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error creating member: " . $th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Member $member)
    {
        $member->load('membership');
        $member->image_profile = config('app.url') . "/storage/" . $member->image_profile;

        return response()->json([
            'status' => 'success',
            'member' => $member
        ], 200);
    }

    function edit(Member $member)
    {
        $title = 'Edit Member';
        $breadcrumbs = ['Master', 'Data Member', 'Edit Member'];
        $memberships = Membership::whereIsActive(1)->get();
        $action = route('members.update', $member->id);
        $method = 'PUT';

        return view('member.form', compact('title', 'breadcrumbs', 'memberships', 'member', 'action', 'method'));
    }

public function update(UpdateMemberRequest $request, Member $member)
    {

        try {
            DB::beginTransaction();
            $attr = $request->validated();
            $is_active = $request->has('is_active') ? 1 : 0;
            $is_membership_changed = false;
            $invoiceUrl = null;

            if ($member->parent_id == 0) {
                $newMembershipId = $request->membership;

                if ($member->membership_id != $newMembershipId) {

                    $is_membership_changed = true;
                    $membership = $member->membership;

                    if (!$membership) {
                         throw new \Exception("Jenis Member baru tidak valid.");
                    }

                    $attr['tgl_lahir'] = $request->tanggal_lahir;
                    $attr['membership_id'] = $membership->id;
                    $attr['tgl_expired'] = now('Asia/Jakarta')->addDay($membership->duration_days)->format('Y-m-d');
                    $attr['access_used'] = 0;

                    HistoryMembership::where('member_id', $member->id)
                        ->where('status', 'active')
                        ->update(['status' => 'inactive']);

                    HistoryMembership::create([
                        'member_id' => $member->id,
                        'membership_id' => $membership->id,
                        'start_date' => now('Asia/Jakarta')->format('Y-m-d'),
                        'end_date' => $attr['tgl_expired'],
                        'status' => 'active',
                    ]);

                    $totalMembersRegistered = 1 + ($member->childs ? $member->childs->count() : 0);
                    $basePricePerMember = (float) $membership->price; // Harga dasar per member
                    $ppnAmount = $membership->use_ppn ? ((float) $membership->ppn) : 0;

                    $now = Carbon::now('Asia/Jakarta');
                    $lastTrx = Transaction::whereDate('created_at', $now->format('Y-m-d'))
                                            ->latest()->first();
                    $notrx = $lastTrx ? $lastTrx->no_trx + 1 : 1;

                    $tipeTransaksi = ($totalMembersRegistered > 1) ? 'group' : 'individual';
                    $ticketCode = 'REG/' . $now->format('dmY') . rand(1000, 9999);

                    $transaction = Transaction::create([
                        'user_id' => auth()->id() ?? 1,
                        'no_trx' => $notrx,
                        'ticket_code' => $ticketCode,
                        'tipe' => $tipeTransaksi,
                        'amount' => $totalMembersRegistered,
                        'discount' => 0,
                        'transaction_type' => 'registration',
                        'ppn' => $ppnAmount,
                        'bayar' => $basePricePerMember,
                        'status' => 'open',
                        'is_active' => 1,
                'metode' => 'cash',
                'ticket_id'=> $membership->id,
                        'member_id' => $member->id,
                        'member_info' => $member->nama . ' - ' . $member->no_hp

                    ]);

                    $invoiceUrl = route('transactions.invoice', $transaction->id);

                    // DetailTransaction::create([
                    //     'transaction_id' => $transaction->id,
                    //     'ticket_id' => $membership->id,
                    //     'qty' => $totalMembersRegistered,
                    //     'total' => $basePricePerMember,
                    //     'ppn' => $ppnAmount,
                    // ]);
                }

                $attr['tgl_lahir'] = $request->tanggal_lahir;
            }

            $attr['is_active'] = $is_active;

            if ($request->image_profile) {
                // Hapus gambar lama jika ada
                if ($member->image_profile) {
                    \Illuminate\Support\Facades\Storage::delete($member->image_profile);
                }

                $image = $request->file('image_profile');
                $rfid = $request->rfid ?? $member->rfid;
                $attr['image_profile'] =
                    $image->storeAs('members', $rfid . now('Asia/Jakarta')->format('YmdHis') . '.' . $image->extension());
            } else {
                // Jika tidak ada upload, pertahankan gambar lama
                $attr['image_profile'] = $member->image_profile;
            }

            $member->update($attr);

            // Update status dan membership (jika berubah) child members
            if ($member->childs) {
                foreach ($member->childs as $child) {
                    $child->update([
                        'is_active' => $is_active,
                        'membership_id' => $is_membership_changed ? $attr['membership_id'] : $child->membership_id,
                        'tgl_expired' => $is_membership_changed ? $attr['tgl_expired'] : $child->tgl_expired,
                        'access_used' => $is_membership_changed ? 0 : $child->access_used,
                    ]);

                    if ($is_membership_changed) {
                        // Nonaktifkan riwayat membership lama child
                        HistoryMembership::where('member_id', $child->id)
                            ->where('status', 'active')
                            ->update(['status' => 'inactive']);

                        // Buat riwayat membership baru child
                        HistoryMembership::create([
                            'member_id' => $child->id,
                            'membership_id' => $attr['membership_id'],
                            'start_date' => now('Asia/Jakarta')->format('Y-m-d'),
                            'end_date' => $attr['tgl_expired'],
                            'status' => 'active',
                        ]);
                    }
                }
            }

            // Jika status di-uncheck (nonaktif), nonaktifkan riwayat membership yang aktif saat ini
            if (!$request->is_active) {
                HistoryMembership::where('member_id', $member->id)
                    ->where('status', 'active')
                    ->first()
                    ?->update(['status' => 'inactive']);
            }

            DB::commit();
            return redirect()->route('members.index')
                ->with('success', "Member {$member->nama} berhasil diupdate")
                ->with('invoice_url', $invoiceUrl);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error updating member: " . $th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }


    public function destroy(Member $member)
    {
        try {
            DB::beginTransaction();

            $member->delete();

            foreach ($member->histories as $history) {
                $history->delete();
            }

            DB::commit();

            return redirect()->route('members.index')->with('success', "Member {$member->nama} berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function findOne(Request $request)
    {
        $member = Member::where('rfid', $request->rfid)->first();

        if ($member) {
            return response()->json([
                'status' => 'success',
                'member' => $member
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    function expired(Member $member)
    {
        try {
            DB::beginTransaction();

            if ($member->membership_id == 0) {
                return back()->with('error', 'Member belum berlangganan');
            }

            $member->update([
                'tgl_expired' => Carbon::now('Asia/Jakarta')->addDay($member->membership->duration_days)->format('Y-m-d'),
                'access_used' => 0
            ]);

            HistoryMembership::create([
                'member_id' => $member->id,
                'membership_id' => $member->membership_id,
                'start_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                'end_date' => Carbon::now('Asia/Jakarta')->addDay($member->membership->duration_days)->format('Y-m-d'),
                'status' => 'active',
            ]);

            DB::commit();
            return redirect()->route('members.index')->with('success', "Member {$member->nama} berhasil diperpanjang");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function update_setting(Request $request)
    {
        try {
            $limit = LimitMember::first();
            if ($limit) {
                $limit->update($request->all());
            } else {
                LimitMember::create($request->all());
            }

            DB::commit();
            return back()->with('success', "Setting member berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            DB::beginTransaction();

            Excel::import(new MemberImport, $request->file('file'));

            DB::commit();
            return back()->with('success', 'Success import member');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function download()
    {
        $path = public_path('example/example-import-member.xlsx');

        if (!File::exists($path)) {
            // Jika tidak ada, tampilkan halaman 404 Not Found
            abort(404, 'File not found.');
        }

        return Response::download($path);
    }

    function print_qr(Member $member)
    {
        return view('member.print-qr', compact('member'));
    }

    function invoice(Member $member)
    {
        $transaction = Transaction::where('member_id', $member->id)
            ->whereIn('transaction_type', ['registration', 'renewal'])
            ->latest()
            ->first();

        if (!$transaction) {
            abort(404);
        }

        return redirect()->route('transactions.invoice', $transaction->id);
    }

    public function invoicePdf(Member $member)
    {
        $transaction = Transaction::where('member_id', $member->id)
            ->whereIn('transaction_type', ['registration', 'renewal'])
            ->latest()
            ->first();

        if (!$transaction) {
            abort(404);
        }

        return redirect()->route('transactions.invoice.pdf', $transaction->id);
    }

    public function bulkRenewIndex(Request $request)
    {
        $title = 'Perpanjangan Member';
        $breadcrumbs = ['Member', 'Perpanjang Massal'];

        return view('member.bulk_renew_index', compact('title', 'breadcrumbs'));
    }

public function getRenewableMembers(Request $request)
{
    $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

    // 1. Ambil settingan hari dari database
    $setting = Setting::first();
    $reminderDays = $setting->member_reminder_days ?? 7; // Default 7 hari jika setting kosong

    // 2. Hitung tanggal batas (Hari ini + X hari dari setting)
    $limitDate = Carbon::now('Asia/Jakarta')->addDays($reminderDays)->format('Y-m-d');

    $data = Member::with('membership')
        ->where('tgl_expired', '<=', $limitDate)
        ->where('parent_id', 0)
        ->where('is_active', 1) // Biasanya reminder hanya untuk member yang masih aktif
        ->orderBy('tgl_expired', 'asc');

    return DataTables::eloquent($data)
        ->addIndexColumn()
        ->addColumn('gross_price', function ($row) {
            if (!$row->membership) return 'N/A';
            $totalDisplayPrice = $row->membership->price + ($row->membership->use_ppn ? $row->membership->ppn : 0);
            return number_format($totalDisplayPrice, 0, ',', '.');
        })
        ->addColumn('renewal_status', function ($row) use ($today) {
            // Logika Status: Jika sudah lewat tanggalnya = Expired, jika belum = Mendekati Expire
            if ($row->tgl_expired < $today) {
                return '<span class="badge bg-danger">Expired</span>';
            } else {
                $sisaHari = Carbon::parse($row->tgl_expired)->diffInDays(Carbon::parse($today));
                return '<span class="badge bg-warning text-dark">' . $sisaHari . ' Hari Lagi</span>';
            }
        })
        ->addColumn('action', function ($row) {
            $grossPrice = 0;
            if ($row->membership) {
                $grossPrice = $row->membership->price + ($row->membership->use_ppn ? $row->membership->ppn : 0);
            }

            $formattedPrice = number_format($grossPrice, 0, ',', '.');

            return '<button type="button"
                        class="btn btn-sm btn-success btn-renew-single"
                        data-id="' . $row->id . '"
                        data-name="' . $row->nama . '"
                        data-price="' . $formattedPrice . '">
                        <i class="fa fa-sync"></i> Perpanjang
                    </button>';
        })
        ->rawColumns(['action', 'renewal_status'])
        ->make(true);
}

public function processBulkRenew(Request $request)
{
    $member_ids = $request->input('member_ids');

    if (empty($member_ids)) {
        return back()->with('error', 'Tidak ada member yang dipilih untuk diperpanjang.');
    }

    DB::beginTransaction();
    try {
        $successCount = 0;
        $firstParentMember = null;

        $member = Member::with('membership')->where('id', $member_ids)->first();
        $totalPpnAmount = 0;
        if ($member->membership->use_ppn) {
            $totalPpnAmount = (float) $member->membership->ppn;
        }

        if ($member->membership_id != 0 && $member->membership && $member->parent_id == 0) {

                if ($firstParentMember === null) {
                    $firstParentMember = $member;
                }

                $duration = $member->membership->duration_days;
                $membershipPrice = $member->membership->price ?? 0;

                // === LOGIKA PPN PER MEMBER ===
                $netPrice = $membershipPrice; // Harga bersih awal
                $ppnItemAmount = 0;

                if ($member->membership->use_ppn) {
                    $itemPpnRate = (float) $member->membership->ppn / 100;

                    $ppnItemAmount = round($membershipPrice * $itemPpnRate);
                }

                // Tambahkan ke total akumulasi transaksi

                $startDate = Carbon::now('Asia/Jakarta');

                $tgl_lama = Carbon::parse($member->tgl_expired);
                // Tentukan tanggal mulai perpanjangan: Hari berikutnya dari tgl_expired lama JIKA tgl_expired > hari ini,
                // jika tidak, mulai dari hari ini.
                $tgl_baru_start = $tgl_lama->greaterThan($startDate) ? $tgl_lama->copy()->addDay() : $startDate->copy();
                $tgl_expired_baru = $tgl_baru_start->copy()->addDays($duration)->format('Y-m-d');

                // Update member
                $member->update(['tgl_expired' => $tgl_expired_baru, 'is_active' => true, 'access_used' => 0]); // Reset access

                // Catat History untuk Parent
                HistoryMembership::create([
                    'member_id' => $member->id,
                    'membership_id' => $member->membership_id,
                    'start_date' => $tgl_baru_start->format('Y-m-d'),
                    'end_date' => $tgl_expired_baru,
                    'status' => 'active',
                    'price' => $netPrice,
                    'ppn' => $ppnItemAmount
                ]);

                $successCount++;

                $childMembers = Member::where('parent_id', $member->id)->get();
                foreach ($childMembers as $child) {
                    $child->update(['tgl_expired' => $tgl_expired_baru, 'is_active' => true, 'access_used' => 0]);
                    $child->update(['access_used' => 0]);

                    HistoryMembership::create([
                        'member_id' => $child->id,
                        'membership_id' => $member->membership_id,
                        'start_date' => $tgl_baru_start->format('Y-m-d'),
                        'end_date' => $tgl_expired_baru,
                        'status' => 'active',
                        'price' => 0,
                        'ppn' => 0
                    ]);
                    $successCount++;
                }
        }

        if ($firstParentMember === null) {
            DB::rollBack();
            return back()->with('error', 'Semua member yang dipilih tidak valid untuk perpanjangan (bukan Parent atau tanpa Membership).');
        }

        $grandTotal = $member->membership->price;

        $now = Carbon::now()->format('Y-m-d');
        $lastTrx = Transaction::whereDate('created_at', $now)->latest()->first();
        $notrx = $lastTrx ? $lastTrx->no_trx + 1 : 1;

        $ticketCode = 'RENEW/' . Carbon::now('Asia/Jakarta')->format('dmY') . rand(1000, 9999);

        $tipeTransaksi = $successCount > 1 ? 'group' : 'individual';

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'no_trx' => $notrx,
            'ticket_code' => $ticketCode,
            'transaction_type' => 'renewal',
            'amount' => $successCount,
            'discount' => 0,
            'ppn' => $totalPpnAmount,
            'bayar' => $grandTotal,
            'status' => 'open',
            'is_active' => 1,
            'tipe' => $tipeTransaksi,
            'metode' => $request->metode ?? 'Cash',
            'ticket_id' => $member->membership_id,
            'member_id' => $firstParentMember ? $firstParentMember->id : null,
            'member_info' => $firstParentMember ? ($firstParentMember->nama . ' - ' . $firstParentMember->no_hp) : null
        ]);

        $invoiceUrl = $transaction ? route('transactions.invoice', $transaction->id) : null;


        DB::commit();
        return redirect()->route('members.index')
            ->with('success', "Berhasil memperpanjang $successCount entri member & mencatat transaksi RENEW.")
            ->with('invoice_url', $invoiceUrl);

    } catch (\Throwable $th) {
        DB::rollBack();
        return back()->with('error', 'Gagal memproses perpanjangan massal: ' . $th->getMessage());
    }
}
}
