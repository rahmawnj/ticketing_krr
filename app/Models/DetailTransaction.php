<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    function scopeFilterDaterange($query)
    {
        if (request('daterange') ?? false) {
            $daterange = explode(' - ', request('daterange'));
            $from = Carbon::createFromFormat('m/d/Y', $daterange[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('m/d/Y', $daterange[1])->addDay(1)->format('Y-m-d');

            $query->whereBetween('scanned_at', [$from, $to]);
        }
    }
}
