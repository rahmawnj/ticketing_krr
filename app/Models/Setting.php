<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    private const DEFAULTS = [
        'name' => null,
        'logo' => null,
        'ucapan' => null,
        'deskripsi' => null,
        'ppn' => 0,
        'member_suspend_before_days' => 7,
        'member_suspend_after_days' => 0,
        'member_reactivation_admin_fee' => 0,
        'print_mode' => 'per_qty',
        'ticket_print_orientation' => 'portrait',
        'dashboard_metric_mode' => 'amount',
        'whatsapp_enabled' => 0,
        'use_logo' => 0,
    ];

    public static function asObject(): object
    {
        return (object) static::allAsArray();
    }

    public static function allAsArray(): array
    {
        $pairs = static::query()->pluck('value', 'key')->toArray();
        $merged = array_merge(self::DEFAULTS, $pairs);

        $merged['ppn'] = (int) $merged['ppn'];
        $merged['member_suspend_before_days'] = max((int) $merged['member_suspend_before_days'], 1);
        $merged['member_suspend_after_days'] = max((int) $merged['member_suspend_after_days'], 0);
        $merged['member_reactivation_admin_fee'] = max((int) $merged['member_reactivation_admin_fee'], 0);
        $merged['whatsapp_enabled'] = (int) $merged['whatsapp_enabled'];
        $merged['use_logo'] = (int) $merged['use_logo'];

        return $merged;
    }

    public static function valueOf(string $key, mixed $default = null): mixed
    {
        $settings = static::allAsArray();
        return $settings[$key] ?? $default;
    }

    public static function putMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? (int) $value : (string) $value]
            );
        }
    }
}
