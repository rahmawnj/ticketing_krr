<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:management-access');
    }

    public function index()
    {
        $title = 'Setting';
        $breadcrumbs = ['Setting'];
        $setting = Setting::asObject();

        return view('setting.index', compact('title', 'breadcrumbs', 'setting'));
    }

    function store(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'ucapan' => 'required|string',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|mimes:jpg,jpeg,png,gif',
            'ppn' => 'required|numeric',
            'member_suspend_before_days' => 'required|integer|min:1',
            'member_suspend_after_days' => 'required|integer|min:0',
            'member_reactivation_admin_fee' => 'required|integer|min:0',
            'print_mode' => 'required|in:per_qty,per_ticket',
            'ticket_print_orientation' => 'required|in:portrait,landscape',
            'dashboard_metric_mode' => 'required|in:amount,count',
            'whatsapp_enabled' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $setting = Setting::asObject();
            $logo = $request->file('logo');
            $logoUrl = null;
            $attr['use_logo'] = $request->has('use_logo') ? 1 : (int) ($setting->use_logo ?? 0);
            $attr['whatsapp_enabled'] = $request->has('whatsapp_enabled') ? 1 : 0;

            if ($logo) {
                $disk = Storage::disk('public');
                if (!empty($setting->logo) && $disk->exists($setting->logo)) {
                    $disk->delete($setting->logo);
                }
                $logoUrl = $logo->storeAs(
                    'logo',
                    now('Asia/Jakarta')->format('ymdHis') . rand(100, 990) . '.' . $logo->extension(),
                    'public'
                );
                $attr['use_logo'] = 1;
            } else {
                $logoUrl = $setting->logo ?? null;
            }

            $attr['logo'] = $logoUrl;
            Setting::putMany($attr);

            DB::commit();
            return back()->with('success', "Setting successfully saved");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
