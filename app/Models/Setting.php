<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ["name", "logo", "ucapan", "deskripsi", 'use_logo', 'ppn', 'member_reminder_days', 'member_delete_grace_days', 'print_mode', 'dashboard_metric_mode'];
}
