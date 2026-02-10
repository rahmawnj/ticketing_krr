<?php

namespace App\Models;

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
}
