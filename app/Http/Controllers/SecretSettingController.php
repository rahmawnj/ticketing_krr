<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SecretSettingController extends Controller
{
    public function index(Request $request)
    {
        $isUnlocked = (bool) $request->session()->get('secret_setting_unlocked', false);
        $isSuspended = (int) Setting::valueOf('site_suspended', 0) === 1;

        return view('setting.secret', [
            'isUnlocked' => $isUnlocked,
            'isSuspended' => $isSuspended,
        ]);
    }

    public function unlock(Request $request)
    {
        $data = $request->validate([
            'pin' => 'required|string',
        ]);

        $expectedPin = (string) config('app.secret_setting_pin', '1250');
        $enteredPin = trim((string) $data['pin']);

        if ($expectedPin === '' || !hash_equals($expectedPin, $enteredPin)) {
            return back()->withErrors(['pin' => 'PIN tidak valid.']);
        }

        $request->session()->put('secret_setting_unlocked', true);

        return redirect()
            ->route('secret-setting.index')
            ->with('success', 'Akses rahasia dibuka.');
    }

    public function store(Request $request)
    {
        $isUnlocked = (bool) $request->session()->get('secret_setting_unlocked', false);
        if (!$isUnlocked) {
            return redirect()->route('secret-setting.index');
        }

        $request->validate([
            'site_suspended' => 'nullable|boolean',
        ]);

        Setting::putMany([
            'site_suspended' => $request->has('site_suspended') ? 1 : 0,
        ]);

        return redirect()
            ->route('secret-setting.index')
            ->with('success', 'Setting rahasia berhasil disimpan.');
    }
}
