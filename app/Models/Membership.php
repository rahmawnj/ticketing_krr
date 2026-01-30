<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $fillable = ["name", "duration_days", "price", "max_person", "is_active", 'ppn', 'use_ppn'];

    function gates()
    {
        return $this->belongsToMany(GateAccess::class, 'gate_access_membership');
    }

    function members()
    {
        return $this->hasMany(Member::class);
    }
}
