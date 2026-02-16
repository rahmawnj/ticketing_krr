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
        $setting = Setting::first() ?? new Setting();

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
            'member_reminder_days' => 'required|numeric|min:1',
            'member_delete_grace_days' => 'required|integer|min:0',
            'print_mode' => 'required|in:per_qty,per_ticket',
            'dashboard_metric_mode' => 'required|in:amount,count',
        ]);

        try {
            DB::beginTransaction();

            $setting = Setting::first();
            $logo = $request->file('logo');
            $logoUrl = null;
            $attr['use_logo'] = $request->has('use_logo') ? 1 : 0;

            if ($setting) {
                if ($logo) {
                    if ($setting->logo && Storage::exists($setting->logo)) {
                        Storage::delete($setting->logo);
                    }
                    $logoUrl = $logo->storeAs('logo', date('ymdhis') . rand(100, 990) . '.' . $logo->extension());
                } else {
                    $logoUrl = $setting->logo;
                }
                $attr["logo"] = $logoUrl;
                $setting->update($attr);
            } else {
                if ($logo) {
                    $logoUrl = $logo->storeAs('logo', date('ymdhis') . rand(100, 990) . '.' . $logo->extension());
                    $attr["logo"] = $logoUrl;
                } else {
                    unset($attr['logo']);
                }
                Setting::create($attr);
            }

            DB::commit();
            return back()->with('success', "Setting successfully saved");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
