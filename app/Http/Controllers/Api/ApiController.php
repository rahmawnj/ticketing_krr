<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaction;
use App\Models\History;
use App\Models\LimitMember;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Terusan;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCode()
    {
        $tickets = Ticket::select(['id', 'name', 'harga'])->get();

        return $this->sendResponse($tickets, 'Tickets list');
    }

    public function detailGroupLast(Request $request)
    {
        $transaction = Transaction::where('status', 'open')
            ->where('tipe', 'group')
            ->where('gate', $request->gate)
            ->select(['ticket_code', 'amount', 'amount_scanned', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();

        $transaction['time'] = Carbon::parse($transaction->updated_at)->format('d/m/Y H:i:s');

        return response()->json($transaction);
    }

    public function getNoTrx()
    {
        $now = Carbon::now()->format('Y-m-d');
        $transaction = Transaction::whereDate('created_at', $now)->orderBy('no_trx', 'DESC')->first();

        if ($transaction) {
            $noTrx = $transaction->no_trx + 1;
        } else {
            $noTrx = 1;
        }

        return response()->json([
            "no_trx" => $noTrx,
        ]);
    }

    public function checkIndividualTicket($ticket)
    {

        $transScanned = DetailTransaction::where('ticket_code', $ticket)
            ->select(['qty', 'scanned', 'status'])->first();

        if (!$transScanned) {
            return response()->json([
                "status" => "Not found"
            ]);
        }

        if ($transScanned->status == "close") {
            return response()->json([
                "status" => $transScanned->status,
                "count" => 0
            ]);
        }

        // $counting = $transScanned->scanned + 1;
        // if ($transScanned->qty == $counting) {
        DetailTransaction::where('ticket_code', $ticket)
            ->update([
                "status" => "close",
                // "scanned" => $counting
            ]);
        // } else {
        //     DetailTransaction::where('ticket_code', $ticket)
        //         ->update([
        //             "scanned" => $counting
        //         ]);
        // }

        return response()->json([
            "status" => $transScanned->status,
            "count" => $transScanned->qty
        ]);
    }

    public function checkGroupTicket(Request $request, $ticket)
    {
        $transScanned = Transaction::where('ticket_code', $ticket)->where('tipe', 'group')
            ->select(['amount', 'amount_scanned', 'status'])->first();

        Transaction::where('ticket_code', $ticket)
            ->update([
                "gate" => $request->gate,
            ]);

        if (!$transScanned) {
            return response()->json([
                "status" => "not found"
            ]);
        }


        if ($transScanned->status == "closed") {
            return response()->json([
                "status" => $transScanned->status,
                "count" => 0
            ]);
        }

        $counting = $transScanned->amount_scanned + 1;

        if ($transScanned->amount == $counting) {
            Transaction::where('ticket_code', $ticket)
                ->update([
                    "status" => "closed",
                    "amount_scanned" => $counting
                ]);
        } else {
            Transaction::where('ticket_code', $ticket)
                ->update([
                    "amount_scanned" => $counting
                ]);
        }

        return response()->json([
            "status" => $transScanned->status,
            "count" => $transScanned->amount - $counting
        ]);
    }

    // In Use
    public function check(Request $request)
    {
        $transScanned = DetailTransaction::where('ticket_code', $request->ticket)->first();

        if ($transScanned) {
            $invoice = Transaction::where('id', $transScanned->transaction_id)->first();

            DetailTransaction::where('ticket_code', $request->ticket)
                ->update([
                    "gate" => $request->gate,
                ]);

            if (!$transScanned) {
                return response()->json([
                    "status" => "Not found"
                ]);
            }

            if ($invoice->detail()->sum('scanned') >= $invoice->detail()->sum('qty')) {
                $invoice->status = "closed";
                $invoice->amount_scanned = $invoice->detail()->sum('scanned');
                $invoice->save();

                return response()->json([
                    "status" => "close",
                    "count" => 0
                ]);
            }

            if ($transScanned->status == "close") {
                return response()->json([
                    "status" => $transScanned->status,
                    "count" => 0
                ]);
            }

            $counting = $transScanned->scanned + 1;
            if ($transScanned->qty == $counting) {
                DetailTransaction::where('ticket_code', $request->ticket)
                    ->update([
                        "status" => "close",
                        "scanned" => $counting,
                        "scanned_at" => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
                    ]);

                if ($invoice->detail()->sum('scanned') >= ($invoice->detail()->sum('qty'))) {
                    $invoice->status = "closed";
                    $invoice->amount_scanned = $invoice->detail()->sum('scanned');
                    $invoice->save();
                }
            } else {
                DetailTransaction::where('ticket_code', $request->ticket)
                    ->update([
                        "scanned" => $counting
                    ]);
            }

            return response()->json([
                "status" => $transScanned->status,
                "count" => $transScanned->qty - $counting
            ]);
        } else {
            $now = Carbon::now('Asia/Jakarta')->format('Y-m-d');

            $member = Member::where('rfid', $request->ticket)->orWhere('qr_code', $request->ticket)->first();
            $employe = User::where('uid', $request->ticket)->first();

            if ($member) {
                if ($now >= $member->tgl_register && $now <= $member->tgl_expired) {
                    $membership = Membership::find($member->membership_id);
                    if (!$membership) {
                        return response()->json([
                            "status" => "error",
                            "message" => "Member not subcribed"
                        ], 500);
                    }

                    $gates = $membership->gates()->pluck('id')->toArray();

                    if (in_array($request->gate, $gates)) {
                        History::create([
                            'member_id' => $member->id,
                            'gate' => $request->gate,
                            'user_id' => 0,
                            'waktu' => now('Asia/Jakarta')->format('Y-m-d H:i:s')
                        ]);

                        return response()->json([
                            "status" => 'open',
                            "message" => "Success open gate"
                        ], 200);
                    } else {
                        return response()->json([
                            "status" => 'close',
                            "message" => "Cannot access gate"
                        ], 500);
                    }
                } else {
                    return response()->json([
                        "status" => 'close',
                        "message" => "Member expired"
                    ]);
                }
            } else if ($employe) {
                if ($employe->is_active == 0) {
                    return response()->json([
                        "status" => 'close',
                        "message" => "Karyawan sudah tidak aktif"
                    ]);
                }

                History::create([
                    'member_id' => 0,
                    'gate' => $request->gate,
                    'user_id' => $employe->id,
                    'waktu' => now('Asia/Jakarta')->format('Y-m-d H:i:s')
                ]);

                return response()->json([
                    "status" => 'open',
                    "message" => "Gate user"
                ]);
            } else {
                return response()->json([
                    "status" => 'close',
                    "message" => "Card tidak terdaftar"
                ]);
            }
        }
    }

    public function gateTerusan(Request $request)
    {
        $ticket = Transaction::where('ticket_code', $request->ticket)->first();
        $now = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $date = Carbon::parse($ticket->created_at)->format('Y-m-d');

        if ($ticket) {
            if ($ticket->ticket->jenis_ticket_id == 2 && $date == $now) {
                $terusan = Terusan::where('tripod', $request->tripod)->first();

                if ($terusan) {
                    return response()->json([
                        "status" => 'open',
                    ]);
                } else {
                    return response()->json([
                        "status" => 'close',
                    ]);
                }
            } else {
                return response()->json([
                    "status" => 'close',
                ]);
            }
        } else {
            return response()->json([
                "status" => 'close',
            ]);
        }
    }

    public function detailGroup()
    {
        return view('detail');
    }

    function last_member()
    {
        $now = now("Asia/Jakarta");

        $lastMember = History::latest("waktu")->first();
        if (!$lastMember) {
            return response()->json([
                "status" => "error",
                "message" => "Not last member data",
                "data" => [
                    "image" => asset("/img/no-image.jpg"),
                ]
            ]);
        }

        $lastMemberTime = Carbon::parse($lastMember->waktu)->addSecond(10);

        if ($now <= $lastMemberTime) {
            $response = [
                "image" => $lastMember->member->image_profile != null ? config('app.url') . "/storage/" . $lastMember->member->image_profile : asset("/img/user-dump.png"),
                "name" => $lastMember->member->nama,
                "membership" => $lastMember->member->membership->name,
                "expired_at" => $lastMember->member->tgl_expired,
                "status" => $lastMember->member->is_active
            ];

            return response()->json([
                "status" => "success",
                "message" => "Success get last member",
                "data" => $response
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Not last member data",
                "data" => [
                    "image" => asset("/img/no-image.jpg"),
                ]
            ]);
        }
    }
}
