<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ["parent_id", "membership_id", "rfid", "no_ktp", "no_hp", "nama", "alamat", "tgl_lahir", "tgl_register", "tgl_expired", "saldo", "jenis_kelamin", "image_profile", "qr_code", "is_active", "limit", "jenis_member", "access_used"];

    function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    function childs()
    {
        return $this->hasMany(Member::class, 'parent_id');
    }

    function HistoryMemberships(): HasMany
    {
        return $this->hasMany(HistoryMembership::class);
    }

    public function getLifecycleStatusAttribute(): string
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $expiredAt = Carbon::parse($this->tgl_expired)->startOfDay();

        if ($today->lessThanOrEqualTo($expiredAt)) {
            return $this->is_active ? 'active' : 'inactive';
        }

        $daysAfterExpired = $expiredAt->diffInDays($today);

        if ($daysAfterExpired <= 30) {
            return 'suspend';
        }

        return 'expired';
    }

    public function getDaysAfterExpiredAttribute(): int
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $expiredAt = Carbon::parse($this->tgl_expired)->startOfDay();

        if ($today->lessThanOrEqualTo($expiredAt)) {
            return 0;
        }

        return $expiredAt->diffInDays($today);
    }
}
