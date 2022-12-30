<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'from_purse_id',
        'to_purse_id',
    ];

    public function purse()
    {
        return $this->belongsTo(Purse::class,'from_purse_id','id');
    }

    public function purseTo()
    {
        return $this->belongsTo(Purse::class,'to_purse_id','id');
    }
}
